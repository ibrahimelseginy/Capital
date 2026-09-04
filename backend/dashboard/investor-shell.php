<?php
$sectionKey = $sectionKey ?? 'portfolio';
$sections = [
  'portfolio' => ['file'=>'investor-portfolio.php','title'=>'المحفظة','crumb'=>'محفظة المستثمر','badge'=>'$185K'],
  'opportunities' => ['file'=>'investor-opportunities.php','title'=>'الفرص الاستثمارية','crumb'=>'الفرص المتاحة','badge'=>'4 فرص'],
  'documents' => ['file'=>'investor-documents.php','title'=>'المستندات والاتفاقيات','crumb'=>'مركز المستندات','badge'=>'NDA موقّعة'],
  'meetings' => ['file'=>'investor-meetings.php','title'=>'الاجتماعات','crumb'=>'جدول الاجتماعات','badge'=>'2 قادمة'],
  'pledges' => ['file'=>'investor-pledges.php','title'=>'التعهدات والمدفوعات','crumb'=>'التعهدات','badge'=>'$50K'],
  'reports' => ['file'=>'investor-reports.php','title'=>'التقارير','crumb'=>'تقارير المحفظة','badge'=>'ربع سنوي'],
  'messages' => ['file'=>'investor-messages.php','title'=>'الرسائل','crumb'=>'التواصل مع الفريق','badge'=>'3 جديد'],
  'support-tickets' => ['file'=>'investor-support-tickets.php','title'=>'تذاكر الدعم','crumb'=>'الدعم الفني','badge'=>'متابعة'],
  'settings' => ['file'=>'investor-settings.php','title'=>'الإعدادات','crumb'=>'إعدادات المستثمر','badge'=>'الأمان والتفضيلات'],
];
$current = $sections[$sectionKey] ?? $sections['portfolio'];
$base = '../';
$title = $current['title'];
$active = 'dashboard investor-dashboard-page';
include __DIR__ . '/../partials/head.php';
$profileError = '';
$profileSuccess = '';
$investor = auth_find_user_by_id((string) ($_SESSION['user_id'] ?? '')) ?? [];
if ($sectionKey === 'settings' && $_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update_profile') {
  if (!auth_verify_csrf($_POST['csrf'] ?? null)) {
    $profileError = 'انتهت صلاحية الطلب. حدّث الصفحة وحاول مرة أخرى.';
  } else {
    [$saved, $profileMessage] = auth_update_investor_profile((string) $_SESSION['user_id'], $_POST);
    if ($saved) {
      $profileSuccess = $profileMessage;
      $investor = auth_find_user_by_id((string) $_SESSION['user_id']) ?? [];
    } else {
      $profileError = $profileMessage;
    }
  }
}
$authName = htmlspecialchars((string) ($investor['name'] ?? 'مستثمر'));
$authType = htmlspecialchars((string) ($investor['investor_type'] ?? 'مستثمر'));
$authInitials = htmlspecialchars(auth_initials((string) ($investor['name'] ?? 'مستثمر')));
$authEmail = htmlspecialchars((string) ($investor['email'] ?? ''));
$authWhatsapp = htmlspecialchars((string) ($investor['whatsapp'] ?? ''));
$authCountry = (string) ($investor['country'] ?? '');
$kycApproved = ($investor['kyc_status'] ?? 'pending') === 'approved';
$isDemo = (bool) ($investor['is_demo'] ?? false);
$investorData = auth_get_investor_data((string) ($investor['id'] ?? ''));
$metrics = auth_investor_metrics($investorData);
$portfolioTotal = $metrics['portfolio_total'];
$opportunityCount = $metrics['opportunity_count'];
$meetingCount = $metrics['meeting_count'];
$pledgeTotal = $metrics['pledge_total'];
$hasInvestmentData = (bool) ($investorData['holdings'] || $investorData['opportunities'] || $investorData['meetings'] || $investorData['pledges']);
$sections['portfolio']['badge'] = '$' . number_format($portfolioTotal);
$sections['opportunities']['badge'] = $opportunityCount . ' فرص';
$sections['meetings']['badge'] = $meetingCount . ' قادمة';
$sections['pledges']['badge'] = '$' . number_format($pledgeTotal);
if (!$hasInvestmentData) {
  foreach ($sections as $key => &$section) {
    if ($key !== 'settings') $section['badge'] = 'لا توجد بيانات';
  }
  unset($section);
}
$current = $sections[$sectionKey] ?? $sections['portfolio'];

