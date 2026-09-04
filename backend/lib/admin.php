<?php
declare(strict_types=1);

require_once __DIR__ . '/auth.php';

function admin_db(): PDO
{
    $database = app_db();
    if (!$database instanceof PDO) {
        throw new RuntimeException('قاعدة البيانات غير متاحة حاليًا.');
    }
    admin_install_schema($database);
    return $database;
}

function admin_ensure_column(PDO $database, string $table, string $column, string $definition): void
{
    if (!preg_match('/^[a-z0-9_]+$/', $table) || !preg_match('/^[a-z0-9_]+$/', $column)) {
        throw new InvalidArgumentException('Invalid schema identifier.');
    }
    $statement = $database->prepare('SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=? AND COLUMN_NAME=?');
    $statement->execute([$table, $column]);
    if ((int) $statement->fetchColumn() === 0) {
        $database->exec("ALTER TABLE `$table` ADD COLUMN `$column` $definition");
    }
}

function admin_install_schema(PDO $database): void
{
    static $installed = false;
    if ($installed) return;
    $database->exec("CREATE TABLE IF NOT EXISTS contracts (
        id VARCHAR(36) PRIMARY KEY, user_id VARCHAR(32) NULL, title VARCHAR(190) NOT NULL,
        status ENUM('draft','review','pending_signature','signed','cancelled') NOT NULL DEFAULT 'draft',
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME NULL,
        KEY contracts_user_index (user_id),
        CONSTRAINT contracts_user_fk FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $database->exec("CREATE TABLE IF NOT EXISTS content_items (
        id VARCHAR(36) PRIMARY KEY, title VARCHAR(190) NOT NULL, content_type ENUM('article','news','update') NOT NULL DEFAULT 'article',
        category_label VARCHAR(80) NOT NULL DEFAULT 'مقال', excerpt VARCHAR(700) NOT NULL DEFAULT '', reading_time VARCHAR(80) NOT NULL DEFAULT '',
        cover_image VARCHAR(500) NOT NULL DEFAULT '', external_url VARCHAR(500) NOT NULL DEFAULT '', is_featured TINYINT(1) NOT NULL DEFAULT 0,
        sort_order INT NOT NULL DEFAULT 0, status ENUM('draft','published','archived') NOT NULL DEFAULT 'draft', created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME NULL, published_at DATETIME NULL, KEY content_publish_index (status,sort_order,published_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $database->exec("CREATE TABLE IF NOT EXISTS events (
        id VARCHAR(36) PRIMARY KEY, title VARCHAR(190) NOT NULL, starts_at DATETIME NOT NULL,
        location VARCHAR(190) NOT NULL DEFAULT '', description VARCHAR(900) NOT NULL DEFAULT '', capacity INT UNSIGNED NULL,
        registered_count INT UNSIGNED NOT NULL DEFAULT 0, registration_url VARCHAR(500) NOT NULL DEFAULT '', calendar_url VARCHAR(500) NOT NULL DEFAULT '',
        sort_order INT NOT NULL DEFAULT 0,
        status ENUM('draft','published','completed','cancelled') NOT NULL DEFAULT 'draft',
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME NULL, KEY events_publish_index (status,sort_order,starts_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    foreach ([
        'category_label' => "VARCHAR(80) NOT NULL DEFAULT 'مقال' AFTER content_type",
        'excerpt' => "VARCHAR(700) NOT NULL DEFAULT '' AFTER category_label",
        'reading_time' => "VARCHAR(80) NOT NULL DEFAULT '' AFTER excerpt",
        'cover_image' => "VARCHAR(500) NOT NULL DEFAULT '' AFTER reading_time",
        'external_url' => "VARCHAR(500) NOT NULL DEFAULT '' AFTER cover_image",
        'is_featured' => "TINYINT(1) NOT NULL DEFAULT 0 AFTER external_url",
        'sort_order' => "INT NOT NULL DEFAULT 0 AFTER is_featured",
    ] as $column => $definition) admin_ensure_column($database, 'content_items', $column, $definition);
    foreach ([
        'description' => "VARCHAR(900) NOT NULL DEFAULT '' AFTER location",
        'registered_count' => "INT UNSIGNED NOT NULL DEFAULT 0 AFTER capacity",
        'registration_url' => "VARCHAR(500) NOT NULL DEFAULT '' AFTER registered_count",
        'calendar_url' => "VARCHAR(500) NOT NULL DEFAULT '' AFTER registration_url",
        'sort_order' => "INT NOT NULL DEFAULT 0 AFTER calendar_url",
    ] as $column => $definition) admin_ensure_column($database, 'events', $column, $definition);
    $database->exec("CREATE TABLE IF NOT EXISTS admin_audit_log (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, admin_user_id VARCHAR(32) NULL,
        action VARCHAR(80) NOT NULL, entity_type VARCHAR(80) NOT NULL, entity_id VARCHAR(64) NOT NULL DEFAULT '',
        details VARCHAR(500) NOT NULL DEFAULT '', created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        KEY audit_created_index (created_at),
        CONSTRAINT audit_admin_fk FOREIGN KEY (admin_user_id) REFERENCES users(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $database->exec("CREATE TABLE IF NOT EXISTS sector_map (
        code CHAR(2) PRIMARY KEY, name VARCHAR(160) NOT NULL, description VARCHAR(500) NOT NULL,
        tags_json TEXT NOT NULL, icon_key VARCHAR(40) NOT NULL DEFAULT 'software', sort_order INT NOT NULL DEFAULT 0,
        is_active TINYINT(1) NOT NULL DEFAULT 1, created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME NULL, KEY sector_map_sort_index (sort_order,is_active)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $database->exec("CREATE TABLE IF NOT EXISTS site_settings (
        setting_key VARCHAR(80) PRIMARY KEY, setting_value TEXT NOT NULL, updated_at DATETIME NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $database->exec("CREATE TABLE IF NOT EXISTS success_stories (
        id VARCHAR(36) PRIMARY KEY, sector_label VARCHAR(120) NOT NULL, category_key VARCHAR(40) NOT NULL,
        title VARCHAR(190) NOT NULL, problem_text VARCHAR(700) NOT NULL, solution_text VARCHAR(900) NOT NULL,
        duration VARCHAR(80) NOT NULL, metrics_json TEXT NOT NULL, sort_order INT NOT NULL DEFAULT 0,
        is_active TINYINT(1) NOT NULL DEFAULT 1, created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME NULL, KEY success_stories_publish_index (is_active,sort_order)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $database->exec("CREATE TABLE IF NOT EXISTS about_page_items (
        id VARCHAR(36) PRIMARY KEY, section_key VARCHAR(40) NOT NULL, title VARCHAR(190) NOT NULL,
        subtitle VARCHAR(255) NOT NULL DEFAULT '', body VARCHAR(900) NOT NULL DEFAULT '',
        badge_label VARCHAR(80) NOT NULL DEFAULT '', badge_style VARCHAR(20) NOT NULL DEFAULT 'info',
        value_text VARCHAR(80) NOT NULL DEFAULT '', value_suffix VARCHAR(20) NOT NULL DEFAULT '',
        icon_key VARCHAR(40) NOT NULL DEFAULT 'default', primary_url VARCHAR(500) NOT NULL DEFAULT '',
        secondary_url VARCHAR(500) NOT NULL DEFAULT '', sort_order INT NOT NULL DEFAULT 0,
        is_active TINYINT(1) NOT NULL DEFAULT 1, created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME NULL, KEY about_items_section_index (section_key,is_active,sort_order)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $database->exec("CREATE TABLE IF NOT EXISTS investor_page_items (
        id VARCHAR(36) PRIMARY KEY, section_key VARCHAR(40) NOT NULL, title VARCHAR(190) NOT NULL,
        subtitle VARCHAR(255) NOT NULL DEFAULT '', body VARCHAR(1000) NOT NULL DEFAULT '',
        badge_label VARCHAR(120) NOT NULL DEFAULT '', badge_style VARCHAR(20) NOT NULL DEFAULT 'info',
        value_text VARCHAR(255) NOT NULL DEFAULT '', value_suffix VARCHAR(255) NOT NULL DEFAULT '',
        icon_key VARCHAR(40) NOT NULL DEFAULT 'default', primary_url VARCHAR(500) NOT NULL DEFAULT '',
        secondary_url VARCHAR(500) NOT NULL DEFAULT '', sort_order INT NOT NULL DEFAULT 0,
        is_active TINYINT(1) NOT NULL DEFAULT 1, created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME NULL, KEY investor_items_section_index (section_key,is_active,sort_order)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $database->exec("CREATE TABLE IF NOT EXISTS entrepreneur_page_items (
        id VARCHAR(36) PRIMARY KEY, section_key VARCHAR(40) NOT NULL, title VARCHAR(190) NOT NULL,
        subtitle VARCHAR(255) NOT NULL DEFAULT '', body VARCHAR(1000) NOT NULL DEFAULT '',
        badge_label VARCHAR(120) NOT NULL DEFAULT '', badge_style VARCHAR(20) NOT NULL DEFAULT 'info',
        value_text VARCHAR(255) NOT NULL DEFAULT '', value_suffix VARCHAR(500) NOT NULL DEFAULT '',
        icon_key VARCHAR(40) NOT NULL DEFAULT 'default', primary_url VARCHAR(500) NOT NULL DEFAULT '',
        secondary_url VARCHAR(500) NOT NULL DEFAULT '', sort_order INT NOT NULL DEFAULT 0,
        is_active TINYINT(1) NOT NULL DEFAULT 1, created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME NULL, KEY entrepreneur_items_section_index (section_key,is_active,sort_order)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $sectorCount = (int) $database->query('SELECT COUNT(*) FROM sector_map')->fetchColumn();
    if ($sectorCount === 0) {
        $seed = $database->prepare('INSERT INTO sector_map (code,name,description,tags_json,icon_key,sort_order,is_active) VALUES (?,?,?,?,?,?,1)');
        $rows = [
            ['01','البرمجيات و SaaS','منصات ومنتجات برمجية قابلة للتوسع بنماذج اشتراك.',['منصات B2B','أدوات إنتاجية','أنظمة إدارة'],'software',1],
            ['02','التقنية المالية','حلول مالية وأسواق مال ومدفوعات ومحافظ رقمية.',['بوابات دفع','إقراض','تمويل جماعي','تحصيل'],'fintech',2],
            ['03','الذكاء الاصطناعي','التنبؤ والتحليلات والأتمتة والنماذج التوليدية.',['مساعدون أذكياء','تحليلات','رؤية حاسوبية'],'ai',3],
            ['04','الصحة الرقمية','منصات رعاية وخدمات صحية وحلول تشغيلية.',['حجوزات','سجلات','رعاية عن بُعد'],'health',4],
            ['05','التعليم التقني','منصات تعلم وتطوير مهارات رقمية.',['تعلم إلكتروني','تدريب مهني','شهادات'],'education',5],
            ['06','إنترنت الأشياء','أجهزة وشبكات ذكية متصلة وتحكم.',['مدن ذكية','تتبع','أتمتة صناعية'],'iot',6],
            ['07','التوصيل واللوجستيات','سلاسل إمداد وتوصيل وأساطيل ذكية.',['توصيل أخير ميل','تتبع','إدارة أسطول'],'logistics',7],
            ['08','الإدارة والتحول الرقمي','أتمتة العمليات وتشغيل المؤسسات.',['ERP','أتمتة','لوحات تحكم','تكاملات'],'digital',8],
        ];
        foreach ($rows as $row) {
            $seed->execute([$row[0],$row[1],$row[2],json_encode($row[3], JSON_UNESCAPED_UNICODE),$row[4],$row[5]]);
        }
    }
    $setting = $database->prepare('INSERT IGNORE INTO site_settings (setting_key,setting_value) VALUES (?,?)');
    foreach ([
        'sectors_eyebrow'=>'خريطة الفرص',
        'sectors_title'=>'قطاعات مختارة بمعايير تشغيلية واضحة',
        'sectors_description'=>'نعرض البيانات العامة للفرص المتاحة، بينما تبقى التفاصيل الحساسة داخل بيئة المستثمر المعتمد.',
    ] as $key=>$value) $setting->execute([$key,$value]);
    if ((int)$database->query('SELECT COUNT(*) FROM success_stories')->fetchColumn() === 0) {
        $storySeed=$database->prepare('INSERT INTO success_stories (id,sector_label,category_key,title,problem_text,solution_text,duration,metrics_json,sort_order,is_active) VALUES (?,?,?,?,?,?,?,?,?,1)');
        $storyRows=[
            ['STY-001','تقنية مالية','fintech','منصة مدفوعات B2B','بطء التسوية وتعقيد تجربة التاجر.','بناء بنية مدفوعات حديثة مع تسوية شبه لحظية ولوحة تاجر موحّدة.','9 أسابيع',[['value'=>'-64%','label'=>'زمن العملية'],['value'=>'3.5x','label'=>'نمو المعاملات'],['value'=>'99.9%','label'=>'توافر']],1],
            ['STY-002','صحة رقمية','health','منصة حجوزات ورعاية','تجربة مريض مجزأة وعمليات ورقية.','رقمنة رحلة المريض من الحجز إلى المتابعة مع تكامل تشغيلي.','12 أسبوع',[['value'=>'+180%','label'=>'مستخدمون'],['value'=>'-38%','label'=>'التكلفة'],['value'=>'4.8★','label'=>'رضا']],2],
            ['STY-003','لوجستيات','logistics','نظام توصيل ذكي','مسارات غير محسّنة وتتبع ضعيف.','محرّك تحسين مسارات وتتبع لحظي وأتمتة إدارة الأسطول.','8 أسابيع',[['value'=>'+42%','label'=>'كفاءة'],['value'=>'-27%','label'=>'زمن التسليم'],['value'=>'2.1x','label'=>'طلبات']],3],
            ['STY-004','ذكاء اصطناعي','ai','محرك تنبؤ للطلب','تخطيط مخزون غير دقيق.','نموذج تنبؤ بالطلب مدمج مع لوحات قرار تشغيلية.','10 أسابيع',[['value'=>'+31%','label'=>'دقة'],['value'=>'-22%','label'=>'هدر'],['value'=>'6x','label'=>'سرعة']],4],
            ['STY-005','SaaS','saas','منصة إدارة عمليات','أدوات متفرقة وتكاليف عالية.','توحيد العمليات في منصة واحدة مع أتمتة سير العمل.','11 أسبوع',[['value'=>'-45%','label'=>'أدوات'],['value'=>'+58%','label'=>'إنتاجية'],['value'=>'3 أشهر','label'=>'استرداد']],5],
            ['STY-006','تقنية مالية','fintech','محفظة رقمية','احتكاك في التسجيل والتحقق.','تدفق تسجيل مبسّط مع تحقق رقمي وامتثال مدمج.','7 أسابيع',[['value'=>'-51%','label'=>'تسرب'],['value'=>'+2.4x','label'=>'تفعيل'],['value'=>'<2د','label'=>'تسجيل']],6],
        ];
        foreach($storyRows as $story) $storySeed->execute([$story[0],$story[1],$story[2],$story[3],$story[4],$story[5],$story[6],json_encode($story[7],JSON_UNESCAPED_UNICODE),$story[8]]);
    }
    if ((int)$database->query('SELECT COUNT(*) FROM content_items')->fetchColumn() === 0) {
        $contentSeed=$database->prepare('INSERT INTO content_items (id,title,content_type,category_label,excerpt,reading_time,cover_image,external_url,is_featured,sort_order,status,published_at) VALUES (?,?,?,?,?,?,?,?,?,?,\'published\',?)');
        $contentRows=[
            ['CNT-001','منهجية تقليل مخاطر التنفيذ في الاستثمار الجريء','article','مقال','كيف نُجهّز المشروع تقنيًا وتشغيليًا قبل تفعيل رأس المال، ولماذا يصنع ذلك فرصًا أكثر جاهزية للنمو.','قراءة 6 دقائق','assets/img/knowledge-risk-cover.png','',1,1,'2026-07-10 12:00:00'],
            ['CNT-002','إطلاق الإصدار التشغيلي الأول','news','خبر','نطلق بوابتَي المستثمر ورائد الأعمال ولوحة الإدارة خلال 10 أيام.','','','',0,2,'2026-07-17 12:00:00'],
            ['CNT-003','توسع مؤسسي نحو السعودية والإمارات','news','شراكة','خطة توسع جغرافي مع فصل بيانات كل دولة وفق الإقامة القانونية.','','','',0,3,'2026-07-02 12:00:00'],
            ['CNT-004','بناء الثقة في المنصات الاستثمارية','article','مقال','دور الشفافية وسجل التدقيق في تجربة المستثمر.','','','',0,4,'2026-06-28 12:00:00'],
            ['CNT-005','انضمام خبراء للمجلس الاستشاري','news','خبر','تعزيز الخبرات في الاستثمار والقانون والتشغيل.','','','',0,5,'2026-06-20 12:00:00'],
            ['CNT-006','KYC/AML: التوازن بين الأمان والتجربة','article','مقال','كيف نُصمم تأهيلًا محكمًا دون احتكاك مفرط.','','','',0,6,'2026-06-15 12:00:00'],
            ['CNT-007','خارطة طريق المرحلة الثانية','update','تحديث','المحاسبة المتكاملة والتسويات والتقارير المتقدمة.','','','',0,7,'2026-06-08 12:00:00'],
        ];
        foreach($contentRows as $row) $contentSeed->execute($row);
    }
    if ((int)$database->query('SELECT COUNT(*) FROM events')->fetchColumn() === 0) {
        $eventSeed=$database->prepare('INSERT INTO events (id,title,starts_at,location,description,capacity,registered_count,registration_url,calendar_url,sort_order,status) VALUES (?,?,?,?,?,?,?,?,?,?,\'published\')');
        $eventRows=[
            ['EVT-001','يوم الاستثمار التقني','2026-07-23 17:00:00','هجين · Zoom','عرض منهجية الصندوق ولقاء مباشر مع الفريق.',60,42,'','',1],
            ['EVT-002','ورشة: تجهيز مشروعك للاستثمار','2026-07-30 18:00:00','عن بُعد · Google Meet','لرواد الأعمال — كيف تبني ملفًا استثماريًا قويًا.',60,60,'','',2],
            ['EVT-003','لقاء المستثمرين الربعي','2026-08-06 16:00:00','حضوري · دبي','استعراض الفرص والتوجهات — للمعتمدين فقط.',40,18,'','',3],
        ];
        foreach($eventRows as $row) $eventSeed->execute($row);
    }
    if ((int)$database->query('SELECT COUNT(*) FROM about_page_items')->fetchColumn() === 0) {
        $aboutSeed=$database->prepare('INSERT INTO about_page_items (id,section_key,title,subtitle,body,badge_label,badge_style,value_text,value_suffix,icon_key,primary_url,secondary_url,sort_order,is_active) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,1)');
        $aboutRows=[
            ['ABT-HERO','hero','نَبني المشروع قبل أن نُفعّل الاستثمار','قصتنا','Seven Tech Capital صندوق استثماري قيد التأسيس مدعوم بذراع تقني بخبرة تمتد إلى 20 عامًا في تأسيس وتشغيل الشركات التقنية.','','info','','','default','','',1],
            ['ABT-BRAND-01','brand','Seven Tech Capital','· A Venture Studio','صندوق/استوديو مشاريع استثماري قيد التأسيس، يستهدف بناء فرص أكثر جاهزية وتقليل مخاطر التنفيذ.','','info','','','default','','',1],
            ['ABT-BRAND-02','brand','Seven Tech','الذراع التقني','الذراع التقني المنفصل المسؤول عن بناء المنتجات والأنظمة ودعم التشغيل، بخبرة 15 عامًا وأكثر من 500 عميل.','','info','','','default','','',2],
            ['ABT-VMM-01','vmm','الرؤية','','أن نكون المنصة الأكثر ثقة لربط رأس المال بالمشاريع التقنية الجاهزة للنمو في المنطقة.','','info','','','vision','','',1],
            ['ABT-VMM-02','vmm','الرسالة','','نختبر الفكرة، نبني المنتج، ونُجهّز المشروع للتشغيل قبل تفعيل رأس المال.','','info','','','mission','','',2],
            ['ABT-VMM-03','vmm','المنهجية','','بوابات مراجعة وجاهزية وتشغيل قبل تحرير التمويل، بآلية قانونية معتمدة.','','info','','','method','','',3],
            ['ABT-STAT-01','stat','عامًا خبرة تراكمية','','','','info','20','+','experience','','',1],
            ['ABT-STAT-02','stat','عامًا عمر Seven Tech','','','','info','15','','building','','',2],
            ['ABT-STAT-03','stat','شركات ومشروعات','','','','info','10','+','projects','','',3],
            ['ABT-STAT-04','stat','عميل في المنطقة','','','','info','500','+','clients','','',4],
            ['ABT-TEAM-HEAD','team_header','خبرات متعددة في مكان واحد','الفريق والمجلس الاستشاري','مجلس استشاري قوي من خبراء الاستثمار والتقنية والمال والقانون والتشغيل.','','info','','','default','','',1],
            ['ABT-TEAM-01','team','المؤسس والرئيس التنفيذي','قيادة تنفيذية','','','info','','','person','','',1],
            ['ABT-TEAM-02','team','مدير الاستثمار','استثمار وتمويل','','','info','','','person','','',2],
            ['ABT-TEAM-03','team','المدير التقني','بناء المنتجات','','','info','','','person','','',3],
            ['ABT-TEAM-04','team','المستشار القانوني','امتثال وحوكمة','','','info','','','person','','',4],
            ['ABT-TEAM-05','team','مدير العمليات','تشغيل ونمو','','','info','','','person','','',5],
            ['ABT-TEAM-06','team','مدير المالية','محاسبة وتقارير','','','info','','','person','','',6],
            ['ABT-TEAM-07','team','مستشار الأسواق','توسع إقليمي','','','info','','','person','','',7],
            ['ABT-TEAM-08','team','مدير المنتج','تجربة واستراتيجية','','','info','','','person','','',8],
            ['ABT-GEO-HEAD','geo_header','نطاق يمتد عبر المنطقة','التوسع الجغرافي','','','info','','','default','','',1],
            ['ABT-GEO-01','geo','MENA والخليج','','الشرق الأوسط وشمال أفريقيا ودول الخليج — نطاق الإطلاق الأساسي.','نشط','success','','','default','','',1],
            ['ABT-GEO-02','geo','السعودية والإمارات','','توسع مؤسسي مستهدف مع فصل بيانات كل دولة.','مستهدف','warning','','','default','','',2],
            ['ABT-GEO-03','geo','جنوب أفريقيا','','بوابة نحو أسواق القارة الإفريقية.','توسّع','info','','','default','','',3],
            ['ABT-CTA','cta','لنبنِ شيئًا يستحق','','سواء كنت مستثمرًا أو رائد أعمال، ابدأ رحلتك مع Seven Tech Capital.','انضم إلينا','info','تواصل معنا','','default','login.php?tab=register','contact.php',1],
        ];
        foreach($aboutRows as $row) $aboutSeed->execute($row);
    }
    if ((int)$database->query('SELECT COUNT(*) FROM investor_page_items')->fetchColumn() === 0) {
        $investorSeed=$database->prepare('INSERT INTO investor_page_items (id,section_key,title,subtitle,body,badge_label,badge_style,value_text,value_suffix,icon_key,primary_url,secondary_url,sort_order,is_active) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,1)');
        $investorRows=[
            ['INVP-HERO','hero','استثمر في فرص مرّت ببوابات جاهزية','للمستثمرين المؤهلين','تأهيل يدوي محكم، فرص محمية لا تُعرض إلا بعد الاعتماد وتوقيع السرية، ومتابعة كاملة لمحفظتك عبر لوحة تحكم مخصصة بثلاث لغات.','سجّل كمستثمر','orange','رحلة المستثمر','KYC/AML,NDA,Dashboard','default','login.php?tab=register','#journey',1],
            ['INVP-TYPE-01','investor_type','فرد مؤهل','مسار أهلية','','','info','','','person','','',1],
            ['INVP-TYPE-02','investor_type','شركات','مسار أهلية','','','info','','','company','','',2],
            ['INVP-TYPE-03','investor_type','صناديق استثمارية','مسار أهلية','','','info','','','fund','','',3],
            ['INVP-TYPE-04','investor_type','مستثمرون ملائكيون','مسار أهلية','','','info','','','angel','','',4],
            ['INVP-TYPE-05','investor_type','مكاتب عائلية','مسار أهلية','','','info','','','family','','',5],
            ['INVP-BEN-HEAD','benefits_header','لماذا تستثمر معنا','المزايا','كل ميزة مصممة لتقليل الغموض قبل الاستثمار، من التأهيل وحتى متابعة المحفظة.','','info','','','default','','',1],
            ['INVP-BEN-01','benefit','فرص أكثر جاهزية','','نُجهّز المشروع تقنيًا وتشغيليًا قبل تفعيل التمويل لتقليل مخاطر التنفيذ.','','orange','','','ready','','',1],
            ['INVP-BEN-02','benefit','حماية وسرية','','لا تُعرض الفرص إلا للمعتمدين بعد توقيع NDA، مع فصل صارم للبيانات الحساسة.','','orange','','','security','','',2],
            ['INVP-BEN-03','benefit','خيارات مرنة','','استثمر في الصندوق ككل، أو فرصة محددة، أو مسار هجين يوزّع مساهمتك.','','orange','','','flexible','','',3],
            ['INVP-BEN-04','benefit','شفافية كاملة','','تابع المراحل والحالات والتقارير والمستندات وسجل الإجراءات بوضوح.','','info','','','transparency','','',4],
            ['INVP-BEN-05','benefit','تأهيل سريع','','مراجعة KYC/AML يدوية مستهدفة خلال 3–5 أيام عمل مع إشعارات بكل خطوة.','','info','','','speed','','',5],
            ['INVP-BEN-06','benefit','نطاق واسع','','من 5,000 دولار إلى 5 ملايين دولار وفق أهليتك والمتطلبات القانونية.','','info','','','money','','',6],
            ['INVP-JOURNEY-HEAD','journey_header','من التسجيل إلى المتابعة','رحلة المستثمر','تسع خطوات واضحة، كل واحدة بحالة معروفة وخطوة تالية ظاهرة في لوحتك.','ابدأ الآن','orange','','','default','login.php?tab=register','',1],
            ['INVP-JOURNEY-01','journey_step','إنشاء حساب','','الدولة، نوع المستثمر، حجم الاستثمار المتوقع، مصدر الأموال وبيانات الاتصال.','','orange','','','default','','',1],
            ['INVP-JOURNEY-02','journey_step','رفع KYC/AML','','هوية/جواز، إثبات عنوان، ومستندات الشركة ومصدر الأموال حسب النوع.','','orange','','','default','','',2],
            ['INVP-JOURNEY-03','journey_step','مراجعة يدوية','','من 3 إلى 5 أيام عمل بحالات: قيد المراجعة، مطلوب استكمال، معتمد، مرفوض.','','orange','','','default','','',3],
            ['INVP-JOURNEY-04','journey_step','توقيع NDA','','توقيع داخل المنصة مع تحقق عبر البريد وواتساب وسجل تدقيق.','','info','','','default','','',4],
            ['INVP-JOURNEY-05','journey_step','استعراض الفرص','','الفكرة، القطاع، المرحلة، الاستثمار المطلوب، الحصة المتاحة والفريق.','','info','','','default','','',5],
            ['INVP-JOURNEY-06','journey_step','طلب اجتماع','','السبب، الفرصة، القيمة المتوقعة، الموعد، طريقة الحضور وملفات.','','info','','','default','','',6],
            ['INVP-JOURNEY-07','journey_step','اتفاقيات إضافية','','قبل إظهار التفاصيل الحساسة والدراسات المالية.','','info','','','default','','',7],
            ['INVP-JOURNEY-08','journey_step','تعهد / استثمار','','تعهد غير ملزم أو رفع إثبات تحويل بعد تفعيل المسار قانونيًا.','','info','','','default','','',8],
            ['INVP-JOURNEY-09','journey_step','المتابعة','','محفظة، حالة المشروعات، التقارير، المستندات، الاجتماعات والتنبيهات.','','info','','','default','','',9],
            ['INVP-FAQ-HEAD','faq_header','أسئلة المستثمرين','الأسئلة الشائعة','','','info','','','default','','',1],
            ['INVP-FAQ-01','faq','هل يمكنني استقبال أرباح أو استرداد أموال الآن؟','','الكيان قيد التأسيس واستكمال التراخيص. حتى اكتمالها واعتماد مسار التحصيل، تُستقبل تعهدات استثمار غير ملزمة فقط، ولا تُفعّل أي تحويلات فعلية قبل الاعتماد القانوني.','','info','','','default','','',1],
            ['INVP-FAQ-02','faq','كم يستغرق اعتماد حسابي؟','','المراجعة اليدوية لـ KYC/AML مستهدفة خلال 3 إلى 5 أيام عمل، مع إشعارات بالبريد وواتساب عند كل تغيير في الحالة.','','info','','','default','','',2],
            ['INVP-FAQ-03','faq','ما حدود مبلغ الاستثمار؟','','من 5,000 دولار إلى 5 ملايين دولار وفق أهلية المستثمر والمتطلبات القانونية لكل دولة.','','info','','','default','','',3],
            ['INVP-FAQ-04','faq','كيف تُحمى الفرص الاستثمارية؟','','لا تُعرض الفرص الحالية إلا للمستثمر المعتمد بعد توقيع NDA، وتُفتح التفاصيل الحساسة فقط بعد اجتماع وتوقيع اتفاقية إضافية.','','info','','','default','','',4],
            ['INVP-FAQ-05','faq','ما مدة الاستثمار المتوقعة؟','','تختلف حسب المشروع من 3 إلى 7 سنوات، وتُوضّح ضمن وثائق كل فرصة.','','info','','','default','','',5],
            ['INVP-CTA','cta','جاهز للانضمام كمستثمر؟','ابدأ بأمان','سجّل الآن وابدأ رحلة التأهيل. سرية كاملة وتجربة رقمية شفافة.','سجّل كمستثمر','orange','تحدث مع الفريق','','default','login.php?tab=register','contact.php',1],
        ];
        foreach($investorRows as $row) $investorSeed->execute($row);
    }
    if ((int)$database->query('SELECT COUNT(*) FROM entrepreneur_page_items')->fetchColumn() === 0) {
        $entrepreneurSeed=$database->prepare('INSERT INTO entrepreneur_page_items (id,section_key,title,subtitle,body,badge_label,badge_style,value_text,value_suffix,icon_key,primary_url,secondary_url,sort_order,is_active) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,1)');
        $entrepreneurRows=[
            ['ENTP-HERO','hero','من الفكرة إلى التشغيل والتوسع','لرواد الأعمال','لا نكتفي بالتمويل، نبني معك المنتج ونُجهّز مشروعك للتشغيل. اختر نوع الدعم المناسب وتابع كل مرحلة من لوحة واحدة.','قدّم مشروعك الآن','orange','استكشف مراحل الدعم','تقييم واضح|6 مراحل دعم|من التحقق والبناء حتى التشغيل والتوسع','default','#apply','#stages',1],
            ['ENTP-STAGE-HEAD','stages_header','نموذج دعم مرن يُصمم لمشروعك','مراحل الدعم','نرتب الرحلة إلى نقاط قرار واضحة، حتى تعرف أين يقف مشروعك وما الخطوة التالية.','','info','','','default','','',1],
            ['ENTP-STAGE-01','stage','الفكرة والتحقق','بحث المشكلة والسوق','','','info','','','default','','',1],
            ['ENTP-STAGE-02','stage','الدراسة والتأسيس','النموذج المالي والقانوني','','','info','','','default','','',2],
            ['ENTP-STAGE-03','stage','البناء التقني','MVP وأنظمة وتكاملات','','','info','','','default','','',3],
            ['ENTP-STAGE-04','stage','التمويل والتوظيف','هيكلة الجولة والفريق','','','info','','','default','','',4],
            ['ENTP-STAGE-05','stage','التشغيل والنمو','تسويق وقياس أداء','','','info','','','default','','',5],
            ['ENTP-STAGE-06','stage','الاستقلال والتوسع','حوكمة وتوسع وتخارج','','','info','','','default','','',6],
            ['ENTP-EVAL-HEAD','evaluation_header','كيف نقيّم مشروعك','معايير التقييم','تقييم متعدد الأبعاد يضمن الجاهزية قبل هيكلة الدعم.','','info','','','default','','',1],
            ['ENTP-CRIT-01','criterion','فني','','جودة المنتج والبنية التقنية والقابلية للتوسع.','','info','','','check','','',1],
            ['ENTP-CRIT-02','criterion','سوقي','','حجم السوق، المشكلة، والميزة التنافسية.','','info','','','check','','',2],
            ['ENTP-CRIT-03','criterion','مالي','','النموذج المالي، الوحدة الاقتصادية والاحتياج.','','info','','','check','','',3],
            ['ENTP-CRIT-04','criterion','تشغيلي','','الفريق، الجاهزية التنفيذية وخطة النمو.','','info','','','check','','',4],
            ['ENTP-REVIEW-HEAD','review_header','مسار شفاف قابل للتخصيص','','','خط المراجعة','orange','','','default','','',1],
            ['ENTP-REVIEW-01','review_step','جديد','','','','orange','','','default','','',1],
            ['ENTP-REVIEW-02','review_step','مراجعة أولية','','','','orange','','','default','','',2],
            ['ENTP-REVIEW-03','review_step','استكمال بيانات','','','','orange','أنت هنا','','default','','',3],
            ['ENTP-REVIEW-04','review_step','مقابلة','','','','info','','','default','','',4],
            ['ENTP-REVIEW-05','review_step','دراسة','','','','info','','','default','','',5],
            ['ENTP-REVIEW-06','review_step','قرار','','','','info','','','default','','',6],
            ['ENTP-REVIEW-07','review_step','تعاقد','','','','info','','','default','','',7],
            ['ENTP-REVIEW-08','review_step','تنفيذ','','','','info','','','default','','',8],
            ['ENTP-APPLY-HEAD','apply_header','قدّم مشروعك','نموذج التقديم','نموذج تفاعلي متعدد الخطوات مع حفظ تلقائي ومسودة. يمكنك التقديم واستكمال البيانات بسلاسة.','','info','','','default','','',1],
            ['ENTP-SUPPORT-01','support_option','تطوير الفكرة','تحقق وبناء فرضيات','','','info','','','layers','','',1],
            ['ENTP-SUPPORT-02','support_option','البناء التقني','MVP وأنظمة','','','orange','','','layers','','',2],
            ['ENTP-SUPPORT-03','support_option','التمويل والتوظيف','جولة وفريق','','','info','','','layers','','',3],
            ['ENTP-SUPPORT-04','support_option','التشغيل والتوسع','نمو وحوكمة','','','info','','','layers','','',4],
        ];
        foreach($entrepreneurRows as $row) $entrepreneurSeed->execute($row);
    }
    $installed = true;
}

function admin_rows(string $sql, array $params = []): array
{
    $statement = admin_db()->prepare($sql);
    $statement->execute($params);
    return $statement->fetchAll();
}

function admin_log(string $action, string $entityType, string $entityId = '', string $details = ''): void
{
    $statement = admin_db()->prepare('INSERT INTO admin_audit_log (admin_user_id,action,entity_type,entity_id,details) VALUES (?,?,?,?,?)');
    $statement->execute([(string)($_SESSION['user_id'] ?? '') ?: null, $action, $entityType, $entityId, mb_substr($details, 0, 500)]);
}

function admin_id(string $prefix): string
{
    return $prefix . '-' . bin2hex(random_bytes(8));
}

function admin_required(array $input, array $keys): bool
{
    foreach ($keys as $key) if (trim((string)($input[$key] ?? '')) === '') return false;
    return true;
}

function admin_optional_url(string $value, bool $allowRelative = false): ?string
{
    $value = trim($value);
    if ($value === '') return '';
    if ($allowRelative && !str_contains($value, '..') && !str_starts_with($value, '//') && preg_match('#^[a-zA-Z0-9_./?=&%+\#@() -]+$#', $value)) return $value;
    if (!filter_var($value, FILTER_VALIDATE_URL)) return null;
    $scheme = strtolower((string) parse_url($value, PHP_URL_SCHEME));
    return in_array($scheme, ['http','https'], true) ? $value : null;
}

function admin_handle_action(string $action, array $input): array
{
    $db = admin_db();
    if ($action === 'create_opportunity') {
        if (!admin_required($input, ['title','sector','stage','target_amount','currency'])) return [false, 'أكمل بيانات الفرصة.'];
        $id = 'OP-' . strtoupper(bin2hex(random_bytes(3)));
        $amount = filter_var($input['target_amount'], FILTER_VALIDATE_FLOAT);
        if ($amount === false || $amount < 0) return [false, 'قيمة التمويل غير صحيحة.'];
        $stmt=$db->prepare('INSERT INTO opportunities (id,title,sector,stage,target_amount,currency,status) VALUES (?,?,?,?,?,?,?)');
        $stmt->execute([$id,trim($input['title']),trim($input['sector']),trim($input['stage']),$amount,strtoupper(trim($input['currency'])),in_array($input['status']??'', ['available','review','completed'], true)?$input['status']:'review']);
        admin_log('create','opportunity',$id,trim($input['title'])); return [true, 'تمت إضافة الفرصة.'];
    }
    if ($action === 'set_opportunity_status') {
        $status=(string)($input['status']??''); if (!in_array($status,['available','review','completed'],true)) return [false,'حالة الفرصة غير صحيحة.'];
        $stmt=$db->prepare('UPDATE opportunities SET status=? WHERE id=?'); $stmt->execute([$status,(string)$input['id']]);
        admin_log('status_change','opportunity',(string)$input['id'],$status); return [$stmt->rowCount()>0,$stmt->rowCount()>0?'تم تحديث الفصة.':'لم يتم العثور على الفرصة.'];
    }
    if ($action === 'assign_opportunity') {
        if (!admin_required($input, ['id','user_id'])) return [false, 'اختر المستثمر المطلوب.'];
        $check=$db->prepare("SELECT COUNT(*) FROM users WHERE id=? AND role='investor'"); $check->execute([(string)$input['user_id']]);
        if (!(int)$check->fetchColumn()) return [false, 'حساب المستثمر غير موجود.'];
        $stmt=$db->prepare('INSERT IGNORE INTO investor_opportunities (user_id,opportunity_id) VALUES (?,?)'); $stmt->execute([(string)$input['user_id'],(string)$input['id']]);
        if (!$stmt->rowCount()) return [false, 'الفرصة مسندة لهذا المستثمر بالفعل.'];
        admin_log('assign','opportunity',(string)$input['id'],'investor='.(string)$input['user_id']); return [true, 'تم إسناد الفرصة للمستثمر.'];
    }
    if ($action === 'update_sector_intro') {
        if (!admin_required($input,['eyebrow','title','description'])) return [false, 'أكمل عنوان ووصف خريطة الفرص.'];
        $stmt=$db->prepare('INSERT INTO site_settings (setting_key,setting_value,updated_at) VALUES (?,?,NOW()) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value),updated_at=NOW()');
        foreach(['sectors_eyebrow'=>'eyebrow','sectors_title'=>'title','sectors_description'=>'description'] as $key=>$field) $stmt->execute([$key,trim((string)$input[$field])]);
        admin_log('update','sector_map_intro','',trim((string)$input['title'])); return [true, 'تم حفظ عنوان خريطة الفرص.'];
    }
    if ($action === 'create_sector_map') {
        if (!admin_required($input,['name','description','tags','sort_order'])) return [false, 'أكمل بيانات القطاع الجديد.'];
        $tags=array_values(array_filter(array_map('trim',preg_split('/[,،]+/u',(string)$input['tags'])?:[])));
        if(!$tags) return [false, 'أضف وسمًا واحدًا على الأقل.'];
        $next=(int)$db->query('SELECT COALESCE(MAX(CAST(code AS UNSIGNED)),0)+1 FROM sector_map')->fetchColumn();
        if($next>99) return [false, 'تم الوصول للحد الأقصى لعدد القطاعات.'];
        $code=str_pad((string)$next,2,'0',STR_PAD_LEFT); $icons=['software','fintech','ai','health','education','iot','logistics','digital']; $icon=in_array($input['icon_key']??'',$icons,true)?$input['icon_key']:'software';
        $stmt=$db->prepare('INSERT INTO sector_map (code,name,description,tags_json,icon_key,sort_order,is_active) VALUES (?,?,?,?,?,?,?)');
        $stmt->execute([$code,trim((string)$input['name']),trim((string)$input['description']),json_encode($tags,JSON_UNESCAPED_UNICODE),$icon,max(0,(int)$input['sort_order']),isset($input['is_active'])?1:0]);
        admin_log('create','sector_map',$code,trim((string)$input['name'])); return [true, 'تمت إضافة القطاع الجديد.'];
    }
    if ($action === 'update_sector_map') {
        if (!admin_required($input,['code','name','description','tags','sort_order'])) return [false, 'أكمل بيانات القطاع.'];
        $code=(string)$input['code']; $tags=array_values(array_filter(array_map('trim',preg_split('/[,،]+/u',(string)$input['tags'])?:[])));
        if(!$tags) return [false, 'أضف وسمًا واحدًا على الأقل.'];
        $icons=['software','fintech','ai','health','education','iot','logistics','digital']; $icon=in_array($input['icon_key']??'',$icons,true)?$input['icon_key']:'software';
        $stmt=$db->prepare('UPDATE sector_map SET name=?,description=?,tags_json=?,icon_key=?,sort_order=?,is_active=?,updated_at=NOW() WHERE code=?');
        $stmt->execute([trim((string)$input['name']),trim((string)$input['description']),json_encode($tags,JSON_UNESCAPED_UNICODE),$icon,max(0,(int)$input['sort_order']),isset($input['is_active'])?1:0,$code]);
        admin_log('update','sector_map',$code,trim((string)$input['name'])); return [$stmt->rowCount()>0,$stmt->rowCount()>0?'تم حفظ بيانات القطاع.':'لم يتم العثور على القطاع أو لم تتغير بياناته.'];
    }
    if ($action === 'delete_sector_map') {
        $code=trim((string)($input['code']??'')); if($code==='') return [false, 'رقم القطاع غير صحيح.'];
        $nameStmt=$db->prepare('SELECT name FROM sector_map WHERE code=?'); $nameStmt->execute([$code]); $name=(string)($nameStmt->fetchColumn()?:'');
        if($name==='') return [false, 'لم يتم العثور على القطاع.'];
        $stmt=$db->prepare('DELETE FROM sector_map WHERE code=?'); $stmt->execute([$code]);
        admin_log('delete','sector_map',$code,$name); return [true, 'تم حذف القطاع من خريطة الفرص.'];
    }
    if (in_array($action,['create_success_story','update_success_story'],true)) {
        if (!admin_required($input,['sector_label','category_key','title','problem_text','solution_text','duration','sort_order','metric_1_value','metric_1_label','metric_2_value','metric_2_label','metric_3_value','metric_3_label'])) return [false, 'أكمل كل بيانات قصة النجاح والمؤشرات الثلاثة.'];
        $category=preg_replace('/[^a-z0-9_-]/','',strtolower((string)$input['category_key'])); if($category==='') return [false, 'تصنيف القصة غير صحيح.'];
        $metrics=[]; for($i=1;$i<=3;$i++) $metrics[]=['value'=>trim((string)$input["metric_{$i}_value"]),'label'=>trim((string)$input["metric_{$i}_label"])];
        $values=[trim((string)$input['sector_label']),$category,trim((string)$input['title']),trim((string)$input['problem_text']),trim((string)$input['solution_text']),trim((string)$input['duration']),json_encode($metrics,JSON_UNESCAPED_UNICODE),max(0,(int)$input['sort_order']),isset($input['is_active'])?1:0];
        if($action==='create_success_story') {
            $id=admin_id('STY'); $stmt=$db->prepare('INSERT INTO success_stories (id,sector_label,category_key,title,problem_text,solution_text,duration,metrics_json,sort_order,is_active) VALUES (?,?,?,?,?,?,?,?,?,?)'); $stmt->execute([$id,...$values]);
            admin_log('create','success_story',$id,$values[2]); return [true, 'تمت إضافة قصة النجاح.'];
        }
        $id=(string)($input['id']??''); $stmt=$db->prepare('UPDATE success_stories SET sector_label=?,category_key=?,title=?,problem_text=?,solution_text=?,duration=?,metrics_json=?,sort_order=?,is_active=?,updated_at=NOW() WHERE id=?'); $stmt->execute([...$values,$id]);
        admin_log('update','success_story',$id,$values[2]); return [$stmt->rowCount()>0,$stmt->rowCount()>0?'تم حفظ قصة النجاح.':'لم تتغير بيانات القصة.'];
    }
    if ($action === 'delete_success_story') {
        $id=(string)($input['id']??''); $stmt=$db->prepare('SELECT title FROM success_stories WHERE id=?'); $stmt->execute([$id]); $storyTitle=(string)($stmt->fetchColumn()?:''); if($storyTitle==='') return [false, 'لم يتم العثور على القصة.'];
        $delete=$db->prepare('DELETE FROM success_stories WHERE id=?'); $delete->execute([$id]); admin_log('delete','success_story',$id,$storyTitle); return [true, 'تم حذف قصة النجاح.'];
    }
    if (in_array($action,['create_about_item','update_about_item'],true)) {
        if (!admin_required($input,['section_key','title','sort_order'])) return [false,'أكمل نوع العنصر والعنوان والترتيب.'];
        $sections=['hero','brand','vmm','stat','team_header','team','geo_header','geo','cta'];
        $icons=['default','vision','mission','method','experience','building','projects','clients','person'];
        $styles=['info','success','warning','orange'];
        $section=(string)$input['section_key']; if(!in_array($section,$sections,true)) return [false,'نوع قسم من نحن غير صحيح.'];
        $icon=in_array($input['icon_key']??'',$icons,true)?$input['icon_key']:'default';
        $style=in_array($input['badge_style']??'',$styles,true)?$input['badge_style']:'info';
        $primary=admin_optional_url((string)($input['primary_url']??''),true); $secondary=admin_optional_url((string)($input['secondary_url']??''),true);
        if($primary===null||$secondary===null) return [false,'رابط الإجراء غير صحيح.'];
        $values=[$section,trim((string)$input['title']),trim((string)($input['subtitle']??'')),trim((string)($input['body']??'')),trim((string)($input['badge_label']??'')),$style,trim((string)($input['value_text']??'')),trim((string)($input['value_suffix']??'')),$icon,$primary,$secondary,max(0,(int)$input['sort_order']),isset($input['is_active'])?1:0];
        if($action==='create_about_item') {
            $id=admin_id('ABT'); $stmt=$db->prepare('INSERT INTO about_page_items (id,section_key,title,subtitle,body,badge_label,badge_style,value_text,value_suffix,icon_key,primary_url,secondary_url,sort_order,is_active) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)'); $stmt->execute([$id,...$values]);
            admin_log('create','about_item',$id,$values[1]); return [true,'تمت إضافة عنصر صفحة من نحن.'];
        }
        $id=trim((string)($input['id']??'')); if($id==='') return [false,'معرّف العنصر غير صحيح.'];
        $exists=$db->prepare('SELECT COUNT(*) FROM about_page_items WHERE id=?'); $exists->execute([$id]); if(!(int)$exists->fetchColumn()) return [false,'لم يتم العثور على العنصر.'];
        $stmt=$db->prepare('UPDATE about_page_items SET section_key=?,title=?,subtitle=?,body=?,badge_label=?,badge_style=?,value_text=?,value_suffix=?,icon_key=?,primary_url=?,secondary_url=?,sort_order=?,is_active=?,updated_at=NOW() WHERE id=?'); $stmt->execute([...$values,$id]);
        admin_log('update','about_item',$id,$values[1]); return [$stmt->rowCount()>0,$stmt->rowCount()>0?'تم حفظ عنصر صفحة من نحن.':'لم تتغير بيانات العنصر.'];
    }
    if ($action === 'delete_about_item') {
        $id=trim((string)($input['id']??'')); $stmt=$db->prepare('SELECT title FROM about_page_items WHERE id=?'); $stmt->execute([$id]); $aboutTitle=(string)($stmt->fetchColumn()?:''); if($aboutTitle==='') return [false,'لم يتم العثور على العنصر.'];
        $delete=$db->prepare('DELETE FROM about_page_items WHERE id=?'); $delete->execute([$id]); admin_log('delete','about_item',$id,$aboutTitle); return [true,'تم حذف العنصر من صفحة من نحن.'];
    }
    if (in_array($action,['create_investor_page_item','update_investor_page_item'],true)) {
        if (!admin_required($input,['section_key','title','sort_order'])) return [false,'أكمل نوع العنصر والعنوان والترتيب.'];
        $sections=['hero','investor_type','benefits_header','benefit','journey_header','journey_step','faq_header','faq','cta'];
        $icons=['default','person','company','fund','angel','family','ready','security','flexible','transparency','speed','money'];
        $styles=['info','success','warning','orange'];
        $section=(string)$input['section_key']; if(!in_array($section,$sections,true)) return [false,'نوع قسم صفحة المستثمرين غير صحيح.'];
        $icon=in_array($input['icon_key']??'',$icons,true)?$input['icon_key']:'default';
        $style=in_array($input['badge_style']??'',$styles,true)?$input['badge_style']:'info';
        $primary=admin_optional_url((string)($input['primary_url']??''),true); $secondary=admin_optional_url((string)($input['secondary_url']??''),true);
        if($primary===null||$secondary===null) return [false,'رابط الإجراء غير صحيح.'];
        $values=[$section,trim((string)$input['title']),trim((string)($input['subtitle']??'')),trim((string)($input['body']??'')),trim((string)($input['badge_label']??'')),$style,trim((string)($input['value_text']??'')),trim((string)($input['value_suffix']??'')),$icon,$primary,$secondary,max(0,(int)$input['sort_order']),isset($input['is_active'])?1:0];
        if($action==='create_investor_page_item') {
            $id=admin_id('INVP'); $stmt=$db->prepare('INSERT INTO investor_page_items (id,section_key,title,subtitle,body,badge_label,badge_style,value_text,value_suffix,icon_key,primary_url,secondary_url,sort_order,is_active) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)'); $stmt->execute([$id,...$values]);
            admin_log('create','investor_page_item',$id,$values[1]); return [true,'تمت إضافة عنصر صفحة المستثمرين.'];
        }
        $id=trim((string)($input['id']??'')); if($id==='') return [false,'معرّف العنصر غير صحيح.'];
        $exists=$db->prepare('SELECT COUNT(*) FROM investor_page_items WHERE id=?'); $exists->execute([$id]); if(!(int)$exists->fetchColumn()) return [false,'لم يتم العثور على العنصر.'];
        $stmt=$db->prepare('UPDATE investor_page_items SET section_key=?,title=?,subtitle=?,body=?,badge_label=?,badge_style=?,value_text=?,value_suffix=?,icon_key=?,primary_url=?,secondary_url=?,sort_order=?,is_active=?,updated_at=NOW() WHERE id=?'); $stmt->execute([...$values,$id]);
        admin_log('update','investor_page_item',$id,$values[1]); return [$stmt->rowCount()>0,$stmt->rowCount()>0?'تم حفظ عنصر صفحة المستثمرين.':'لم تتغير بيانات العنصر.'];
    }
    if ($action === 'delete_investor_page_item') {
        $id=trim((string)($input['id']??'')); $stmt=$db->prepare('SELECT title FROM investor_page_items WHERE id=?'); $stmt->execute([$id]); $itemTitle=(string)($stmt->fetchColumn()?:''); if($itemTitle==='') return [false,'لم يتم العثور على العنصر.'];
        $delete=$db->prepare('DELETE FROM investor_page_items WHERE id=?'); $delete->execute([$id]); admin_log('delete','investor_page_item',$id,$itemTitle); return [true,'تم حذف العنصر من صفحة المستثمرين.'];
    }
    if (in_array($action,['create_entrepreneur_page_item','update_entrepreneur_page_item'],true)) {
        if (!admin_required($input,['section_key','title','sort_order'])) return [false,'أكمل نوع العنصر والعنوان والترتيب.'];
        $sections=['hero','stages_header','stage','evaluation_header','criterion','review_header','review_step','apply_header','support_option'];
        $icons=['default','check','layers'];
        $styles=['info','success','warning','orange'];
        $section=(string)$input['section_key']; if(!in_array($section,$sections,true)) return [false,'نوع قسم صفحة رواد الأعمال غير صحيح.'];
        $icon=in_array($input['icon_key']??'',$icons,true)?$input['icon_key']:'default';
        $style=in_array($input['badge_style']??'',$styles,true)?$input['badge_style']:'info';
        $primary=admin_optional_url((string)($input['primary_url']??''),true); $secondary=admin_optional_url((string)($input['secondary_url']??''),true);
        if($primary===null||$secondary===null) return [false,'رابط الإجراء غير صحيح.'];
        $values=[$section,trim((string)$input['title']),trim((string)($input['subtitle']??'')),trim((string)($input['body']??'')),trim((string)($input['badge_label']??'')),$style,trim((string)($input['value_text']??'')),trim((string)($input['value_suffix']??'')),$icon,$primary,$secondary,max(0,(int)$input['sort_order']),isset($input['is_active'])?1:0];
        if($action==='create_entrepreneur_page_item') {
            $id=admin_id('ENTP'); $stmt=$db->prepare('INSERT INTO entrepreneur_page_items (id,section_key,title,subtitle,body,badge_label,badge_style,value_text,value_suffix,icon_key,primary_url,secondary_url,sort_order,is_active) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)'); $stmt->execute([$id,...$values]);
            admin_log('create','entrepreneur_page_item',$id,$values[1]); return [true,'تمت إضافة عنصر صفحة رواد الأعمال.'];
        }
        $id=trim((string)($input['id']??'')); if($id==='') return [false,'معرّف العنصر غير صحيح.'];
        $exists=$db->prepare('SELECT COUNT(*) FROM entrepreneur_page_items WHERE id=?'); $exists->execute([$id]); if(!(int)$exists->fetchColumn()) return [false,'لم يتم العثور على العنصر.'];
        $stmt=$db->prepare('UPDATE entrepreneur_page_items SET section_key=?,title=?,subtitle=?,body=?,badge_label=?,badge_style=?,value_text=?,value_suffix=?,icon_key=?,primary_url=?,secondary_url=?,sort_order=?,is_active=?,updated_at=NOW() WHERE id=?'); $stmt->execute([...$values,$id]);
        admin_log('update','entrepreneur_page_item',$id,$values[1]); return [$stmt->rowCount()>0,$stmt->rowCount()>0?'تم حفظ عنصر صفحة رواد الأعمال.':'لم تتغير بيانات العنصر.'];
    }
    if ($action === 'delete_entrepreneur_page_item') {
        $id=trim((string)($input['id']??'')); $stmt=$db->prepare('SELECT title FROM entrepreneur_page_items WHERE id=?'); $stmt->execute([$id]); $itemTitle=(string)($stmt->fetchColumn()?:''); if($itemTitle==='') return [false,'لم يتم العثور على العنصر.'];
        $delete=$db->prepare('DELETE FROM entrepreneur_page_items WHERE id=?'); $delete->execute([$id]); admin_log('delete','entrepreneur_page_item',$id,$itemTitle); return [true,'تم حذف العنصر من صفحة رواد الأعمال.'];
    }
    if ($action === 'create_meeting') {
        if (!admin_required($input,['user_id','subject','scheduled_at','platform'])) return [false,'أكمل بيانات الاجتماع.'];
        $date=app_db_datetime((string)$input['scheduled_at']); if (!$date) return [false,'موعد الاجتماع غير صحيح.'];
        $id=admin_id('MTG'); $stmt=$db->prepare('INSERT INTO meetings (id,user_id,subject,opportunity,scheduled_at,platform,status) VALUES (?,?,?,?,?,?,?)');
        $stmt->execute([$id,$input['user_id'],trim($input['subject']),trim((string)($input['opportunity']??'')),$date,trim($input['platform']),'pending']);
        admin_log('create','meeting',$id,trim($input['subject'])); return [true,'تم جدولة الاجتماع.'];
    }
    if ($action === 'set_meeting_status') {
        $status=(string)($input['status']??''); if(!in_array($status,['pending','confirmed','cancelled','completed'],true)) return [false,'حالة الاجتماع غير صحيحة.'];
        $stmt=$db->prepare('UPDATE meetings SET status=? WHERE id=?'); $stmt->execute([$status,(string)$input['id']]); admin_log('status_change','meeting',(string)$input['id'],$status); return [$stmt->rowCount()>0,$stmt->rowCount()>0?'تم تحديث الاجتماع.':'لم يتم العثور على الاجتماع.'];
    }
    if ($action === 'create_contract') {
        if (!admin_required($input,['title'])) return [false,'أدخل اسم العقد.']; $id=admin_id('CTR');
        $stmt=$db->prepare('INSERT INTO contracts (id,user_id,title,status) VALUES (?,?,?,?)'); $stmt->execute([$id,($input['user_id']??'')?:null,trim($input['title']),'draft']); admin_log('create','contract',$id,trim($input['title'])); return [true,'تمت إضافة العقد.'];
    }
    if ($action === 'set_contract_status') {
        $status=(string)($input['status']??''); if(!in_array($status,['draft','review','pending_signature','signed','cancelled'],true)) return [false,'حالة العقد غير صحيحة.'];
        $stmt=$db->prepare('UPDATE contracts SET status=?,updated_at=NOW() WHERE id=?'); $stmt->execute([$status,(string)$input['id']]); admin_log('status_change','contract',(string)$input['id'],$status); return [$stmt->rowCount()>0,$stmt->rowCount()>0?'تم تحديث العقد.':'لم يتم العثور على العقد.'];
    }
    if (in_array($action,['create_news_item','update_news_item'],true)) {
        if (!admin_required($input,['title','content_type','category_label','excerpt','published_at','sort_order','status'])) return [false,'أكمل بيانات الخبر أو المقال.'];
        $type=in_array($input['content_type']??'', ['article','news','update'],true)?$input['content_type']:'';
        $status=in_array($input['status']??'', ['draft','published','archived'],true)?$input['status']:'';
        $publishedAt=app_db_datetime((string)$input['published_at']);
        $cover=admin_optional_url((string)($input['cover_image']??''),true); $external=admin_optional_url((string)($input['external_url']??''));
        if($type===''||$status==='') return [false,'نوع المحتوى أو حالته غير صحيحة.'];
        if(!$publishedAt) return [false,'تاريخ النشر غير صحيح.'];
        if($cover===null) return [false,'رابط صورة الغلاف غير صحيح.'];
        if($external===null) return [false,'رابط قراءة المحتوى غير صحيح.'];
        $id='';
        if($action==='update_news_item') {
            $id=trim((string)($input['id']??''));
            if($id==='') return [false,'معرّف المحتوى غير صحيح.'];
            $exists=$db->prepare('SELECT COUNT(*) FROM content_items WHERE id=?'); $exists->execute([$id]); if(!(int)$exists->fetchColumn()) return [false,'لم يتم العثور على المحتوى.'];
        }
        $featured=isset($input['is_featured'])?1:0;
        $values=[trim((string)$input['title']),$type,trim((string)$input['category_label']),trim((string)$input['excerpt']),trim((string)($input['reading_time']??'')),$cover,$external,$featured,max(0,(int)$input['sort_order']),$status,$publishedAt];
        if($featured) $db->exec('UPDATE content_items SET is_featured=0');
        if($action==='create_news_item') {
            $id=admin_id('CNT'); $stmt=$db->prepare('INSERT INTO content_items (id,title,content_type,category_label,excerpt,reading_time,cover_image,external_url,is_featured,sort_order,status,published_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)'); $stmt->execute([$id,...$values]);
            admin_log('create','news_item',$id,$values[0]); return [true,'تمت إضافة المحتوى.'];
        }
        $stmt=$db->prepare('UPDATE content_items SET title=?,content_type=?,category_label=?,excerpt=?,reading_time=?,cover_image=?,external_url=?,is_featured=?,sort_order=?,status=?,published_at=?,updated_at=NOW() WHERE id=?'); $stmt->execute([...$values,$id]);
        admin_log('update','news_item',$id,$values[0]); return [$stmt->rowCount()>0,$stmt->rowCount()>0?'تم حفظ المحتوى.':'لم تتغير بيانات المحتوى.'];
    }
    if ($action === 'delete_news_item') {
        $id=trim((string)($input['id']??'')); $stmt=$db->prepare('SELECT title FROM content_items WHERE id=?'); $stmt->execute([$id]); $itemTitle=(string)($stmt->fetchColumn()?:''); if($itemTitle==='') return [false,'لم يتم العثور على المحتوى.'];
        $delete=$db->prepare('DELETE FROM content_items WHERE id=?'); $delete->execute([$id]); admin_log('delete','news_item',$id,$itemTitle); return [true,'تم حذف المحتوى.'];
    }
    if (in_array($action,['create_event','update_event'],true)) {
        if (!admin_required($input,['title','starts_at','location','description','sort_order','status'])) return [false,'أكمل بيانات الفعالية.'];
        $date=app_db_datetime((string)$input['starts_at']); if(!$date) return [false,'موعد الفعالية غير صحيح.'];
        $status=in_array($input['status']??'', ['draft','published','completed','cancelled'],true)?$input['status']:''; if($status==='') return [false,'حالة الفعالية غير صحيحة.'];
        $registration=admin_optional_url((string)($input['registration_url']??'')); $calendar=admin_optional_url((string)($input['calendar_url']??''));
        if($registration===null||$calendar===null) return [false,'رابط التسجيل أو التقويم غير صحيح.'];
        $capacityText=trim((string)($input['capacity']??'')); $capacity=$capacityText===''?null:max(0,(int)$capacityText); $registered=max(0,(int)($input['registered_count']??0));
        $values=[trim((string)$input['title']),$date,trim((string)$input['location']),trim((string)$input['description']),$capacity,$registered,$registration,$calendar,max(0,(int)$input['sort_order']),$status];
        if($action==='create_event') {
            $id=admin_id('EVT'); $stmt=$db->prepare('INSERT INTO events (id,title,starts_at,location,description,capacity,registered_count,registration_url,calendar_url,sort_order,status) VALUES (?,?,?,?,?,?,?,?,?,?,?)'); $stmt->execute([$id,...$values]);
            admin_log('create','event',$id,$values[0]); return [true,'تمت إضافة الفعالية.'];
        }
        $id=trim((string)($input['id']??'')); if($id==='') return [false,'معرّف الفعالية غير صحيح.'];
        $stmt=$db->prepare('UPDATE events SET title=?,starts_at=?,location=?,description=?,capacity=?,registered_count=?,registration_url=?,calendar_url=?,sort_order=?,status=?,updated_at=NOW() WHERE id=?'); $stmt->execute([...$values,$id]);
        admin_log('update','event',$id,$values[0]); return [$stmt->rowCount()>0,$stmt->rowCount()>0?'تم حفظ الفعالية.':'لم تتغير بيانات الفعالية.'];
    }
    if ($action === 'delete_event') {
        $id=trim((string)($input['id']??'')); $stmt=$db->prepare('SELECT title FROM events WHERE id=?'); $stmt->execute([$id]); $eventTitle=(string)($stmt->fetchColumn()?:''); if($eventTitle==='') return [false,'لم يتم العثور على الفعالية.'];
        $delete=$db->prepare('DELETE FROM events WHERE id=?'); $delete->execute([$id]); admin_log('delete','event',$id,$eventTitle); return [true,'تم حذف الفعالية.'];
    }
    if ($action === 'set_user_role') {
        $id=(string)($input['id']??''); $role=(string)($input['role']??''); if($id===(string)($_SESSION['user_id']??''))return[false,'لا يمكنك تغيير صلاحية حسابك الحالي.']; if(!in_array($role,['investor','entrepreneur','admin'],true))return[false,'الصلاحية غير صحيحة.'];
        $stmt=$db->prepare('UPDATE users SET role=?,updated_at=NOW() WHERE id=?'); $stmt->execute([$role,$id]); admin_log('role_change','user',$id,$role); return [$stmt->rowCount()>0,$stmt->rowCount()>0?'تم تغيير صلاحية المستخدم.':'لم يتم العثور على المستخدم.'];
    }
    if ($action === 'update_admin_password') {
        $current=(string)($input['current_password']??''); $new=(string)($input['new_password']??''); $confirm=(string)($input['confirm_password']??''); $user=auth_find_user_by_id((string)($_SESSION['user_id']??''));
        if(!$user || !password_verify($current,(string)$user['password']))return[false,'كلمة المرور الحالية غير صحيحة.']; if($new!==$confirm)return[false,'تأكيد كلمة المرور غير مطابق.']; $error=auth_password_error($new); if($error!=='')return[false,$error];
        $stmt=$db->prepare('UPDATE users SET password=?,password_updated_at=NOW(),updated_at=NOW() WHERE id=? AND role=\'admin\''); $stmt->execute([password_hash($new,PASSWORD_DEFAULT),(string)$user['id']]); admin_log('password_change','user',(string)$user['id']); return [true,'تم تغيير كلمة المرور.'];
    }
    return [false, 'الإجراء غير معروف.'];
}

function admin_status_label(string $type, string $status): string
{
    $labels=['available'=>'متاحة','review'=>'قيد المراجعة','completed'=>'مكتملة','pending'=>'معلق','confirmed'=>'مؤكد','cancelled'=>'ملغى','signed'=>'موقع','pending_signature'=>'بانتظار التوقيع','draft'=>'مسودة','published'=>'منشور','archived'=>'مؤرشف'];
    return $labels[$status] ?? $status;
}
