<?php
declare(strict_types=1);
require_once __DIR__.'/admin.php';
require_once __DIR__.'/home.php';

function success_stories_schema():array
{
    $text=static fn(string $label,int $max=250):array=>['label'=>$label,'type'=>'text','max'=>$max];
    $area=static fn(string $label,int $max=2000):array=>['label'=>$label,'type'=>'textarea','max'=>$max];
    $meta=['sort_order'=>['label'=>'الترتيب','type'=>'number','max'=>1000,'default'=>1],'is_active'=>['label'=>'إظهار للجمهور','type'=>'checkbox','default'=>true]];
    $list=static fn(string $label,array $fields,int $max=30):array=>['label'=>$label,'type'=>'list','max'=>$max,'fields'=>$fields+$meta];
    $metrics=$list('النتائج والمؤشرات',['value'=>$text('القيمة',80),'label'=>$text('اسم المؤشر',120)],3);
    return [
        'hero'=>['label'=>'المقدمة — دراسات حالة','fields'=>[
            'eyebrow'=>$text('التسمية أعلى العنوان'),'title'=>$text('العنوان الرئيسي'),'description'=>$area('الوصف'),
        ]+$meta],
        'cases'=>['label'=>'الفلاتر ودراسات الحالة','fields'=>[
            'filters'=>$list('فلاتر القطاعات',['key'=>$text('مفتاح الفلتر بالإنجليزية',40),'label'=>$text('اسم الفلتر',120)],12),
            'items'=>$list('بطاقات قصص النجاح',[
                'sector_label'=>$text('القطاع',150),'category_key'=>$text('مفتاح التصنيف بالإنجليزية',40),'anonymous_label'=>$text('شارة إخفاء الاسم',100),
                'title'=>$text('عنوان الدراسة'),'problem_label'=>$text('عنوان المشكلة',100),'problem'=>$area('المشكلة',1200),
                'solution_label'=>$text('عنوان الحل',100),'solution'=>$area('الحل',1600),'duration'=>$text('مدة الإطلاق',120),
                'launch_suffix'=>$text('النص بعد المدة',100),'metrics'=>$metrics,
            ],30),
        ]+$meta],
    ];
}

function success_stories_defaults():array
{
    return [
        'hero'=>[
            'eyebrow'=>'دراسات حالة','title'=>'نتائج موثقة، دون أسماء العملاء',
            'description'=>'مشروعات سابقة بناها وشغّلها الفريق. كل دراسة تعرض القطاع، المشكلة، الدور، المدة، الحل، والنتائج — بعد التحقق والمراجعة.',
            'sort_order'=>1,'is_active'=>true,
        ],
        'cases'=>[
            'filters'=>[
                ['key'=>'all','label'=>'الكل','sort_order'=>1,'is_active'=>true],
                ['key'=>'fintech','label'=>'تقنية مالية','sort_order'=>2,'is_active'=>true],
                ['key'=>'health','label'=>'صحة رقمية','sort_order'=>3,'is_active'=>true],
                ['key'=>'logistics','label'=>'لوجستيات','sort_order'=>4,'is_active'=>true],
            ],
            'items'=>[
                ['sector_label'=>'تقنية مالية','category_key'=>'fintech','anonymous_label'=>'مجهّلة','title'=>'منصة مدفوعات B2B','problem_label'=>'المشكلة','problem'=>'بطء التسوية وتعقيد تجربة التاجر.','solution_label'=>'الحل','solution'=>'بناء بنية مدفوعات حديثة مع تسوية شبه لحظية ولوحة تاجر موحّدة.','duration'=>'9 أسابيع','launch_suffix'=>'للإطلاق','metrics'=>[
                    ['value'=>'-64%','label'=>'زمن العملية','sort_order'=>1,'is_active'=>true],['value'=>'3.5x','label'=>'نمو المعاملات','sort_order'=>2,'is_active'=>true],['value'=>'99.9%','label'=>'توافر','sort_order'=>3,'is_active'=>true],
                ],'sort_order'=>1,'is_active'=>true],
                ['sector_label'=>'صحة رقمية','category_key'=>'health','anonymous_label'=>'مجهّلة','title'=>'منصة حجوزات ورعاية','problem_label'=>'المشكلة','problem'=>'تجربة مريض مجزأة وعمليات ورقية.','solution_label'=>'الحل','solution'=>'رقمنة رحلة المريض من الحجز إلى المتابعة مع تكامل تشغيلي.','duration'=>'12 أسبوع','launch_suffix'=>'للإطلاق','metrics'=>[
                    ['value'=>'+180%','label'=>'مستخدمون','sort_order'=>1,'is_active'=>true],['value'=>'-38%','label'=>'التكلفة','sort_order'=>2,'is_active'=>true],['value'=>'4.8★','label'=>'رضا','sort_order'=>3,'is_active'=>true],
                ],'sort_order'=>2,'is_active'=>true],
                ['sector_label'=>'لوجستيات','category_key'=>'logistics','anonymous_label'=>'مجهّلة','title'=>'نظام توصيل ذكي','problem_label'=>'المشكلة','problem'=>'مسارات غير محسّنة وتتبع ضعيف.','solution_label'=>'الحل','solution'=>'محرّك تحسين مسارات وتتبع لحظي وأتمتة إدارة الأسطول.','duration'=>'8 أسابيع','launch_suffix'=>'للإطلاق','metrics'=>[
                    ['value'=>'+42%','label'=>'كفاءة','sort_order'=>1,'is_active'=>true],['value'=>'-27%','label'=>'زمن التسليم','sort_order'=>2,'is_active'=>true],['value'=>'2.1x','label'=>'طلبات','sort_order'=>3,'is_active'=>true],
                ],'sort_order'=>3,'is_active'=>true],
            ],
            'sort_order'=>2,'is_active'=>true,
        ],
    ];
}

