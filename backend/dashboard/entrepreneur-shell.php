<?php
$sectionKey = $sectionKey ?? 'applications';
$sections = [
  'applications' => ['file'=>'entrepreneur-applications.php','title'=>'طلباتي','crumb'=>'طلبات رائد الأعمال','badge'=>'2 طلبات'],
  'evaluation' => ['file'=>'entrepreneur-evaluation.php','title'=>'مراحل التقييم','crumb'=>'مسار التقييم','badge'=>'مرحلة الدراسة'],
  'completion' => ['file'=>'entrepreneur-completion.php','title'=>'الاستكمالات','crumb'=>'طلبات الاستكمال','badge'=>'1 مطلوب'],
  'meetings' => ['file'=>'entrepreneur-meetings.php','title'=>'الاجتماعات','crumb'=>'جدول الاجتماعات','badge'=>'اجتماع قادم'],
  'documents' => ['file'=>'entrepreneur-documents.php','title'=>'المستندات','crumb'=>'ملفات المشروع','badge'=>'3 ملفات'],
  'tasks' => ['file'=>'entrepreneur-tasks.php','title'=>'المهام','crumb'=>'مهام المشروع','badge'=>'5 مهام'],
  'conversations' => ['file'=>'entrepreneur-conversations.php','title'=>'المحادثات','crumb'=>'التواصل مع الفريق','badge'=>'2 جديد'],
  'support-tickets' => ['file'=>'entrepreneur-support-tickets.php','title'=>'تذاكر الدعم','crumb'=>'الدعم الفني','badge'=>'متابعة'],
  'settings' => ['file'=>'entrepreneur-settings.php','title'=>'الإعدادات','crumb'=>'إعدادات الحساب','badge'=>'الأمان والتفضيلات'],
];
$current = $sections[$sectionKey] ?? $sections['applications'];
$base = '../';
$title = $current['title'];
include __DIR__ . '/../partials/head.php';
$authName = htmlspecialchars((string) ($_SESSION['name'] ?? 'رائد أعمال'));
$authProject = htmlspecialchars((string) ($_SESSION['project'] ?? 'المشروع'));

