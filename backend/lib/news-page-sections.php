<?php
declare(strict_types=1);
require_once __DIR__.'/admin.php';
require_once __DIR__.'/home.php';

function news_page_schema():array
{
    $text=static fn(string $label,int $max=250):array=>['label'=>$label,'type'=>'text','max'=>$max];
    $area=static fn(string $label,int $max=2000):array=>['label'=>$label,'type'=>'textarea','max'=>$max];
    $url=static fn(string $label):array=>['label'=>$label,'type'=>'url','max'=>1000];
    $date=static fn(string $label):array=>['label'=>$label,'type'=>'date','max'=>10];
    $select=static fn(string $label,array $options):array=>['label'=>$label,'type'=>'select','options'=>$options];
    $meta=['sort_order'=>['label'=>'الترتيب','type'=>'number','max'=>1000,'default'=>1],'is_active'=>['label'=>'إظهار للجمهور','type'=>'checkbox','default'=>true]];
    $list=static fn(string $label,array $fields,int $max=30):array=>['label'=>$label,'type'=>'list','max'=>$max,'fields'=>$fields+$meta];
    return [
        'hero'=>['label'=>'المقدمة — مركز المعرفة','fields'=>[
            'eyebrow'=>$text('التسمية أعلى العنوان'),'title'=>$text('العنوان الرئيسي'),'description'=>$area('الوصف'),
            'note_title'=>$text('عنوان البطاقة'),'note_description'=>$area('وصف البطاقة'),
        ]+$meta],
        'content'=>['label'=>'الأخبار والمقالات','fields'=>[
            'news_tab_label'=>$text('اسم تبويب الأخبار والمقالات',100),'events_tab_label'=>$text('اسم تبويب الفعاليات',100),
            'items'=>$list('بطاقات الأخبار والمقالات',[
                'content_type'=>$select('نوع المحتوى',['article'=>'مقال','news'=>'خبر','update'=>'تحديث','partnership'=>'شراكة']),
                'category_label'=>$text('التسمية الظاهرة',100),'title'=>$text('العنوان'),'excerpt'=>$area('الملخص',1600),
                'published_date'=>$date('تاريخ النشر'),'reading_time'=>$text('مدة القراءة',100),
                'cover_image'=>$text('مسار صورة الغلاف',700),'external_url'=>$url('رابط قراءة المحتوى'),
                'is_featured'=>['label'=>'محتوى مميّز','type'=>'checkbox','default'=>false],
            ],40),
        ]+$meta],
        'events'=>['label'=>'الفعاليات','fields'=>[
            'note'=>$area('التنويه أسفل الفعاليات'),
            'items'=>$list('بطاقات الفعاليات',[
                'title'=>$text('اسم الفعالية'),'event_date'=>$date('تاريخ الفعالية'),'event_time'=>$text('وقت الفعالية بصيغة 24 ساعة',5),
                'location'=>$text('المكان أو المنصة'),'description'=>$area('الوصف',1600),
                'capacity'=>['label'=>'السعة','type'=>'number','max'=>100000,'default'=>0],
                'registered_count'=>['label'=>'عدد المسجلين','type'=>'number','max'=>100000,'default'=>0],
                'registration_url'=>$url('رابط التسجيل'),'calendar_url'=>$url('رابط التقويم'),
            ],30),
        ]+$meta],
    ];
}

