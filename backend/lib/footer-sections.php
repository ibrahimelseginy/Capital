<?php
declare(strict_types=1);require_once __DIR__.'/admin.php';require_once __DIR__.'/home.php';

function footer_schema():array
{
    $text=static fn(string $label,int $max=250):array=>['label'=>$label,'type'=>'text','max'=>$max];$area=static fn(string $label,int $max=2000):array=>['label'=>$label,'type'=>'textarea','max'=>$max];$url=static fn(string $label):array=>['label'=>$label,'type'=>'url','max'=>1000];$meta=['sort_order'=>['label'=>'الترتيب','type'=>'number','max'=>1000,'default'=>1],'is_active'=>['label'=>'إظهار','type'=>'checkbox','default'=>true]];$list=static fn(string $label,array $fields,int $max=30):array=>['label'=>$label,'type'=>'list','max'=>$max,'fields'=>$fields+$meta];
    return ['footer'=>['label'=>'محتوى الفوتر','fields'=>[
        'description'=>$area('وصف Seven Tech Capital'),
        'platform_title'=>$text('عنوان روابط المنصة',100),'platform_links'=>$list('روابط المنصة',['label'=>$text('اسم الرابط',150),'url'=>$url('الرابط')],20),
        'company_title'=>$text('عنوان روابط الشركة',100),'company_links'=>$list('روابط الشركة',['label'=>$text('اسم الرابط',150),'url'=>$url('الرابط')],20),
        'contact_title'=>$text('عنوان التواصل السريع',100),'email'=>$text('البريد الإلكتروني',250),'phone'=>$text('رقم الهاتف',60),'address'=>$text('العنوان',350),
        'legal_title'=>$text('عنوان الروابط القانونية',100),'legal_links'=>$list('الروابط القانونية',['label'=>$text('اسم الرابط',150),'url'=>$url('الرابط — يمكن تركه فارغًا')],20),
        'copyright'=>$text('حقوق النشر',300),'team_label'=>$text('نص رابط التواصل مع الفريق',150),'team_url'=>$url('رابط التواصل مع الفريق'),
    ]+$meta]];
}

function footer_defaults():array
{
    return ['footer'=>[
        'description'=>'صندوق استثماري مدعوم بذراع تقني بخبرة تمتد إلى 20 عامًا. نبني المشروع ونُجهّزه للتشغيل قبل تفعيل رأس المال.',
        'platform_title'=>'المنصة','platform_links'=>[
            ['label'=>'للمستثمرين','url'=>'investors.html','sort_order'=>1,'is_active'=>true],['label'=>'لرواد الأعمال','url'=>'entrepreneurs.html','sort_order'=>2,'is_active'=>true],['label'=>'القطاعات المستهدفة','url'=>'sectors.html','sort_order'=>3,'is_active'=>true],['label'=>'قصص النجاح','url'=>'success-stories.html','sort_order'=>4,'is_active'=>true],['label'=>'تسجيل الدخول','url'=>'login.html','sort_order'=>5,'is_active'=>true],
        ],
        'company_title'=>'الشركة','company_links'=>[
            ['label'=>'من نحن','url'=>'about.html','sort_order'=>1,'is_active'=>true],['label'=>'الذراع التقني','url'=>'seven-tech.html','sort_order'=>2,'is_active'=>true],['label'=>'الأخبار والفعاليات','url'=>'news-events.html','sort_order'=>3,'is_active'=>true],['label'=>'تواصل معنا','url'=>'contact.html','sort_order'=>4,'is_active'=>true],
        ],
        'contact_title'=>'تواصل سريع','email'=>'hello@seventech.capital','phone'=>'+966539555889','address'=>'القاهرة، مصر · نطاق العمل MENA والخليج',
        'legal_title'=>'قانوني','legal_links'=>[
            ['label'=>'الشروط والأحكام','url'=>'','sort_order'=>1,'is_active'=>true],['label'=>'سياسة الخصوصية','url'=>'','sort_order'=>2,'is_active'=>true],['label'=>'إخلاء المسؤولية','url'=>'','sort_order'=>3,'is_active'=>true],['label'=>'سياسة KYC/AML','url'=>'','sort_order'=>4,'is_active'=>true],['label'=>'ملفات تعريف الارتباط','url'=>'','sort_order'=>5,'is_active'=>true],
        ],
        'copyright'=>'© 2026 Seven Tech Capital — جميع الحقوق محفوظة.','team_label'=>'تواصل مع الفريق','team_url'=>'contact.html','sort_order'=>1,'is_active'=>true,
    ]];
}

