<?php
declare(strict_types=1);
require_once __DIR__.'/admin.php';
require_once __DIR__.'/home.php';

function investors_schema(): array
{
    $text=static fn(string $label,int $max=190):array=>['label'=>$label,'type'=>'text','max'=>$max];
    $body=['label'=>'الوصف','type'=>'textarea','max'=>1000];
    $url=static fn(string $label):array=>['label'=>$label,'type'=>'url','max'=>500];
    $meta=['sort_order'=>['label'=>'الترتيب','type'=>'number','max'=>1000,'default'=>1],'is_active'=>['label'=>'إظهار للجمهور','type'=>'checkbox','default'=>true]];
    $icons=['default'=>'افتراضية','person'=>'فرد','company'=>'شركة','fund'=>'صندوق','angel'=>'مستثمر ملائكي','family'=>'مكتب عائلي','ready'=>'جاهزية','security'=>'حماية','flexible'=>'مرونة','transparency'=>'شفافية','speed'=>'سرعة','money'=>'استثمار'];
    $icon=['label'=>'الأيقونة','type'=>'select','options'=>$icons];
    $style=['label'=>'نمط البطاقة','type'=>'select','options'=>['orange'=>'بارز برتقالي','info'=>'عادي','success'=>'أخضر','warning'=>'ذهبي'],'default'=>'orange'];
    $list=static fn(string $label,array $fields):array=>['label'=>$label,'type'=>'list','max'=>40,'fields'=>['id'=>['label'=>'المعرّف','type'=>'hidden','max'=>36]]+$fields+$meta];
    $intro=['subtitle'=>$text('التسمية أعلى العنوان',255),'title'=>$text('العنوان'),'body'=>$body];
    $buttons=['badge_label'=>$text('نص زر سجّل كمستثمر',120),'primary_url'=>$url('رابط التسجيل'),'value_text'=>$text('نص الزر الثاني',255),'secondary_url'=>$url('رابط الزر الثاني')];
    return [
        'hero'=>['label'=>'المقدمة — للمستثمرين المؤهلين','header'=>'hero','fields'=>$intro+['value_suffix'=>$text('شارات الثقة — افصل بفاصلة: KYC/AML,NDA,Dashboard',255)]+$buttons+$meta],
        'investor_type'=>['label'=>'نوعيات المستثمرين','fields'=>['items'=>$list('بطاقات أنواع المستثمرين',['title'=>$text('اسم نوع المستثمر'),'subtitle'=>$text('مسار الأهلية',255)+['default'=>'مسار أهلية'],'icon_key'=>$icon])]+$meta],
        'benefit'=>['label'=>'المزايا — لماذا تستثمر معنا','header'=>'benefits_header','fields'=>$intro+['items'=>$list('بطاقات المزايا',['title'=>$text('عنوان الميزة'),'body'=>$body,'icon_key'=>$icon,'badge_style'=>$style])]+$meta],
        'journey'=>['label'=>'رحلة المستثمر','header'=>'journey_header','item_key'=>'journey_step','fields'=>$intro+['badge_label'=>$text('نص زر ابدأ الآن',120),'primary_url'=>$url('رابط صفحة تسجيل الدخول'),'items'=>$list('خطوات رحلة المستثمر',['title'=>$text('عنوان الخطوة'),'body'=>$body,'badge_style'=>$style])]+$meta],
        'faq'=>['label'=>'الأسئلة الشائعة — أسئلة المستثمرين','header'=>'faq_header','fields'=>$intro+['items'=>$list('الأسئلة والإجابات',['title'=>$text('السؤال'),'body'=>['label'=>'الإجابة','type'=>'textarea','max'=>1000]])]+$meta],
        'cta'=>['label'=>'الدعوة للانضمام — ابدأ بأمان','header'=>'cta','fields'=>$intro+$buttons+$meta],
    ];
}