function news_page_defaults():array
{
    return [
        'hero'=>[
            'eyebrow'=>'مركز المعرفة','title'=>'الأخبار والمقالات والفعاليات',
            'description'=>'تحديثات الصندوق، مقالات استثمارية، وفعاليات مجانية هجينة بتسجيل وقائمة انتظار.',
            'note_title'=>'تحديثات موثقة','note_description'=>'محتوى مختصر يساعد المستثمر ورائد الأعمال على متابعة الصورة الكبيرة.',
            'sort_order'=>1,'is_active'=>true,
        ],
        'content'=>[
            'news_tab_label'=>'الأخبار والمقالات','events_tab_label'=>'الفعاليات',
            'items'=>[
                ['content_type'=>'article','category_label'=>'مقال','title'=>'منهجية تقليل مخاطر التنفيذ في الاستثمار الجريء','excerpt'=>'كيف نُجهّز المشروع تقنيًا وتشغيليًا قبل تفعيل رأس المال، ولماذا يصنع ذلك فرصًا أكثر جاهزية للنمو.','published_date'=>'2026-07-10','reading_time'=>'قراءة 6 دقائق','cover_image'=>'assets/img/knowledge-risk-cover.png','external_url'=>'','is_featured'=>true,'sort_order'=>1,'is_active'=>true],
                ['content_type'=>'news','category_label'=>'خبر','title'=>'إطلاق الإصدار التشغيلي الأول','excerpt'=>'نطلق بوابتَي المستثمر ورائد الأعمال ولوحة الإدارة خلال 10 أيام.','published_date'=>'2026-07-17','reading_time'=>'','cover_image'=>'','external_url'=>'','is_featured'=>false,'sort_order'=>2,'is_active'=>true],
                ['content_type'=>'partnership','category_label'=>'شراكة','title'=>'توسع مؤسسي نحو السعودية والإمارات','excerpt'=>'خطة توسع جغرافي مع فصل بيانات كل دولة وفق الإقامة القانونية.','published_date'=>'2026-07-02','reading_time'=>'','cover_image'=>'','external_url'=>'','is_featured'=>false,'sort_order'=>3,'is_active'=>true],
                ['content_type'=>'article','category_label'=>'مقال','title'=>'بناء الثقة في المنصات الاستثمارية','excerpt'=>'دور الشفافية وسجل التدقيق في تجربة المستثمر.','published_date'=>'2026-06-28','reading_time'=>'','cover_image'=>'','external_url'=>'','is_featured'=>false,'sort_order'=>4,'is_active'=>true],
                ['content_type'=>'news','category_label'=>'خبر','title'=>'انضمام خبراء للمجلس الاستشاري','excerpt'=>'تعزيز الخبرات في الاستثمار والقانون والتشغيل.','published_date'=>'2026-06-20','reading_time'=>'','cover_image'=>'','external_url'=>'','is_featured'=>false,'sort_order'=>5,'is_active'=>true],
                ['content_type'=>'article','category_label'=>'مقال','title'=>'KYC/AML: التوازن بين الأمان والتجربة','excerpt'=>'كيف نُصمم تأهيلًا محكمًا دون احتكاك مفرط.','published_date'=>'2026-06-15','reading_time'=>'','cover_image'=>'','external_url'=>'','is_featured'=>false,'sort_order'=>6,'is_active'=>true],
                ['content_type'=>'update','category_label'=>'تحديث','title'=>'خارطة طريق المرحلة الثانية','excerpt'=>'المحاسبة المتكاملة والتسويات والتقارير المتقدمة.','published_date'=>'2026-06-08','reading_time'=>'','cover_image'=>'','external_url'=>'','is_featured'=>false,'sort_order'=>7,'is_active'=>true],
            ],
            'sort_order'=>2,'is_active'=>true,
        ],
        'events'=>[
            'note'=>'تظهر روابط التسجيل والتقويم فقط عند إضافتها واعتمادها من الإدارة.',
            'items'=>[
                ['title'=>'يوم الاستثمار التقني','event_date'=>'2026-07-23','event_time'=>'17:00','location'=>'هجين · Zoom','description'=>'عرض منهجية الصندوق ولقاء مباشر مع الفريق.','capacity'=>60,'registered_count'=>42,'registration_url'=>'','calendar_url'=>'','sort_order'=>1,'is_active'=>true],
                ['title'=>'ورشة: تجهيز مشروعك للاستثمار','event_date'=>'2026-07-30','event_time'=>'18:00','location'=>'عن بُعد · Google Meet','description'=>'لرواد الأعمال — كيف تبني ملفًا استثماريًا قويًا.','capacity'=>60,'registered_count'=>60,'registration_url'=>'','calendar_url'=>'','sort_order'=>2,'is_active'=>true],
                ['title'=>'لقاء المستثمرين الربعي','event_date'=>'2026-08-06','event_time'=>'16:00','location'=>'حضوري · دبي','description'=>'استعراض الفرص والتوجهات — للمعتمدين فقط.','capacity'=>40,'registered_count'=>18,'registration_url'=>'','calendar_url'=>'','sort_order'=>3,'is_active'=>true],
            ],
            'sort_order'=>3,'is_active'=>true,
        ],
    ];
}

