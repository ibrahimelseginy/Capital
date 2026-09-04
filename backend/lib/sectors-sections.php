<?php
declare(strict_types=1);
require_once __DIR__.'/admin.php';
require_once __DIR__.'/home.php';

function sectors_schema(): array
{
    $text=static fn(string $label,int $max=250):array=>['label'=>$label,'type'=>'text','max'=>$max];
    $area=static fn(string $label,int $max=2000):array=>['label'=>$label,'type'=>'textarea','max'=>$max];
    $url=static fn(string $label):array=>['label'=>$label,'type'=>'url','max'=>500];
    $meta=['sort_order'=>['label'=>'الترتيب','type'=>'number','max'=>1000,'default'=>1],'is_active'=>['label'=>'إظهار للجمهور','type'=>'checkbox','default'=>true]];
    $icons=['software'=>'برمجيات','fintech'=>'تقنية مالية','ai'=>'ذكاء اصطناعي','health'=>'صحة رقمية','education'=>'تعليم','iot'=>'إنترنت الأشياء','logistics'=>'لوجستيات','digital'=>'تحول رقمي'];
    $items=['label'=>'بطاقات القطاعات','type'=>'list','max'=>24,'fields'=>[
        'code'=>$text('رقم / رمز القطاع',20),'name'=>$text('اسم القطاع',190),'description'=>$area('وصف القطاع',1000),
        'tags'=>$area('التخصصات — افصل بعلامة |',1000),'icon_key'=>['label'=>'الأيقونة','type'=>'select','options'=>$icons,'default'=>'software'],
    ]+$meta];
    return [
        'hero'=>['label'=>'المقدمة — القطاعات المستهدفة','fields'=>[
            'eyebrow'=>$text('التسمية أعلى العنوان'),'title'=>$text('العنوان الرئيسي'),'description'=>$area('الوصف'),
            'summary_value'=>$text('القيمة داخل البوكس',80),'summary_text'=>$text('وصف البوكس',500),
        ]+$meta],
        'map'=>['label'=>'خريطة الفرص','fields'=>[
            'eyebrow'=>$text('التسمية أعلى العنوان'),'title'=>$text('العنوان'),'description'=>$area('الوصف'),'items'=>$items,
        ]+$meta],
        'opportunities'=>['label'=>'الفرص الاستثمارية المتاحة','fields'=>[
            'eyebrow'=>$text('التسمية أعلى العنوان'),'title'=>$text('العنوان'),'description'=>$area('الوصف'),
            'updated_label'=>$text('تسمية وقت التحديث',100),'empty_title'=>$text('رسالة عدم وجود فرص',255),'empty_description'=>$area('وصف عدم وجود فرص',1000),
        ]+$meta],
        'protected'=>['label'=>'تفاصيل الفرص محمية','fields'=>[
            'title'=>$text('العنوان'),'description'=>$area('الوصف'),'button_label'=>$text('نص الزر',120),'button_url'=>$url('رابط الزر'),
        ]+$meta],
    ];
}

function sectors_initial_defaults(PDO $db): array
{
    $settings=[];
    try { foreach ($db->query("SELECT setting_key,setting_value FROM site_settings WHERE setting_key IN ('sectors_eyebrow','sectors_title','sectors_description')") as $row) $settings[$row['setting_key']]=$row['setting_value']; }
    catch (Throwable) {}
    $items=[];
    try {
        foreach ($db->query('SELECT code,name,description,tags_json,icon_key,sort_order,is_active FROM sector_map ORDER BY sort_order,code') as $row) {
            $tags=json_decode((string)$row['tags_json'],true);
            $items[]=['code'=>(string)$row['code'],'name'=>(string)$row['name'],'description'=>(string)$row['description'],'tags'=>is_array($tags)?implode('|',$tags):'',
                'icon_key'=>(string)$row['icon_key'],'sort_order'=>(int)$row['sort_order'],'is_active'=>(bool)$row['is_active']];
        }
    } catch (Throwable) {}
    return [
        'hero'=>['eyebrow'=>'القطاعات المستهدفة','title'=>'نستثمر في ما نفهمه ونبنيه','description'=>'ثمانية قطاعات نمتلك فيها خبرة تقنية وتشغيلية عميقة، مع عرض الفرص المتاحة التي تنشرها الإدارة مباشرة.','summary_value'=>'8 قطاعات','summary_text'=>'فرص تقنية قابلة للبناء والتوسع في MENA والخليج.','sort_order'=>1,'is_active'=>true],
        'map'=>['eyebrow'=>$settings['sectors_eyebrow']??'خريطة الفرص','title'=>$settings['sectors_title']??'قطاعات مختارة بمعايير تشغيلية واضحة','description'=>$settings['sectors_description']??'نعرض البيانات العامة للفرص المتاحة، بينما تبقى التفاصيل الحساسة داخل بيئة المستثمر المعتمد.','items'=>$items,'sort_order'=>2,'is_active'=>true],
        'opportunities'=>['eyebrow'=>'متصلة بلوحة الإدارة','title'=>'الفرص الاستثمارية المتاحة','description'=>'تُحدّث القائمة تلقائيًا من الفرص المنشورة في لوحة الإدارة.','updated_label'=>'آخر تحديث','empty_title'=>'لا توجد فرص استثمارية منشورة حاليًا','empty_description'=>'ستظهر الفرص هنا تلقائيًا فور نشرها من لوحة الإدارة.','sort_order'=>3,'is_active'=>true],
        'protected'=>['title'=>'تفاصيل الفرص محمية','description'=>'تظهر البيانات العامة هنا، أما المستندات والتفاصيل الحساسة فتتاح للمستثمرين المعتمدين.','button_label'=>'سجّل كمستثمر','button_url'=>'login.php?tab=register','sort_order'=>4,'is_active'=>true],
    ];
}

