<?php
$base = '../';
$title = 'لوحة المستثمر';
$active = 'dashboard investor-dashboard-page';
include '../partials/head.php';
$investor = auth_find_user_by_id((string) ($_SESSION['user_id'] ?? '')) ?? [];
$authName = htmlspecialchars((string) ($investor['name'] ?? 'مستثمر'));
$authType = htmlspecialchars((string) ($investor['investor_type'] ?? 'مستثمر'));
$authInitials = htmlspecialchars(auth_initials((string) ($investor['name'] ?? 'مستثمر')));
$authEmail = htmlspecialchars((string) ($investor['email'] ?? ''));
$authWhatsapp = htmlspecialchars((string) ($investor['whatsapp'] ?? ''));
$authCountry = htmlspecialchars((string) ($investor['country'] ?? 'غير محددة'));
$kycApproved = ($investor['kyc_status'] ?? 'pending') === 'approved';
$isDemo = (bool) ($investor['is_demo'] ?? false);
$investorData = auth_get_investor_data((string) ($investor['id'] ?? ''));
$metrics = auth_investor_metrics($investorData);
$portfolioTotal = $metrics['portfolio_total'];
$opportunityCount = $metrics['opportunity_count'];
$meetingCount = $metrics['meeting_count'];
$pledgeTotal = $metrics['pledge_total'];
$hasInvestmentData = (bool) ($investorData['holdings'] || $investorData['opportunities'] || $investorData['meetings'] || $investorData['pledges']);
$portfolioHistory = $investorData['portfolio_history'];
$historyMax = max(1, ...array_map(static fn(array $point): int => (int) ($point['value'] ?? 0), $portfolioHistory ?: [['value'=>0]]));
$sectorTotals = [];
foreach ($investorData['holdings'] as $holding) {
  $sector = (string) ($holding['sector'] ?? 'غير محدد');
  $sectorTotals[$sector] = ($sectorTotals[$sector] ?? 0) + max(0, (int) ($holding['current_value'] ?? 0));
}
$allocationColors = ['var(--orange)', '#FF8A4D', 'var(--info)', 'var(--success)', 'var(--text-3)'];
$allocations = [];
$gradientParts = [];
$allocationStart = 0.0;
$allocationIndex = 0;
foreach ($sectorTotals as $sector => $value) {
  $percentage = $portfolioTotal > 0 ? ($value / $portfolioTotal) * 100 : 0;
  $color = $allocationColors[$allocationIndex++ % count($allocationColors)];
  $allocationEnd = $allocationStart + $percentage;
  $gradientParts[] = $color . ' ' . round($allocationStart, 2) . '% ' . round($allocationEnd, 2) . '%';
  $allocations[] = ['sector'=>$sector, 'value'=>$value, 'percentage'=>$percentage, 'color'=>$color];
  $allocationStart = $allocationEnd;
}
$donutGradient = $gradientParts ? 'conic-gradient(' . implode(', ', $gradientParts) . ')' : 'var(--surface-2)';
$upcomingMeetings = array_values(array_filter($investorData['meetings'], static function (array $meeting): bool {
  try { return new DateTimeImmutable((string) ($meeting['scheduled_at'] ?? '')) >= new DateTimeImmutable('now'); }
  catch (Throwable) { return false; }
}));
usort($upcomingMeetings, static fn(array $a, array $b): int => strcmp((string) ($a['scheduled_at'] ?? ''), (string) ($b['scheduled_at'] ?? '')));
$activities = $investorData['activities'];
usort($activities, static fn(array $a, array $b): int => strcmp((string) ($b['created_at'] ?? ''), (string) ($a['created_at'] ?? '')));
$notifications = [];
if (!$kycApproved) {
  $notifications[] = [
    'id' => 'kyc-pending',
    'type' => 'review',
    'title' => 'حسابك قيد المراجعة',
    'text' => 'سنخبرك فور اكتمال مراجعة KYC/AML.',
    'time' => 'الآن',
    'href' => 'investor-settings.php',
  ];
}
foreach (array_slice($activities, 0, 3) as $index => $activity) {
  $type = (string) ($activity['type'] ?? 'info');
  $notifications[] = [
    'id' => 'activity-' . substr(hash('sha256', (string) ($activity['text'] ?? '') . (string) ($activity['created_at'] ?? '') . $index), 0, 12),
    'type' => $type,
    'title' => (string) ($activity['text'] ?? 'تحديث جديد'),
    'text' => $type === 'meeting' ? 'راجع تفاصيل الاجتماع والموعد.' : ($type === 'opportunity' ? 'اطّلع على أحدث تفاصيل الفرص المتاحة.' : 'تم تحديث حالة حسابك.'),
    'time' => (string) ($activity['created_at'] ?? 'حديثًا'),
    'href' => $type === 'meeting' ? 'investor-meetings.php' : ($type === 'opportunity' ? 'investor-opportunities.php' : 'investor.php'),
  ];
}
if (!$notifications && $kycApproved) {
  $notifications[] = [
    'id' => 'account-approved',
    'type' => 'success',
    'title' => 'حسابك معتمد',
    'text' => 'يمكنك الآن متابعة بياناتك والفرص المتاحة.',
    'time' => 'حديثًا',
    'href' => 'investor.php',
  ];
}
$notificationScope = substr(hash('sha256', (string) ($investor['id'] ?? 'investor')), 0, 12);
?>
<div class="app">
  <!-- ============ SIDEBAR ============ -->
  <aside class="sidebar" id="sidebar">
    <a href="../index.php" class="brand" aria-label="Seven Tech Capital — الرئيسية"><?php include '../partials/logo.php'; ?></a>

    <div class="side-role">
      <div class="avatar"><?= $authInitials ?></div>
      <div style="min-width:0"><b><?= $authName ?></b><span>مستثمر · <?= $authType ?></span></div>
    </div>

    <div class="side-label">المساحة</div>
    <a href="investor.php" class="side-link active"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/><rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/></svg> لوحة القيادة</a>
    <a href="investor-portfolio.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="M7 14l4-4 3 3 5-6"/></svg> المحفظة</a>
    <a href="investor-opportunities.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg> الفرص الاستثمارية <?php if ($opportunityCount): ?><span class="count"><?= $opportunityCount ?></span><?php endif; ?></a>
    <a href="investor-documents.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg> المستندات والاتفاقيات</a>
    <a href="investor-meetings.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg> الاجتماعات <?php if ($meetingCount): ?><span class="count muted"><?= $meetingCount ?></span><?php endif; ?></a>
    <a href="investor-pledges.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg> التعهدات والمدفوعات</a>
    <a href="investor-reports.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M9 13h6M9 17h6M9 9h1"/></svg> التقارير</a>

    <div class="side-label">التواصل</div>
    <a href="investor-messages.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg> الرسائل <?php if ($isDemo): ?><span class="count">3</span><?php endif; ?></a>
    <a href="investor-support-tickets.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg> تذاكر الدعم</a>

    <div style="margin-top:auto"></div>
    <a href="investor-settings.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9c.2.61.79 1 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg> الإعدادات</a>
    <a href="../logout.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg> تسجيل الخروج</a>
  </aside>

  <!-- ============ MAIN ============ -->
  <div class="main">
    <header class="topbar">
      <button class="icon-btn side-toggle" onclick="toggleSidebar()" aria-label="فتح القائمة الجانبية" aria-expanded="false" aria-controls="sidebar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg></button>
      <div class="topbar-title">
        <div class="crumb">لوحة المستثمر</div>
        <h1>مرحبًا، <?= $authName ?></h1>
      </div>
      <div class="spacer"></div>
      <div class="search-box hide-mobile"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg><input id="investor-search" name="q" aria-label="بحث" placeholder="ابحث عن فرصة، مستند، اجتماع…" oninput="filterTable('investor-search', 'opps-table')"></div>
      <button class="icon-btn" onclick="toggleTheme()" aria-label="تبديل الوضع الفاتح والداكن"><span data-theme-icon></span></button>
      <div class="notification-center" data-notification-center data-notification-scope="<?= $notificationScope ?>">
        <button class="icon-btn notification-trigger" type="button" aria-label="فتح الإشعارات" aria-haspopup="dialog" aria-expanded="false" aria-controls="investor-notifications">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9M13.7 21a2 2 0 0 1-3.4 0"/></svg>
          <span class="notification-count" aria-label="<?= count($notifications) ?> إشعارات جديدة"><?= count($notifications) ?></span>
        </button>
        <section class="notification-panel" id="investor-notifications" role="dialog" aria-label="الإشعارات" hidden>
          <header class="notification-head">
            <div><h2>الإشعارات</h2><p><span data-unread-count><?= count($notifications) ?></span> غير مقروءة</p></div>
            <button type="button" class="notification-read-all">تحديد الكل كمقروء</button>
          </header>
          <div class="notification-list">
            <?php foreach ($notifications as $notification): ?>
              <a class="notification-item unread" href="<?= htmlspecialchars($notification['href']) ?>" data-notification-id="<?= htmlspecialchars($notification['id']) ?>">
                <span class="notification-item-icon notification-item-icon-<?= htmlspecialchars($notification['type']) ?>" aria-hidden="true"></span>
                <span class="notification-copy"><b><?= htmlspecialchars($notification['title']) ?></b><span><?= htmlspecialchars($notification['text']) ?></span><time><?= htmlspecialchars($notification['time']) ?></time></span>
                <span class="notification-unread-dot" aria-hidden="true"></span>
              </a>
            <?php endforeach; ?>
            <div class="notification-empty" hidden><b>لا توجد إشعارات جديدة</b><span>سنظهر لك التحديثات المهمة هنا.</span></div>
          </div>
        </section>
      </div>
    </header>

    <div class="page-body investor-dashboard">
      <!-- KYC status banner -->
      <div class="panel reveal investor-kyc-panel" style="background:linear-gradient(120deg,<?= $kycApproved ? 'var(--success-bg)' : 'var(--warning-bg)' ?>,transparent);border-color:<?= $kycApproved ? 'var(--success)' : 'var(--warning)' ?>;margin-bottom:22px">
        <div class="panel-body row gap-16 flex-wrap investor-kyc-content">
          <div class="avatar" style="background:<?= $kycApproved ? 'var(--success)' : 'var(--warning)' ?>;color:#fff;width:44px;height:44px"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:22px;height:22px"><?= $kycApproved ? '<path d="M20 6L9 17l-5-5"/>' : '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>' ?></svg></div>
          <div style="flex:1;min-width:200px"><b style="font-family:var(--font-head)"><?= $kycApproved ? 'حسابك معتمد ✓' : 'حسابك قيد المراجعة' ?></b><p class="text-2" style="font-size:13.5px"><?= $kycApproved ? 'اكتملت مراجعة KYC/AML ويمكنك استعراض الفرص المتاحة.' : 'تم تسجيل بياناتك، وسيظهر الاعتماد بعد مراجعة KYC/AML بواسطة الإدارة.' ?></p></div>
          <a href="<?= $kycApproved ? 'investor-opportunities.php' : 'investor-settings.php' ?>" class="btn btn-soft btn-sm"><?= $kycApproved ? 'استعرض الفرص' : 'راجع بياناتك' ?></a>
        </div>
      </div>

      <div class="panel reveal investor-account-panel" style="margin-bottom:22px">
        <div class="panel-head"><h3>بيانات الحساب المسجلة</h3><div class="spacer"></div><a href="investor-settings.php" class="btn btn-soft btn-sm">تعديل البيانات</a></div>
        <div class="panel-body">
          <div class="detail-stat-grid">
            <div class="detail-stat"><span>الاسم</span><b><?= $authName ?></b><small><?= $authEmail ?></small></div>
            <div class="detail-stat"><span>واتساب</span><b class="mono"><?= $authWhatsapp ?: 'غير مسجل' ?></b><small><?= $authCountry ?></small></div>
            <div class="detail-stat"><span>نوع المستثمر</span><b><?= $authType ?></b><small>بيانات محفوظة في حسابك</small></div>
          </div>
        </div>
      </div>

      <!-- KPI grid — Financial Dashboard patterns: count-up, trend, sparkline, currency -->
      <div class="kpi-grid reveal">
        <div class="kpi">
          <div class="kpi-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
          <?php if ($portfolioTotal > 0): ?><span class="kpi-trend up"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M7 17L17 7M17 7H9M17 7v8"/></svg> مسجل</span><?php endif; ?>
          <div class="kpi-val mono"><span data-currency="<?= $portfolioTotal ?>" data-cur="USD" data-compact>$<?= number_format($portfolioTotal) ?></span></div>
          <div class="kpi-label">إجمالي المحفظة</div>
          <?php if ($portfolioHistory): ?><svg class="sparkline mt-8" data-spark="<?= htmlspecialchars(implode(',', array_map(static fn(array $point): int => (int) ($point['value'] ?? 0), $portfolioHistory))) ?>"></svg><?php endif; ?>
        </div>
        <div class="kpi">
          <div class="kpi-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></div>
          <div class="kpi-val mono" data-count="<?= $opportunityCount ?>">0</div>
          <div class="kpi-label">فرص متاحة لك</div>
        </div>
        <div class="kpi">
          <div class="kpi-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg></div>
          <div class="kpi-val mono" data-count="<?= $meetingCount ?>">0</div>
          <div class="kpi-label">اجتماعات قادمة</div>
        </div>
        <div class="kpi">
          <div class="kpi-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
          <span class="kpi-trend flat">قيد الاعتماد</span>
          <div class="kpi-val mono"><span data-currency="<?= $pledgeTotal ?>" data-cur="USD" data-compact>$<?= number_format($pledgeTotal) ?></span></div>
          <div class="kpi-label">تعهد غير ملزم</div>
        </div>
      </div>

      <?php if ($hasInvestmentData): ?>
      <!-- Row: portfolio + allocation -->
      <div class="grid mt-24 investor-dashboard-grid" style="grid-template-columns:1.4fr 1fr;gap:22px;align-items:start">
        <div class="panel reveal">
          <div class="panel-head"><h3>أداء المحفظة</h3><div class="spacer"></div><span class="badge">آخر 6 أشهر</span></div>
          <div class="panel-body">
            <div class="bars">
              <?php foreach($portfolioHistory as $i => $point): $barHeight=round(((int)($point['value'] ?? 0)/$historyMax)*100); ?>
              <div class="bar-col"><div class="bar <?= $i>=count($portfolioHistory)-2?'hl':'' ?>" data-h="<?= $barHeight ?>" style="height:0" title="$<?= number_format((int) ($point['value'] ?? 0)) ?>"></div><small><?= htmlspecialchars((string) ($point['label'] ?? '')) ?></small></div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <div class="panel reveal">
          <div class="panel-head"><h3>توزيع المحفظة</h3></div>
          <div class="panel-body row gap-24" style="justify-content:center;flex-wrap:wrap">
            <div class="donut" style="background:<?= htmlspecialchars($donutGradient) ?>">
              <div class="dcenter"><b><?= count($allocations) ?></b><span>مجالات</span></div>
            </div>
            <div class="legend">
              <?php foreach($allocations as $allocation): ?>
                <div class="li"><span class="sw" style="background:<?= $allocation['color'] ?>"></span> <?= htmlspecialchars((string) $allocation['sector']) ?> <b><?= round((float) $allocation['percentage']) ?>%</b></div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Row: opportunities + activity -->
      <div class="grid mt-24 investor-dashboard-grid" style="grid-template-columns:1.5fr 1fr;gap:22px;align-items:start">
        <!-- Opportunities -->
        <div class="panel reveal">
          <div class="panel-head"><h3>الفرص المسجلة لحسابك</h3><div class="spacer"></div><a href="investor-opportunities.php" class="text-orange" style="font-size:13px;font-family:var(--font-head);font-weight:600">عرض الكل</a></div>
          <div class="panel-body flush">
            <div class="table-wrap opportunities-desktop-table" style="border:none;border-radius:0">
              <table class="data mobile-card-table" id="opps-table">
                <thead><tr><th>الفرصة</th><th>القطاع</th><th>المرحلة</th><th>المطلوب</th><th>الحالة</th></tr></thead>
                <tbody>
                  <?php foreach($investorData['opportunities'] as $opportunity):
                    $opportunityStatus = (string) ($opportunity['status'] ?? '');
                    $opportunityLabel = ['available'=>'متاحة','review'=>'قيد المراجعة','completed'=>'مكتملة'][$opportunityStatus] ?? 'غير محددة';
                    $opportunityBadge = ['available'=>'badge-success','review'=>'badge-warning','completed'=>'badge'][$opportunityStatus] ?? '';
                  ?>
                  <tr>
                    <td data-label="الفرصة"><b style="font-family:var(--font-head)"><?= htmlspecialchars((string) ($opportunity['title'] ?? '')) ?> · #<?= htmlspecialchars((string) ($opportunity['id'] ?? '')) ?></b></td>
                    <td data-label="القطاع" class="text-2"><?= htmlspecialchars((string) ($opportunity['sector'] ?? '')) ?></td>
                    <td data-label="المرحلة" class="text-2"><?= htmlspecialchars((string) ($opportunity['stage'] ?? '')) ?></td>
                    <td data-label="المطلوب" class="mono"><?= htmlspecialchars((string) ($opportunity['currency'] ?? 'USD')) ?> <?= number_format((int) ($opportunity['target_amount'] ?? 0)) ?></td>
                    <td data-label="الحالة"><span class="badge <?= $opportunityBadge ?>"><?= $opportunityLabel ?></span></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            <div class="mobile-opportunity-list" aria-label="الفرص المسجلة لحسابك">
              <?php foreach($investorData['opportunities'] as $opportunity):
                $opportunityStatus = (string) ($opportunity['status'] ?? '');
                $opportunityLabel = ['available'=>'متاحة','review'=>'قيد المراجعة','completed'=>'مكتملة'][$opportunityStatus] ?? 'غير محددة';
                $opportunityBadge = ['available'=>'badge-success','review'=>'badge-warning','completed'=>'badge'][$opportunityStatus] ?? '';
              ?>
              <article class="mobile-opportunity-card">
                <div class="mobile-opportunity-head">
                  <div>
                    <span class="mobile-opportunity-id">#<?= htmlspecialchars((string) ($opportunity['id'] ?? '')) ?></span>
                    <h4><?= htmlspecialchars((string) ($opportunity['title'] ?? '')) ?></h4>
                  </div>
                  <span class="badge <?= $opportunityBadge ?>"><?= $opportunityLabel ?></span>
                </div>
                <div class="mobile-opportunity-meta">
                  <div><span>القطاع</span><b><?= htmlspecialchars((string) ($opportunity['sector'] ?? '')) ?></b></div>
                  <div><span>المرحلة</span><b><?= htmlspecialchars((string) ($opportunity['stage'] ?? '')) ?></b></div>
                </div>
                <div class="mobile-opportunity-amount">
                  <span>التمويل المطلوب</span>
                  <b class="mono"><?= htmlspecialchars((string) ($opportunity['currency'] ?? 'USD')) ?> <?= number_format((int) ($opportunity['target_amount'] ?? 0)) ?></b>
                </div>
              </article>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <!-- Activity -->
        <div class="panel reveal">
          <div class="panel-head"><h3>النشاط الأخير</h3></div>
          <div class="panel-body">
            <?php foreach($activities as $activity):
              $activityType=(string)($activity['type'] ?? '');
              $activityColor=['success'=>'var(--success)','opportunity'=>'var(--orange)','meeting'=>'var(--info)'][$activityType] ?? 'var(--text-2)';
              $activityIcon=['success'=>'<path d="M20 6L9 17l-5-5"/>','opportunity'=>'<circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/>','meeting'=>'<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4"/>'][$activityType] ?? '<circle cx="12" cy="12" r="4"/>';
            ?>
            <div class="feed-item">
              <div class="feed-dot" style="color:<?= $activityColor ?>;background:color-mix(in srgb,<?= $activityColor ?> 14%,transparent)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><?= $activityIcon ?></svg></div>
              <div><div class="ft"><?= htmlspecialchars((string) ($activity['text'] ?? '')) ?></div><div class="fm"><?= htmlspecialchars((string) ($activity['created_at'] ?? '')) ?></div></div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- Meetings -->
      <div class="panel reveal mt-24">
        <div class="panel-head"><h3>الاجتماعات القادمة</h3><div class="spacer"></div><a href="investor-meetings.php" class="btn btn-soft btn-sm">طلب اجتماع</a></div>
        <div class="panel-body flush">
          <div class="table-wrap" style="border:none;border-radius:0">
            <table class="data mobile-card-table">
              <thead><tr><th>الموضوع</th><th>الفرصة</th><th>الموعد</th><th>المنصة</th><th>الحالة</th></tr></thead>
              <tbody>
                <?php foreach($upcomingMeetings as $meeting):
                  $meetingStatus=(string)($meeting['status'] ?? '');
                  $meetingLabel=$meetingStatus==='confirmed'?'مؤكد':'بانتظار التأكيد';
                  $meetingBadge=$meetingStatus==='confirmed'?'badge-success':'badge-warning';
                  try { $meetingDate=(new DateTimeImmutable((string)($meeting['scheduled_at'] ?? '')))->setTimezone(new DateTimeZone('Africa/Cairo'))->format('Y-m-d · H:i'); } catch(Throwable) { $meetingDate='موعد غير صالح'; }
                ?>
                <tr>
                  <td data-label="الموضوع"><b style="font-family:var(--font-head)"><?= htmlspecialchars((string)($meeting['subject'] ?? '')) ?></b></td>
                  <td data-label="الفرصة" class="text-2"><?= htmlspecialchars((string)($meeting['opportunity'] ?? '')) ?></td>
                  <td data-label="الموعد" class="text-2 ltr-input"><?= htmlspecialchars($meetingDate) ?></td>
                  <td data-label="المنصة"><span class="badge badge-info"><?= htmlspecialchars((string)($meeting['platform'] ?? '')) ?></span></td>
                  <td data-label="الحالة"><span class="badge <?= $meetingBadge ?>"><?= $meetingLabel ?></span></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <?php else: ?>
      <div class="panel reveal mt-24 investor-empty-state" role="status">
        <div class="panel-body">
          <h3>لا توجد بيانات فعلية في هذا القسم بعد</h3>
          <p>ستظهر البيانات هنا بعد اعتماد الحساب وإضافتها من الإدارة.</p>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<div class="scrim" onclick="closeOverlays()"></div>
<script src="../assets/js/app.js?v=<?= @filemtime(__DIR__ . '/../assets/js/app.js') ?: 1 ?>"></script>
</body></html>
