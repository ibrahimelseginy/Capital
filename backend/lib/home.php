<?php
declare(strict_types=1);
require_once __DIR__ . '/database.php';

function home_schema(): array
{
    $text = static fn(string $label, int $max = 250): array => ['label'=>$label, 'type'=>'text', 'max'=>$max];
    $area = static fn(string $label): array => ['label'=>$label, 'type'=>'textarea', 'max'=>3000];
    $url = static fn(string $label): array => ['label'=>$label, 'type'=>'url', 'max'=>1000];
    $select = static fn(string $label, array $options): array => ['label'=>$label,'type'=>'select','options'=>$options];
    $meta = ['is_active'=>['label'=>'إظهار للجمهور','type'=>'checkbox','default'=>true], 'sort_order'=>['label'=>'الترتيب','type'=>'number','max'=>1000,'default'=>0]];
    $list = static fn(string $label, array $fields, int $max = 16): array => ['label'=>$label,'type'=>'list','fields'=>$fields + $meta,'max'=>$max];
    $icon = $select('الأيقونة', array_map(static fn(array $icon): string => $icon[0], home_icons()));
    $intro = ['eyebrow'=>$text('التسمية أعلى العنوان'), 'title'=>$text('العنوان'), 'description'=>$area('الوصف')];
    $button = ['button_label'=>$text('نص الزر'), 'button_url'=>$url('رابط الزر')];
    $twoButtons = ['primary_label'=>$text('نص الزر الرئيسي'),'primary_url'=>$url('رابط الزر الرئيسي'),'secondary_label'=>$text('نص الزر الثاني'),'secondary_url'=>$url('رابط الزر الثاني')];
    $card = ['title'=>$text('العنوان'),'description'=>$area('المحتوى')];
    $featured = ['label'=>'مميّز','type'=>'checkbox','default'=>false];
    return [
        'hero'=>['label'=>'المقدمة — Hero','fields'=>[
            'badge'=>$text('شارة الحالة'),'region'=>$text('النطاق الإقليمي'),'title'=>$text('العنوان الرئيسي'),'highlight'=>$text('جزء العنوان الملوّن'),'description'=>$area('وصف المقدمة'),
        ] + $twoButtons + [
            'proofs'=>$list('مزايا أسفل الوصف',['text'=>$text('الميزة')],10),
            'ledger_tag'=>$text('التسمية الإنجليزية للسجل'),'ledger_title'=>$text('عنوان سجل الجاهزية'),'ledger_status'=>$text('حالة القفل'),'ledger_description'=>$area('وصف سجل الجاهزية'),
            'gates'=>$list('بوابات الجاهزية',$card + ['status'=>$text('نص الحالة'),'style'=>$select('شكل الحالة',['done'=>'مُجتاز','active'=>'جارٍ','waiting'=>'بانتظار','unlock'=>'الإفراج'])],10),
            'meter_label'=>$text('عنوان شريط التقدم'),'meter_status'=>$text('حالة شريط التقدم'),'progress'=>['label'=>'نسبة التقدم %','type'=>'number','max'=>100],
        ] + $meta],
        'stats'=>['label'=>'شريط الإحصائيات','fields'=>['items'=>$list('الإحصائيات',['value'=>$text('القيمة المعروضة مثل 20+ أو $50M',80),'label'=>$text('وصف الإحصائية'),'icon'=>$icon],12)] + $meta],
        'why'=>['label'=>'لماذا Seven Tech Capital','fields'=>$intro + ['items'=>$list('بطاقات المزايا',$card + ['icon'=>$icon,'featured'=>$featured])] + $meta],
        'sectors'=>['label'=>'القطاعات المستهدفة','fields'=>$intro + $button + ['items'=>$list('بطاقات القطاعات',$card + ['icon'=>$icon,'button_label'=>$text('نص رابط أمثلة الفرص'),'url'=>$url('رابط القطاع — مثال sectors.php#sector-01')])] + $meta],
        'paths'=>['label'=>'مسارات المنصة','fields'=>$intro + ['items'=>$list('بطاقات المسارات',['badge'=>$text('تسمية المسار')] + $card + ['icon'=>$icon,'style'=>$select('شكل البطاقة',['investor'=>'مستثمر','entrepreneur'=>'رائد أعمال']),'button_label'=>$text('نص الزر'),'url'=>$url('رابط المسار'),'features'=>$list('نقاط المسار',['text'=>$text('النقطة',500)],12)],6)] + $meta],
        'stories'=>['label'=>'قصص النجاح','fields'=>$intro + $button + [
            'note'=>$area('التنويه أسفل البطاقات'),'preview_label'=>$text('نص المعاينة'),'modal_badge'=>$text('شارة نافذة المعاينة'),'modal_note'=>$area('تنويه نافذة المعاينة'),'modal_button_label'=>$text('نص زر نافذة المعاينة'),'modal_button_url'=>$url('رابط زر نافذة المعاينة'),
            'items'=>$list('بطاقات قصص النجاح',['category'=>$text('القطاع')] + $card + ['modal_description'=>$area('المحتوى داخل نافذة المعاينة'),'metrics'=>$list('مؤشرات الأداء',['value'=>$text('القيمة',80),'label'=>$text('وصف المؤشر'),'style'=>$select('لون المؤشر',['is-positive'=>'أخضر','is-reduction'=>'برتقالي','is-time'=>'أزرق'])],6)],12),
        ] + $meta],
        'news'=>['label'=>'الأخبار والفعاليات','fields'=>$intro + $button + ['items'=>$list('بطاقات المستجدات',['category'=>$text('التسمية الظاهرة'),'style'=>$select('لون النوع',['is-event'=>'فعالية — أزرق','is-article'=>'مقال — برتقالي','is-partner'=>'شراكة — أخضر'])] + $card + ['date'=>['label'=>'تاريخ النشر','type'=>'date','max'=>10],'date_label'=>$text('التاريخ المعروض'),'featured'=>$featured,'button_label'=>$text('نص رابط القراءة'),'url'=>$url('رابط الخبر — مثال news-events.php#news-CNT-001')],12)] + $meta],
        'cta'=>['label'=>'الدعوة لاتخاذ إجراء — CTA','fields'=>$intro + $twoButtons + $meta],
    ];
}