function sectors_db(): PDO
{
    $db=admin_db();
    static $ready=false;
    if (!$ready) {
        $db->exec('CREATE TABLE IF NOT EXISTS sectors_page_sections (section_key VARCHAR(40) PRIMARY KEY, content_json MEDIUMTEXT NOT NULL, revision INT UNSIGNED NOT NULL DEFAULT 1, updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        $seed=$db->prepare('INSERT IGNORE INTO sectors_page_sections(section_key,content_json) VALUES (?,?)');
        foreach (sectors_initial_defaults($db) as $key=>$content) $seed->execute([$key,json_encode($content,JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR)]);
        $ready=true;
    }
    return $db;
}

function sectors_read(?PDO $db=null): array
{
    $db??=sectors_db();$defaults=sectors_initial_defaults($db);$sections=[];
    foreach ($db->query('SELECT * FROM sectors_page_sections') as $row) {
        $key=(string)$row['section_key'];if (!isset(sectors_schema()[$key])) continue;
        $stored=json_decode((string)$row['content_json'],true,64,JSON_THROW_ON_ERROR);
        $values=is_array($stored)?$stored:[];
        foreach ($defaults[$key] as $field=>$value) if (!array_key_exists($field,$values)) $values[$field]=$value;
        $sections[$key]=['content'=>home_default_fields(sectors_schema()[$key]['fields'],$values),'revision'=>(int)$row['revision'],'updated_at'=>(string)$row['updated_at']];
    }
    foreach (sectors_schema() as $key=>$schema) if (!isset($sections[$key])) $sections[$key]=['content'=>home_default_fields($schema['fields'],$defaults[$key]),'revision'=>1,'updated_at'=>''];
    return $sections;
}

function sectors_validate(string $key,array $input): array
{
    $schema=sectors_schema()[$key]??null;if (!$schema) throw new InvalidArgumentException('القسم غير موجود.');
    $content=home_validate($schema['fields'],$input);
    if (($content['title']??'')==='') throw new InvalidArgumentException('اكتب عنوان القسم.');
    if ($key==='map') {
        $codes=[];
        foreach ($content['items'] as $item) {
            if ($item['code']==='' || $item['name']==='') throw new InvalidArgumentException('اكتب رمز واسم كل قطاع.');
            if (isset($codes[$item['code']])) throw new InvalidArgumentException('رمز القطاع مكرر.');
            $codes[$item['code']]=true;
        }
    }
    return $content;
}

function sectors_save(string $key,array $input,int $revision,string $adminId): int
{
    $content=sectors_validate($key,$input);$db=sectors_db();$db->beginTransaction();
    try {
        $stmt=$db->prepare('UPDATE sectors_page_sections SET content_json=?,revision=revision+1,updated_at=NOW() WHERE section_key=? AND revision=?');
        $stmt->execute([json_encode($content,JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR),$key,$revision]);
        if ($stmt->rowCount()!==1) throw new RuntimeException('تم تعديل هذا القسم في جلسة أخرى. حدّث الصفحة قبل الحفظ.',409);
        $db->prepare('INSERT INTO admin_audit_log(admin_user_id,action,entity_type,entity_id,details) VALUES (?,?,?,?,?)')->execute([$adminId,'update','sectors_page',$key,'تحديث قسم '.sectors_schema()[$key]['label']]);
        $db->commit();return $revision+1;
    } catch (Throwable $error) { if ($db->inTransaction()) $db->rollBack();throw $error; }
}

function sectors_public_payload(array $sections): array
{
    $ordered=$sections;uasort($ordered,static fn(array $a,array $b):int=>$a['content']['sort_order']<=>$b['content']['sort_order']);
    $meta=[];$visible=[];
    foreach ($ordered as $key=>$section) {
        $content=$section['content'];$meta[]=['key'=>$key,'is_active'=>(bool)$content['is_active'],'sort_order'=>(int)$content['sort_order']];
        if ($content['is_active']) $visible[$key]=$content;
    }
    $map=$visible['map']??null;$data=[];
    if ($map) foreach (home_visible($map['items']) as $item) {
        $tags=array_values(array_filter(array_map('trim',explode('|',(string)$item['tags']))));
        $data[]=['code'=>$item['code'],'name'=>$item['name'],'description'=>$item['description'],'tags'=>$tags,'icon_key'=>$item['icon_key'],'sort_order'=>(int)$item['sort_order']];
    }
    return ['ok'=>true,'hero'=>$visible['hero']??null,'intro'=>$map?['eyebrow'=>$map['eyebrow'],'title'=>$map['title'],'description'=>$map['description']]:null,'data'=>$data,
        'opportunities'=>$visible['opportunities']??null,'protected'=>$visible['protected']??null,'meta'=>['count'=>count($data),'sections'=>$meta]];
}
