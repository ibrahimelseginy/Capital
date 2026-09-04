<?php
declare(strict_types=1);require_once __DIR__.'/admin.php';require_once __DIR__.'/home.php';

function contact_page_schema():array
{
    $text=static fn(string $label,int $max=250):array=>['label'=>$label,'type'=>'text','max'=>$max];$area=static fn(string $label,int $max=2000):array=>['label'=>$label,'type'=>'textarea','max'=>$max];$url=static fn(string $label):array=>['label'=>$label,'type'=>'url','max'=>1000];$select=static fn(string $label,array $options):array=>['label'=>$label,'type'=>'select','options'=>$options];
    $meta=['sort_order'=>['label'=>'الترتيب','type'=>'number','max'=>1000,'default'=>1],'is_active'=>['label'=>'إظهار للجمهور','type'=>'checkbox','default'=>true]];$list=static fn(string $label,array $fields,int $max=30):array=>['label'=>$label,'type'=>'list','max'=>$max,'fields'=>$fields+$meta];
    return [
        'hero'=>['label'=>'المقدمة — نحن هنا','fields'=>['eyebrow'=>$text('التسمية أعلى العنوان'),'title'=>$text('العنوان الرئيسي'),'description'=>$area('الوصف')]+$meta],
        'contact'=>['label'=>'النموذج وبيانات التواصل','fields'=>[
            'form_kicker'=>$text('التسمية أعلى النموذج'),'form_title'=>$text('عنوان النموذج'),'form_description'=>$area('وصف النموذج'),
            'name_label'=>$text('اسم حقل الاسم',100),'name_placeholder'=>$text('مثال حقل الاسم',150),'email_label'=>$text('اسم حقل البريد',100),'email_placeholder'=>$text('مثال حقل البريد',150),
            'whatsapp_label'=>$text('اسم حقل واتساب',100),'whatsapp_placeholder'=>$text('مثال رقم واتساب',100),'topic_label'=>$text('اسم حقل نوع الاستفسار',100),
            'message_label'=>$text('اسم حقل الرسالة',100),'message_placeholder'=>$text('مثال الرسالة',200),'submit_label'=>$text('نص زر الإرسال',100),'success_message'=>$text('رسالة نجاح الإرسال',300),
            'topics'=>$list('أنواع الاستفسارات',['key'=>$text('المفتاح بالإنجليزية',40),'label'=>$text('الاسم الظاهر',150)],20),
            'email_title'=>$text('عنوان بطاقة البريد',100),'email_address'=>$text('البريد الإلكتروني',250),
            'whatsapp_title'=>$text('عنوان بطاقة واتساب',100),'whatsapp_number'=>$text('رقم واتساب الظاهر',50),'whatsapp_url'=>$url('رابط واتساب'),
            'offices_title'=>$text('عنوان المكاتب',100),'offices'=>$list('المكاتب',['city'=>$text('المدينة',100),'description'=>$text('الوصف',250),'style'=>$select('لون الشارة',['orange'=>'برتقالي','info'=>'أزرق','success'=>'أخضر'])],20),
            'notice'=>$area('التنويه القانوني',1200),
        ]+$meta],
    ];
}