function investors_db(): PDO
{
    $db=admin_db();
    static $ready=false;
    if (!$ready) {
        $db->exec('CREATE TABLE IF NOT EXISTS investor_page_sections (section_key VARCHAR(40) PRIMARY KEY, is_active TINYINT(1) NOT NULL DEFAULT 1, sort_order INT NOT NULL DEFAULT 0, revision INT UNSIGNED NOT NULL DEFAULT 1) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        $seed=$db->prepare('INSERT IGNORE INTO investor_page_sections (section_key,sort_order) VALUES (?,?)');
        foreach (array_keys(investors_schema()) as $index=>$key) $seed->execute([$key,$index+1]);
        $ready=true;
    }
    return $db;
}

function investors_section_rows(array $rows,string $key): array
{
    $schema=investors_schema()[$key];
    $types=[$schema['item_key']??$key,$schema['header']??$key];
    return array_values(array_filter($rows,static fn(array $row):bool=>in_array($row['section_key'],$types,true)));
}

function investors_sections_from_rows(array $rows,array $settings): array
{
    $sections=[];
    foreach (investors_schema() as $key=>$schema) {
        $owned=investors_section_rows($rows,$key);
        $meta=$settings[$key];
        $values=['is_active'=>(bool)$meta['is_active'],'sort_order'=>(int)$meta['sort_order']];
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

function investors_read(?PDO $db=null,bool $lock=false): array
{
    $db??=investors_db();
    $suffix=$lock?' FOR UPDATE':'';
    $settings=[];
    foreach ($db->query('SELECT * FROM investor_page_sections ORDER BY section_key'.$suffix) as $row) $settings[$row['section_key']]=$row;
    $rows=$db->query('SELECT * FROM investor_page_items ORDER BY section_key,sort_order,id'.$suffix)->fetchAll();
    return ['rows'=>$rows,'settings'=>$settings,'sections'=>investors_sections_from_rows($rows,$settings)];
}

function investors_validate(string $key,array $input): array
{
    $schema=investors_schema()[$key]??null;
    if (!$schema) throw new InvalidArgumentException('القسم غير موجود.');
    $content=home_validate($schema['fields'],$input);
    if (isset($content['title']) && $content['title']==='') throw new InvalidArgumentException('اكتب عنوان القسم.');
    $ids=[];
    foreach ($content['items']??[] as $item) {
        if ($item['title']==='') throw new InvalidArgumentException('اكتب عنوان كل بطاقة.');
        if ($item['id']!=='' && isset($ids[$item['id']])) throw new InvalidArgumentException('توجد بطاقة مكررة.');
        if ($item['id']!=='') $ids[$item['id']]=true;
    }
    return $content;
}

function investors_blank_row(): array
{
    return ['title'=>'','subtitle'=>'','body'=>'','badge_label'=>'','badge_style'=>'info','value_text'=>'','value_suffix'=>'','icon_key'=>'default','primary_url'=>'','secondary_url'=>'','sort_order'=>1,'is_active'=>true];
}

function investors_write_row(PDO $db,array $row): void
{
    $columns=['id','section_key',...array_keys(investors_blank_row())];
    $sql='INSERT INTO investor_page_items ('.implode(',',$columns).') VALUES ('.implode(',',array_fill(0,count($columns),'?')).') ON DUPLICATE KEY UPDATE ';
    $sql.=implode(',',array_map(static fn(string $column):string=>$column.'=VALUES('.$column.')',array_slice($columns,2))).',updated_at=NOW()';
    $db->prepare($sql)->execute(array_map(static fn(string $column)=>$column==='is_active'?(int)$row[$column]:$row[$column],$columns));
}

function investors_save(string $key,array $input,string $revision,string $adminId): array
{
    $content=investors_validate($key,$input);
    $db=investors_db();
    $db->beginTransaction();
    try {
        $current=investors_read($db,true);
        if (!hash_equals($current['sections'][$key]['revision'],$revision)) throw new RuntimeException('تم تعديل هذا القسم في جلسة أخرى. حدّث الصفحة قبل الحفظ.',409);
        $schema=investors_schema()[$key];
        $owned=investors_section_rows($current['rows'],$key);
        $itemIds=[];
        if (isset($schema['header'])) {
            $existing=null;
            foreach ($owned as $row) if ($row['section_key']===$schema['header']) { $existing=$row; break; }
            $header=($existing??[])+investors_blank_row();
            $header['id']=$existing['id']??admin_id('INVP');
            $header['section_key']=$schema['header'];
            foreach (array_keys($schema['fields']) as $field) if (array_key_exists($field,investors_blank_row()) && !in_array($field,['sort_order','is_active'],true)) $header[$field]=$content[$field];
            $header['is_active']=true;
            investors_write_row($db,$header);
        }
        if (isset($content['items'])) {
            $existing=[];
            foreach ($owned as $row) if ($row['section_key']===($schema['item_key']??$key)) $existing[$row['id']]=$row;
            foreach ($content['items'] as $item) {
                $id=$item['id'];
                if ($id!=='' && !isset($existing[$id])) throw new InvalidArgumentException('البطاقة غير موجودة في هذا القسم.');
                $row=($existing[$id]??[])+investors_blank_row();
                foreach ($item as $field=>$value) $row[$field]=$value;
                $row['id']=$id!==''?$id:admin_id('INVP');
                $row['section_key']=$schema['item_key']??$key;
                investors_write_row($db,$row);
                $itemIds[]=$row['id'];
            }
            foreach (array_keys($existing) as $id) if (!in_array($id,$itemIds,true)) $db->prepare('DELETE FROM investor_page_items WHERE id=? AND section_key=?')->execute([$id,$schema['item_key']??$key]);
        }
        $db->prepare('UPDATE investor_page_sections SET is_active=?,sort_order=?,revision=revision+1 WHERE section_key=?')->execute([(int)$content['is_active'],$content['sort_order'],$key]);
        $db->prepare('INSERT INTO admin_audit_log (admin_user_id,action,entity_type,entity_id,details) VALUES (?,?,?,?,?)')->execute([$adminId,'update','investors_page',$key,'تحديث قسم '.$schema['label']]);
        $saved=investors_read($db);
        $db->commit();
        return ['revision'=>$saved['sections'][$key]['revision'],'item_ids'=>$itemIds];
    } catch (Throwable $error) { if ($db->inTransaction()) $db->rollBack(); throw $error; }
}

function investors_public_payload(array $state): array
{
    $schema=investors_schema();
    $settings=$state['settings'];
    uasort($settings,static fn(array $a,array $b):int=>(int)$a['sort_order']<=>(int)$b['sort_order']);
    $rows=[];$sections=[];
    foreach ($settings as $key=>$setting) {
        if (!isset($schema[$key])) continue;
        $sections[]=['key'=>$key,'is_active'=>(bool)$setting['is_active'],'sort_order'=>(int)$setting['sort_order']];
        if (!$setting['is_active']) continue;
        foreach (investors_section_rows($state['rows'],$key) as $row) {
            if (!$row['is_active']) continue;
            $public=array_intersect_key($row,array_flip(['id','section_key',...array_keys(investors_blank_row())]));
            $public['sort_order']=(int)$public['sort_order'];$public['is_active']=true;
            foreach (['primary_url','secondary_url'] as $field) if (!home_safe_url($public[$field])) $public[$field]='';
            $rows[]=$public;
        }
    }
    return ['ok'=>true,'data'=>$rows,'meta'=>['count'=>count($rows),'sections'=>$sections]];
}