function success_stories_sections_db():PDO
{
    $db=admin_db();static $ready=false;
    if(!$ready){
        $db->exec('CREATE TABLE IF NOT EXISTS success_stories_page_sections(section_key VARCHAR(40) PRIMARY KEY,content_json MEDIUMTEXT NOT NULL,revision INT UNSIGNED NOT NULL DEFAULT 1,updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        $seed=$db->prepare('INSERT IGNORE INTO success_stories_page_sections(section_key,content_json) VALUES (?,?)');
        foreach(success_stories_defaults() as $key=>$value)$seed->execute([$key,json_encode($value,JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR)]);
        $ready=true;
    }
    return $db;
}

function success_stories_sections_read(?PDO $db=null):array
{
    $db??=success_stories_sections_db();$defaults=success_stories_defaults();$sections=[];
    foreach($db->query('SELECT * FROM success_stories_page_sections') as $row){
        $key=(string)$row['section_key'];if(!isset(success_stories_schema()[$key]))continue;
        $stored=json_decode((string)$row['content_json'],true,64,JSON_THROW_ON_ERROR);$values=is_array($stored)?$stored:[];
        foreach($defaults[$key] as $field=>$value)if(!array_key_exists($field,$values))$values[$field]=$value;
        $sections[$key]=['content'=>home_default_fields(success_stories_schema()[$key]['fields'],$values),'revision'=>(int)$row['revision'],'updated_at'=>(string)$row['updated_at']];
    }
    return $sections;
}

function success_stories_validate(string $key,array $input):array
{
    $schema=success_stories_schema()[$key]??null;if(!$schema)throw new InvalidArgumentException('القسم غير موجود.');
    $content=home_validate($schema['fields'],$input);
    if($key==='hero'&&$content['title']==='')throw new InvalidArgumentException('اكتب عنوان المقدمة.');
    if($key==='cases'){
        $filterKeys=[];
        foreach($content['filters'] as $filter){
            if(!preg_match('/^[a-z0-9_-]{1,40}$/D',$filter['key'])||$filter['label']==='')throw new InvalidArgumentException('أكمل بيانات الفلاتر واستخدم مفتاحًا إنجليزيًا صحيحًا.');
            if(isset($filterKeys[$filter['key']]))throw new InvalidArgumentException('مفتاح الفلتر مكرر.');$filterKeys[$filter['key']]=true;
        }
        foreach($content['items'] as $item){
            if($item['title']===''||$item['sector_label']===''||!preg_match('/^[a-z0-9_-]{1,40}$/D',$item['category_key']))throw new InvalidArgumentException('أكمل القطاع والتصنيف وعنوان كل دراسة.');
            if(count($item['metrics'])>3)throw new InvalidArgumentException('الحد الأقصى ثلاثة مؤشرات لكل دراسة.');
        }
    }
    return $content;
}

function success_stories_sections_save(string $key,array $input,int $revision,string $adminId):int
{
    $content=success_stories_validate($key,$input);$db=success_stories_sections_db();$db->beginTransaction();
    try{
        $stmt=$db->prepare('UPDATE success_stories_page_sections SET content_json=?,revision=revision+1,updated_at=NOW() WHERE section_key=? AND revision=?');
        $stmt->execute([json_encode($content,JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR),$key,$revision]);
        if($stmt->rowCount()!==1)throw new RuntimeException('تم تعديل هذا القسم في جلسة أخرى. حدّث الصفحة قبل الحفظ.',409);
        $db->prepare('INSERT INTO admin_audit_log(admin_user_id,action,entity_type,entity_id,details) VALUES (?,?,?,?,?)')->execute([$adminId,'update','success_stories_page',$key,'تحديث قسم '.success_stories_schema()[$key]['label']]);
        $db->commit();return $revision+1;
    }catch(Throwable $error){if($db->inTransaction())$db->rollBack();throw $error;}
}

function success_stories_public_payload(array $sections):array
{
    $ordered=$sections;uasort($ordered,static fn(array $a,array $b):int=>$a['content']['sort_order']<=>$b['content']['sort_order']);$meta=[];$visible=[];
    foreach($ordered as $key=>$section){$content=$section['content'];$meta[]=['key'=>$key,'is_active'=>(bool)$content['is_active'],'sort_order'=>(int)$content['sort_order']];if($content['is_active'])$visible[$key]=$content;}
    $filters=[];$stories=[];
    if(isset($visible['cases'])){
        foreach(home_visible($visible['cases']['filters']) as $item)$filters[]=['key'=>$item['key'],'label'=>$item['label']];
        foreach(home_visible($visible['cases']['items']) as $index=>$item){
            $metrics=[];foreach(home_visible($item['metrics']) as $metric)$metrics[]=['value'=>$metric['value'],'label'=>$metric['label']];
            $stories[]=['id'=>'CASE-'.str_pad((string)($index+1),3,'0',STR_PAD_LEFT),'sector_label'=>$item['sector_label'],'category_key'=>$item['category_key'],'anonymous_label'=>$item['anonymous_label'],'title'=>$item['title'],'problem_label'=>$item['problem_label'],'problem'=>$item['problem'],'solution_label'=>$item['solution_label'],'solution'=>$item['solution'],'duration'=>$item['duration'],'launch_suffix'=>$item['launch_suffix'],'metrics'=>$metrics,'sort_order'=>(int)$item['sort_order']];
        }
    }
    return ['ok'=>true,'hero'=>$visible['hero']??null,'data'=>$stories,'filters'=>$filters,'meta'=>['count'=>count($stories),'sections'=>$meta]];
}

