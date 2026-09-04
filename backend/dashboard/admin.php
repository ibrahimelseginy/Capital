<?php
$base = '../';
$title = 'لوحة الإدارة';
include '../partials/head.php';

$adminMessage = '';
$adminError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'set_kyc') {
  if (!auth_verify_csrf($_POST['csrf'] ?? null)) {
    $adminError = 'انتهت صلاحية الطلب. حدّث الصفحة وحاول مرة أخرى.';
  } else {
    [$changed, $message] = auth_set_kyc_status((string) ($_POST['user_id'] ?? ''), (string) ($_POST['status'] ?? ''));
    if ($changed) { require_once __DIR__ . '/../lib/admin.php'; admin_log('kyc_status_change', 'user', (string) ($_POST['user_id'] ?? ''), (string) ($_POST['status'] ?? '')); $adminMessage = $message; } else $adminError = $message;
  }
}

$users = auth_read_users();
usort($users, static fn(array $a, array $b): int => strcmp((string) ($b['created_at'] ?? ''), (string) ($a['created_at'] ?? '')));
$investors = array_values(array_filter($users, static fn(array $user): bool => ($user['role'] ?? '') === 'investor'));
$entrepreneurs = array_values(array_filter($users, static fn(array $user): bool => ($user['role'] ?? '') === 'entrepreneur'));
$pendingInvestors = array_values(array_filter($investors, static fn(array $user): bool => ($user['kyc_status'] ?? 'pending') === 'pending'));
$approvedInvestors = array_values(array_filter($investors, static fn(array $user): bool => ($user['kyc_status'] ?? 'pending') === 'approved'));
$pledgeTotal = 0;
foreach ($investors as $investorAccount) {
  $pledgeTotal += auth_investor_metrics(auth_get_investor_data((string) ($investorAccount['id'] ?? '')))['pledge_total'];
}
$countryCounts = [];
foreach ($users as $user) {
  $country = trim((string) ($user['country'] ?? '')) ?: 'غير محددة';
  $countryCounts[$country] = ($countryCounts[$country] ?? 0) + 1;
}
arsort($countryCounts);
$admin = auth_find_user_by_id((string) ($_SESSION['user_id'] ?? '')) ?? [];
$authName = htmlspecialchars((string) ($admin['name'] ?? 'إدارة النظام'));
$authEmail = htmlspecialchars((string) ($admin['email'] ?? ''));
$authInitials = htmlspecialchars(auth_initials((string) ($admin['name'] ?? 'إدارة')));
?>
<div class="app">
  <!-- ============ SIDEBAR ============ -->
  <aside class="sidebar" id="sidebar">
    <a href="../index.php" class="brand" aria-label="Seven Tech Capital — الرئيسية"><?php include '../partials/logo.php'; ?></a>

    <div class="side-role">
      <div class="avatar" style="background:var(--charcoal);color:#fff"><?= $authInitials ?></div>
      <div style="min-width:0"><b><?= $authName ?></b><span>مدير عام · كل الدول</span></div>
    </div>

    <div class="side-label">الإشراف</div>
    <a href="admin.php" class="side-link active"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/><rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/></svg> اللوحة التنفيذية</a>
    <a href="admin-users.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg> المستخدمون <span class="count muted"><?= count($users) ?></span></a>
    <a href="admin-kyc.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4"/><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg> مراجعات KYC/AML <?php if ($pendingInvestors): ?><span class="count"><?= count($pendingInvestors) ?></span><?php endif; ?></a>
    <a href="admin-opportunities.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg> الفرص الاستثمارية</a>
    <a href="admin-entrepreneur-requests.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg> طلبات رواد الأعمال <?php if ($entrepreneurs): ?><span class="count"><?= count($entrepreneurs) ?></span><?php endif; ?></a>
    <a href="admin-projects.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg> المشاريع والمهام</a>
    <a href="admin-meetings.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg> الاجتماعات</a>
    <a href="admin-contracts.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M12 18v-6M9 15h6"/></svg> العقود والتوقيع</a>

    <div class="side-label">المحتوى</div>
    <a href="admin-home.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg> الصفحة الرئيسية</a>
    <a href="admin-about.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg> صفحة من نحن</a>
    <a href="admin-investors-page.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/></svg> صفحة المستثمرون</a>
    <a href="admin-content.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg> إدارة المحتوى</a>
    <a href="admin-events.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 2.1l4 4-4 4M7 21.9l-4-4 4-4"/><path d="M21 6.1H8a5 5 0 0 0-5 5M3 17.9h13a5 5 0 0 0 5-5"/></svg> الفعاليات</a>

    <div class="side-label">النظام</div>
    <a href="admin-roles.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg> الأدوار والصلاحيات</a>
    <a href="admin-audit.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M9 12h6M9 16h6M9 8h1"/></svg> سجل التدقيق</a>
    <a href="admin-settings.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9c.2.61.79 1 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg> الإعدادات</a>
    <a href="../logout.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg> تسجيل الخروج</a>
  </aside>

  <!-- ============ MAIN ============ -->
  <div class="main">
    <header class="topbar">
      <button class="icon-btn side-toggle" onclick="toggleSidebar()" aria-label="فتح القائمة الجانبية" aria-expanded="false" aria-controls="sidebar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg></button>
      <div><div class="crumb">لوحة الإدارة</div><h1>اللوحة التنفيذية الموحدة</h1></div>
      <div class="spacer"></div>
      <div class="search-box hide-mobile"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg><input id="admin-search" name="q" aria-label="بحث في النظام" placeholder="بحث في النظام…" oninput="filterTable('admin-search', 'kyc-table')"></div>
      <button class="icon-btn" onclick="toggleTheme()" aria-label="تبديل الوضع الفاتح والداكن"><span data-theme-icon></span></button>
    </header>

    <div class="page-body admin-dashboard-detail">
      <?php if ($adminError): ?><div class="auth-message auth-message-error" role="alert"><?= htmlspecialchars($adminError) ?></div><?php endif; ?>
      <?php if ($adminMessage): ?><div class="auth-message auth-message-success" role="status"><?= htmlspecialchars($adminMessage) ?></div><?php endif; ?>
      <!-- Executive KPIs -->
      <div class="kpi-grid reveal">
        <div class="kpi"><div class="kpi-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/></svg></div><div class="kpi-val mono" data-count="<?= count($users) ?>">0</div><div class="kpi-label">إجمالي المستخدمين</div></div>
        <div class="kpi"><div class="kpi-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div><span class="kpi-trend" style="color:var(--warning)"><?= count($pendingInvestors) ?> معلّقة</span><div class="kpi-val mono" data-count="<?= count($approvedInvestors) ?>">0</div><div class="kpi-label">مستثمرون معتمدون</div></div>
        <div class="kpi"><div class="kpi-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div><div class="kpi-val mono"><span data-currency="<?= $pledgeTotal ?>" data-cur="USD" data-compact>$<?= number_format($pledgeTotal) ?></span></div><div class="kpi-label">حجم التعهدات المسجلة</div></div>
        <div class="kpi"><div class="kpi-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg></div><div class="kpi-val mono" data-count="<?= count($entrepreneurs) ?>">0</div><div class="kpi-label">حسابات رواد الأعمال</div></div>
      </div>

      <!-- Row: chart + kyc by country -->
      <div class="grid mt-24" style="grid-template-columns:1.5fr 1fr;gap:22px;align-items:start">
        <div class="panel reveal">
          <div class="panel-head"><h3>توزيع الحسابات المسجلة</h3><span class="badge">بيانات مباشرة</span></div>
          <div class="panel-body">
            <?php
              $roleRows = [
                ['مستثمرون', count($investors), 'var(--orange)'],
                ['رواد أعمال', count($entrepreneurs), 'var(--info)'],
                ['إدارة', count(array_filter($users, static fn(array $user): bool => ($user['role'] ?? '') === 'admin')), 'var(--success)'],
              ];
              $maxRoleCount = max(1, ...array_column($roleRows, 1));
              foreach ($roleRows as $row):
            ?>
              <div style="margin-bottom:18px">
                <div class="row" style="justify-content:space-between;margin-bottom:7px"><span><?= $row[0] ?></span><b class="mono"><?= $row[1] ?></b></div>
                <div class="progress"><span style="width:<?= round(($row[1] / $maxRoleCount) * 100) ?>%;background:<?= $row[2] ?>"></span></div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="panel reveal">
          <div class="panel-head"><h3>حسب الدولة</h3></div>
          <div class="panel-body">
            <?php $maxCountryCount = max(1, ...array_values($countryCounts)); $countryColors=['var(--orange)','#FF8A4D','var(--info)','var(--success)']; $countryIndex=0;
            foreach($countryCounts as $countryName => $countryCount): ?>
            <div style="margin-bottom:16px">
              <div class="row" style="justify-content:space-between;margin-bottom:6px"><span style="font-size:14px"><?= htmlspecialchars($countryName) ?></span><b class="mono" style="font-family:var(--font-head)"><?= $countryCount ?></b></div>
              <div class="progress"><span style="width:<?= round(($countryCount / $maxCountryCount) * 100) ?>%;background:<?= $countryColors[$countryIndex++ % count($countryColors)] ?>"></span></div>
            </div>
            <?php endforeach; ?>
            <p class="hint mt-16" style="border-top:1px solid var(--border);padding-top:12px">الأعداد محسوبة من الدول المسجلة في حسابات المستخدمين.</p>
          </div>
        </div>
      </div>

      <!-- KYC review queue -->
      <div class="panel reveal mt-24">
        <div class="panel-head"><h3>قائمة مراجعة KYC/AML</h3><span class="badge badge-warning" style="margin-inline-start:8px"><?= count($pendingInvestors) ?> معلّقة</span><div class="spacer"></div><a href="admin-kyc.php" class="text-orange" style="font-size:13px;font-family:var(--font-head);font-weight:600">فتح كل الطلبات</a></div>
        <div class="panel-body flush">
          <div class="table-wrap admin-mobile-card-wrap admin-queue-table-wrap" style="border:none;border-radius:0">
            <table class="data admin-mobile-card-table admin-queue-table" id="kyc-table">
              <thead><tr><th>المتقدم</th><th>البريد</th><th>نوع المستثمر</th><th>الدولة</th><th>تاريخ التسجيل</th><th>الحالة</th><th>إجراء</th></tr></thead>
              <tbody>
                <?php foreach($pendingInvestors as $kycUser): ?>
                <tr>
                  <td class="row gap-8"><div class="avatar sm"><?= htmlspecialchars(auth_initials((string) ($kycUser['name'] ?? 'م'))) ?></div><b style="font-family:var(--font-head)"><?= htmlspecialchars((string) ($kycUser['name'] ?? '')) ?></b></td>
                  <td class="text-2 ltr-input"><?= htmlspecialchars((string) ($kycUser['email'] ?? '')) ?></td>
                  <td class="text-2"><?= htmlspecialchars((string) ($kycUser['investor_type'] ?? '')) ?></td>
                  <td class="text-2"><?= htmlspecialchars((string) ($kycUser['country'] ?? '')) ?></td>
                  <td class="text-2"><?= htmlspecialchars(substr((string) ($kycUser['created_at'] ?? ''), 0, 10)) ?></td>
                  <td><span class="badge badge-warning">قيد المراجعة</span></td>
                  <td>
                    <form method="post">
                      <input type="hidden" name="action" value="set_kyc"><input type="hidden" name="csrf" value="<?= htmlspecialchars(auth_csrf_token()) ?>">
                      <input type="hidden" name="user_id" value="<?= htmlspecialchars((string) ($kycUser['id'] ?? '')) ?>"><input type="hidden" name="status" value="approved">
                      <button type="submit" class="btn btn-soft btn-sm" style="padding:6px 12px">اعتماد</button>
                    </form>
                  </td>
                </tr>
                <?php endforeach; ?>
                <?php if (!$pendingInvestors): ?><tr><td colspan="7" class="text-2" style="text-align:center;padding:28px">لا توجد مراجعات معلّقة حاليًا.</td></tr><?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Real account data -->
      <div class="grid mt-24" style="grid-template-columns:1fr 1fr;gap:22px;align-items:start">
        <div class="panel reveal">
          <div class="panel-head"><h3>أحدث الحسابات المسجلة</h3><a href="admin-users.php" class="text-orange">عرض الجميع</a></div>
          <div class="panel-body">
            <?php foreach(array_slice($users, 0, 5) as $recentUser): ?>
            <div class="feed-item">
              <div class="avatar sm"><?= htmlspecialchars(auth_initials((string) ($recentUser['name'] ?? 'م'))) ?></div>
              <div style="flex:1"><div class="ft"><b><?= htmlspecialchars((string) ($recentUser['name'] ?? '')) ?></b> · <?= auth_role_label((string) ($recentUser['role'] ?? '')) ?></div><div class="fm"><?= htmlspecialchars((string) ($recentUser['email'] ?? '')) ?></div></div>
              <span class="badge"><?= htmlspecialchars(substr((string) ($recentUser['created_at'] ?? ''), 0, 10)) ?></span>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="panel reveal">
          <div class="panel-head"><h3>بيانات حساب الإدارة</h3><a href="admin-settings.php" class="btn btn-soft btn-sm">تعديل</a></div>
          <div class="panel-body">
            <div class="support-ticket"><div><b>الاسم</b><p class="text-2"><?= $authName ?></p></div><span class="badge badge-success">نشط</span></div>
            <div class="support-ticket"><div><b>البريد الإلكتروني</b><p class="text-2 ltr-input"><?= $authEmail ?></p></div><span class="badge">مؤكد</span></div>
            <div class="support-ticket"><div><b>الدولة</b><p class="text-2"><?= htmlspecialchars((string) ($admin['country'] ?? 'غير محددة')) ?></p></div><span class="badge">إدارة</span></div>
            <div class="support-ticket"><div><b>تاريخ إنشاء الحساب</b><p class="text-2"><?= htmlspecialchars((string) ($admin['created_at'] ?? 'غير متاح')) ?></p></div></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="scrim" onclick="closeOverlays()"></div>
<script src="../assets/js/app.js"></script>
</body></html>
