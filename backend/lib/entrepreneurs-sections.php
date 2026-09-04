<?php
declare(strict_types=1);
require_once __DIR__.'/admin.php';
require_once __DIR__.'/home.php';

function entrepreneurs_schema(): array
{
    $text=static fn(string $label,int $max=190):array=>['label'=>$label,'type'=>'text','max'=>$max];
    $body=['label'=>'الوصف','type'=>'textarea','max'=>1000];
    $url=static fn(string $label):array=>['label'=>$label,'type'=>'url','max'=>500];
    $meta=['sort_order'=>['label'=>'الترتيب','type'=>'number','max'=>1000,'default'=>1],'is_active'=>['label'=>'إظهار للجمهور','type'=>'checkbox','default'=>true]];
    $style=['label'=>'نمط البطاقة','type'=>'select','options'=>['orange'=>'بارز برتقالي','info'=>'عادي','success'=>'أخضر','warning'=>'ذهبي'],'default'=>'info'];
    $icons=['default'=>'افتراضية','check'=>'علامة تحقق','layers'=>'طبقات الدعم'];
    $icon=['label'=>'الأيقونة','type'=>'select','options'=>$icons];
    $list=static fn(string $label,array $fields):array=>['label'=>$label,'type'=>'list','max'=>40,'fields'=>['id'=>['label'=>'المعرّف','type'=>'hidden','max'=>36]]+$fields+$meta];
    $fixed=static fn(string $label,array $fields):array=>['label'=>$label,'type'=>'list','max'=>1,'fixed'=>true,'fields'=>$fields];
    $intro=['subtitle'=>$text('التسمية أعلى العنوان',255),'title'=>$text('العنوان'),'body'=>$body];
    return [
        'hero'=>['label'=>'المقدمة — لرواد الأعمال','header'=>'hero','fields'=>$intro+[
            'badge_label'=>$text('نص زر قدّم مشروعك الآن',120),'primary_url'=>$url('رابط نموذج التقديم'),
            'value_text'=>$text('نص زر استكشف مراحل الدعم',255),'secondary_url'=>$url('رابط مراحل الدعم'),
            'value_suffix'=>$text('محتوى بطاقة الملخص — افصل بعلامة |',500),
        ]+$meta],
        'stages'=>['label'=>'مراحل الدعم','header'=>'stages_header','item_key'=>'stage','fields'=>$intro+[
            'items'=>$list('بطاقات مراحل الدعم',['title'=>$text('اسم المرحلة'),'subtitle'=>$text('وصف المرحلة',255)])
        ]+$meta],
        'evaluation'=>['label'=>'معايير التقييم','header'=>'evaluation_header','item_key'=>'criterion','fields'=>$intro+[
            'items'=>$list('بطاقات معايير التقييم',['title'=>$text('اسم المعيار'),'body'=>$body,'icon_key'=>$icon])
        ]+$meta],
        'apply'=>['label'=>'نموذج التقديم','header'=>'apply_header','item_key'=>'support_option','fields'=>$intro+[
            'items'=>$list('خيارات الدعم في الخطوة الأولى',['title'=>$text('نوع الدعم'),'subtitle'=>$text('وصف الدعم',255),'icon_key'=>$icon,'badge_style'=>$style]),
            'step_1'=>$fixed('01 — الدعم',[
                'number'=>$text('رقم الخطوة',10),'title'=>$text('اسم الخطوة في الشريط',80),'section_title'=>$text('عنوان اختيار الدعم',190),
                'project_name_label'=>$text('تسمية اسم المشروع',190),'project_name_placeholder'=>$text('مثال اسم المشروع',255),
                'sector_label'=>$text('تسمية القطاع',190),'sector_options'=>['label'=>'خيارات القطاع — افصل بعلامة |','type'=>'textarea','max'=>1000],
                'summary_label'=>$text('تسمية ملخص الفكرة',190),'summary_placeholder'=>$text('مثال ملخص الفكرة',500),
                'draft_label'=>$text('نص الحفظ التلقائي',190),'save_label'=>$text('نص زر حفظ المسودة',190),'next_label'=>$text('نص زر الخطوة التالية',190),
            ]),
            'step_2'=>$fixed('02 — المشروع',[
                'number'=>$text('رقم الخطوة',10),'title'=>$text('اسم الخطوة في الشريط',80),'section_title'=>$text('عنوان الخطوة',190),
                'stage_label'=>$text('تسمية المرحلة الحالية',190),'stage_options'=>['label'=>'خيارات المرحلة — افصل بعلامة |','type'=>'textarea','max'=>1000],
                'funding_label'=>$text('تسمية التمويل المطلوب',190),'funding_placeholder'=>$text('مثال التمويل المطلوب',190),
                'market_label'=>$text('تسمية السوق المستهدف والعملاء',190),'market_placeholder'=>$text('مثال السوق المستهدف والعملاء',500),
                'advantage_label'=>$text('تسمية الميزة التنافسية',190),'advantage_placeholder'=>$text('مثال الميزة التنافسية',500),
                'draft_label'=>$text('نص الحفظ التلقائي',190),'save_label'=>$text('نص زر حفظ المسودة',190),'next_label'=>$text('نص زر الخطوة التالية',190),'previous_label'=>$text('نص زر الخطوة السابقة',190),
            ]),
            'step_3'=>$fixed('03 — الفريق',[
                'number'=>$text('رقم الخطوة',10),'title'=>$text('اسم الخطوة في الشريط',80),'section_title'=>$text('عنوان الخطوة',190),
                'founders_label'=>$text('تسمية عدد المؤسسين',190),'founders_default'=>$text('القيمة الافتراضية لعدد المؤسسين',20),
                'cto_label'=>$text('سؤال الشريك التقني (CTO)',255),'cto_options'=>['label'=>'خيارات الشريك التقني — افصل بعلامة |','type'=>'textarea','max'=>1000],
                'experience_label'=>$text('تسمية خبرات الفريق السابقة',190),'experience_placeholder'=>$text('مثال خبرات الفريق السابقة',500),
                'draft_label'=>$text('نص الحفظ التلقائي',190),'save_label'=>$text('نص زر حفظ المسودة',190),'next_label'=>$text('نص زر الخطوة التالية',190),'previous_label'=>$text('نص زر الخطوة السابقة',190),
            ]),
            'step_4'=>$fixed('04 — الملفات',[
                'number'=>$text('رقم الخطوة',10),'title'=>$text('اسم الخطوة في الشريط',80),'section_title'=>$text('عنوان الخطوة',190),
                'upload_label'=>$text('تسمية عرض المشروع',255),'upload_title'=>$text('نص رفع الملف',500),'upload_hint'=>$text('أنواع الملفات والحد الأقصى',500),
                'consent_label'=>$text('نص الموافقة على السرية ومعالجة البيانات',500),
                'draft_label'=>$text('نص الحفظ التلقائي',190),'save_label'=>$text('نص زر حفظ المسودة',190),'submit_label'=>$text('نص زر الإرسال النهائي',190),'previous_label'=>$text('نص زر الخطوة السابقة',190),
            ]),
        ]+$meta],
    ];
}