function contact_page_defaults():array
{
    return [
        'hero'=>['eyebrow'=>'نحن هنا','title'=>'تواصل مع فريق Seven Tech Capital','description'=>'استفسارات المستثمرين ورواد الأعمال والشراكات — نجيبك في أقرب وقت.','sort_order'=>1,'is_active'=>true],
        'contact'=>[
            'form_kicker'=>'راسلنا مباشرة','form_title'=>'أرسل رسالة','form_description'=>'شاركنا تفاصيل استفسارك وسيوجّه الفريق رسالتك إلى الشخص المناسب.',
            'name_label'=>'الاسم','name_placeholder'=>'اسمك…','email_label'=>'البريد','email_placeholder'=>'you@example.com…','whatsapp_label'=>'رقم واتساب','whatsapp_placeholder'=>'+966539555889','topic_label'=>'نوع الاستفسار','message_label'=>'الرسالة','message_placeholder'=>'كيف يمكننا مساعدتك؟','submit_label'=>'إرسال الرسالة','success_message'=>'شكرًا! تم استلام رسالتك وسيتواصل معك الفريق.',
            'topics'=>[
                ['key'=>'investor','label'=>'استفسار مستثمر','sort_order'=>1,'is_active'=>true],['key'=>'project','label'=>'تقديم مشروع','sort_order'=>2,'is_active'=>true],['key'=>'partnership','label'=>'شراكة استراتيجية','sort_order'=>3,'is_active'=>true],['key'=>'media','label'=>'إعلامي / صحفي','sort_order'=>4,'is_active'=>true],['key'=>'general','label'=>'عام','sort_order'=>5,'is_active'=>true],
            ],
            'email_title'=>'البريد الإلكتروني','email_address'=>'hello@seventech.capital','whatsapp_title'=>'واتساب','whatsapp_number'=>'+966539555889','whatsapp_url'=>'https://wa.me/966539555889','offices_title'=>'المكاتب',
            'offices'=>[
                ['city'=>'القاهرة','description'=>'مصر — المقر الرئيسي','style'=>'orange','sort_order'=>1,'is_active'=>true],['city'=>'دبي','description'=>'الإمارات — قيد التوسع','style'=>'info','sort_order'=>2,'is_active'=>true],['city'=>'الرياض','description'=>'السعودية — مستهدف','style'=>'success','sort_order'=>3,'is_active'=>true],
            ],
            'notice'=>'كيان قيد التأسيس واستكمال التراخيص — لا يُستقبل تمويل قبل الاعتماد القانوني.','sort_order'=>2,'is_active'=>true,
        ],
    ];
}

function contact_page_db():PDO
{
    $db=admin_db();static $ready=false;if(!$ready){$db->exec('CREATE TABLE IF NOT EXISTS contact_page_sections(section_key VARCHAR(40) PRIMARY KEY,content_json MEDIUMTEXT NOT NULL,revision INT UNSIGNED NOT NULL DEFAULT 1,updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');$db->exec('CREATE TABLE IF NOT EXISTS contact_messages(id VARCHAR(40) PRIMARY KEY,name VARCHAR(180) NOT NULL,email VARCHAR(250) NOT NULL,whatsapp VARCHAR(60) NOT NULL DEFAULT "",topic_key VARCHAR(40) NOT NULL,topic_label VARCHAR(150) NOT NULL,message TEXT NOT NULL,ip_hash CHAR(64) NOT NULL,user_agent VARCHAR(300) NOT NULL DEFAULT "",created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,INDEX idx_contact_created(created_at),INDEX idx_contact_rate(ip_hash,created_at)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');$seed=$db->prepare('INSERT IGNORE INTO contact_page_sections(section_key,content_json) VALUES (?,?)');foreach(contact_page_defaults() as $key=>$value)$seed->execute([$key,json_encode($value,JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR)]);$ready=true;}return $db;
}

function contact_page_read(?PDO $db=null):array
{
    $db??=contact_page_db();$defaults=contact_page_defaults();$schema=contact_page_schema();$sections=[];foreach($db->query('SELECT * FROM contact_page_sections') as $row){$key=(string)$row['section_key'];if(!isset($schema[$key]))continue;$stored=json_decode((string)$row['content_json'],true,64,JSON_THROW_ON_ERROR);$values=is_array($stored)?$stored:[];foreach($defaults[$key] as $field=>$value)if(!array_key_exists($field,$values))$values[$field]=$value;$sections[$key]=['content'=>home_default_fields($schema[$key]['fields'],$values),'revision'=>(int)$row['revision'],'updated_at'=>(string)$row['updated_at']];}return $sections;
}

function contact_page_validate(string $key,array $input):array
{
    $schema=contact_page_schema()[$key]??null;if(!$schema)throw new InvalidArgumentException('القسم غير موجود.');$content=home_validate($schema['fields'],$input);if($key==='hero'&&$content['title']==='')throw new InvalidArgumentException('اكتب عنوان المقدمة.');if($key==='contact'){
        if($content['form_title']===''||$content['email_address']===''||!filter_var($content['email_address'],FILTER_VALIDATE_EMAIL))throw new InvalidArgumentException('أكمل عنوان النموذج واكتب بريدًا صحيحًا.');$keys=[];foreach($content['topics'] as $item){if(!preg_match('/^[a-z0-9_-]{1,40}$/D',$item['key'])||$item['label']==='')throw new InvalidArgumentException('أكمل أنواع الاستفسارات بمفاتيح إنجليزية صحيحة.');if(isset($keys[$item['key']]))throw new InvalidArgumentException('مفتاح نوع الاستفسار مكرر.');$keys[$item['key']]=true;}foreach($content['offices'] as $item)if($item['city']===''||$item['description']==='')throw new InvalidArgumentException('أكمل المدينة ووصف كل مكتب.');
    }return $content;
}

