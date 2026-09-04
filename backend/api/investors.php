<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, max-age=0');
header('X-Content-Type-Options: nosniff');

$defaultJson = <<<'JSON'
[
  {
    "id": "INVP-HERO",
    "section_key": "hero",
    "title": "استثمر في فرص مرّت ببوابات جاهزية",
    "subtitle": "للمستثمرين المؤهلين",
    "body": "تأهيل يدوي محكم، فرص محمية لا تُعرض إلا بعد الاعتماد وتوقيع السرية، ومتابعة كاملة لمحفظتك عبر لوحة تحكم مخصصة بثلاث لغات.",
    "badge_label": "سجّل كمستثمر",
    "badge_style": "orange",
    "value_text": "رحلة المستثمر",
    "value_suffix": "KYC/AML,NDA,Dashboard",
    "icon_key": "default",
    "primary_url": "login.html?tab=register",
    "secondary_url": "#journey",
    "sort_order": 1
  },
  {
    "id": "INVP-TYPE-01",
    "section_key": "investor_type",
    "title": "فرد مؤهل",
    "subtitle": "مسار أهلية",
    "body": "",
    "badge_label": "",
    "badge_style": "info",
    "value_text": "",
    "value_suffix": "",
    "icon_key": "person",
    "primary_url": "",
    "secondary_url": "",
    "sort_order": 1
  },
  {
    "id": "INVP-TYPE-02",
    "section_key": "investor_type",
    "title": "شركات",
    "subtitle": "مسار أهلية",
    "body": "",
    "badge_label": "",
    "badge_style": "info",
    "value_text": "",
    "value_suffix": "",
    "icon_key": "company",
    "primary_url": "",
    "secondary_url": "",
    "sort_order": 2
  },
  {
    "id": "INVP-BEN-HEAD",
    "section_key": "benefits_header",
    "title": "لماذا تستثمر معنا",
    "subtitle": "المزايا",
    "body": "كل ميزة مصممة لتقليل الغموض قبل الاستثمار، من التأهيل وحتى متابعة المحفظة.",
    "badge_label": "",
    "badge_style": "info",
    "value_text": "",
    "value_suffix": "",
    "icon_key": "default",
    "primary_url": "",
    "secondary_url": "",
    "sort_order": 1
  },
  {
    "id": "INVP-BEN-01",
    "section_key": "benefit",
    "title": "فرص أكثر جاهزية",
    "subtitle": "",
    "body": "نُجهّز المشروع تقنيًا وتشغيليًا قبل تفعيل التمويل لتقليل مخاطر التنفيذ.",
    "badge_label": "",
    "badge_style": "orange",
    "value_text": "",
    "value_suffix": "",
    "icon_key": "ready",
    "primary_url": "",
    "secondary_url": "",
    "sort_order": 1
  },
  {
    "id": "INVP-BEN-02",
    "section_key": "benefit",
    "title": "حماية وسرية",
    "subtitle": "",
    "body": "لا تُعرض الفرص إلا للمعتمدين بعد توقيع NDA، مع فصل صارم للبيانات الحساسة.",
    "badge_label": "",
    "badge_style": "orange",
    "value_text": "",
    "value_suffix": "",
    "icon_key": "security",
    "primary_url": "",
    "secondary_url": "",
    "sort_order": 2
  },
  {
    "id": "INVP-JOURNEY-HEAD",
    "section_key": "journey_header",
    "title": "من التسجيل إلى المتابعة",
    "subtitle": "رحلة المستثمر",
    "body": "تسع خطوات واضحة، كل واحدة بحالة معروفة وخطوة تالية ظاهرة في لوحتك.",
    "badge_label": "ابدأ الآن",
    "badge_style": "orange",
    "value_text": "",
    "value_suffix": "",
    "icon_key": "default",
    "primary_url": "login.html?tab=register",
    "secondary_url": "",
    "sort_order": 1
  },
  {
    "id": "INVP-JOURNEY-01",
    "section_key": "journey_step",
    "title": "إنشاء حساب",
    "subtitle": "",
    "body": "الدولة، نوع المستثمر، حجم الاستثمار المتوقع، مصدر الأموال وبيانات الاتصال.",
    "badge_label": "",
    "badge_style": "orange",
    "value_text": "",
    "value_suffix": "",
    "icon_key": "default",
    "primary_url": "",
    "secondary_url": "",
    "sort_order": 1
  },
  {
    "id": "INVP-CTA",
    "section_key": "cta",
    "title": "جاهز للانضمام كمستثمر؟",
    "subtitle": "ابدأ بأمان",
    "body": "سجّل الآن وابدأ رحلة التأهيل. سرية كاملة وتجربة رقمية شفافة.",
    "badge_label": "سجّل كمستثمر",
    "badge_style": "orange",
    "value_text": "تحدث مع الفريق",
    "value_suffix": "",
    "icon_key": "default",
    "primary_url": "login.html?tab=register",
    "secondary_url": "contact.html",
    "sort_order": 1
  }
]
JSON;

$defaultData = json_decode($defaultJson, true) ?: [];

try {
    require_once __DIR__ . '/../lib/admin.php';
    $rows=admin_rows('SELECT id,section_key,title,subtitle,body,badge_label,badge_style,value_text,value_suffix,icon_key,primary_url,secondary_url,sort_order FROM investor_page_items WHERE is_active=1 ORDER BY section_key,sort_order,created_at');
    $data=array_map(static fn(array $row):array=>[
        'id'=>(string)$row['id'],'section_key'=>(string)$row['section_key'],'title'=>(string)$row['title'],
        'subtitle'=>(string)$row['subtitle'],'body'=>(string)$row['body'],'badge_label'=>(string)$row['badge_label'],
        'badge_style'=>(string)$row['badge_style'],'value_text'=>(string)$row['value_text'],'value_suffix'=>(string)$row['value_suffix'],
        'icon_key'=>(string)$row['icon_key'],'primary_url'=>(string)$row['primary_url'],'secondary_url'=>(string)$row['secondary_url'],
        'sort_order'=>(int)$row['sort_order'],
    ],$rows);
    if($data) $defaultData = $data;
} catch(Throwable $e){}

echo json_encode(['ok'=>true,'data'=>$defaultData,'meta'=>['count'=>count($defaultData)]], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
