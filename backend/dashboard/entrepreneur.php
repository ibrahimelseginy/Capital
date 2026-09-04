<?php $base='../'; $title='لوحة رائد الأعمال'; include '../partials/head.php'; $authName=htmlspecialchars((string)($_SESSION['name'] ?? 'رائد أعمال')); $authProject=htmlspecialchars((string)($_SESSION['project'] ?? 'المشروع')); ?>
<div class="app">
  <!-- ============ SIDEBAR ============ -->
  <aside class="sidebar" id="sidebar">
    <a href="../index.php" class="brand" aria-label="Seven Tech Capital — الرئيسية"><?php include '../partials/logo.php'; ?></a>

    <div class="side-role">
      <div class="avatar" style="background:var(--info)">م.س</div>
      <div style="min-width:0"><b><?= $authName ?></b><span>رائد أعمال · <?= $authProject ?></span></div>
    </div>

    <div class="side-label">المساحة</div>
    <a href="entrepreneur.php" class="side-link active"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/><rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/></svg> لوحة القيادة</a>
    <a href="entrepreneur-applications.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M9 15l2 2 4-4"/></svg> طلباتي <span class="count">2</span></a>
    <a href="entrepreneur-evaluation.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg> مراحل التقييم</a>
    <a href="entrepreneur-completion.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg> الاستكمالات <span class="count">1</span></a>
    <a href="entrepreneur-meetings.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg> الاجتماعات</a>
    <a href="entrepreneur-documents.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg> المستندات</a>
    <a href="entrepreneur-tasks.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg> المهام <span class="count muted">5</span></a>

    <div class="side-label">التواصل</div>
    <a href="entrepreneur-conversations.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg> المحادثات <span class="count">2</span></a>
    <a href="entrepreneur-support-tickets.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg> تذاكر الدعم</a>

    <div style="margin-top:auto"></div>
    <a href="entrepreneur-settings.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9c.2.61.79 1 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg> الإعدادات</a>
    <a href="../logout.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg> تسجيل الخروج</a>
  </aside>

  <!-- ============ MAIN ============ -->
  <div class="main">
    <header class="topbar dashboard-topbar">
      <button class="icon-btn side-toggle" onclick="toggleSidebar()" aria-label="فتح القائمة الجانبية" aria-expanded="false" aria-controls="sidebar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg></button>
      <div class="topbar-title"><div class="crumb">لوحة رائد الأعمال</div><h1>أهلًا <?= $authName ?>، طلبك في مرحلة الدراسة</h1></div>
      <div class="spacer"></div>
      <a href="#" onclick="demoAction(event,'نموذج تقديم متعدد الخطوات مع حفظ تلقائي.')" class="btn btn-primary btn-sm hide-mobile">طلب دعم جديد</a>
      <button class="icon-btn" onclick="toggleTheme()" aria-label="تبديل الوضع الفاتح والداكن"><span data-theme-icon></span></button>
    </header>

    <div class="page-body entrepreneur-dashboard">
      <section class="dashboard-hero-panel reveal dashboard-anchor" id="overview" aria-label="ملخص حالة الطلب">
        <div class="dashboard-hero-copy">
          <span class="eyebrow">طلب #E-231 · منصة X</span>
          <h2>البناء التقني والتمويل في مرحلة الدراسة</h2>
          <p>تابعي ما ينقص الطلب، المرحلة الحالية، والاجتماعات القادمة من مكان واحد بدون تشتيت.</p>
          <div class="dashboard-actions">
            <a href="entrepreneur-completion.php" class="btn btn-primary btn-sm">استكمال المطلوب</a>
            <a href="entrepreneur-conversations.php" class="btn btn-soft btn-sm">مراسلة الفريق</a>
          </div>
        </div>
        <div class="dashboard-hero-status">
          <div class="status-ring" aria-label="اكتمال الطلب 85%">
            <span>85%</span>
          </div>
          <b>قيد الدراسة</b>
          <small>الخطوة التالية خلال 3 أيام</small>
        </div>
      </section>

      <section class="panel status-panel reveal dashboard-anchor" id="evaluation">
        <div class="panel-body">
          <div class="status-head">
            <div class="row gap-12">
              <div class="project-avatar">X</div>
              <div>
                <b class="status-title">منصة X</b>
                <div class="text-2">نوع الدعم: البناء التقني + التمويل</div>
              </div>
            </div>
            <span class="badge badge-warning"><span class="dot"></span> قيد الدراسة</span>
          </div>
          <div class="pipeline">
            <?php $stages=[['جديد','done'],['مراجعة','done'],['استكمال','done'],['مقابلة','done'],['دراسة','active'],['قرار',''],['تعاقد',''],['تنفيذ','']];
            foreach($stages as $s): ?>
            <div class="pstep <?= $s[1] ?>"><div class="pbar"></div><small><?= $s[0] ?></small></div>
            <?php endforeach; ?>
          </div>
          <div class="status-next">
            <div class="row gap-8"><svg viewBox="0 0 24 24" fill="none" stroke="var(--orange)" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg><span>الخطوة التالية: <b>تقييم مالي وتشغيلي</b></span></div>
            <span class="text-2">متوقع خلال 3 أيام</span>
          </div>
        </div>
      </section>

      <div class="kpi-grid reveal dashboard-anchor" id="applications">
        <div class="kpi"><div class="kpi-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg></div><div class="kpi-val mono" data-count="2">0</div><div class="kpi-label">طلبات نشطة</div></div>
        <div class="kpi"><div class="kpi-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4.5V3h6v1.5M9 10l1.5 1.5L14 8M9 16h6"/></svg></div><span class="kpi-trend down">1 متأخرة</span><div class="kpi-val mono" data-count="5">0</div><div class="kpi-label">مهام مفتوحة</div></div>
        <div class="kpi"><div class="kpi-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg></div><div class="kpi-val mono" data-count="1">0</div><div class="kpi-label">اجتماع قادم</div></div>
        <div class="kpi"><div class="kpi-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></div><span class="kpi-trend up">جديد</span><div class="kpi-val mono" data-count="2">0</div><div class="kpi-label">رسائل غير مقروءة</div></div>
      </div>

      <div class="dashboard-main-grid">
        <div class="panel reveal dashboard-anchor" id="completion">
          <div class="panel-head"><h3>طلبات استكمال وملاحظات</h3><div class="spacer"></div><span class="badge badge-warning">مطلوب إجراء</span></div>
          <div class="panel-body">
            <div class="feed-item action-feed">
              <div class="feed-dot" style="color:var(--warning);background:var(--warning-bg)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 9v4M12 17h.01"/><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg></div>
              <div><div class="ft"><b>مطلوب:</b> تحديث النموذج المالي لآخر 12 شهرًا</div><div class="fm">من فريق التقييم · منذ يوم</div></div>
              <a href="#" onclick="demoAction(event)" class="btn btn-soft btn-sm">رفع الملف</a>
            </div>
            <div class="feed-item">
              <div class="feed-dot" style="color:var(--info);background:var(--info-bg)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></div>
              <div><div class="ft"><b>ملاحظة:</b> نقدّر توضيح استراتيجية الاستحواذ</div><div class="fm">مرئية لك · منذ يومين</div></div>
            </div>
            <div class="feed-item">
              <div class="feed-dot" style="color:var(--success);background:var(--success-bg)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg></div>
              <div><div class="ft"><b>مكتمل:</b> تم قبول Pitch Deck المحدّث</div><div class="fm">منذ 3 أيام</div></div>
            </div>
          </div>
        </div>

        <div class="panel reveal dashboard-anchor" id="tasks">
          <div class="panel-head"><h3>مهامي</h3><div class="spacer"></div><span class="badge badge-danger">1 متأخرة</span></div>
          <div class="panel-body">
            <?php
            $tasks=[
              ['رفع دراسة الجدوى النهائية','متأخرة · أمس','danger',true],
              ['تجهيز عرض المقابلة المالية','اليوم','warning',false],
              ['مراجعة مسودة العقد المبدئي','خلال 3 أيام','info',false],
              ['تحديث بيانات الفريق','خلال أسبوع','',false],
            ];
            foreach($tasks as $t): ?>
            <div class="row gap-12" style="padding:11px 0;border-bottom:1px solid var(--border)">
              <input type="checkbox" <?= $t[3]?'':'' ?> onclick="demoAction(event,'تحديث حالة المهمة (نموذج).')">
              <div style="flex:1"><div style="font-size:14px;<?= $t[3]?'':'' ?>"><?= $t[0] ?></div><div class="fm" style="color:var(--<?= $t[2]?:'text-3' ?>)"><?= $t[1] ?></div></div>
              <?php if($t[2]): ?><span class="badge badge-<?= $t[2] ?>" style="font-size:10px"><?= $t[2]==='danger'?'عاجل':($t[2]==='warning'?'قريب':'قادم') ?></span><?php endif; ?>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <div class="dashboard-main-grid mt-24">
        <div class="panel reveal dashboard-anchor" id="meetings">
          <div class="panel-head"><h3>الاجتماع القادم</h3><div class="spacer"></div><span class="badge badge-info">غدًا</span></div>
          <div class="panel-body">
            <div class="meeting-card">
              <div class="meeting-date"><b>24</b><span>يوليو</span></div>
              <div>
                <h4>مقابلة تقييم مالي وتشغيلي</h4>
                <p class="text-2">45 دقيقة · Google Meet · فريق الاستثمار والتشغيل</p>
              </div>
            </div>
            <div class="dashboard-actions mt-16">
              <a href="#" onclick="demoAction(event,'فتح رابط الاجتماع.')" class="btn btn-primary btn-sm">دخول الاجتماع</a>
              <a href="#" onclick="demoAction(event,'إعادة جدولة الاجتماع.')" class="btn btn-soft btn-sm">إعادة جدولة</a>
            </div>
          </div>
        </div>
        <div class="panel reveal">
          <div class="panel-head"><h3>اختصارات سريعة</h3></div>
          <div class="panel-body quick-actions-grid">
            <a href="entrepreneur-documents.php" class="quick-action"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="M17 8l-5-5-5 5"/><path d="M12 3v12"/></svg><span>رفع مستند</span></a>
            <a href="entrepreneur-conversations.php" class="quick-action"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg><span>رسالة للفريق</span></a>
            <a href="entrepreneur-meetings.php" class="quick-action"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg><span>حجز موعد</span></a>
            <a href="#" onclick="demoAction(event)" class="quick-action"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M4 4.5A2.5 2.5 0 0 1 6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5z"/></svg><span>دليل التقديم</span></a>
          </div>
        </div>
      </div>

      <div class="panel reveal mt-24 dashboard-anchor" id="documents">
        <div class="panel-head"><h3>المستندات المرفوعة</h3><div class="spacer"></div><a href="#" onclick="demoAction(event)" class="btn btn-soft btn-sm">رفع مستند</a></div>
        <div class="panel-body flush">
          <div class="table-wrap" style="border:none;border-radius:0">
            <table class="data">
              <thead><tr><th>المستند</th><th>النوع</th><th>الحجم</th><th>آخر تحديث</th><th>الحالة</th></tr></thead>
              <tbody>
                <tr><td class="row gap-8"><svg viewBox="0 0 24 24" fill="none" stroke="var(--orange)" stroke-width="2" style="width:16px;height:16px"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg> Pitch Deck v3</td><td class="text-2">PDF</td><td class="text-2 mono">4.2MB</td><td class="text-2">منذ 3 أيام</td><td><span class="badge badge-success">معتمد</span></td></tr>
                <tr><td class="row gap-8"><svg viewBox="0 0 24 24" fill="none" stroke="var(--orange)" stroke-width="2" style="width:16px;height:16px"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg> دراسة الجدوى</td><td class="text-2">XLSX</td><td class="text-2 mono">1.1MB</td><td class="text-2">قيد الإعداد</td><td><span class="badge badge-warning">مطلوب</span></td></tr>
                <tr><td class="row gap-8"><svg viewBox="0 0 24 24" fill="none" stroke="var(--orange)" stroke-width="2" style="width:16px;height:16px"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg> النموذج المالي</td><td class="text-2">XLSX</td><td class="text-2 mono">820KB</td><td class="text-2">منذ أسبوع</td><td><span class="badge badge-info">قيد المراجعة</span></td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="dashboard-main-grid mt-24">
        <div class="panel reveal dashboard-anchor" id="conversations">
          <div class="panel-head"><h3>المحادثات</h3><div class="spacer"></div><span class="badge badge-info">2 جديد</span></div>
          <div class="panel-body">
            <div class="feed-item">
              <div class="feed-dot" style="color:var(--orange);background:var(--orange-050)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></div>
              <div><div class="ft"><b>فريق التقييم</b> أرسل ملاحظة على النموذج المالي</div><div class="fm">منذ 20 دقيقة</div></div>
            </div>
            <div class="feed-item">
              <div class="feed-dot" style="color:var(--info);background:var(--info-bg)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 10h.01M12 10h.01M16 10h.01"/><path d="M21 12c0 4.42-4.03 8-9 8a10 10 0 0 1-3.8-.74L3 21l1.74-4.22A7.6 7.6 0 0 1 3 12c0-4.42 4.03-8 9-8s9 3.58 9 8Z"/></svg></div>
              <div><div class="ft"><b>مسؤول الحساب</b> أكد موعد المقابلة القادمة</div><div class="fm">اليوم · 11:30 صباحًا</div></div>
            </div>
            <a href="#" onclick="demoAction(event,'فتح مركز المحادثات.')" class="btn btn-soft btn-sm mt-16">فتح المحادثات</a>
          </div>
        </div>

        <div class="panel reveal dashboard-anchor" id="support-tickets">
          <div class="panel-head"><h3>تذاكر الدعم</h3><div class="spacer"></div><span class="badge badge-success">لا توجد عوائق</span></div>
          <div class="panel-body">
            <div class="support-ticket">
              <div>
                <b>مشكلة في رفع ملف Excel</b>
                <p class="text-2">تم حلها بواسطة فريق الدعم، ويمكنك رفع نسخة جديدة الآن.</p>
              </div>
              <span class="badge badge-success">مغلقة</span>
            </div>
            <div class="support-ticket">
              <div>
                <b>طلب توضيح متطلبات KYC</b>
                <p class="text-2">بانتظار ردك على سؤال واحد داخل المحادثة.</p>
              </div>
              <span class="badge badge-warning">متابعة</span>
            </div>
            <a href="#" onclick="demoAction(event,'إنشاء تذكرة دعم جديدة.')" class="btn btn-primary btn-sm mt-16">تذكرة جديدة</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="scrim" onclick="closeOverlays()"></div>
<script src="../assets/js/app.js"></script>
</body></html>