function contact_page_save(string $key,array $input,int $revision,string $adminId):int
{
    $content=contact_page_validate($key,$input);$db=contact_page_db();$db->beginTransaction();try{$stmt=$db->prepare('UPDATE contact_page_sections SET content_json=?,revision=revision+1,updated_at=NOW() WHERE section_key=? AND revision=?');$stmt->execute([json_encode($content,JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR),$key,$revision]);if($stmt->rowCount()!==1)throw new RuntimeException('تم تعديل هذا القسم في جلسة أخرى. حدّث الصفحة قبل الحفظ.',409);$db->prepare('INSERT INTO admin_audit_log(admin_user_id,action,entity_type,entity_id,details) VALUES (?,?,?,?,?)')->execute([$adminId,'update','contact_page',$key,'تحديث قسم '.contact_page_schema()[$key]['label']]);$db->commit();return $revision+1;}catch(Throwable $error){if($db->inTransaction())$db->rollBack();throw $error;}
}

function contact_page_public_payload(array $sections):array
{
    $meta=[];$data=[];foreach($sections as $key=>$section){$value=$section['content'];$meta[]=['key'=>$key,'is_active'=>(bool)$value['is_active'],'sort_order'=>(int)$value['sort_order']];if(!$value['is_active'])continue;unset($value['sort_order'],$value['is_active']);if(isset($value['topics']))$value['topics']=home_visible($value['topics']);if(isset($value['offices']))$value['offices']=home_visible($value['offices']);$data[$key]=$value;}usort($meta,static fn(array $a,array $b):int=>$a['sort_order']<=>$b['sort_order']);return ['ok'=>true,'sections'=>$data,'meta'=>['sections'=>$meta]];
}

function contact_page_submit(array $input,string $ip,string $userAgent):string
{
    if(trim((string)($input['website']??''))!=='')return '';$name=trim((string)($input['name']??''));$email=trim((string)($input['email']??''));$whatsapp=trim((string)($input['whatsapp']??''));$topic=trim((string)($input['topic']??''));$message=trim((string)($input['message']??''));
    if(mb_strlen($name)<2||mb_strlen($name)>180||!filter_var($email,FILTER_VALIDATE_EMAIL)||mb_strlen($email)>250||mb_strlen($whatsapp)>60||mb_strlen($message)<10||mb_strlen($message)>5000)throw new InvalidArgumentException('تحقق من الاسم والبريد والرسالة ثم حاول مرة أخرى.');
    $sections=contact_page_read();$topics=[];foreach(home_visible($sections['contact']['content']['topics']) as $item)$topics[$item['key']]=$item['label'];if(!isset($topics[$topic]))throw new InvalidArgumentException('نوع الاستفسار غير صالح.');
    $db=contact_page_db();$ipHash=hash('sha256',$ip.'|contact-seven-tech-capital');$rate=$db->prepare('SELECT COUNT(*) FROM contact_messages WHERE ip_hash=? AND created_at>=DATE_SUB(NOW(),INTERVAL 1 HOUR)');$rate->execute([$ipHash]);if((int)$rate->fetchColumn()>=3)throw new RuntimeException('تم إرسال عدة رسائل مؤخرًا. حاول مرة أخرى لاحقًا.',429);
    $id=admin_id('MSG');$db->prepare('INSERT INTO contact_messages(id,name,email,whatsapp,topic_key,topic_label,message,ip_hash,user_agent) VALUES (?,?,?,?,?,?,?,?,?)')->execute([$id,$name,$email,$whatsapp,$topic,$topics[$topic],$message,$ipHash,mb_substr($userAgent,0,300)]);return $id;
}

function contact_page_messages(int $limit=30):array
{
    $limit=max(1,min(100,$limit));return contact_page_db()->query('SELECT id,name,email,whatsapp,topic_label,message,created_at FROM contact_messages ORDER BY created_at DESC LIMIT '.$limit)->fetchAll();
}