function entrepreneur_side_link($key, $label, $icon, $count = null, $muted = false) {
  global $sections, $sectionKey;
  $active = $sectionKey === $key ? ' active' : '';
  $countHtml = $count !== null ? '<span class="count'.($muted ? ' muted' : '').'">'.$count.'</span>' : '';
  echo '<a href="'.$sections[$key]['file'].'" class="side-link'.$active.'"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">'.$icon.'</svg> '.$label.' '.$countHtml.'</a>';
}
?>
<div class="app">
  <aside class="sidebar" id="sidebar">
    <a href="../index.php" class="brand" aria-label="Seven Tech Capital — الرئيسية"><?php include __DIR__ . '/../partials/logo.php'; ?></a>

    <div class="side-role">
      <div class="avatar" style="background:var(--info)">م.س</div>
      <div style="min-width:0"><b><?= $authName ?></b><span>رائد أعمال · <?= $authProject ?></span></div>
    </div>

    <div class="side-label">المساحة</div>
    <a href="entrepreneur.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/><rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/></svg> لوحة القيادة</a>
    <?php
      entrepreneur_side_link('applications','طلباتي','<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M9 15l2 2 4-4"/>','2');
      entrepreneur_side_link('evaluation','مراحل التقييم','<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/>');
      entrepreneur_side_link('completion','الاستكمالات','<path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>','1');
      entrepreneur_side_link('meetings','الاجتماعات','<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>');
      entrepreneur_side_link('documents','المستندات','<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/>');
      entrepreneur_side_link('tasks','المهام','<path d="M9 11l3 3L22 4M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>','5', true);
    ?>

    <div class="side-label">التواصل</div>
    <?php
      entrepreneur_side_link('conversations','المحادثات','<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>','2');
      entrepreneur_side_link('support-tickets','تذاكر الدعم','<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>');
    ?>

    <div style="margin-top:auto"></div>
    <?php entrepreneur_side_link('settings','الإعدادات','<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9c.2.61.79 1 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>'); ?>
    <a href="../logout.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg> تسجيل الخروج</a>
  </aside>

  <div class="main">
    <header class="topbar dashboard-topbar">
      <button class="icon-btn side-toggle" onclick="toggleSidebar()" aria-label="فتح القائمة الجانبية" aria-expanded="false" aria-controls="sidebar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg></button>
      <div class="topbar-title"><div class="crumb"><?= $current['crumb'] ?></div><h1><?= $current['title'] ?></h1></div>
      <div class="spacer"></div>
      <a href="entrepreneur.php" class="btn btn-soft btn-sm hide-mobile">الرجوع للوحة</a>
      <button class="icon-btn" onclick="toggleTheme()" aria-label="تبديل الوضع الفاتح والداكن"><span data-theme-icon></span></button>
    </header>

    <div class="page-body entrepreneur-dashboard">
      <section class="dashboard-detail-hero reveal">
        <div>
          <span class="eyebrow"><?= $current['badge'] ?></span>
          <h2><?= $current['title'] ?></h2>
          <p>صفحة مخصصة لإدارة هذا الجزء من رحلة مشروع منصة X، مع إجراءات واضحة ومعلومات قابلة للمراجعة بسرعة.</p>
        </div>
      </section>

      <?php if ($sectionKey === 'settings'): ?>
        <div class="detail-stat-grid reveal">
          <div class="detail-stat"><span>حالة الحساب</span><b>نشط</b><small>تم تأكيد البريد ورقم واتساب</small></div>
          <div class="detail-stat"><span>الأمان</span><b>2FA</b><small>المصادقة الثنائية مفعلة</small></div>
          <div class="detail-stat"><span>آخر دخول</span><b>اليوم</b><small>القاهرة · متصفح Chrome</small></div>
        </div>
        <div class="dashboard-main-grid">
          <div class="panel reveal"><div class="panel-head"><h3>بيانات الحساب</h3><span class="badge badge-success">مؤكد</span></div><div class="panel-body">
            <div class="field"><label class="label" for="name">الاسم</label><input class="input" id="name" value="منى سالم"></div>
            <div class="auth-two-col mt-16"><div class="field"><label class="label" for="email">البريد الإلكتروني</label><input class="input ltr-input" id="email" value="mona@example.com"></div><div class="field"><label class="label" for="phone">رقم واتساب</label><input class="input ltr-input" id="phone" value="+966539555889"></div></div>
            <div class="field mt-16"><label class="label" for="role">الدور</label><input class="input" id="role" value="مؤسس · منصة X"></div>
            <a href="#" onclick="demoAction(event)" class="btn btn-primary btn-sm mt-16">حفظ التغييرات</a>
          </div></div>
          <div class="panel reveal"><div class="panel-head"><h3>الأمان والخصوصية</h3></div><div class="panel-body">
            <div class="support-ticket"><div><b>المصادقة الثنائية</b><p class="text-2">رمز تحقق عبر البريد وواتساب عند تسجيل الدخول.</p></div><span class="badge badge-success">مفعلة</span></div>
            <div class="support-ticket"><div><b>جلسات الدخول</b><p class="text-2">راجع الأجهزة النشطة وسجل آخر دخول.</p></div><a href="#" onclick="demoAction(event)" class="btn btn-soft btn-sm">إدارة</a></div>
            <div class="support-ticket"><div><b>صلاحيات مشاركة البيانات</b><p class="text-2">تحكم في الملفات التي يمكن للفريق الاستشاري رؤيتها.</p></div><span class="badge badge-info">محددة</span></div>
          </div></div>
        </div>
        <div class="panel reveal"><div class="panel-head"><h3>تفضيلات الإشعارات</h3></div><div class="panel-body">
          <?php foreach([['طلبات الاستكمال','تنبيه عند إضافة ملاحظة أو ملف مطلوب','badge-success','مفعّل'],['مواعيد الاجتماعات','تذكير قبل الاجتماع بيوم وساعة','badge-success','مفعّل'],['تحديثات التقييم','إشعار عند انتقال الطلب من مرحلة لأخرى','badge-success','مفعّل'],['نشرة المنصة','ملخص شهري عن فرص ومقالات المنصة','badge','اختياري']] as $n): ?><div class="support-ticket"><div><b><?= $n[0] ?></b><p class="text-2"><?= $n[1] ?></p></div><span class="badge <?= $n[2] ?>"><?= $n[3] ?></span></div><?php endforeach; ?>
        </div></div>
      <?php elseif ($sectionKey === 'applications'): ?>
        <div class="detail-stat-grid reveal">
          <div class="detail-stat"><span>الطلبات النشطة</span><b>2</b><small>طلب قيد الدراسة ومسودة محفوظة</small></div>
          <div class="detail-stat"><span>اكتمال الطلب الرئيسي</span><b>85%</b><small>ينقص النموذج المالي وتوضيح الاستحواذ</small></div>
          <div class="detail-stat"><span>آخر تحديث</span><b>اليوم</b><small>ملاحظة من فريق التقييم</small></div>
        </div>
        <div class="dashboard-main-grid">
          <div class="panel reveal"><div class="panel-head"><h3>طلباتك الحالية</h3><span class="badge badge-warning">قيد الدراسة</span></div><div class="panel-body">
            <div class="detail-card"><div><span class="eyebrow">طلب #E-231</span><h3>منصة X · البناء التقني + التمويل</h3><p>طلب كامل لدعم بناء المنتج، تجهيز البنية التشغيلية، وهيكلة التمويل بعد التحقق. المرحلة الحالية هي الدراسة المالية والتشغيلية.</p><div class="progress mt-16"><span style="width:85%"></span></div></div><a href="entrepreneur-evaluation.php" class="btn btn-primary btn-sm">عرض المسار</a></div>
            <div class="detail-card"><div><span class="eyebrow">مسودة</span><h3>توسّع منصة X في الخليج</h3><p>طلب توسع جغرافي محفوظ كمسودة. يحتاج إلى تحديد السوق الأول، نموذج الإيرادات المحلي، وفريق التشغيل المقترح.</p><div class="progress mt-16"><span style="width:35%"></span></div></div><a href="#" onclick="demoAction(event)" class="btn btn-soft btn-sm">استكمال المسودة</a></div>
          </div></div>
          <div class="panel reveal"><div class="panel-head"><h3>إجراءات سريعة</h3></div><div class="panel-body quick-actions-grid">
            <a href="entrepreneur-completion.php" class="quick-action"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg><span>استكمال المطلوب</span></a>
            <a href="entrepreneur-documents.php" class="quick-action"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="M17 8l-5-5-5 5"/></svg><span>رفع مستند</span></a>
            <a href="entrepreneur-meetings.php" class="quick-action"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg><span>جدولة اجتماع</span></a>
            <a href="entrepreneur-conversations.php" class="quick-action"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg><span>مراسلة الفريق</span></a>
          </div></div>
        </div>
        <div class="panel reveal"><div class="panel-head"><h3>سجل الطلبات</h3></div><div class="panel-body flush"><div class="table-wrap" style="border:none;border-radius:0"><table class="data"><thead><tr><th>الطلب</th><th>نوع الدعم</th><th>المرحلة</th><th>الاكتمال</th><th>آخر إجراء</th><th>الحالة</th></tr></thead><tbody><tr><td>منصة X</td><td>بناء تقني + تمويل</td><td>الدراسة</td><td class="mono">85%</td><td>ملاحظة مالية</td><td><span class="badge badge-warning">قيد الدراسة</span></td></tr><tr><td>توسّع الخليج</td><td>تشغيل وتوسع</td><td>مسودة</td><td class="mono">35%</td><td>حفظ تلقائي</td><td><span class="badge">مسودة</span></td></tr></tbody></table></div></div></div>
      <?php elseif ($sectionKey === 'evaluation'): ?>
        <div class="panel status-panel reveal"><div class="panel-body">
          <div class="pipeline"><?php foreach([['جديد','done'],['مراجعة','done'],['استكمال','done'],['مقابلة','done'],['دراسة','active'],['قرار',''],['تعاقد',''],['تنفيذ','']] as $s): ?><div class="pstep <?= $s[1] ?>"><div class="pbar"></div><small><?= $s[0] ?></small></div><?php endforeach; ?></div>
          <div class="status-next"><div class="row gap-8"><svg viewBox="0 0 24 24" fill="none" stroke="var(--orange)" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg><span>المرحلة الحالية: <b>الدراسة المالية والتشغيلية</b></span></div><span class="text-2">متوقع خلال 3 أيام</span></div>
        </div></div>
        <div class="dashboard-main-grid">
          <div class="panel reveal"><div class="panel-head"><h3>تفاصيل التقييم</h3></div><div class="panel-body"><?php foreach([['فني','جودة البنية التقنية، قابلية التوسع، وخطة بناء MVP.','badge-success','مقبول مبدئيًا'],['سوقي','حجم المشكلة، وضوح شريحة العملاء، وقنوات الاستحواذ.','badge-info','يحتاج توضيح'],['مالي','النموذج المالي، الوحدة الاقتصادية، واحتياج الجولة.','badge-warning','قيد المراجعة'],['تشغيلي','الفريق، خطة الإطلاق، ومتطلبات التشغيل بعد التمويل.','badge-warning','قيد الدراسة']] as $r): ?><div class="support-ticket"><div><b><?= $r[0] ?></b><p class="text-2"><?= $r[1] ?></p></div><span class="badge <?= $r[2] ?>"><?= $r[3] ?></span></div><?php endforeach; ?></div></div>
          <div class="panel reveal"><div class="panel-head"><h3>ملاحظات الفريق</h3></div><div class="panel-body"><div class="feed-item"><div class="feed-dot" style="color:var(--orange);background:var(--orange-050)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 9v4M12 17h.01"/></svg></div><div><div class="ft"><b>المالية:</b> مطلوب سيناريو تحفظي لمدة 18 شهرًا.</div><div class="fm">منذ يوم</div></div></div><div class="feed-item"><div class="feed-dot" style="color:var(--info);background:var(--info-bg)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></div><div><div class="ft"><b>السوق:</b> توضيح تكلفة الاستحواذ حسب القناة.</div><div class="fm">منذ يومين</div></div></div></div></div>
        </div>
      <?php elseif ($sectionKey === 'completion'): ?>
        <div class="detail-stat-grid reveal"><div class="detail-stat"><span>إجراءات مفتوحة</span><b>2</b><small>واحد إلزامي قبل الاجتماع</small></div><div class="detail-stat"><span>موعد التسليم</span><b>24 يوليو</b><small>قبل المقابلة المالية</small></div><div class="detail-stat"><span>حالة الملفات</span><b>3/5</b><small>ملفات مكتملة ومقبولة</small></div></div>
        <div class="panel reveal"><div class="panel-head"><h3>المطلوب منك الآن</h3><span class="badge badge-warning">إجراء مطلوب</span></div><div class="panel-body">
          <div class="detail-card"><div><h3>تحديث النموذج المالي لآخر 12 شهرًا</h3><p>أرفق ملف Excel يتضمن الإيرادات، التكاليف، الافتراضات، وسيناريو النمو الأساسي والتحفظي. هذا الملف شرط لاستكمال الدراسة.</p><div class="detail-meta"><span>المالك: منى سالم</span><span>الموعد: اليوم</span><span>الأولوية: عالية</span></div></div><a href="entrepreneur-documents.php" class="btn btn-primary btn-sm">رفع الملف</a></div>
          <div class="detail-card"><div><h3>توضيح استراتيجية الاستحواذ</h3><p>اكتبي شرحًا مختصرًا لقنوات الوصول للعملاء، تكلفة الاستحواذ المتوقعة، وخطة أول 90 يومًا بعد الإطلاق.</p><div class="detail-meta"><span>المالك: فريق النمو</span><span>الموعد: خلال يومين</span><span>الأولوية: متوسطة</span></div></div><a href="entrepreneur-conversations.php" class="btn btn-soft btn-sm">إرسال رد</a></div>
        </div></div>
      <?php elseif ($sectionKey === 'meetings'): ?>
        <div class="dashboard-main-grid"><div class="panel reveal"><div class="panel-head"><h3>الاجتماع القادم</h3><span class="badge badge-info">غدًا</span></div><div class="panel-body"><div class="meeting-card"><div class="meeting-date"><b>24</b><span>يوليو</span></div><div><h4>مقابلة تقييم مالي وتشغيلي</h4><p class="text-2">45 دقيقة · Google Meet · فريق الاستثمار والتشغيل</p><div class="detail-meta"><span>المطلوب قبل الاجتماع: نموذج مالي محدث</span><span>الحضور: 4</span></div></div></div><div class="dashboard-actions"><a href="#" onclick="demoAction(event)" class="btn btn-primary btn-sm">دخول الاجتماع</a><a href="#" onclick="demoAction(event)" class="btn btn-soft btn-sm">إعادة جدولة</a></div></div></div><div class="panel reveal"><div class="panel-head"><h3>أجندة الاجتماع</h3></div><div class="panel-body"><?php foreach(['مراجعة الافتراضات المالية','فحص خطة التشغيل لأول 90 يومًا','تحديد المخاطر المتبقية','الاتفاق على الخطوة التالية'] as $i=>$item): ?><div class="support-ticket"><div><b><?= $i+1 ?>. <?= $item ?></b><p class="text-2">بند قابل للتوثيق في سجل الاجتماع.</p></div></div><?php endforeach; ?></div></div></div>
        <div class="panel reveal"><div class="panel-head"><h3>سجل الاجتماعات</h3></div><div class="panel-body flush"><div class="table-wrap" style="border:none;border-radius:0"><table class="data"><thead><tr><th>الموضوع</th><th>التاريخ</th><th>الحضور</th><th>المخرجات</th><th>الحالة</th></tr></thead><tbody><tr><td>مراجعة أولية</td><td>17 يوليو 2026</td><td>فريق التقييم</td><td>3 مهام متابعة</td><td><span class="badge badge-success">مكتمل</span></td></tr><tr><td>عرض المنتج</td><td>14 يوليو 2026</td><td>تقني + استثمار</td><td>قبول MVP مبدئي</td><td><span class="badge badge-success">مكتمل</span></td></tr></tbody></table></div></div></div>
      <?php elseif ($sectionKey === 'documents'): ?>
        <div class="detail-stat-grid reveal"><div class="detail-stat"><span>ملفات مرفوعة</span><b>5</b><small>3 معتمدة و2 قيد المراجعة</small></div><div class="detail-stat"><span>مطلوب</span><b>1</b><small>دراسة جدوى نهائية</small></div><div class="detail-stat"><span>آخر رفع</span><b>منذ 3 أيام</b><small>Pitch Deck v3</small></div></div>
        <div class="panel reveal"><div class="panel-head"><h3>المستندات المرفوعة</h3><a href="#" onclick="demoAction(event)" class="btn btn-soft btn-sm">رفع مستند</a></div><div class="panel-body flush"><div class="table-wrap" style="border:none;border-radius:0"><table class="data"><thead><tr><th>المستند</th><th>النوع</th><th>الحجم</th><th>آخر تحديث</th><th>المراجع</th><th>الحالة</th></tr></thead><tbody><tr><td>Pitch Deck v3</td><td>PDF</td><td class="mono">4.2MB</td><td>منذ 3 أيام</td><td>فريق الاستثمار</td><td><span class="badge badge-success">معتمد</span></td></tr><tr><td>دراسة الجدوى</td><td>XLSX</td><td class="mono">1.1MB</td><td>قيد الإعداد</td><td>المالية</td><td><span class="badge badge-warning">مطلوب</span></td></tr><tr><td>النموذج المالي</td><td>XLSX</td><td class="mono">820KB</td><td>منذ أسبوع</td><td>المالية</td><td><span class="badge badge-info">قيد المراجعة</span></td></tr><tr><td>خريطة المنتج</td><td>PDF</td><td class="mono">2.8MB</td><td>منذ 5 أيام</td><td>الفريق التقني</td><td><span class="badge badge-success">معتمد</span></td></tr><tr><td>ملخص قانوني</td><td>DOCX</td><td class="mono">540KB</td><td>منذ أسبوعين</td><td>القانوني</td><td><span class="badge badge-info">قيد المراجعة</span></td></tr></tbody></table></div></div></div>
      <?php elseif ($sectionKey === 'tasks'): ?>
        <div class="panel reveal"><div class="panel-head"><h3>قائمة المهام</h3><span class="badge badge-danger">1 متأخرة</span></div><div class="panel-body">
          <?php foreach([['رفع دراسة الجدوى النهائية','متأخرة · أمس','danger','المالية','رفع ملف XLSX مع الافتراضات والتدفقات'],['تجهيز عرض المقابلة المالية','اليوم','warning','منى سالم','10 شرائح مختصرة عن الإيراد والتكلفة'],['مراجعة مسودة العقد المبدئي','خلال 3 أيام','info','المستشار القانوني','تأكيد البنود التجارية والسرية'],['تحديث بيانات الفريق','خلال أسبوع','','فريق المشروع','إضافة أدوار ومسؤوليات الأعضاء'],['رفع خريطة Roadmap','خلال أسبوع','','المنتج','خطة تطوير 6 أشهر']] as $t): ?><div class="support-ticket"><div><b><?= $t[0] ?></b><p class="text-2"><?= $t[4] ?></p><div class="detail-meta"><span><?= $t[1] ?></span><span>المسؤول: <?= $t[3] ?></span></div></div><?php if($t[2]): ?><span class="badge badge-<?= $t[2] ?>"><?= $t[2]==='danger'?'عاجل':($t[2]==='warning'?'قريب':'قادم') ?></span><?php endif; ?></div><?php endforeach; ?>
        </div></div>
      <?php elseif ($sectionKey === 'conversations'): ?>
        <div class="dashboard-main-grid"><div class="panel reveal"><div class="panel-head"><h3>المحادثات</h3><span class="badge badge-info">2 جديد</span></div><div class="panel-body">
          <div class="message-thread"><b>فريق التقييم</b><p>نحتاج تحديث النموذج المالي مع توضيح الافتراضات الخاصة بتكلفة الاستحواذ وقيمة العميل.</p><small>منذ 20 دقيقة</small></div>
          <div class="message-thread mine"><b>منى سالم</b><p>تم استلام الملاحظة. سأرفع نسخة محدثة قبل اجتماع الغد.</p><small>منذ 12 دقيقة</small></div>
          <div class="message-thread"><b>مسؤول الحساب</b><p>تم تأكيد موعد المقابلة المالية غدًا الساعة 4:00 مساءً عبر Google Meet.</p><small>اليوم · 11:30 صباحًا</small></div>
          <div class="field mt-16"><label class="label" for="reply">رد سريع</label><textarea class="textarea" id="reply" rows="4" placeholder="اكتب ردك للفريق..."></textarea></div><a href="#" onclick="demoAction(event)" class="btn btn-primary btn-sm mt-16">إرسال الرد</a>
        </div></div><div class="panel reveal"><div class="panel-head"><h3>قنوات التواصل</h3></div><div class="panel-body"><div class="support-ticket"><div><b>فريق الاستثمار</b><p class="text-2">مراجعة فرصة التمويل والتعهدات.</p></div><span class="badge badge-info">نشط</span></div><div class="support-ticket"><div><b>الفريق التقني</b><p class="text-2">مراجعة المنتج والتكاملات.</p></div><span class="badge">متاح</span></div><div class="support-ticket"><div><b>الدعم الفني</b><p class="text-2">مشاكل الحساب والملفات.</p></div><span class="badge">متاح</span></div></div></div></div>
      <?php else: ?>
        <div class="dashboard-main-grid"><div class="panel reveal"><div class="panel-head"><h3>تذاكر الدعم</h3><span class="badge badge-warning">1 متابعة</span></div><div class="panel-body">
          <div class="support-ticket"><div><b>#SUP-142 · مشكلة في رفع ملف Excel</b><p class="text-2">تم حلها بواسطة فريق الدعم ويمكنك رفع نسخة جديدة الآن.</p><div class="detail-meta"><span>الأولوية: متوسطة</span><span>آخر رد: منذ يوم</span></div></div><span class="badge badge-success">مغلقة</span></div>
          <div class="support-ticket"><div><b>#SUP-151 · طلب توضيح متطلبات KYC</b><p class="text-2">بانتظار ردك على سؤال واحد داخل المحادثة.</p><div class="detail-meta"><span>الأولوية: منخفضة</span><span>آخر رد: اليوم</span></div></div><span class="badge badge-warning">متابعة</span></div>
          <a href="#" onclick="demoAction(event)" class="btn btn-primary btn-sm mt-16">تذكرة جديدة</a>
        </div></div><div class="panel reveal"><div class="panel-head"><h3>إرشادات الدعم</h3></div><div class="panel-body"><div class="support-ticket"><div><b>الملفات</b><p class="text-2">استخدم PDF و XLSX، وحافظ على أسماء ملفات واضحة.</p></div></div><div class="support-ticket"><div><b>الردود</b><p class="text-2">أضف رقم الطلب أو اسم المستند في بداية الرسالة.</p></div></div><div class="support-ticket"><div><b>الأولوية</b><p class="text-2">تذاكر ما قبل الاجتماعات تُراجع أولًا.</p></div></div></div></div></div>
      <?php endif; ?>
    </div>
  </div>
</div>
<div class="scrim" onclick="closeOverlays()"></div>
<script src="../assets/js/app.js"></script>
</body></html>