function news_page_db():PDO
{
    $db=admin_db();static $ready=false;
    if(!$ready){
        $db->exec('CREATE TABLE IF NOT EXISTS news_page_sections(section_key VARCHAR(40) PRIMARY KEY,content_json MEDIUMTEXT NOT NULL,revision INT UNSIGNED NOT NULL DEFAULT 1,updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        $seed=$db->prepare('INSERT IGNORE INTO news_page_sections(section_key,content_json) VALUES (?,?)');
        foreach(news_page_defaults() as $key=>$value)$seed->execute([$key,json_encode($value,JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR)]);
        $ready=true;
    }
    return $db;
}

function news_page_read(?PDO $db=null):array
{
    $db??=news_page_db();$defaults=news_page_defaults();$schema=news_page_schema();$sections=[];
    foreach($db->query('SELECT * FROM news_page_sections') as $row){
        $key=(string)$row['section_key'];if(!isset($schema[$key]))continue;
        $stored=json_decode((string)$row['content_json'],true,64,JSON_THROW_ON_ERROR);$values=is_array($stored)?$stored:[];
        foreach($defaults[$key] as $field=>$value)if(!array_key_exists($field,$values))$values[$field]=$value;
        $sections[$key]=['content'=>home_default_fields($schema[$key]['fields'],$values),'revision'=>(int)$row['revision'],'updated_at'=>(string)$row['updated_at']];
    }
    return $sections;
}

function news_page_validate(string $key,array $input):array
{
    $schema=news_page_schema()[$key]??null;if(!$schema)throw new InvalidArgumentException('القسم غير موجود.');
    $content=home_validate($schema['fields'],$input);
    if($key==='hero'&&$content['title']==='')throw new InvalidArgumentException('اكتب عنوان المقدمة.');
    if($key==='content'){
        if($content['news_tab_label']===''||$content['events_tab_label']==='')throw new InvalidArgumentException('اكتب اسمي التبويبين.');
        $featured=0;
        foreach($content['items'] as $item){
            if($item['title']===''||$item['category_label']===''||$item['excerpt']===''||$item['published_date']==='')throw new InvalidArgumentException('أكمل عنوان وتصنيف وملخص وتاريخ كل بطاقة.');
            if($item['cover_image']!==''&&!preg_match('~^(?:assets/)?img/[a-zA-Z0-9_.-]+$~D',$item['cover_image'])&&!preg_match('~^https?://~i',$item['cover_image']))throw new InvalidArgumentException('مسار صورة الغلاف غير صالح.');
            if($item['is_featured'])$featured++;
        }
        if($featured>1)throw new InvalidArgumentException('اختر بطاقة مميّزة واحدة فقط.');
    }
    if($key==='events')foreach($content['items'] as $item){
        if($item['title']===''||$item['event_date']===''||$item['location']===''||$item['description']==='')throw new InvalidArgumentException('أكمل اسم وتاريخ ومكان ووصف كل فعالية.');
        if(!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/D',$item['event_time']))throw new InvalidArgumentException('اكتب وقت الفعالية بصيغة 24 ساعة، مثال 17:00.');
    }
    return $content;
}

function news_page_save(string $key,array $input,int $revision,string $adminId):int
{
    $content=news_page_validate($key,$input);$db=news_page_db();$db->beginTransaction();
    try{
        $stmt=$db->prepare('UPDATE news_page_sections SET content_json=?,revision=revision+1,updated_at=NOW() WHERE section_key=? AND revision=?');
        $stmt->execute([json_encode($content,JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR),$key,$revision]);
        if($stmt->rowCount()!==1)throw new RuntimeException('تم تعديل هذا القسم في جلسة أخرى. حدّث الصفحة قبل الحفظ.',409);
        $db->prepare('INSERT INTO admin_audit_log(admin_user_id,action,entity_type,entity_id,details) VALUES (?,?,?,?,?)')->execute([$adminId,'update','news_page',$key,'تحديث قسم '.news_page_schema()[$key]['label']]);
        $db->commit();return $revision+1;
    }catch(Throwable $error){if($db->inTransaction())$db->rollBack();throw $error;}
}

function news_page_public_payload(array $sections):array
{
    $ordered=$sections;uasort($ordered,static fn(array $a,array $b):int=>$a['content']['sort_order']<=>$b['content']['sort_order']);$meta=[];$visible=[];
    foreach($ordered as $key=>$section){$value=$section['content'];$meta[]=['key'=>$key,'is_active'=>(bool)$value['is_active'],'sort_order'=>(int)$value['sort_order']];if($value['is_active'])$visible[$key]=$value;}
    $content=[];$events=[];
    if(isset($visible['content']))foreach(home_visible($visible['content']['items']) as $index=>$item)$content[]=[
        'id'=>'CNT-'.str_pad((string)($index+1),3,'0',STR_PAD_LEFT),'title'=>$item['title'],'content_type'=>$item['content_type'],'category_label'=>$item['category_label'],
        'excerpt'=>$item['excerpt'],'reading_time'=>$item['reading_time'],'cover_image'=>$item['cover_image'],'external_url'=>$item['external_url'],
        'is_featured'=>(bool)$item['is_featured'],'sort_order'=>(int)$item['sort_order'],'published_at'=>$item['published_date'].'T12:00:00',
    ];
    if(isset($visible['events']))foreach(home_visible($visible['events']['items']) as $index=>$item)$events[]=[
        'id'=>'EVT-'.str_pad((string)($index+1),3,'0',STR_PAD_LEFT),'title'=>$item['title'],'starts_at'=>$item['event_date'].'T'.$item['event_time'].':00','location'=>$item['location'],
        'description'=>$item['description'],'capacity'=>(int)$item['capacity'],'registered_count'=>(int)$item['registered_count'],
        'registration_url'=>$item['registration_url'],'calendar_url'=>$item['calendar_url'],'sort_order'=>(int)$item['sort_order'],
    ];
    $hero=$visible['hero']??null;if($hero){unset($hero['sort_order'],$hero['is_active']);}
    return ['ok'=>true,'hero'=>$hero,'tabs'=>[
        'news'=>$visible['content']['news_tab_label']??'الأخبار والمقالات','events'=>$visible['content']['events_tab_label']??'الفعاليات',
    ],'content'=>$content,'events'=>$events,'events_note'=>$visible['events']['note']??'',
        'meta'=>['content_count'=>count($content),'event_count'=>count($events),'sections'=>$meta,'generated_at'=>gmdate(DATE_ATOM)]];
}