function entrepreneurs_form_defaults(): array
{
    return [
        'step_1'=>[[
            'number'=>'01','title'=>'الدعم','section_title'=>'اختر الدعم *','project_name_label'=>'اسم المشروع *','project_name_placeholder'=>'مثال: منصة X',
            'sector_label'=>'القطاع *','sector_options'=>'البرمجيات و SaaS|التقنية المالية|الذكاء الاصطناعي|الصحة الرقمية|التعليم التقني|اللوجستيات',
            'summary_label'=>'ملخص الفكرة *','summary_placeholder'=>'المشكلة، الحل، والقيمة المضافة...','draft_label'=>'حفظ تلقائي للمسودة',
            'save_label'=>'حفظ كمسودة','next_label'=>'الانتقال إلى الخطوة التالية →',
        ]],
        'step_2'=>[[
            'number'=>'02','title'=>'المشروع','section_title'=>'المرحلة والاحتياج التمويلي','stage_label'=>'المرحلة الحالية','stage_options'=>'فكرة|نموذج أولي|MVP|إطلاق|نمو',
            'funding_label'=>'التمويل المطلوب ($)','funding_placeholder'=>'$ 250,000','market_label'=>'السوق المستهدف والعملاء',
            'market_placeholder'=>'مثال: قطاع التجزئة والمقاهي في السعودية ومصر','advantage_label'=>'الميزة التنافسية','advantage_placeholder'=>'ما الذي يميّز حلّك عن المنافسين؟',
            'draft_label'=>'حفظ تلقائي للمسودة','save_label'=>'حفظ كمسودة','next_label'=>'الانتقال إلى الخطوة التالية →','previous_label'=>'← الانتقال إلى الخطوة السابقة',
        ]],
        'step_3'=>[[
            'number'=>'03','title'=>'الفريق','section_title'=>'الفريق الحالي والهيكل','founders_label'=>'عدد المؤسسين','founders_default'=>'2',
            'cto_label'=>'هل يوجد شريك تقني (CTO)؟','cto_options'=>'نعم — شريك مؤسس|لا — بحاجة لذراع تقني|قيد التوظيف',
            'experience_label'=>'خبرات الفريق السابقة','experience_placeholder'=>'نبذة عن خبرات وإنجازات فريق العمل...','draft_label'=>'حفظ تلقائي للمسودة',
            'save_label'=>'حفظ كمسودة','next_label'=>'الانتقال إلى الخطوة التالية →','previous_label'=>'← الانتقال إلى الخطوة السابقة',
        ]],
        'step_4'=>[[
            'number'=>'04','title'=>'الملفات','section_title'=>'المرفقات وطلب الدعم','upload_label'=>'عرض المشروع (Pitch Deck / Business Plan)',
            'upload_title'=>'اسحب وأسقط عرض المشروع هنا أو انقر للتصفح','upload_hint'=>'يدعم PDF, PPTX, DOCX (بحد أقصى 25MB)',
            'consent_label'=>'أوافق على شروط السرية وسياسة معالجة البيانات','draft_label'=>'حفظ تلقائي للمسودة','save_label'=>'حفظ كمسودة',
            'submit_label'=>'إرسال الطلب النهائي ✔','previous_label'=>'← الانتقال إلى الخطوة السابقة',
        ]],
    ];
}