function investor_side_link($key, $label, $icon, $count = null, $muted = false) {
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
      <div class="avatar"><?= $authInitials ?></div>
      <div style="min-width:0"><b><?= $authName ?></b><span>مستثمر · <?= $authType ?></span></div>
    </div>

    <div class="side-label">المساحة</div>
    <a href="investor.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/><rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/></svg> لوحة القيادة</a>
    <?php
      investor_side_link('portfolio','المحفظة','<path d="M3 3v18h18"/><path d="M7 14l4-4 3 3 5-6"/>');
      investor_side_link('opportunities','الفرص الاستثمارية','<circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/>', $opportunityCount ?: null);
      investor_side_link('documents','المستندات والاتفاقيات','<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/>');
      investor_side_link('meetings','الاجتماعات','<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>', $meetingCount ?: null, true);
      investor_side_link('pledges','التعهدات والمدفوعات','<path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>');
      investor_side_link('reports','التقارير','<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M9 13h6M9 17h6M9 9h1"/>');
    ?>

    <div class="side-label">التواصل</div>
    <?php
      investor_side_link('messages','الرسائل','<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>', $isDemo ? '3' : null);
      investor_side_link('support-tickets','تذاكر الدعم','<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>');
    ?>

    <div style="margin-top:auto"></div>
    <?php investor_side_link('settings','الإعدادات','<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9c.2.61.79 1 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>'); ?>
    <a href="../logout.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg> تسجيل الخروج</a>
  </aside>

  <div class="main">
    <header class="topbar dashboard-topbar">
      <button class="icon-btn side-toggle" onclick="toggleSidebar()" aria-label="فتح القائمة الجانبية" aria-expanded="false" aria-controls="sidebar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg></button>
      <div class="topbar-title"><div class="crumb"><?= $current['crumb'] ?></div><h1><?= $current['title'] ?></h1></div>
      <div class="spacer"></div>
      <a href="investor.php" class="btn btn-soft btn-sm hide-mobile">الرجوع للوحة</a>
      <button class="icon-btn" onclick="toggleTheme()" aria-label="تبديل الوضع الفاتح والداكن"><span data-theme-icon></span></button>
    </header>

    <div class="page-body entrepreneur-dashboard">
      <section class="dashboard-detail-hero reveal">
        <div>
          <span class="eyebrow"><?= $current['badge'] ?></span>
          <h2><?= $current['title'] ?></h2>
          <p>صفحة مخصصة لإدارة هذا الجزء من رحلة المستثمر، مع بيانات واضحة وإجراءات مباشرة بدون تشتيت.</p>
        </div>
      </section>

      <?php if ($sectionKey === 'settings'): ?>
        <div class="detail-stat-grid reveal">
          <div class="detail-stat"><span>حالة الحساب</span><b><?= $kycApproved ? 'معتمد' : 'قيد المراجعة' ?></b><small><?= $kycApproved ? 'KYC/AML مكتملة' : 'بانتظار مراجعة KYC/AML' ?></small></div>
          <div class="detail-stat"><span>البريد</span><b>مؤكد</b><small><?= $authEmail ?></small></div>
          <div class="detail-stat"><span>الدولة</span><b><?= htmlspecialchars($authCountry ?: 'غير محددة') ?></b><small>بيانات الحساب المسجلة</small></div>
        </div>
        <?php if ($profileError): ?><div class="auth-message auth-message-error" role="alert"><?= htmlspecialchars($profileError) ?></div><?php endif; ?>
        <?php if ($profileSuccess): ?><div class="auth-message auth-message-success" role="status"><?= htmlspecialchars($profileSuccess) ?></div><?php endif; ?>
        <div class="dashboard-main-grid">
          <div class="panel reveal">
            <div class="panel-head"><h3>بيانات المستثمر</h3><span class="badge <?= $kycApproved ? 'badge-success' : 'badge-warning' ?>"><?= $kycApproved ? 'مؤكد' : 'قيد المراجعة' ?></span></div>
            <div class="panel-body">
              <form method="post" action="investor-settings.php">
                <input type="hidden" name="action" value="update_profile">
                <input type="hidden" name="csrf" value="<?= htmlspecialchars(auth_csrf_token()) ?>">
                <div class="field"><label class="label" for="profile-name">الاسم</label><input class="input" id="profile-name" name="name" value="<?= $authName ?>" required autocomplete="name"></div>
                <div class="auth-two-col mt-16">
                  <div class="field"><label class="label" for="profile-email">البريد الإلكتروني</label><input class="input ltr-input" id="profile-email" name="email" type="email" value="<?= $authEmail ?>" required autocomplete="email"></div>
                  <div class="field"><label class="label" for="profile-whatsapp">رقم واتساب</label><input class="input ltr-input" id="profile-whatsapp" name="whatsapp" type="tel" value="<?= $authWhatsapp ?>" required autocomplete="tel"></div>
                </div>
                <div class="auth-two-col mt-16">
                  <div class="field"><label class="label" for="profile-country">الدولة</label>
                    <select class="select" id="profile-country" name="country" required>
                      <?php foreach (['مصر','السعودية','الإمارات','قطر','الكويت','جنوب أفريقيا'] as $country): ?>
                        <option value="<?= htmlspecialchars($country) ?>" <?= $authCountry === $country ? 'selected' : '' ?>><?= htmlspecialchars($country) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="field"><label class="label" for="profile-investor-type">نوع المستثمر</label>
                    <select class="select" id="profile-investor-type" name="investor_type" required>
                      <?php foreach (['فرد مؤهل','شركة','صندوق استثماري','مكتب عائلي'] as $type): ?>
                        <option value="<?= htmlspecialchars($type) ?>" <?= ($investor['investor_type'] ?? '') === $type ? 'selected' : '' ?>><?= htmlspecialchars($type) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <button type="submit" class="btn btn-primary btn-sm mt-16">حفظ التغييرات</button>
              </form>
            </div>
          </div>
          <div class="panel reveal">
            <div class="panel-head"><h3>الأمان والحالة</h3></div>
            <div class="panel-body">
              <div class="support-ticket"><div><b>كلمة المرور</b><p class="text-2">مشفرة ويمكن تغييرها عبر استعادة كلمة المرور.</p></div><span class="badge badge-success">محمية</span></div>
              <div class="support-ticket"><div><b>مراجعة KYC/AML</b><p class="text-2">لا يتم اعتماد الحساب تلقائيًا.</p></div><span class="badge <?= $kycApproved ? 'badge-success' : 'badge-warning' ?>"><?= $kycApproved ? 'مكتملة' : 'معلّقة' ?></span></div>
              <div class="support-ticket"><div><b>تاريخ إنشاء الحساب</b><p class="text-2"><?= htmlspecialchars((string) ($investor['created_at'] ?? 'غير متاح')) ?></p></div><span class="badge">مسجل</span></div>
            </div>
          </div>
        </div>
      <?php elseif (!$hasInvestmentData): ?>
        <div class="panel reveal investor-empty-state" role="status">
          <div class="panel-body">
            <h3>لا توجد بيانات فعلية في هذا القسم بعد</h3>
            <p>ستظهر البيانات هنا بعد اعتماد الحساب وإضافتها من الإدارة.</p>
          </div>
        </div>
      <?php elseif ($sectionKey === 'portfolio'): ?>
        <div class="panel reveal">
          <div class="panel-head"><h3>استثمارات المحفظة</h3><span class="badge">$<?= number_format($portfolioTotal) ?></span></div>
          <div class="panel-body flush"><div class="table-wrap" style="border:none;border-radius:0"><table class="data">
            <thead><tr><th>الاستثمار</th><th>القطاع</th><th>المبلغ المستثمر</th><th>القيمة الحالية</th><th>الحالة</th></tr></thead>
            <tbody><?php foreach($investorData['holdings'] as $holding): ?><tr>
              <td><b><?= htmlspecialchars((string)($holding['name'] ?? '')) ?></b><small class="text-2"> · <?= htmlspecialchars((string)($holding['id'] ?? '')) ?></small></td>
              <td><?= htmlspecialchars((string)($holding['sector'] ?? '')) ?></td>
              <td class="mono"><?= htmlspecialchars((string)($holding['currency'] ?? 'USD')) ?> <?= number_format((int)($holding['invested_amount'] ?? 0)) ?></td>
              <td class="mono"><?= htmlspecialchars((string)($holding['currency'] ?? 'USD')) ?> <?= number_format((int)($holding['current_value'] ?? 0)) ?></td>
              <td><span class="badge badge-success"><?= ($holding['status'] ?? '')==='active'?'نشط':'غير نشط' ?></span></td>
            </tr><?php endforeach; ?></tbody>
          </table></div></div>
        </div>
      <?php elseif ($sectionKey === 'opportunities'): ?>
        <div class="panel reveal"><div class="panel-head"><h3>الفرص المسجلة لحسابك</h3><span class="badge badge-success"><?= $opportunityCount ?> متاحة</span></div><div class="panel-body flush"><div class="table-wrap" style="border:none;border-radius:0"><table class="data"><thead><tr><th>الفرصة</th><th>القطاع</th><th>المرحلة</th><th>المطلوب</th><th>الحالة</th></tr></thead><tbody>
          <?php foreach($investorData['opportunities'] as $opportunity): $status=(string)($opportunity['status']??''); $label=['available'=>'متاحة','review'=>'قيد المراجعة','completed'=>'مكتملة'][$status]??'غير محددة'; $badge=['available'=>'badge-success','review'=>'badge-warning','completed'=>'badge'][$status]??''; ?><tr>
            <td><b><?= htmlspecialchars((string)($opportunity['title']??'')) ?> · #<?= htmlspecialchars((string)($opportunity['id']??'')) ?></b></td><td><?= htmlspecialchars((string)($opportunity['sector']??'')) ?></td><td><?= htmlspecialchars((string)($opportunity['stage']??'')) ?></td><td class="mono"><?= htmlspecialchars((string)($opportunity['currency']??'USD')) ?> <?= number_format((int)($opportunity['target_amount']??0)) ?></td><td><span class="badge <?= $badge ?>"><?= $label ?></span></td>
          </tr><?php endforeach; ?></tbody></table></div></div></div>
      <?php elseif ($sectionKey === 'documents'): ?>
        <div class="panel reveal"><div class="panel-head"><h3>المستندات والاتفاقيات</h3><span class="badge badge-success">NDA موقّعة</span></div><div class="panel-body"><div class="support-ticket"><div><b>اتفاقية السرية NDA</b><p class="text-2">موقعة إلكترونيًا ومؤرشفة في سجل التدقيق.</p></div><span class="badge badge-success">موقعة</span></div><div class="support-ticket"><div><b>ملف أهلية المستثمر</b><p class="text-2">تمت مراجعته ضمن KYC/AML.</p></div><span class="badge badge-success">معتمد</span></div><div class="support-ticket"><div><b>مذكرة فرصة #A-104</b><p class="text-2">متاحة بعد اعتماد الصلاحيات.</p></div><span class="badge badge-info">متاح</span></div></div></div>
      <?php elseif ($sectionKey === 'meetings'): ?>
        <div class="panel reveal"><div class="panel-head"><h3>الاجتماعات القادمة</h3><span class="badge"><?= $meetingCount ?></span></div><div class="panel-body">
          <?php foreach($investorData['meetings'] as $meeting): try{$date=new DateTimeImmutable((string)($meeting['scheduled_at']??''));}catch(Throwable){continue;} if($date<new DateTimeImmutable('now'))continue; ?>
            <div class="meeting-card mt-16"><div class="meeting-date"><b><?= $date->setTimezone(new DateTimeZone('Africa/Cairo'))->format('d') ?></b><span><?= $date->format('Y-m') ?></span></div><div><h4><?= htmlspecialchars((string)($meeting['subject']??'')) ?></h4><p class="text-2"><?= htmlspecialchars((string)($meeting['platform']??'')) ?> · <?= $date->setTimezone(new DateTimeZone('Africa/Cairo'))->format('Y-m-d H:i') ?> · <?= ($meeting['status']??'')==='confirmed'?'مؤكد':'بانتظار التأكيد' ?></p></div></div>
          <?php endforeach; ?>
        </div></div>
      <?php elseif ($sectionKey === 'pledges'): ?>
        <div class="dashboard-main-grid"><div class="panel reveal"><div class="panel-head"><h3>التعهدات المسجلة</h3><span class="badge badge-warning">$<?= number_format($pledgeTotal) ?></span></div><div class="panel-body">
          <?php foreach($investorData['pledges'] as $pledge): ?><div class="support-ticket"><div><b>تعهد <?= htmlspecialchars((string)($pledge['opportunity_id']??'')) ?></b><p class="text-2"><?= htmlspecialchars((string)($pledge['created_at']??'')) ?> · غير ملزم</p></div><span class="badge"><?= htmlspecialchars((string)($pledge['currency']??'USD')) ?> <?= number_format((int)($pledge['amount']??0)) ?></span></div><?php endforeach; ?>
        </div></div><div class="panel reveal"><div class="panel-head"><h3>تنبيه مهم</h3></div><div class="panel-body"><p class="text-2">هذه السجلات تعهدات غير ملزمة وليست تحويلات مالية منفذة.</p></div></div></div>
      <?php elseif ($sectionKey === 'reports'): ?>
        <div class="panel reveal"><div class="panel-head"><h3>التقارير المتاحة</h3><span class="badge">ربع سنوي</span></div><div class="panel-body"><div class="support-ticket"><div><b>تقرير المحفظة Q2 2026</b><p class="text-2">أداء، توزيع، وملاحظات تشغيلية.</p></div><a href="#" onclick="demoAction(event)" class="btn btn-soft btn-sm">عرض</a></div><div class="support-ticket"><div><b>تقرير فرصة #A-104</b><p class="text-2">تحديثات المرحلة، المخاطر، وخطوات المتابعة.</p></div><a href="#" onclick="demoAction(event)" class="btn btn-soft btn-sm">عرض</a></div></div></div>
      <?php elseif ($sectionKey === 'messages'): ?>
        <div class="panel reveal"><div class="panel-head"><h3>الرسائل</h3><span class="badge badge-info">3 جديد</span></div><div class="panel-body"><div class="feed-item"><div class="feed-dot" style="color:var(--orange);background:var(--orange-050)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></div><div><div class="ft"><b>فريق الاستثمار</b> أرسل تحديثًا عن فرصة #A-104</div><div class="fm">منذ ساعة</div></div></div><div class="feed-item"><div class="feed-dot" style="color:var(--info);background:var(--info-bg)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 10h.01M12 10h.01M16 10h.01"/></svg></div><div><div class="ft"><b>مسؤول الحساب</b> أكد اجتماع المحفظة الربعي</div><div class="fm">أمس</div></div></div></div></div>
      <?php else: ?>
        <div class="panel reveal"><div class="panel-head"><h3>تذاكر الدعم</h3><span class="badge badge-success">مستقر</span></div><div class="panel-body"><div class="support-ticket"><div><b>استفسار عن مستند NDA</b><p class="text-2">تمت الإجابة وإغلاق التذكرة.</p></div><span class="badge badge-success">مغلقة</span></div><div class="support-ticket"><div><b>طلب تغيير موعد اجتماع</b><p class="text-2">قيد المتابعة مع مسؤول الحساب.</p></div><span class="badge badge-warning">متابعة</span></div><a href="#" onclick="demoAction(event)" class="btn btn-primary btn-sm mt-16">تذكرة جديدة</a></div></div>
      <?php endif; ?>
    </div>
  </div>
</div>
<div class="scrim" onclick="closeOverlays()"></div>
<script src="../assets/js/app.js"></script>
</body></html>
