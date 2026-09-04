<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, max-age=0');
header('X-Content-Type-Options: nosniff');

$defaultJson = <<<'JSON'
[
  {
    "id": "ABT-HERO",
    "section_key": "hero",
    "title": "نَبني المشروع قبل أن نُفعّل الاستثمار",
    "subtitle": "قصتنا",
    "body": "Seven Tech Capital صندوق استثماري مدعوم بذراع تقني بخبرة تمتد إلى 20 عامًا في تأسيس وتشغيل الشركات التقنية.",
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
    "id": "ABT-BRAND-01",
    "section_key": "brand",
    "title": "Seven Tech Capital",
    "subtitle": "· A Venture Studio",
    "body": "صندوق/استوديو مشاريع استثماري قيد التأسيس، يستهدف بناء فرص أكثر جاهزية وتقليل مخاطر التنفيذ.",
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
    "id": "ABT-BRAND-02",
    "section_key": "brand",
    "title": "Seven Tech",
    "subtitle": "الذراع التقني",
    "body": "الذراع التقني المنفصل المسؤول عن بناء المنتجات والأنظمة ودعم التشغيل، بخبرة 15 عامًا وأكثر من 500 عميل.",
    "badge_label": "",
    "badge_style": "info",
    "value_text": "",
    "value_suffix": "",
    "icon_key": "default",
    "primary_url": "",
    "secondary_url": "",
    "sort_order": 2
  },
  {
    "id": "ABT-VMM-01",
    "section_key": "vmm",
    "title": "الرؤية",
    "subtitle": "",
    "body": "أن نكون المنصة الأكثر ثقة لربط رأس المال بالمشاريع التقنية الجاهزة للنمو في المنطقة.",
    "badge_label": "",
    "badge_style": "info",
    "value_text": "",
    "value_suffix": "",
    "icon_key": "vision",
    "primary_url": "",
    "secondary_url": "",
    "sort_order": 1
  },
  {
    "id": "ABT-VMM-02",
    "section_key": "vmm",
    "title": "الرسالة",
    "subtitle": "",
    "body": "نختبر الفكرة، نبني المنتج، ونُجهّز المشروع للتشغيل قبل تفعيل رأس المال.",
    "badge_label": "",
    "badge_style": "info",
    "value_text": "",
    "value_suffix": "",
    "icon_key": "mission",
    "primary_url": "",
    "secondary_url": "",
    "sort_order": 2
  },
  {
    "id": "ABT-VMM-03",
    "section_key": "vmm",
    "title": "المنهجية",
    "subtitle": "",
    "body": "بوابات مراجعة وجاهزية وتشغيل قبل تحرير التمويل، بآلية قانونية معتمدة.",
    "badge_label": "",
    "badge_style": "info",
    "value_text": "",
    "value_suffix": "",
    "icon_key": "method",
    "primary_url": "",
    "secondary_url": "",
    "sort_order": 3
  },
  {
    "id": "ABT-STAT-01",
    "section_key": "stat",
    "title": "عامًا خبرة تراكمية",
    "subtitle": "",
    "body": "",
    "badge_label": "",
    "badge_style": "info",
    "value_text": "20",
    "value_suffix": "+",
    "icon_key": "experience",
    "primary_url": "",
    "secondary_url": "",
    "sort_order": 1
  },
  {
    "id": "ABT-STAT-02",
    "section_key": "stat",
    "title": "عامًا عمر Seven Tech",
    "subtitle": "",
    "body": "",
    "badge_label": "",
    "badge_style": "info",
    "value_text": "15",
    "value_suffix": "",
    "icon_key": "building",
    "primary_url": "",
    "secondary_url": "",
    "sort_order": 2
  },
  {
    "id": "ABT-STAT-03",
    "section_key": "stat",
    "title": "شركات ومشروعات",
    "subtitle": "",
    "body": "",
    "badge_label": "",
    "badge_style": "info",
    "value_text": "10",
    "value_suffix": "+",
    "icon_key": "projects",
    "primary_url": "",
    "secondary_url": "",
    "sort_order": 3
  },
  {
    "id": "ABT-STAT-04",
    "section_key": "stat",
    "title": "عميل في المنطقة",
    "subtitle": "",
    "body": "",
    "badge_label": "",
    "badge_style": "info",
    "value_text": "500",
    "value_suffix": "+",
    "icon_key": "clients",
    "primary_url": "",
    "secondary_url": "",
    "sort_order": 4
  },
  {
    "id": "ABT-TEAM-HEAD",
    "section_key": "team_header",
    "title": "خبرات متعددة في مكان واحد",
    "subtitle": "الفريق والمجلس الاستشاري",
    "body": "مجلس استشاري قوي من خبراء الاستثمار والتقنية والمال والقانون والتشغيل.",
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
    "id": "ABT-TEAM-01",
    "section_key": "team",
    "title": "المؤسس والرئيس التنفيذي",
    "subtitle": "قيادة تنفيذية",
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
    "id": "ABT-GEO-HEAD",
    "section_key": "geo_header",
    "title": "نطاق يمتد عبر المنطقة",
    "subtitle": "التوسع الجغرافي",
    "body": "",
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
    "id": "ABT-GEO-01",
    "section_key": "geo",
    "title": "MENA والخليج",
    "subtitle": "",
    "body": "الشرق الأوسط وشمال أفريقيا ودول الخليج — نطاق الإطلاق الأساسي.",
    "badge_label": "نشط",
    "badge_style": "success",
    "value_text": "",
    "value_suffix": "",
    "icon_key": "default",
    "primary_url": "",
    "secondary_url": "",
    "sort_order": 1
  },
  {
    "id": "ABT-CTA",
    "section_key": "cta",
    "title": "لنبنِ شيئًا يستحق",
    "subtitle": "",
    "body": "سواء كنت مستثمرًا أو رائد أعمال، ابدأ رحلتك مع Seven Tech Capital.",
    "badge_label": "انضم إلينا",
    "badge_style": "info",
    "value_text": "تواصل معنا",
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
    $rows=admin_rows('SELECT id,section_key,title,subtitle,body,badge_label,badge_style,value_text,value_suffix,icon_key,primary_url,secondary_url,sort_order FROM about_page_items WHERE is_active=1 ORDER BY section_key,sort_order,created_at');
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