function footer_db():PDO
{
    $db=admin_db();static $ready=false;if(!$ready){$db->exec('CREATE TABLE IF NOT EXISTS footer_sections(section_key VARCHAR(40) PRIMARY KEY,content_json MEDIUMTEXT NOT NULL,revision INT UNSIGNED NOT NULL DEFAULT 1,updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');$seed=$db->prepare('INSERT IGNORE INTO footer_sections(section_key,content_json) VALUES (?,?)');foreach(footer_defaults() as $key=>$value)$seed->execute([$key,json_encode($value,JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR)]);$ready=true;}return $db;
}

function footer_read(?PDO $db=null):array
{
    $db??=footer_db();$defaults=footer_defaults();$schema=footer_schema();$sections=[];foreach($db->query('SELECT * FROM footer_sections') as $row){$key=(string)$row['section_key'];if(!isset($schema[$key]))continue;$stored=json_decode((string)$row['content_json'],true,64,JSON_THROW_ON_ERROR);$values=is_array($stored)?$stored:[];foreach($defaults[$key] as $field=>$value)if(!array_key_exists($field,$values))$values[$field]=$value;$sections[$key]=['content'=>home_default_fields($schema[$key]['fields'],$values),'revision'=>(int)$row['revision'],'updated_at'=>(string)$row['updated_at']];}return $sections;
}

function footer_validate(string $key,array $input):array
{
    $schema=footer_schema()[$key]??null;if(!$schema)throw new InvalidArgumentException('القسم غير موجود.');$content=home_validate($schema['fields'],$input);if($content['description']===''||$content['platform_title']===''||$content['company_title']===''||$content['contact_title']===''||$content['legal_title']===''||$content['copyright']==='')throw new InvalidArgumentException('أكمل عناوين الفوتر والوصف وحقوق النشر.');if(!filter_var($content['email'],FILTER_VALIDATE_EMAIL))throw new InvalidArgumentException('اكتب بريدًا إلكترونيًا صحيحًا.');foreach(['platform_links','company_links','legal_links'] as $group)foreach($content[$group] as $item)if($item['label']==='')throw new InvalidArgumentException('اكتب اسم كل رابط.');return $content;
}

function footer_save(string $key,array $input,int $revision,string $adminId):int
{
    $content=footer_validate($key,$input);$db=footer_db();$db->beginTransaction();try{$stmt=$db->prepare('UPDATE footer_sections SET content_json=?,revision=revision+1,updated_at=NOW() WHERE section_key=? AND revision=?');$stmt->execute([json_encode($content,JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR),$key,$revision]);if($stmt->rowCount()!==1)throw new RuntimeException('تم تعديل الفوتر في جلسة أخرى. حدّث الصفحة قبل الحفظ.',409);$db->prepare('INSERT INTO admin_audit_log(admin_user_id,action,entity_type,entity_id,details) VALUES (?,?,?,?,?)')->execute([$adminId,'update','footer',$key,'تحديث محتوى الفوتر']);$db->commit();return $revision+1;}catch(Throwable $error){if($db->inTransaction())$db->rollBack();throw $error;}
}

function footer_public_payload(array $sections):array
{
    $content=$sections['footer']['content']??footer_defaults()['footer'];$active=(bool)$content['is_active'];unset($content['sort_order'],$content['is_active']);foreach(['platform_links','company_links','legal_links'] as $group)$content[$group]=home_visible($content[$group]);return ['ok'=>true,'active'=>$active,'data'=>$content];
}