function entrepreneurs_db(): PDO
{
    $db=admin_db();
    static $ready=false;
    if (!$ready) {
        $db->exec('CREATE TABLE IF NOT EXISTS entrepreneur_page_sections (section_key VARCHAR(40) PRIMARY KEY, is_active TINYINT(1) NOT NULL DEFAULT 1, sort_order INT NOT NULL DEFAULT 0, revision INT UNSIGNED NOT NULL DEFAULT 1, content_json MEDIUMTEXT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        if (!$db->query("SHOW COLUMNS FROM entrepreneur_page_sections LIKE 'content_json'")->fetch()) $db->exec('ALTER TABLE entrepreneur_page_sections ADD COLUMN content_json MEDIUMTEXT NULL AFTER revision');
        $seed=$db->prepare('INSERT IGNORE INTO entrepreneur_page_sections (section_key,sort_order) VALUES (?,?)');
        foreach (array_keys(entrepreneurs_schema()) as $index=>$key) $seed->execute([$key,$index+1]);
        $ready=true;
    }
    return $db;
}

function entrepreneurs_section_rows(array $rows,string $key): array
{
    $schema=entrepreneurs_schema()[$key];
    $types=[$schema['item_key']??$key,$schema['header']??$key];
    return array_values(array_filter($rows,static fn(array $row):bool=>in_array($row['section_key'],$types,true)));
}

function entrepreneurs_sections_from_rows(array $rows,array $settings): array
{
    $sections=[];
    foreach (entrepreneurs_schema() as $key=>$schema) {
        $owned=entrepreneurs_section_rows($rows,$key);
        $meta=$settings[$key];
        $stored=[];
        if (!empty($meta['content_json'])) { $decoded=json_decode((string)$meta['content_json'],true); if (is_array($decoded)) $stored=$decoded; }
        $values=$key==='apply'?array_replace_recursive(entrepreneurs_form_defaults(),$stored):$stored;
        $values['is_active']=(bool)$meta['is_active'];$values['sort_order']=(int)$meta['sort_order'];
        if (isset($schema['header'])) {
            foreach ($owned as $row) if ($row['section_key']===$schema['header']) { $values+=array_diff_key($row,['id'=>true]); break; }
        }
        if (isset($schema['fields']['items'])) {
            $values['items']=array_values(array_filter($owned,static fn(array $row):bool=>$row['section_key']===($schema['item_key']??$key)));
            foreach ($values['items'] as &$item) { $item['sort_order']=(int)$item['sort_order']; $item['is_active']=(bool)$item['is_active']; } unset($item);
        }
        $sections[$key]=['content'=>home_default_fields($schema['fields'],$values),'revision'=>hash('sha256',json_encode([$owned,$meta],JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR))];
    }
    return $sections;
}

function entrepreneurs_read(?PDO $db=null,bool $lock=false): array
{
    $db??=entrepreneurs_db();
    $suffix=$lock?' FOR UPDATE':'';
    $settings=[];
    foreach ($db->query('SELECT * FROM entrepreneur_page_sections ORDER BY section_key'.$suffix) as $row) $settings[$row['section_key']]=$row;
    $rows=$db->query('SELECT * FROM entrepreneur_page_items ORDER BY section_key,sort_order,id'.$suffix)->fetchAll();
    return ['rows'=>$rows,'settings'=>$settings,'sections'=>entrepreneurs_sections_from_rows($rows,$settings)];
}

function entrepreneurs_validate(string $key,array $input): array
{
    $schema=entrepreneurs_schema()[$key]??null;
    if (!$schema) throw new InvalidArgumentException('القسم غير موجود.');
    $content=home_validate($schema['fields'],$input);
    if (isset($content['title']) && $content['title']==='') throw new InvalidArgumentException('اكتب عنوان القسم.');
    $ids=[];
    foreach ($content['items']??[] as $item) {
        if ($item['title']==='') throw new InvalidArgumentException('اكتب عنوان كل بطاقة.');
        if ($item['id']!=='' && isset($ids[$item['id']])) throw new InvalidArgumentException('توجد بطاقة مكررة.');
        if ($item['id']!=='') $ids[$item['id']]=true;
    }
    if ($key==='apply') foreach (['step_1','step_2','step_3','step_4'] as $step) {
        if (count($content[$step]??[])!==1 || trim((string)($content[$step][0]['title']??''))==='') throw new InvalidArgumentException('أكمل محتوى خطوات نموذج التقديم الأربع.');
    }
    return $content;
}

function entrepreneurs_blank_row(): array
{
    return ['title'=>'','subtitle'=>'','body'=>'','badge_label'=>'','badge_style'=>'info','value_text'=>'','value_suffix'=>'','icon_key'=>'default','primary_url'=>'','secondary_url'=>'','sort_order'=>1,'is_active'=>true];
}

function entrepreneurs_write_row(PDO $db,array $row): void
{
    $columns=['id','section_key',...array_keys(entrepreneurs_blank_row())];
    $sql='INSERT INTO entrepreneur_page_items ('.implode(',',$columns).') VALUES ('.implode(',',array_fill(0,count($columns),'?')).') ON DUPLICATE KEY UPDATE ';
    $sql.=implode(',',array_map(static fn(string $column):string=>$column.'=VALUES('.$column.')',array_slice($columns,2))).',updated_at=NOW()';
    $db->prepare($sql)->execute(array_map(static fn(string $column)=>$column==='is_active'?(int)$row[$column]:$row[$column],$columns));
}

function entrepreneurs_save(string $key,array $input,string $revision,string $adminId): array
{
    $content=entrepreneurs_validate($key,$input);
    $db=entrepreneurs_db();
    $db->beginTransaction();
    try {
        $current=entrepreneurs_read($db,true);
        if (!hash_equals($current['sections'][$key]['revision'],$revision)) throw new RuntimeException('تم تعديل هذا القسم في جلسة أخرى. حدّث الصفحة قبل الحفظ.',409);
        $schema=entrepreneurs_schema()[$key];
        $owned=entrepreneurs_section_rows($current['rows'],$key);
        $itemIds=[];
        if (isset($schema['header'])) {
            $existing=null;
            foreach ($owned as $row) if ($row['section_key']===$schema['header']) { $existing=$row; break; }
            $header=($existing??[])+entrepreneurs_blank_row();
            $header['id']=$existing['id']??admin_id('ENTP');
            $header['section_key']=$schema['header'];
            foreach (array_keys($schema['fields']) as $field) if (array_key_exists($field,entrepreneurs_blank_row()) && !in_array($field,['sort_order','is_active'],true)) $header[$field]=$content[$field];
            $header['is_active']=true;
            entrepreneurs_write_row($db,$header);
        }
        if (isset($content['items'])) {
            $existing=[];
            foreach ($owned as $row) if ($row['section_key']===($schema['item_key']??$key)) $existing[$row['id']]=$row;
            foreach ($content['items'] as $item) {
                $id=$item['id'];
                if ($id!=='' && !isset($existing[$id])) throw new InvalidArgumentException('البطاقة غير موجودة في هذا القسم.');
                $row=($existing[$id]??[])+entrepreneurs_blank_row();
                foreach ($item as $field=>$value) $row[$field]=$value;
                $row['id']=$id!==''?$id:admin_id('ENTP');
                $row['section_key']=$schema['item_key']??$key;
                entrepreneurs_write_row($db,$row);
                $itemIds[]=$row['id'];
            }
            foreach (array_keys($existing) as $id) if (!in_array($id,$itemIds,true)) $db->prepare('DELETE FROM entrepreneur_page_items WHERE id=? AND section_key=?')->execute([$id,$schema['item_key']??$key]);
        }
        $stored=[];
        foreach ($schema['fields'] as $field=>$definition) if (!array_key_exists($field,entrepreneurs_blank_row()) && !in_array($field,['items','is_active','sort_order'],true)) $stored[$field]=$content[$field];
        $db->prepare('UPDATE entrepreneur_page_sections SET is_active=?,sort_order=?,content_json=?,revision=revision+1 WHERE section_key=?')->execute([(int)$content['is_active'],$content['sort_order'],$stored?json_encode($stored,JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR):null,$key]);
        $db->prepare('INSERT INTO admin_audit_log (admin_user_id,action,entity_type,entity_id,details) VALUES (?,?,?,?,?)')->execute([$adminId,'update','entrepreneurs_page',$key,'تحديث قسم '.$schema['label']]);
        $saved=entrepreneurs_read($db);
        $db->commit();
        return ['revision'=>$saved['sections'][$key]['revision'],'item_ids'=>$itemIds];
    } catch (Throwable $error) { if ($db->inTransaction()) $db->rollBack(); throw $error; }
}

function entrepreneurs_public_payload(array $state): array
{
    $schema=entrepreneurs_schema();
    $settings=$state['settings'];
    uasort($settings,static fn(array $a,array $b):int=>(int)$a['sort_order']<=>(int)$b['sort_order']);
    $rows=[];$sections=[];
    foreach ($settings as $key=>$setting) {
        if (!isset($schema[$key])) continue;
        $sections[]=['key'=>$key,'is_active'=>(bool)$setting['is_active'],'sort_order'=>(int)$setting['sort_order']];
        if (!$setting['is_active']) continue;
        foreach (entrepreneurs_section_rows($state['rows'],$key) as $row) {
            if (!$row['is_active']) continue;
            $public=array_intersect_key($row,array_flip(['id','section_key',...array_keys(entrepreneurs_blank_row())]));
            $public['sort_order']=(int)$public['sort_order'];$public['is_active']=true;
            foreach (['primary_url','secondary_url'] as $field) if (!home_safe_url($public[$field])) $public[$field]='';
            $rows[]=$public;
        }
    }
    $form=[];$apply=$state['sections']['apply']['content']??[];
    foreach (['step_1','step_2','step_3','step_4'] as $step) $form[$step]=$apply[$step][0]??entrepreneurs_form_defaults()[$step][0];
    return ['ok'=>true,'data'=>$rows,'meta'=>['count'=>count($rows),'sections'=>$sections,'form'=>$form]];
}
