<?php
declare(strict_types=1);
require_once __DIR__.'/admin.php';
require_once __DIR__.'/home.php';

function seven_tech_schema():array
{
    $text=static fn(string $label,int $max=250):array=>['label'=>$label,'type'=>'text','max'=>$max];
    $area=static fn(string $label,int $max=2000):array=>['label'=>$label,'type'=>'textarea','max'=>$max];
    $select=static fn(string $label,array $options):array=>['label'=>$label,'type'=>'select','options'=>$options];
    $meta=['sort_order'=>['label'=>'الترتيب','type'=>'number','max'=>1000,'default'=>1],'is_active'=>['label'=>'إظهار للجمهور','type'=>'checkbox','default'=>true]];
    $list=static fn(string $label,array $fields,int $max=30):array=>['label'=>$label,'type'=>'list','max'=>$max,'fields'=>$fields+$meta];
    $icons=['product'=>'بناء المنتجات','systems'=>'الأنظمة والتكاملات','ai'=>'الذكاء الاصطناعي','digital'=>'التحول الرقمي','support'=>'دعم التشغيل','cloud'=>'البنية السحابية'];
    return [
        'hero'=>['label'=>'المقدمة — Seven Tech','fields'=>[
            'brand_name'=>$text('اسم العلامة',100),'brand_subtitle'=>$text('وصف العلامة',100),'title'=>$text('العنوان الرئيسي'),'description'=>$area('الوصف'),
            'card_label'=>$text('تسمية البطاقة',120),'card_title'=>$text('عنوان البطاقة'),'card_description'=>$area('وصف البطاقة'),
        ]+$meta],
        'services'=>['label'=>'الخدمات','fields'=>[
            'eyebrow'=>$text('التسمية أعلى العنوان'),'title'=>$text('العنوان'),'description'=>$area('الوصف'),
            'items'=>$list('بطاقات الخدمات',['number'=>$text('رقم البطاقة',10),'title'=>$text('العنوان'),'description'=>$area('الوصف',1000),'icon'=>$select('الأيقونة',$icons)],24),
        ]+$meta],
        'role'=>['label'=>'الدور في المنظومة','fields'=>[
            'eyebrow'=>$text('التسمية أعلى العنوان'),'title'=>$text('العنوان'),'description'=>$area('الوصف'),
            'points'=>$list('نقاط تقليل المخاطر',['text'=>$text('النقطة',500)],20),
            'stats'=>$list('الإحصائيات',['value'=>$text('القيمة',50),'label'=>$text('الوصف',150)],12),
        ]+$meta],
    ];
}

function seven_tech_defaults():array
{
    return [
        'hero'=>[
            'brand_name'=>'Seven Tech','brand_subtitle'=>'الذراع التقني','title'=>'الذراع التقني الذي يبني ويُشغّل',
            'description'=>'شركة تقنية بخبرة 15 عامًا وأكثر من 500 عميل، مسؤولة عن بناء المنتجات والأنظمة ودعم التشغيل، وهي المحرّك خلف منهجية تقليل المخاطر في Seven Tech Capital.',
            'card_label'=>'الذراع التقني','card_title'=>'نبني قبل رأس المال','card_description'=>'منتج، أنظمة، تكاملات، وتشغيل مستمر قبل وبعد الاستثمار.',
            'sort_order'=>1,'is_active'=>true,
        ],
        'services'=>[
            'eyebrow'=>'الخدمات','title'=>'ما نبنيه','description'=>'قدرات تقنية وتشغيلية تغطي دورة المنتج من أول نسخة قابلة للاختبار حتى البنية السحابية والدعم المستمر.',
            'items'=>[
                ['number'=>'01','title'=>'بناء المنتجات','description'=>'MVP، تطبيقات ويب وموبايل، ومنتجات قابلة للتوسع.','icon'=>'product','sort_order'=>1,'is_active'=>true],
                ['number'=>'02','title'=>'الأنظمة والتكاملات','description'=>'بنى خلفية، APIs، تكاملات، وأتمتة العمليات.','icon'=>'systems','sort_order'=>2,'is_active'=>true],
                ['number'=>'03','title'=>'الذكاء الاصطناعي','description'=>'نماذج تنبؤ وتحليلات ومساعدون أذكياء.','icon'=>'ai','sort_order'=>3,'is_active'=>true],
                ['number'=>'04','title'=>'التحول الرقمي','description'=>'رقمنة العمليات ولوحات القرار.','icon'=>'digital','sort_order'=>4,'is_active'=>true],
                ['number'=>'05','title'=>'دعم التشغيل','description'=>'مراقبة، صيانة، وتطوير مستمر.','icon'=>'support','sort_order'=>5,'is_active'=>true],
                ['number'=>'06','title'=>'البنية السحابية','description'=>'AWS، أمان، نسخ احتياطي، وقابلية توسع.','icon'=>'cloud','sort_order'=>6,'is_active'=>true],
            ],'sort_order'=>2,'is_active'=>true,
        ],
        'role'=>[
            'eyebrow'=>'الدور في المنظومة','title'=>'كيف يقلّل Seven Tech المخاطر',
            'description'=>'قبل تفعيل أي تمويل، يبني الذراع التقني المنتج ويُجهّز المشروع للتشغيل — فتصل الفرص للمستثمرين أكثر جاهزية.',
            'points'=>[
                ['text'=>'نبني المنتج ونختبره في السوق قبل الجولة','sort_order'=>1,'is_active'=>true],
                ['text'=>'نُجهّز البنية التقنية والتشغيلية للنمو','sort_order'=>2,'is_active'=>true],
                ['text'=>'ندعم التنفيذ بعد الاستثمار لضمان الاستمرارية','sort_order'=>3,'is_active'=>true],
            ],
            'stats'=>[
                ['value'=>'15','label'=>'عامًا خبرة','sort_order'=>1,'is_active'=>true],
                ['value'=>'500+','label'=>'عميل','sort_order'=>2,'is_active'=>true],
                ['value'=>'10+','label'=>'مشروعات','sort_order'=>3,'is_active'=>true],
                ['value'=>'$50M','label'=>'قيمة مشروعات','sort_order'=>4,'is_active'=>true],
            ],'sort_order'=>3,'is_active'=>true,
        ],
    ];
}