// Reuses the site's existing icon paths; editors choose keys, never raw markup.
function home_icons(): array
{
    return [
        'award'=>['خبرة / وسام','<circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/>'],
        'company'=>['شركة','<path d="M3 21h18M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16M9 7h.5M9 11h.5M9 15h.5M14 7h.5M14 11h.5M14 15h.5"/>'],
        'growth'=>['نمو','<path d="M3 3v18h18M7 14l4-4 3 3 5-6"/>'],
        'people'=>['عملاء','<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>'],
        'shield'=>['حماية','<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>'],
        'chip'=>['تقنية','<rect x="4" y="4" width="16" height="16" rx="2"/><path d="M9 9h6v6H9z"/>'],
        'bolt'=>['ثقة / طاقة','<path d="M13 2L3 14h9l-1 8 10-12h-9z"/>'],
        'software'=>['برمجيات','<rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>'],
        'fintech'=>['تقنية مالية','<rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20M6 15h4"/>'],
        'ai'=>['ذكاء اصطناعي','<circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3M5 5l2 2M17 17l2 2M19 5l-2 2M7 17l-2 2"/>'],
        'health'=>['صحة','<path d="M12 21s-8-5-8-11a5 5 0 0 1 9-3 5 5 0 0 1 9 3c0 6-8 11-8 11z"/>'],
        'education'=>['تعليم','<path d="M22 10L12 5 2 10l10 5 10-5zM6 12v5c3 2 9 2 12 0v-5"/>'],
        'iot'=>['إنترنت الأشياء','<circle cx="12" cy="12" r="2"/><path d="M4.9 4.9a10 10 0 0 0 0 14.2M19.1 4.9a10 10 0 0 1 0 14.2"/>'],
        'logistics'=>['لوجستيات','<rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 3v5h-7z"/><circle cx="5.5" cy="18.5" r="2"/><circle cx="18.5" cy="18.5" r="2"/>'],
        'digital'=>['تحول رقمي','<path d="M21 12a9 9 0 1 1-3-6.7M21 3v6h-6"/>'],
        'edit'=>['بناء المشروع','<path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5ZM14.5 5.5l4 4"/>'],
    ];
}