function seven_tech_db():PDO
{
    $db=admin_db();static $ready=false;if(!$ready){
        $db->exec('CREATE TABLE IF NOT EXISTS seven_tech_page_sections(section_key VARCHAR(40) PRIMARY KEY,content_json MEDIUMTEXT NOT NULL,revision INT UNSIGNED NOT NULL DEFAULT 1,updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        $seed=$db->prepare('INSERT IGNORE INTO seven_tech_page_sections(section_key,content_json) VALUES (?,?)');foreach(seven_tech_defaults() as $key=>$value)$seed->execute([$key,json_encode($value,JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR)]);$ready=true;
    }return $db;
}

function seven_tech_read(?PDO $db=null):array
{
    $db??=seven_tech_db();$defaults=seven_tech_defaults();$schema=seven_tech_schema();$sections=[];
    foreach($db->query('SELECT * FROM seven_tech_page_sections') as $row){$key=(string)$row['section_key'];if(!isset($schema[$key]))continue;$stored=json_decode((string)$row['content_json'],true,64,JSON_THROW_ON_ERROR);$values=is_array($stored)?$stored:[];foreach($defaults[$key] as $field=>$value)if(!array_key_exists($field,$values))$values[$field]=$value;$sections[$key]=['content'=>home_default_fields($schema[$key]['fields'],$values),'revision'=>(int)$row['revision'],'updated_at'=>(string)$row['updated_at']];}
    return $sections;
}

function seven_tech_validate(string $key,array $input):array
{
    $schema=seven_tech_schema()[$key]??null;if(!$schema)throw new InvalidArgumentException('القسم غير موجود.');$content=home_validate($schema['fields'],$input);
    if($key==='hero'&&($content['brand_name']===''||$content['title']===''))throw new InvalidArgumentException('أكمل اسم العلامة والعنوان الرئيسي.');
    if($key==='services')foreach($content['items'] as $item)if($item['number']===''||$item['title']===''||$item['description']==='')throw new InvalidArgumentException('أكمل رقم وعنوان ووصف كل خدمة.');
    if($key==='role'){foreach($content['points'] as $item)if($item['text']==='')throw new InvalidArgumentException('اكتب نص كل نقطة.');foreach($content['stats'] as $item)if($item['value']===''||$item['label']==='')throw new InvalidArgumentException('أكمل قيمة ووصف كل إحصائية.');}
    return $content;
}

function seven_tech_save(string $key,array $input,int $revision,string $adminId):int
{
    $content=seven_tech_validate($key,$input);$db=seven_tech_db();$db->beginTransaction();try{$stmt=$db->prepare('UPDATE seven_tech_page_sections SET content_json=?,revision=revision+1,updated_at=NOW() WHERE section_key=? AND revision=?');$stmt->execute([json_encode($content,JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR),$key,$revision]);if($stmt->rowCount()!==1)throw new RuntimeException('تم تعديل هذا القسم في جلسة أخرى. حدّث الصفحة قبل الحفظ.',409);$db->prepare('INSERT INTO admin_audit_log(admin_user_id,action,entity_type,entity_id,details) VALUES (?,?,?,?,?)')->execute([$adminId,'update','seven_tech_page',$key,'تحديث قسم '.seven_tech_schema()[$key]['label']]);$db->commit();return $revision+1;}catch(Throwable $error){if($db->inTransaction())$db->rollBack();throw $error;}
}

function seven_tech_public_payload(array $sections):array
{
    $ordered=$sections;uasort($ordered,static fn(array $a,array $b):int=>$a['content']['sort_order']<=>$b['content']['sort_order']);$meta=[];$data=[];
    foreach($ordered as $key=>$section){$value=$section['content'];$meta[]=['key'=>$key,'is_active'=>(bool)$value['is_active'],'sort_order'=>(int)$value['sort_order']];if(!$value['is_active'])continue;unset($value['sort_order'],$value['is_active']);if(isset($value['items']))$value['items']=home_visible($value['items']);if(isset($value['points']))$value['points']=home_visible($value['points']);if(isset($value['stats']))$value['stats']=home_visible($value['stats']);$data[$key]=$value;}
    return ['ok'=>true,'sections'=>$data,'meta'=>['sections'=>$meta]];
}