function home_default_fields(array $fields, array $values = []): array
{
    $result=[];
    foreach ($fields as $key=>$field) {
        if ($field['type']==='list') {
            $result[$key]=[];
            foreach ($values[$key] ?? [] as $i=>$item) $result[$key][]=home_default_fields($field['fields'], $item + ['sort_order'=>$i+1]);
        } else $result[$key]=$values[$key] ?? $field['default'] ?? ($field['type']==='number' ? 0 : ($field['type']==='select' ? array_key_first($field['options']) : ''));
    }
    return $result;
}

function home_defaults(): array
{
    $values=json_decode(file_get_contents(__DIR__.'/../config/home-defaults.json'),true,512,JSON_THROW_ON_ERROR);
    $sections=[];
    foreach (home_schema() as $key=>$section) $sections[$key]=home_default_fields($section['fields'],$values[$key] + ['sort_order'=>count($sections)+1]);
    return $sections;
}

function home_db(): PDO
{
    $db=app_db();
    if (!$db instanceof PDO) throw new RuntimeException('قاعدة البيانات غير متاحة. تأكد من تشغيل MySQL في MAMP.');
    static $ready=false;
    if (!$ready) {
        $db->exec('CREATE TABLE IF NOT EXISTS home_page_sections (section_key VARCHAR(30) PRIMARY KEY, content_json MEDIUMTEXT NOT NULL, revision INT UNSIGNED NOT NULL DEFAULT 1, updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        $seed=$db->prepare('INSERT IGNORE INTO home_page_sections (section_key,content_json) VALUES (?,?)');
        foreach (home_defaults() as $key=>$content) $seed->execute([$key,json_encode($content,JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR)]);
        $ready=true;
    }
    return $db;
}

function home_read(): array
{
    $sections=[];
    foreach (home_db()->query('SELECT * FROM home_page_sections') as $row) {
        if (!isset(home_schema()[$row['section_key']])) continue;
        $sections[$row['section_key']]=['content'=>json_decode($row['content_json'],true,512,JSON_THROW_ON_ERROR),'revision'=>(int)$row['revision'],'updated_at'=>$row['updated_at']];
    }
    return $sections;
}

function home_safe_url(string $value): bool
{
    if ($value==='') return true;
    if (preg_match('/[\x00-\x20\x7f\\\\]/', $value) || str_starts_with($value,'//')) return false;
    if (preg_match('~^https?://~i',$value)) {
        $parts=parse_url($value);
        return filter_var($value,FILTER_VALIDATE_URL)!==false && !isset($parts['user']) && !isset($parts['pass']);
    }
    // Relative app routes only; blocks javascript:, data: and encoded schemes.
    return preg_match('~^(?:[a-zA-Z0-9_-]+(?:\.php|\.html)?/)*[a-zA-Z0-9_-]+\.(?:php|html)(?:[?#][^<>"\x27]*)?$~D',$value)===1 || preg_match('~^#[a-zA-Z0-9_-]+$~D',$value)===1;
}

function home_validate(array $fields, array $input, string $path=''): array
{
    $out=[];
    foreach ($fields as $key=>$field) {
        $label=$path.$field['label'];
        if (!array_key_exists($key,$input)) throw new InvalidArgumentException('حقل مفقود: '.$label);
        $value=$input[$key];
        switch ($field['type']) {
            case 'list':
                if (!is_array($value) || !array_is_list($value) || count($value)>$field['max']) throw new InvalidArgumentException('عدد العناصر غير صالح: '.$label);
                $out[$key]=[];
                foreach ($value as $i=>$item) {
                    if (!is_array($item)) throw new InvalidArgumentException('عنصر غير صالح: '.$label);
                    $out[$key][]=home_validate($field['fields'],$item,$label.' / '.($i+1).' / ');
                }
                break;
            case 'checkbox':
                if (!is_bool($value)) throw new InvalidArgumentException('قيمة الإظهار غير صالحة: '.$label);
                $out[$key]=$value; break;
            case 'number':
                if (!is_int($value) || $value<0 || $value>$field['max']) throw new InvalidArgumentException('الرقم خارج النطاق: '.$label);
                $out[$key]=$value; break;
            default:
                if (!is_string($value) || mb_strlen($value)>($field['max']??250)) throw new InvalidArgumentException('النص أطول من المسموح: '.$label);
                $value=trim($value);
                if ($field['type']==='url' && !home_safe_url($value)) throw new InvalidArgumentException('استخدم رابطًا آمنًا أو مسار صفحة صحيحًا: '.$label);
                if ($field['type']==='select' && !array_key_exists($value,$field['options'])) throw new InvalidArgumentException('اختيار غير صالح: '.$label);
                if ($field['type']==='date' && $value!=='') {
                    $date=DateTimeImmutable::createFromFormat('!Y-m-d',$value);
                    if (!$date || $date->format('Y-m-d')!==$value) throw new InvalidArgumentException('تاريخ غير صالح: '.$label);
                }
                $out[$key]=$value;
        }
    }
    return $out;
}

function home_save(string $key, array $content, int $revision, string $adminId): int
{
    $schema=home_schema();
    if (!isset($schema[$key])) throw new InvalidArgumentException('القسم غير موجود.');
    $content=home_validate($schema[$key]['fields'],$content);
    $db=home_db();
    $db->beginTransaction();
    try {
        $stmt=$db->prepare('UPDATE home_page_sections SET content_json=?,revision=revision+1,updated_at=NOW() WHERE section_key=? AND revision=?');
        $stmt->execute([json_encode($content,JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR),$key,$revision]);
        if ($stmt->rowCount()!==1) throw new RuntimeException('تم تعديل هذا القسم في جلسة أخرى. حدّث الصفحة قبل الحفظ.',409);
        $log=$db->prepare('INSERT INTO admin_audit_log (admin_user_id,action,entity_type,entity_id,details) VALUES (?,?,?,?,?)');
        $log->execute([$adminId,'update','home_page',$key,'تحديث قسم '.$schema[$key]['label']]);
        $db->commit();
    } catch (Throwable $error) { $db->rollBack(); throw $error; }
    return $revision+1;
}

function home_visible(array $items): array
{
    $items=array_values(array_filter($items,static fn(array $item): bool => !empty($item['is_active'])));
    usort($items,static fn(array $a,array $b): int => $a['sort_order'] <=> $b['sort_order']);
    return $items;
}

function home_public_content(array $sections): array
{
    $filter=static function(array $content) use (&$filter): array {
        foreach ($content as $key=>$value) if (is_array($value)) $content[$key]=array_map($filter,home_visible($value));
        return $content;
    };
    $result=[];
    foreach ($sections as $key=>$section) if ($section['content']['is_active']) $result[$key]=$filter($section['content']);
    uasort($result,static fn(array $a,array $b): int => $a['sort_order'] <=> $b['sort_order']);
    return $result;
}

function home_escape(string $text): string { return htmlspecialchars($text,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8'); }
function home_icon(string $key): string
{
    $icon=home_icons()[$key] ?? home_icons()['software'];
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.$icon[1].'</svg>';
}
