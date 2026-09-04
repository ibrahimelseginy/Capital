<?php
$sectionKey = $sectionKey ?? 'users';
$sections = [
  'users' => ['file'=>'admin-users.php','title'=>'المستخدمون','crumb'=>'إدارة المستخدمين','badge'=>'0 مستخدم'],
  'kyc' => ['file'=>'admin-kyc.php','title'=>'مراجعات KYC/AML','crumb'=>'قائمة المراجعات','badge'=>'0 معلّقة'],
  'opportunities' => ['file'=>'admin-opportunities.php','title'=>'الفرص الاستثمارية','crumb'=>'إدارة الفرص','badge'=>'0 فرصة'],
  'entrepreneur-requests' => ['file'=>'admin-entrepreneur-requests.php','title'=>'طلبات رواد الأعمال','crumb'=>'طلبات التقديم','badge'=>'0 حساب'],
  'projects' => ['file'=>'admin-projects.php','title'=>'المشاريع','crumb'=>'المشاريع المسجلة','badge'=>'0 مشروع'],
  'meetings' => ['file'=>'admin-meetings.php','title'=>'الاجتماعات','crumb'=>'جدول المنصة','badge'=>'0 اجتماع'],
  'contracts' => ['file'=>'admin-contracts.php','title'=>'العقود والتوقيع','crumb'=>'المستندات القانونية','badge'=>'0 عقد'],
  'content' => ['file'=>'admin-content.php','title'=>'الأخبار والمقالات','crumb'=>'إدارة مركز المعرفة','badge'=>'0 عنصر'],
  'about' => ['file'=>'admin-about.php','title'=>'صفحة من نحن','crumb'=>'إدارة محتوى الشركة','badge'=>'0 عنصر'],
  'investors-page' => ['file'=>'admin-investors-page.php','title'=>'صفحة المستثمرين','crumb'=>'إدارة محتوى المستثمرين','badge'=>'0 عنصر'],
  'entrepreneurs-page' => ['file'=>'admin-entrepreneurs-page.php','title'=>'صفحة رواد الأعمال','crumb'=>'إدارة محتوى رواد الأعمال','badge'=>'0 عنصر'],
  'stories' => ['file'=>'admin-stories.php','title'=>'قصص النجاح','crumb'=>'إدارة دراسات الحالة','badge'=>'0 قصة'],
  'events' => ['file'=>'admin-events.php','title'=>'الفعاليات','crumb'=>'إدارة الفعاليات','badge'=>'0 فعالية'],
  'roles' => ['file'=>'admin-roles.php','title'=>'الأدوار والصلاحيات','crumb'=>'صلاحيات النظام','badge'=>'3 أدوار'],
  'audit' => ['file'=>'admin-audit.php','title'=>'سجل التدقيق','crumb'=>'سجل الإجراءات','badge'=>'مباشر'],
  'settings' => ['file'=>'admin-settings.php','title'=>'الإعدادات','crumb'=>'إعدادات النظام','badge'=>'عام'],
];
$current = $sections[$sectionKey] ?? $sections['users'];
$base = '../';
$title = $current['title'];
include __DIR__ . '/../partials/head.php';
require_once __DIR__ . '/../lib/admin.php';
$pageError = '';
$pageSuccess = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!auth_verify_csrf($_POST['csrf'] ?? null)) {
    $pageError = 'انتهت صلاحية الطلب. حدّث الصفحة وحاول مرة أخرى.';
  } elseif (($_POST['action'] ?? '') === 'set_kyc') {
    [$changed, $message] = auth_set_kyc_status((string) ($_POST['user_id'] ?? ''), (string) ($_POST['status'] ?? ''));
    if ($changed) { admin_log('kyc_status_change', 'user', (string) ($_POST['user_id'] ?? ''), (string) ($_POST['status'] ?? '')); $pageSuccess = $message; } else $pageError = $message;
  } elseif (($_POST['action'] ?? '') === 'update_admin_profile') {
    [$changed, $message] = auth_update_admin_profile((string) ($_SESSION['user_id'] ?? ''), $_POST);
    if ($changed) admin_log('update', 'admin_profile', (string) ($_SESSION['user_id'] ?? ''));
    if ($changed) $pageSuccess = $message; else $pageError = $message;
  } else {
    try {
      [$changed, $message] = admin_handle_action((string) ($_POST['action'] ?? ''), $_POST);
      if ($changed) $pageSuccess = $message; else $pageError = $message;
    } catch (Throwable $error) {
      $pageError = 'تعذر تنفيذ الطلب. تحقق من البيانات وحاول مرة أخرى.';
    }
  }
}
$users = auth_read_users();
usort($users, static fn(array $a, array $b): int => strcmp((string) ($b['created_at'] ?? ''), (string) ($a['created_at'] ?? '')));
$investors = array_values(array_filter($users, static fn(array $user): bool => ($user['role'] ?? '') === 'investor'));
$entrepreneurs = array_values(array_filter($users, static fn(array $user): bool => ($user['role'] ?? '') === 'entrepreneur'));
$pendingInvestors = array_values(array_filter($investors, static fn(array $user): bool => ($user['kyc_status'] ?? 'pending') === 'pending'));
$approvedInvestors = array_values(array_filter($investors, static fn(array $user): bool => ($user['kyc_status'] ?? 'pending') === 'approved'));
$opportunities = admin_rows('SELECT o.*,COUNT(io.user_id) AS assigned_count FROM opportunities o LEFT JOIN investor_opportunities io ON io.opportunity_id=o.id GROUP BY o.id ORDER BY o.created_at DESC');
$meetings = admin_rows('SELECT m.*,u.name AS user_name,u.email AS user_email FROM meetings m JOIN users u ON u.id=m.user_id ORDER BY m.scheduled_at DESC');
$contracts = admin_rows('SELECT c.*,u.name AS user_name FROM contracts c LEFT JOIN users u ON u.id=c.user_id ORDER BY c.created_at DESC');
$contentItems = admin_rows('SELECT * FROM content_items ORDER BY created_at DESC');
$aboutItems = admin_rows('SELECT * FROM about_page_items ORDER BY section_key,sort_order,created_at');
$investorPageItems = admin_rows('SELECT * FROM investor_page_items ORDER BY section_key,sort_order,created_at');
$entrepreneurPageItems = admin_rows('SELECT * FROM entrepreneur_page_items ORDER BY section_key,sort_order,created_at');
$successStories = admin_rows('SELECT * FROM success_stories ORDER BY sort_order,created_at');
$events = admin_rows('SELECT * FROM events ORDER BY starts_at DESC');
$sectorMapRows = admin_rows('SELECT * FROM sector_map ORDER BY sort_order,code');
$sectorIntro = [];
foreach (admin_rows("SELECT setting_key,setting_value FROM site_settings WHERE setting_key IN ('sectors_eyebrow','sectors_title','sectors_description')") as $settingRow) $sectorIntro[$settingRow['setting_key']] = $settingRow['setting_value'];
$auditRows = admin_rows('SELECT a.*,u.name AS admin_name FROM admin_audit_log a LEFT JOIN users u ON u.id=a.admin_user_id ORDER BY a.created_at DESC LIMIT 100');
$projects = array_values(array_filter($entrepreneurs, static fn(array $user): bool => trim((string)($user['project'] ?? '')) !== ''));
$activity = [];
foreach ($users as $user) {
  if (!empty($user['created_at'])) $activity[] = ['time'=>$user['created_at'],'name'=>$user['name'] ?? 'مستخدم','text'=>'تم إنشاء حساب ' . auth_role_label((string) ($user['role'] ?? ''))];
  if (!empty($user['updated_at'])) $activity[] = ['time'=>$user['updated_at'],'name'=>$user['name'] ?? 'مستخدم','text'=>'تم تحديث بيانات الحساب'];
  if (!empty($user['kyc_updated_at'])) $activity[] = ['time'=>$user['kyc_updated_at'],'name'=>$user['name'] ?? 'مستثمر','text'=>'تغيرت حالة KYC إلى ' . auth_kyc_label((string) ($user['kyc_status'] ?? 'pending'))];
}
usort($activity, static fn(array $a, array $b): int => strcmp((string) $b['time'], (string) $a['time']));
$admin = auth_find_user_by_id((string) ($_SESSION['user_id'] ?? '')) ?? [];
$authName = htmlspecialchars((string) ($admin['name'] ?? 'إدارة النظام'));
$authEmail = htmlspecialchars((string) ($admin['email'] ?? ''));
$authCountry = (string) ($admin['country'] ?? '');
$authInitials = htmlspecialchars(auth_initials((string) ($admin['name'] ?? 'إدارة')));
$sections['users']['badge'] = count($users) . ' مستخدم';
$sections['kyc']['badge'] = count($pendingInvestors) . ' معلّقة';
$sections['entrepreneur-requests']['badge'] = count($entrepreneurs) . ' حساب';
$sections['opportunities']['badge'] = count($opportunities) . ' فرصة';
$sections['projects']['badge'] = count($projects) . ' مشروع';
$sections['meetings']['badge'] = count($meetings) . ' اجتماع';
$sections['contracts']['badge'] = count($contracts) . ' عقد';
$sections['content']['badge'] = count($contentItems) . ' عنصر';
$sections['about']['badge'] = count($aboutItems) . ' عنصر';
$sections['investors-page']['badge'] = count($investorPageItems) . ' عنصر';
$sections['entrepreneurs-page']['badge'] = count($entrepreneurPageItems) . ' عنصر';
$sections['stories']['badge'] = count($successStories) . ' قصة';
$sections['events']['badge'] = count($events) . ' فعالية';
$sections['roles']['badge'] = '3 أدوار';
$sections['audit']['badge'] = count($auditRows) . ' إجراء';
$current = $sections[$sectionKey] ?? $sections['users'];

function admin_side_link($key, $label, $icon, $count = null, $muted = false) {
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
      <div class="avatar" style="background:var(--charcoal);color:#fff"><?= $authInitials ?></div>
      <div style="min-width:0"><b><?= $authName ?></b><span>مدير عام · كل الدول</span></div>
    </div>

    <div class="side-label">الإشراف</div>
    <a href="admin.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/><rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/></svg> اللوحة التنفيذية</a>
    <?php
      admin_side_link('users','المستخدمون','<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>', count($users), true);
      admin_side_link('kyc','مراجعات KYC/AML','<path d="M9 12l2 2 4-4"/><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>', count($pendingInvestors) ?: null);
      admin_side_link('opportunities','الفرص الاستثمارية','<circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/>');
      admin_side_link('entrepreneur-requests','طلبات رواد الأعمال','<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/>', count($entrepreneurs) ?: null);
      admin_side_link('projects','المشاريع والمهام','<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>');
      admin_side_link('meetings','الاجتماعات','<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>');
      admin_side_link('contracts','العقود والتوقيع','<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M12 18v-6M9 15h6"/>');
    ?>

    <div class="side-label">المحتوى</div>
    <?php
      admin_side_link('content','الأخبار والمقالات','<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>',count($contentItems) ?: null,true);
      admin_side_link('about','صفحة من نحن','<circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>',count($aboutItems) ?: null,true);
      admin_side_link('investors-page','صفحة المستثمرين','<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/>',count($investorPageItems) ?: null,true);
      admin_side_link('entrepreneurs-page','صفحة رواد الأعمال','<path d="M12 2l3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7z"/>',count($entrepreneurPageItems) ?: null,true);
      admin_side_link('stories','قصص النجاح','<path d="M3 3v18h18"/><path d="m7 15 4-4 3 3 5-7"/>',count($successStories) ?: null,true);
      admin_side_link('events','الفعاليات','<path d="M17 2.1l4 4-4 4M7 21.9l-4-4 4-4"/><path d="M21 6.1H8a5 5 0 0 0-5 5M3 17.9h13a5 5 0 0 0 5-5"/>',count($events) ?: null, true);
    ?>

    <div class="side-label">النظام</div>
    <?php
      admin_side_link('roles','الأدوار والصلاحيات','<rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>');
      admin_side_link('audit','سجل التدقيق','<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M9 12h6M9 16h6M9 8h1"/>');
      admin_side_link('settings','الإعدادات','<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9c.2.61.79 1 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>');
    ?>
    <div style="margin-top:auto"></div>
    <a href="../logout.php" class="side-link"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg> تسجيل الخروج</a>
  </aside>

  <div class="main">
    <header class="topbar dashboard-topbar">
      <button class="icon-btn side-toggle" onclick="toggleSidebar()" aria-label="فتح القائمة الجانبية" aria-expanded="false" aria-controls="sidebar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg></button>
      <div class="topbar-title"><div class="crumb"><?= $current['crumb'] ?></div><h1><?= $current['title'] ?></h1></div>
      <div class="spacer"></div>
      <a href="admin.php" class="btn btn-soft btn-sm hide-mobile">الرجوع للوحة</a>
      <button class="icon-btn" onclick="toggleTheme()" aria-label="تبديل الوضع الفاتح والداكن"><span data-theme-icon></span></button>
    </header>

    <div class="page-body entrepreneur-dashboard admin-dashboard-detail">
      <?php if ($pageError): ?><div class="auth-message auth-message-error" role="alert"><?= htmlspecialchars($pageError) ?></div><?php endif; ?>
      <?php if ($pageSuccess): ?><div class="auth-message auth-message-success" role="status"><?= htmlspecialchars($pageSuccess) ?></div><?php endif; ?>
      <section class="dashboard-detail-hero reveal">
        <div>
          <span class="eyebrow"><?= $current['badge'] ?></span>
          <h2><?= $current['title'] ?></h2>
          <p>صفحة إدارة تفصيلية تعرض البيانات التشغيلية، حالات المراجعة، وسجل الإجراءات مع روابط مباشرة للمتابعة.</p>
        </div>
      </section>

      <?php if ($sectionKey === 'users'): ?>
        <div class="detail-stat-grid reveal">
          <div class="detail-stat"><span>إجمالي المستخدمين</span><b><?= count($users) ?></b><small>جميع الحسابات المسجلة</small></div>
          <div class="detail-stat"><span>المستثمرون</span><b><?= count($investors) ?></b><small><?= count($approvedInvestors) ?> معتمد</small></div>
          <div class="detail-stat"><span>رواد الأعمال</span><b><?= count($entrepreneurs) ?></b><small>حسابات مسجلة فعليًا</small></div>
        </div>
        <div class="panel reveal">
          <div class="panel-head"><h3>قائمة المستخدمين</h3><span class="badge"><?= count($users) ?> حساب</span></div>
          <div class="panel-body flush"><div class="table-wrap admin-mobile-card-wrap admin-users-table-wrap" style="border:none;border-radius:0"><table class="data admin-mobile-card-table admin-users-table">
            <thead><tr><th>المستخدم</th><th>البريد</th><th>النوع</th><th>الدولة</th><th>الحالة</th><th>تاريخ التسجيل</th><th>الصلاحية</th></tr></thead>
            <tbody>
              <?php foreach($users as $user): $status = ($user['role'] ?? '') === 'investor' ? (string) ($user['kyc_status'] ?? 'pending') : 'approved'; ?>
                <tr>
                  <td data-label="المستخدم"><b style="font-family:var(--font-head)"><?= htmlspecialchars((string) ($user['name'] ?? '')) ?></b></td>
                  <td data-label="البريد" class="ltr-input"><?= htmlspecialchars((string) ($user['email'] ?? '')) ?></td>
                  <td data-label="النوع"><?= auth_role_label((string) ($user['role'] ?? '')) ?></td>
                  <td><?= htmlspecialchars((string) ($user['country'] ?? 'غير محددة')) ?></td>
                  <td data-label="الحالة"><span class="badge <?= auth_kyc_badge($status) ?>"><?= ($user['role'] ?? '') === 'investor' ? auth_kyc_label($status) : 'نشط' ?></span></td>
                  <td data-label="تاريخ التسجيل"><?= htmlspecialchars(substr((string) ($user['created_at'] ?? ''), 0, 10)) ?></td>
                  <td class="admin-role-cell"><?php if (($user['id'] ?? '') !== ($_SESSION['user_id'] ?? '')): ?><form method="post" class="admin-role-form"><input type="hidden" name="csrf" value="<?= htmlspecialchars(auth_csrf_token()) ?>"><input type="hidden" name="action" value="set_user_role"><input type="hidden" name="id" value="<?= htmlspecialchars((string)$user['id']) ?>"><select class="select admin-role-select" name="role" aria-label="صلاحية <?= htmlspecialchars((string)$user['name']) ?>"><option value="investor" <?= ($user['role']??'')==='investor'?'selected':'' ?>>مستثمر</option><option value="entrepreneur" <?= ($user['role']??'')==='entrepreneur'?'selected':'' ?>>رائد أعمال</option><option value="admin" <?= ($user['role']??'')==='admin'?'selected':'' ?>>إدارة</option></select><button class="btn btn-primary btn-sm admin-role-save" type="submit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="m5 12 4 4L19 6"/></svg><span>حفظ</span></button></form><?php else: ?><span class="badge badge-success admin-current-account">حسابك الحالي</span><?php endif; ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table></div></div>
        </div>
      <?php elseif ($sectionKey === 'kyc'): ?>
        <div class="detail-stat-grid reveal">
          <div class="detail-stat"><span>مراجعات معلقة</span><b><?= count($pendingInvestors) ?></b><small>تحتاج قرارًا من الإدارة</small></div>
          <div class="detail-stat"><span>حسابات معتمدة</span><b><?= count($approvedInvestors) ?></b><small>KYC/AML مكتملة</small></div>
          <div class="detail-stat"><span>إجمالي المستثمرين</span><b><?= count($investors) ?></b><small>كل الحالات</small></div>
        </div>
        <div class="panel reveal">
          <div class="panel-head"><h3>قائمة مراجعة KYC/AML</h3><span class="badge badge-warning"><?= count($pendingInvestors) ?> معلّقة</span></div>
          <div class="panel-body flush"><div class="table-wrap admin-mobile-card-wrap admin-kyc-table-wrap" style="border:none;border-radius:0"><table class="data admin-mobile-card-table admin-kyc-table">
            <thead><tr><th>المتقدم</th><th>البريد</th><th>النوع</th><th>الدولة</th><th>الحالة</th><th>الإجراء</th></tr></thead>
            <tbody>
              <?php foreach($investors as $kycUser): $status=(string)($kycUser['kyc_status'] ?? 'pending'); ?>
              <tr>
                <td><b><?= htmlspecialchars((string) ($kycUser['name'] ?? '')) ?></b></td>
                <td class="ltr-input"><?= htmlspecialchars((string) ($kycUser['email'] ?? '')) ?></td>
                <td><?= htmlspecialchars((string) ($kycUser['investor_type'] ?? '')) ?></td>
                <td><?= htmlspecialchars((string) ($kycUser['country'] ?? '')) ?></td>
                <td><span class="badge <?= auth_kyc_badge($status) ?>"><?= auth_kyc_label($status) ?></span></td>
                <td>
                  <form method="post" class="row gap-8">
                    <input type="hidden" name="action" value="set_kyc"><input type="hidden" name="csrf" value="<?= htmlspecialchars(auth_csrf_token()) ?>"><input type="hidden" name="user_id" value="<?= htmlspecialchars((string) ($kycUser['id'] ?? '')) ?>">
                    <?php if ($status !== 'approved'): ?><button class="btn btn-soft btn-sm" type="submit" name="status" value="approved">اعتماد</button><?php endif; ?>
                    <?php if ($status !== 'pending'): ?><button class="btn btn-ghost btn-sm" type="submit" name="status" value="pending">إعادة للمراجعة</button><?php endif; ?>
                    <?php if ($status !== 'rejected'): ?><button class="btn btn-ghost btn-sm" type="submit" name="status" value="rejected">رفض</button><?php endif; ?>
                  </form>
                </td>
              </tr>
              <?php endforeach; ?>
              <?php if (!$investors): ?><tr><td colspan="6" class="text-2" style="text-align:center;padding:28px">لا توجد حسابات مستثمرين.</td></tr><?php endif; ?>
            </tbody>
          </table></div></div>
        </div>
      <?php elseif ($sectionKey === 'opportunities'): ?>
        <div class="dashboard-main-grid">
          <div class="panel reveal"><div class="panel-head"><h3>الفرص المسجلة</h3><span class="badge"><?= count($opportunities) ?></span></div><div class="panel-body">
            <?php foreach($opportunities as $o): ?>
              <div class="support-ticket admin-opportunity-row">
                <div class="admin-opportunity-info">
                  <b><?= htmlspecialchars($o['title']) ?> · <?= htmlspecialchars($o['id']) ?></b>
                  <p class="text-2"><?= htmlspecialchars($o['sector']) ?> · <?= htmlspecialchars($o['stage']) ?> · <?= number_format((float)$o['target_amount']) ?> <?= htmlspecialchars($o['currency']) ?> · مسندة لـ <?= (int)$o['assigned_count'] ?></p>
                </div>
                <div class="admin-opportunity-actions">
                  <form method="post" class="admin-opportunity-control">
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars(auth_csrf_token()) ?>"><input type="hidden" name="action" value="set_opportunity_status"><input type="hidden" name="id" value="<?= htmlspecialchars($o['id']) ?>">
                    <select class="select" name="status" aria-label="حالة الفرصة"><option value="review" <?= $o['status']==='review'?'selected':'' ?>>قيد المراجعة — مخفية</option><option value="available" <?= $o['status']==='available'?'selected':'' ?>>متاحة — منشورة</option><option value="completed" <?= $o['status']==='completed'?'selected':'' ?>>مكتملة — مخفية</option></select>
                    <button class="btn btn-primary btn-sm admin-opportunity-button" type="submit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="m5 12 4 4L19 6"/></svg><span>حفظ الحالة</span></button>
                  </form>
                  <?php if($investors): ?>
                    <form method="post" class="admin-opportunity-control">
                      <input type="hidden" name="csrf" value="<?= htmlspecialchars(auth_csrf_token()) ?>"><input type="hidden" name="action" value="assign_opportunity"><input type="hidden" name="id" value="<?= htmlspecialchars($o['id']) ?>">
                      <select class="select" name="user_id" required aria-label="اختيار المستثمر" onchange="this.nextElementSibling.disabled = !this.value"><option value="">اختر مستثمرًا</option><?php foreach($investors as $investor): ?><option value="<?= htmlspecialchars($investor['id']) ?>"><?= htmlspecialchars($investor['name']) ?></option><?php endforeach; ?></select>
                      <button class="btn btn-soft btn-sm admin-opportunity-button" type="submit" disabled><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M19 8v6M22 11h-6"/></svg><span>إسناد</span></button>
                    </form>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
            <?php if(!$opportunities): ?><div class="empty-state"><b>لا توجد بيانات فعلية في هذا القسم بعد</b><p>ستظهر البيانات هنا بعد إضافتها من الإدارة.</p></div><?php endif; ?>
          </div></div>
          <div class="panel reveal"><div class="panel-head"><h3>إضافة فرصة</h3></div><div class="panel-body"><form method="post"><input type="hidden" name="csrf" value="<?= htmlspecialchars(auth_csrf_token()) ?>"><input type="hidden" name="action" value="create_opportunity"><div class="field"><label class="label">الاسم</label><input class="input" name="title" required></div><div class="grid mt-16" style="grid-template-columns:1fr 1fr;gap:12px"><input class="input" name="sector" placeholder="القطاع" required><input class="input" name="stage" placeholder="المرحلة" required><input class="input" name="target_amount" type="number" min="0" step="0.01" placeholder="قيمة التمويل" required><input class="input ltr-input" name="currency" value="USD" maxlength="3" required></div><select class="select mt-16" name="status"><option value="available">متاحة — تظهر في صفحة القطاعات</option><option value="review">قيد المراجعة — مخفية</option></select><button class="btn btn-primary btn-sm mt-16">إضافة ونشر</button></form></div></div>
        </div>

        <section class="panel reveal mt-24 admin-sector-manager">
          <div class="panel-head"><div><h3>تحرير خريطة الفرص</h3><p class="hint">التعديلات تظهر في صفحة القطاعات عبر API.</p></div><span class="badge"><?= count($sectorMapRows) ?> قطاعات</span></div>
          <div class="panel-body">
            <form method="post" class="admin-sector-intro-form">
              <input type="hidden" name="csrf" value="<?= htmlspecialchars(auth_csrf_token()) ?>"><input type="hidden" name="action" value="update_sector_intro">
              <div class="field"><label class="label" for="sector-eyebrow">العنوان التمهيدي</label><input class="input" id="sector-eyebrow" name="eyebrow" value="<?= htmlspecialchars((string)($sectorIntro['sectors_eyebrow'] ?? '')) ?>" required></div>
              <div class="field"><label class="label" for="sector-title">العنوان الرئيسي</label><input class="input" id="sector-title" name="title" value="<?= htmlspecialchars((string)($sectorIntro['sectors_title'] ?? '')) ?>" required></div>
              <div class="field"><label class="label" for="sector-description">الوصف</label><textarea class="textarea" id="sector-description" name="description" required><?= htmlspecialchars((string)($sectorIntro['sectors_description'] ?? '')) ?></textarea></div>
              <button class="btn btn-primary btn-sm" type="submit">حفظ عنوان الخريطة</button>
            </form>

            <?php $sectorIconLabels=['software'=>'برمجيات','fintech'=>'تقنية مالية','ai'=>'ذكاء اصطناعي','health'=>'صحة','education'=>'تعليم','iot'=>'إنترنت الأشياء','logistics'=>'لوجستيات','digital'=>'تحول رقمي']; ?>
            <details class="admin-sector-create mt-24">
              <summary><span>إضافة قطاع جديد</span><small>سيحصل على رقم تلقائي</small></summary>
              <form method="post" class="admin-sector-create-form">
                <input type="hidden" name="csrf" value="<?= htmlspecialchars(auth_csrf_token()) ?>"><input type="hidden" name="action" value="create_sector_map">
                <div class="field"><label class="label">اسم القطاع</label><input class="input" name="name" required></div>
                <div class="field"><label class="label">الوصف</label><textarea class="textarea" name="description" required></textarea></div>
                <div class="field"><label class="label">الوسوم — افصل بفاصلة</label><input class="input" name="tags" placeholder="وسم أول، وسم ثانٍ" required></div>
                <div class="admin-sector-editor-options"><div class="field"><label class="label">الأيقونة</label><select class="select" name="icon_key"><?php foreach($sectorIconLabels as $iconKey=>$iconLabel): ?><option value="<?= $iconKey ?>"><?= $iconLabel ?></option><?php endforeach; ?></select></div><div class="field"><label class="label">الترتيب</label><input class="input" type="number" min="0" name="sort_order" value="<?= count($sectorMapRows)+1 ?>" required></div></div>
                <label class="auth-check"><input type="checkbox" name="is_active" value="1" checked><span>إظهاره فورًا في صفحة القطاعات</span></label>
                <button class="btn btn-primary btn-sm" type="submit">إضافة القطاع</button>
              </form>
            </details>

            <div class="admin-sector-editor-grid mt-24">
              <?php foreach($sectorMapRows as $sectorRow): $sectorTags=json_decode((string)$sectorRow['tags_json'],true); ?>
                <form method="post" class="admin-sector-editor-card">
                  <input type="hidden" name="csrf" value="<?= htmlspecialchars(auth_csrf_token()) ?>"><input type="hidden" name="code" value="<?= htmlspecialchars($sectorRow['code']) ?>">
                  <div class="admin-sector-editor-head"><span class="sector-code"><?= htmlspecialchars($sectorRow['code']) ?></span><label class="auth-check"><input type="checkbox" name="is_active" value="1" <?= !empty($sectorRow['is_active'])?'checked':'' ?>><span>ظاهر في الموقع</span></label></div>
                  <div class="field"><label class="label">اسم القطاع</label><input class="input" name="name" value="<?= htmlspecialchars($sectorRow['name']) ?>" required></div>
                  <div class="field"><label class="label">الوصف</label><textarea class="textarea" name="description" required><?= htmlspecialchars($sectorRow['description']) ?></textarea></div>
                  <div class="field"><label class="label">الوسوم — افصل بفاصلة</label><input class="input" name="tags" value="<?= htmlspecialchars(implode('، ',is_array($sectorTags)?$sectorTags:[])) ?>" required></div>
                  <div class="admin-sector-editor-options"><div class="field"><label class="label">الأيقونة</label><select class="select" name="icon_key"><?php foreach($sectorIconLabels as $iconKey=>$iconLabel): ?><option value="<?= $iconKey ?>" <?= $sectorRow['icon_key']===$iconKey?'selected':'' ?>><?= $iconLabel ?></option><?php endforeach; ?></select></div><div class="field"><label class="label">الترتيب</label><input class="input" type="number" min="0" name="sort_order" value="<?= (int)$sectorRow['sort_order'] ?>" required></div></div>
                  <div class="admin-sector-editor-actions"><button class="btn btn-primary btn-sm" type="submit" name="action" value="update_sector_map">حفظ التعديلات</button><button class="btn btn-sm admin-delete-button" type="submit" name="action" value="delete_sector_map" formnovalidate onclick="return confirm('هل أنت متأكد من حذف هذا القطاع؟ لن يظهر في صفحة القطاعات.')">حذف القطاع</button></div>
                </form>
              <?php endforeach; ?>
            </div>
          </div>
        </section>
      <?php elseif ($sectionKey === 'entrepreneur-requests'): ?>
        <div class="panel reveal">
          <div class="panel-head"><h3>حسابات رواد الأعمال</h3><span class="badge"><?= count($entrepreneurs) ?> حساب</span></div>
          <div class="panel-body">
            <?php foreach($entrepreneurs as $entrepreneur): ?>
              <div class="support-ticket"><div><b><?= htmlspecialchars((string) (($entrepreneur['project'] ?? '') ?: ($entrepreneur['name'] ?? ''))) ?></b><p class="text-2"><?= htmlspecialchars((string) ($entrepreneur['name'] ?? '')) ?> · <?= htmlspecialchars((string) ($entrepreneur['email'] ?? '')) ?> · <?= htmlspecialchars((string) ($entrepreneur['country'] ?? '')) ?></p></div><span class="badge"><?= htmlspecialchars(substr((string) ($entrepreneur['created_at'] ?? ''), 0, 10)) ?></span></div>
            <?php endforeach; ?>
            <?php if (!$entrepreneurs): ?><p class="text-2" style="text-align:center;padding:24px">لا توجد حسابات رواد أعمال.</p><?php endif; ?>
          </div>
        </div>
      <?php elseif ($sectionKey === 'projects'): ?>
        <div class="panel reveal"><div class="panel-head"><h3>المشاريع المسجلة</h3><span class="badge"><?= count($projects) ?></span></div><div class="panel-body"><?php foreach($projects as $project): ?><div class="support-ticket"><div><b><?= htmlspecialchars($project['project']) ?></b><p class="text-2"><?= htmlspecialchars($project['name']) ?> · <?= htmlspecialchars($project['email']) ?> · <?= htmlspecialchars($project['country']) ?></p></div><span class="badge"><?= htmlspecialchars(substr((string)$project['created_at'],0,10)) ?></span></div><?php endforeach; ?><?php if(!$projects): ?><div class="empty-state"><b>لا توجد بيانات فعلية في هذا القسم بعد</b><p>ستظهر المشاريع هنا بعد تسجيل حسابات رواد الأعمال.</p></div><?php endif; ?></div></div>
      <?php elseif ($sectionKey === 'meetings'): ?>
        <div class="dashboard-main-grid"><div class="panel reveal"><div class="panel-head"><h3>الاجتماعات المسجلة</h3><span class="badge"><?= count($meetings) ?></span></div><div class="panel-body"><?php foreach($meetings as $m): ?><div class="support-ticket"><div><b><?= htmlspecialchars($m['subject']) ?></b><p class="text-2"><?= htmlspecialchars($m['user_name']) ?> · <?= htmlspecialchars($m['scheduled_at']) ?> · <?= htmlspecialchars($m['platform']) ?></p></div><form method="post" class="row gap-8"><input type="hidden" name="csrf" value="<?= htmlspecialchars(auth_csrf_token()) ?>"><input type="hidden" name="action" value="set_meeting_status"><input type="hidden" name="id" value="<?= htmlspecialchars($m['id']) ?>"><select class="select" name="status"><?php foreach(['pending'=>'معلق','confirmed'=>'مؤكد','completed'=>'مكتمل','cancelled'=>'ملغى'] as $value=>$label): ?><option value="<?= $value ?>" <?= $m['status']===$value?'selected':'' ?>><?= $label ?></option><?php endforeach; ?></select><button class="btn btn-soft btn-sm">حفظ</button></form></div><?php endforeach; ?><?php if(!$meetings): ?><div class="empty-state"><b>لا توجد بيانات فعلية في هذا القسم بعد</b><p>ستظهر الاجتماعات هنا بعد جدولتها.</p></div><?php endif; ?></div></div><div class="panel reveal"><div class="panel-head"><h3>جدولة اجتماع</h3></div><div class="panel-body"><form method="post"><input type="hidden" name="csrf" value="<?= htmlspecialchars(auth_csrf_token()) ?>"><input type="hidden" name="action" value="create_meeting"><select class="select" name="user_id" required><option value="">اختر المستخدم</option><?php foreach(array_filter($users,fn($u)=>$u['role']!=='admin') as $u): ?><option value="<?= htmlspecialchars($u['id']) ?>"><?= htmlspecialchars($u['name'].' — '.$u['email']) ?></option><?php endforeach; ?></select><input class="input mt-16" name="subject" placeholder="موضوع الاجتماع" required><input class="input mt-16" name="opportunity" placeholder="الفرصة/المشروع (اختياري)"><input class="input mt-16" name="scheduled_at" type="datetime-local" required><input class="input mt-16" name="platform" placeholder="المكان أو المنصة" required><button class="btn btn-primary btn-sm mt-16">حفظ الاجتماع</button></form></div></div></div>
      <?php elseif ($sectionKey === 'contracts'): ?>
        <div class="dashboard-main-grid"><div class="panel reveal"><div class="panel-head"><h3>العقود</h3><span class="badge"><?= count($contracts) ?></span></div><div class="panel-body"><?php foreach($contracts as $c): ?><div class="support-ticket"><div><b><?= htmlspecialchars($c['title']) ?></b><p class="text-2"><?= htmlspecialchars($c['user_name'] ?: 'غير مرتبط بمستخدم') ?> · <?= htmlspecialchars($c['created_at']) ?></p></div><form method="post" class="row gap-8"><input type="hidden" name="csrf" value="<?= htmlspecialchars(auth_csrf_token()) ?>"><input type="hidden" name="action" value="set_contract_status"><input type="hidden" name="id" value="<?= htmlspecialchars($c['id']) ?>"><select class="select" name="status"><?php foreach(['draft'=>'مسودة','review'=>'مراجعة','pending_signature'=>'بانتظار التوقيع','signed'=>'موقع','cancelled'=>'ملغى'] as $value=>$label): ?><option value="<?= $value ?>" <?= $c['status']===$value?'selected':'' ?>><?= $label ?></option><?php endforeach; ?></select><button class="btn btn-soft btn-sm">حفظ</button></form></div><?php endforeach; ?><?php if(!$contracts): ?><div class="empty-state"><b>لا توجد بيانات فعلية في هذا القسم بعد</b><p>ستظهر العقود هنا بعد إضافتها.</p></div><?php endif; ?></div></div><div class="panel reveal"><div class="panel-head"><h3>إضافة عقد</h3></div><div class="panel-body"><form method="post"><input type="hidden" name="csrf" value="<?= htmlspecialchars(auth_csrf_token()) ?>"><input type="hidden" name="action" value="create_contract"><input class="input" name="title" placeholder="اسم العقد" required><select class="select mt-16" name="user_id"><option value="">بدون مستخدم</option><?php foreach($users as $u): ?><option value="<?= htmlspecialchars($u['id']) ?>"><?= htmlspecialchars($u['name'].' — '.$u['email']) ?></option><?php endforeach; ?></select><button class="btn btn-primary btn-sm mt-16">إضافة</button></form></div></div></div>
      <?php elseif ($sectionKey === 'entrepreneurs-page'): ?>
        <?php include __DIR__ . '/admin-entrepreneurs-page-view.php'; ?>
      <?php elseif ($sectionKey === 'investors-page'): ?>
        <?php
          $investorPageSections=['hero'=>'مقدمة الصفحة','investor_type'=>'أنواع المستثمرين','benefits_header'=>'مقدمة المزايا','benefit'=>'بطاقات المزايا','journey_header'=>'مقدمة رحلة المستثمر','journey_step'=>'خطوات الرحلة','faq_header'=>'مقدمة الأسئلة','faq'=>'الأسئلة الشائعة','cta'=>'الدعوة الأخيرة'];
          $investorPageIcons=['default'=>'بدون أيقونة','person'=>'فرد','company'=>'شركة','fund'=>'صندوق','angel'=>'مستثمر ملائكي','family'=>'مكتب عائلي','ready'=>'جاهزية','security'=>'حماية','flexible'=>'مرونة','transparency'=>'شفافية','speed'=>'سرعة','money'=>'استثمار'];
          $investorPageStyles=['info'=>'عادي','orange'=>'بارز برتقالي','success'=>'أخضر','warning'=>'ذهبي'];
        ?>
        <div class="panel admin-knowledge-manager admin-investors-page-manager" data-admin-investors-page-manager>
          <div class="panel-head"><div><h3>إدارة صفحة المستثمرين</h3><p class="hint">تحكم في كل قسم وبطاقة وخطوة وسؤال عبر API.</p></div><span class="badge"><?= count($investorPageItems) ?> عنصر</span></div>
          <div class="panel-body">
            <details class="admin-sector-create admin-knowledge-create"><summary><span>إضافة عنصر جديد</span><small>اختر القسم ثم أدخل البيانات المطلوبة</small></summary>
              <form method="post" class="admin-sector-create-form admin-knowledge-form" data-admin-investors-page-form><input type="hidden" name="csrf" value="<?= htmlspecialchars(auth_csrf_token()) ?>"><input type="hidden" name="action" value="create_investor_page_item">
                <div class="admin-knowledge-fields"><div class="field"><label class="label">القسم</label><select class="select" name="section_key"><?php foreach($investorPageSections as $value=>$label): ?><option value="<?= $value ?>"><?= $label ?></option><?php endforeach; ?></select></div><div class="field"><label class="label">العنوان</label><input class="input" name="title" required></div><div class="field"><label class="label">العنوان الفرعي</label><input class="input" name="subtitle"></div><div class="field"><label class="label">نص الزر الأول / الشارة</label><input class="input" name="badge_label"></div></div>
                <div class="field"><label class="label">الوصف أو الإجابة</label><textarea class="textarea" name="body"></textarea></div>
                <div class="admin-knowledge-fields"><div class="field"><label class="label">نص الزر الثاني</label><input class="input" name="value_text"></div><div class="field"><label class="label">شارات الثقة — افصل بفاصلة</label><input class="input ltr-input" name="value_suffix" placeholder="KYC/AML,NDA,Dashboard"></div><div class="field"><label class="label">الأيقونة</label><select class="select" name="icon_key"><?php foreach($investorPageIcons as $value=>$label): ?><option value="<?= $value ?>"><?= $label ?></option><?php endforeach; ?></select></div><div class="field"><label class="label">نمط العنصر</label><select class="select" name="badge_style"><?php foreach($investorPageStyles as $value=>$label): ?><option value="<?= $value ?>"><?= $label ?></option><?php endforeach; ?></select></div><div class="field"><label class="label">الرابط الأول</label><input class="input ltr-input" name="primary_url"></div><div class="field"><label class="label">الرابط الثاني</label><input class="input ltr-input" name="secondary_url"></div></div>
                <div class="admin-knowledge-footer"><label class="auth-check"><input type="checkbox" name="is_active" value="1" checked><span>ظاهر في الموقع</span></label><div class="field"><label class="label">الترتيب</label><input class="input" type="number" min="0" name="sort_order" value="<?= count($investorPageItems)+1 ?>" required></div><button class="btn btn-primary btn-sm" type="submit">إضافة العنصر</button></div>
              </form>
            </details>
            <div class="admin-knowledge-editor-grid mt-24">
              <?php foreach($investorPageItems as $item): ?>
                <form method="post" class="admin-knowledge-editor-card" data-admin-investors-page-form><input type="hidden" name="csrf" value="<?= htmlspecialchars(auth_csrf_token()) ?>"><input type="hidden" name="id" value="<?= htmlspecialchars($item['id']) ?>">
                  <div class="admin-knowledge-card-head"><span class="badge"><?= htmlspecialchars($investorPageSections[$item['section_key']]??$item['section_key']) ?></span><label class="auth-check"><input type="checkbox" name="is_active" value="1" <?= !empty($item['is_active'])?'checked':'' ?>><span>ظاهر</span></label></div>
                  <div class="admin-knowledge-fields"><div class="field"><label class="label">القسم</label><select class="select" name="section_key"><?php foreach($investorPageSections as $value=>$label): ?><option value="<?= $value ?>" <?= $item['section_key']===$value?'selected':'' ?>><?= $label ?></option><?php endforeach; ?></select></div><div class="field"><label class="label">العنوان</label><input class="input" name="title" value="<?= htmlspecialchars($item['title']) ?>" required></div><div class="field"><label class="label">العنوان الفرعي</label><input class="input" name="subtitle" value="<?= htmlspecialchars($item['subtitle']) ?>"></div><div class="field"><label class="label">نص الزر الأول / الشارة</label><input class="input" name="badge_label" value="<?= htmlspecialchars($item['badge_label']) ?>"></div></div>
                  <div class="field"><label class="label">الوصف أو الإجابة</label><textarea class="textarea" name="body"><?= htmlspecialchars($item['body']) ?></textarea></div>
                  <div class="admin-knowledge-fields"><div class="field"><label class="label">نص الزر الثاني</label><input class="input" name="value_text" value="<?= htmlspecialchars($item['value_text']) ?>"></div><div class="field"><label class="label">شارات الثقة</label><input class="input ltr-input" name="value_suffix" value="<?= htmlspecialchars($item['value_suffix']) ?>"></div><div class="field"><label class="label">الأيقونة</label><select class="select" name="icon_key"><?php foreach($investorPageIcons as $value=>$label): ?><option value="<?= $value ?>" <?= $item['icon_key']===$value?'selected':'' ?>><?= $label ?></option><?php endforeach; ?></select></div><div class="field"><label class="label">نمط العنصر</label><select class="select" name="badge_style"><?php foreach($investorPageStyles as $value=>$label): ?><option value="<?= $value ?>" <?= $item['badge_style']===$value?'selected':'' ?>><?= $label ?></option><?php endforeach; ?></select></div><div class="field"><label class="label">الرابط الأول</label><input class="input ltr-input" name="primary_url" value="<?= htmlspecialchars($item['primary_url']) ?>"></div><div class="field"><label class="label">الرابط الثاني</label><input class="input ltr-input" name="secondary_url" value="<?= htmlspecialchars($item['secondary_url']) ?>"></div><div class="field admin-knowledge-order"><label class="label">الترتيب</label><input class="input" type="number" min="0" name="sort_order" value="<?= (int)$item['sort_order'] ?>" required></div></div>
                  <div class="admin-sector-editor-actions"><button class="btn btn-primary btn-sm" type="submit" name="action" value="update_investor_page_item">حفظ التعديلات</button><button class="btn btn-sm admin-delete-button" type="submit" name="action" value="delete_investor_page_item" formnovalidate onclick="return confirm('هل أنت متأكد من حذف هذا العنصر من صفحة المستثمرين؟')">حذف العنصر</button></div>
                </form>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      <?php elseif ($sectionKey === 'about'): ?>
        <?php
          $aboutSections=['hero'=>'مقدمة الصفحة','brand'=>'بطاقات العلامات','vmm'=>'الرؤية والرسالة والمنهجية','stat'=>'الأرقام','team_header'=>'مقدمة الفريق','team'=>'أعضاء الفريق','geo_header'=>'مقدمة التوسع','geo'=>'مناطق التوسع','cta'=>'الدعوة الأخيرة'];
          $aboutIcons=['default'=>'بدون أيقونة','vision'=>'الرؤية','mission'=>'الرسالة','method'=>'المنهجية','experience'=>'الخبرة','building'=>'الشركة','projects'=>'المشروعات','clients'=>'العملاء','person'=>'شخص'];
          $aboutStyles=['info'=>'أزرق','success'=>'أخضر','warning'=>'ذهبي','orange'=>'برتقالي'];
        ?>
        <div class="panel admin-knowledge-manager admin-about-manager" data-admin-about-manager>
          <div class="panel-head"><div><h3>إدارة صفحة من نحن</h3><p class="hint">يمكن تعديل أو إخفاء أو حذف كل قسم وبطاقة عبر API.</p></div><span class="badge"><?= count($aboutItems) ?> عنصر</span></div>
          <div class="panel-body">
            <details class="admin-sector-create admin-knowledge-create">
              <summary><span>إضافة عنصر جديد</span><small>اختر مكان ظهوره داخل الصفحة</small></summary>
              <form method="post" class="admin-sector-create-form admin-knowledge-form" data-admin-about-form><input type="hidden" name="csrf" value="<?= htmlspecialchars(auth_csrf_token()) ?>"><input type="hidden" name="action" value="create_about_item">
                <div class="admin-knowledge-fields"><div class="field"><label class="label">القسم</label><select class="select" name="section_key"><?php foreach($aboutSections as $value=>$label): ?><option value="<?= $value ?>"><?= $label ?></option><?php endforeach; ?></select></div><div class="field"><label class="label">العنوان</label><input class="input" name="title" required></div><div class="field"><label class="label">العنوان الفرعي / الوظيفة</label><input class="input" name="subtitle"></div><div class="field"><label class="label">النص المختصر / الشارة</label><input class="input" name="badge_label"></div></div>
                <div class="field"><label class="label">الوصف</label><textarea class="textarea" name="body"></textarea></div>
                <div class="admin-knowledge-fields"><div class="field"><label class="label">القيمة الرقمية أو نص الزر الثاني</label><input class="input" name="value_text"></div><div class="field"><label class="label">لاحقة القيمة</label><input class="input" name="value_suffix" placeholder="+"></div><div class="field"><label class="label">الأيقونة</label><select class="select" name="icon_key"><?php foreach($aboutIcons as $value=>$label): ?><option value="<?= $value ?>"><?= $label ?></option><?php endforeach; ?></select></div><div class="field"><label class="label">لون الشارة</label><select class="select" name="badge_style"><?php foreach($aboutStyles as $value=>$label): ?><option value="<?= $value ?>"><?= $label ?></option><?php endforeach; ?></select></div><div class="field"><label class="label">الرابط الأول</label><input class="input ltr-input" name="primary_url" placeholder="contact.php أو https://..."></div><div class="field"><label class="label">الرابط الثاني</label><input class="input ltr-input" name="secondary_url"></div></div>
                <div class="admin-knowledge-footer"><label class="auth-check"><input type="checkbox" name="is_active" value="1" checked><span>ظاهر في الموقع</span></label><div class="field"><label class="label">الترتيب</label><input class="input" type="number" min="0" name="sort_order" value="<?= count($aboutItems)+1 ?>" required></div><button class="btn btn-primary btn-sm" type="submit">إضافة العنصر</button></div>
              </form>
            </details>
            <div class="admin-knowledge-editor-grid mt-24">
              <?php foreach($aboutItems as $item): ?>
                <form method="post" class="admin-knowledge-editor-card" data-admin-about-form><input type="hidden" name="csrf" value="<?= htmlspecialchars(auth_csrf_token()) ?>"><input type="hidden" name="id" value="<?= htmlspecialchars($item['id']) ?>">
                  <div class="admin-knowledge-card-head"><span class="badge"><?= htmlspecialchars($aboutSections[$item['section_key']]??$item['section_key']) ?></span><label class="auth-check"><input type="checkbox" name="is_active" value="1" <?= !empty($item['is_active'])?'checked':'' ?>><span>ظاهر</span></label></div>
                  <div class="admin-knowledge-fields"><div class="field"><label class="label">القسم</label><select class="select" name="section_key"><?php foreach($aboutSections as $value=>$label): ?><option value="<?= $value ?>" <?= $item['section_key']===$value?'selected':'' ?>><?= $label ?></option><?php endforeach; ?></select></div><div class="field"><label class="label">العنوان</label><input class="input" name="title" value="<?= htmlspecialchars($item['title']) ?>" required></div><div class="field"><label class="label">العنوان الفرعي / الوظيفة</label><input class="input" name="subtitle" value="<?= htmlspecialchars($item['subtitle']) ?>"></div><div class="field"><label class="label">النص المختصر / الشارة</label><input class="input" name="badge_label" value="<?= htmlspecialchars($item['badge_label']) ?>"></div></div>
                  <div class="field"><label class="label">الوصف</label><textarea class="textarea" name="body"><?= htmlspecialchars($item['body']) ?></textarea></div>
                  <div class="admin-knowledge-fields"><div class="field"><label class="label">القيمة / نص الزر الثاني</label><input class="input" name="value_text" value="<?= htmlspecialchars($item['value_text']) ?>"></div><div class="field"><label class="label">لاحقة القيمة</label><input class="input" name="value_suffix" value="<?= htmlspecialchars($item['value_suffix']) ?>"></div><div class="field"><label class="label">الأيقونة</label><select class="select" name="icon_key"><?php foreach($aboutIcons as $value=>$label): ?><option value="<?= $value ?>" <?= $item['icon_key']===$value?'selected':'' ?>><?= $label ?></option><?php endforeach; ?></select></div><div class="field"><label class="label">لون الشارة</label><select class="select" name="badge_style"><?php foreach($aboutStyles as $value=>$label): ?><option value="<?= $value ?>" <?= $item['badge_style']===$value?'selected':'' ?>><?= $label ?></option><?php endforeach; ?></select></div><div class="field"><label class="label">الرابط الأول</label><input class="input ltr-input" name="primary_url" value="<?= htmlspecialchars($item['primary_url']) ?>"></div><div class="field"><label class="label">الرابط الثاني</label><input class="input ltr-input" name="secondary_url" value="<?= htmlspecialchars($item['secondary_url']) ?>"></div><div class="field admin-knowledge-order"><label class="label">الترتيب</label><input class="input" type="number" min="0" name="sort_order" value="<?= (int)$item['sort_order'] ?>" required></div></div>
                  <div class="admin-sector-editor-actions"><button class="btn btn-primary btn-sm" type="submit" name="action" value="update_about_item">حفظ التعديلات</button><button class="btn btn-sm admin-delete-button" type="submit" name="action" value="delete_about_item" formnovalidate onclick="return confirm('هل أنت متأكد من حذف هذا العنصر من صفحة من نحن؟')">حذف العنصر</button></div>
                </form>
              <?php endforeach; ?>
              <?php if(!$aboutItems): ?><div class="empty-state"><b>لا توجد بيانات فعلية في هذا القسم بعد</b><p>أضف عناصر صفحة من نحن من النموذج أعلاه.</p></div><?php endif; ?>
            </div>
          </div>
        </div>
      <?php elseif ($sectionKey === 'content'): ?>
        <?php $contentTypes=['article'=>'مقال','news'=>'خبر','update'=>'تحديث']; $contentStatuses=['draft'=>'مسودة','published'=>'منشور','archived'=>'مؤرشف']; ?>
        <div class="panel admin-knowledge-manager" data-admin-news-events-manager>
          <div class="panel-head"><div><h3>إدارة الأخبار والمقالات</h3><p class="hint">كل العمليات تتم عبر API، ولا يظهر للعامة إلا المحتوى المنشور.</p></div><span class="badge"><?= count($contentItems) ?> عنصر</span></div>
          <div class="panel-body">
            <details class="admin-sector-create admin-knowledge-create">
              <summary><span>إضافة خبر أو مقال</span><small>أدخل محتوى موثقًا فقط</small></summary>
              <form method="post" class="admin-sector-create-form admin-knowledge-form" data-admin-news-events-form><input type="hidden" name="csrf" value="<?= htmlspecialchars(auth_csrf_token()) ?>"><input type="hidden" name="action" value="create_news_item">
                <div class="admin-knowledge-fields"><div class="field"><label class="label">العنوان</label><input class="input" name="title" required></div><div class="field"><label class="label">النوع</label><select class="select" name="content_type"><?php foreach($contentTypes as $value=>$label): ?><option value="<?= $value ?>"><?= $label ?></option><?php endforeach; ?></select></div><div class="field"><label class="label">التسمية الظاهرة</label><input class="input" name="category_label" value="مقال" required></div><div class="field"><label class="label">تاريخ النشر</label><input class="input" type="datetime-local" name="published_at" value="<?= date('Y-m-d\TH:i') ?>" required></div><div class="field"><label class="label">مدة القراءة</label><input class="input" name="reading_time" placeholder="قراءة 6 دقائق"></div><div class="field"><label class="label">الحالة</label><select class="select" name="status"><?php foreach($contentStatuses as $value=>$label): ?><option value="<?= $value ?>" <?= $value==='draft'?'selected':'' ?>><?= $label ?></option><?php endforeach; ?></select></div></div>
                <div class="field"><label class="label">الملخص</label><textarea class="textarea" name="excerpt" required></textarea></div>
                <div class="admin-knowledge-fields"><div class="field"><label class="label">صورة الغلاف — رابط أو مسار</label><input class="input ltr-input" name="cover_image" placeholder="assets/img/cover.png"></div><div class="field"><label class="label">رابط قراءة المحتوى</label><input class="input ltr-input" type="url" name="external_url" placeholder="https://..."></div></div>
                <div class="admin-knowledge-footer"><label class="auth-check"><input type="checkbox" name="is_featured" value="1"><span>محتوى مميّز</span></label><div class="field"><label class="label">الترتيب</label><input class="input" type="number" min="0" name="sort_order" value="<?= count($contentItems)+1 ?>" required></div><button class="btn btn-primary btn-sm" type="submit">إضافة المحتوى</button></div>
              </form>
            </details>
            <div class="admin-knowledge-editor-grid mt-24">
              <?php foreach($contentItems as $c): ?>
                <form method="post" class="admin-knowledge-editor-card" data-admin-news-events-form><input type="hidden" name="csrf" value="<?= htmlspecialchars(auth_csrf_token()) ?>"><input type="hidden" name="id" value="<?= htmlspecialchars($c['id']) ?>">
                  <div class="admin-knowledge-card-head"><span class="badge"><?= htmlspecialchars($c['id']) ?></span><label class="auth-check"><input type="checkbox" name="is_featured" value="1" <?= !empty($c['is_featured'])?'checked':'' ?>><span>مميّز</span></label></div>
                  <div class="admin-knowledge-fields"><div class="field"><label class="label">العنوان</label><input class="input" name="title" value="<?= htmlspecialchars($c['title']) ?>" required></div><div class="field"><label class="label">النوع</label><select class="select" name="content_type"><?php foreach($contentTypes as $value=>$label): ?><option value="<?= $value ?>" <?= $c['content_type']===$value?'selected':'' ?>><?= $label ?></option><?php endforeach; ?></select></div><div class="field"><label class="label">التسمية الظاهرة</label><input class="input" name="category_label" value="<?= htmlspecialchars($c['category_label']) ?>" required></div><div class="field"><label class="label">تاريخ النشر</label><input class="input" type="datetime-local" name="published_at" value="<?= htmlspecialchars(date('Y-m-d\TH:i',strtotime((string)$c['published_at']))) ?>" required></div><div class="field"><label class="label">مدة القراءة</label><input class="input" name="reading_time" value="<?= htmlspecialchars($c['reading_time']) ?>"></div><div class="field"><label class="label">الحالة</label><select class="select" name="status"><?php foreach($contentStatuses as $value=>$label): ?><option value="<?= $value ?>" <?= $c['status']===$value?'selected':'' ?>><?= $label ?></option><?php endforeach; ?></select></div></div>
                  <div class="field"><label class="label">الملخص</label><textarea class="textarea" name="excerpt" required><?= htmlspecialchars($c['excerpt']) ?></textarea></div>
                  <div class="admin-knowledge-fields"><div class="field"><label class="label">صورة الغلاف</label><input class="input ltr-input" name="cover_image" value="<?= htmlspecialchars($c['cover_image']) ?>"></div><div class="field"><label class="label">رابط القراءة</label><input class="input ltr-input" type="url" name="external_url" value="<?= htmlspecialchars($c['external_url']) ?>"></div><div class="field admin-knowledge-order"><label class="label">الترتيب</label><input class="input" type="number" min="0" name="sort_order" value="<?= (int)$c['sort_order'] ?>" required></div></div>
                  <div class="admin-sector-editor-actions"><button class="btn btn-primary btn-sm" type="submit" name="action" value="update_news_item">حفظ التعديلات</button><button class="btn btn-sm admin-delete-button" type="submit" name="action" value="delete_news_item" formnovalidate onclick="return confirm('هل أنت متأكد من حذف هذا المحتوى؟')">حذف المحتوى</button></div>
                </form>
              <?php endforeach; ?>
              <?php if(!$contentItems): ?><div class="empty-state"><b>لا توجد بيانات فعلية في هذا القسم بعد</b><p>ستظهر الأخبار والمقالات هنا بعد إضافتها.</p></div><?php endif; ?>
            </div>
          </div>
        </div>
      <?php elseif ($sectionKey === 'stories'): ?>
        <?php $storyCategories=['fintech'=>'تقنية مالية','health'=>'صحة رقمية','logistics'=>'لوجستيات','ai'=>'ذكاء اصطناعي','saas'=>'SaaS','other'=>'أخرى']; ?>
        <div class="panel admin-stories-manager">
          <div class="panel-head"><div><h3>إدارة قصص النجاح</h3><p class="hint">الإضافة والتعديل والحذف تتم عبر API، والقصص الظاهرة تُنشر تلقائيًا في الموقع.</p></div><span class="badge"><?= count($successStories) ?> قصة</span></div>
          <div class="panel-body">
            <details class="admin-sector-create admin-story-create">
              <summary><span>إضافة قصة نجاح</span><small>أدخل بيانات موثقة فقط</small></summary>
              <form method="post" class="admin-sector-create-form admin-story-form"><input type="hidden" name="csrf" value="<?= htmlspecialchars(auth_csrf_token()) ?>"><input type="hidden" name="action" value="create_success_story">
                <div class="admin-story-fields"><div class="field"><label class="label">القطاع</label><input class="input" name="sector_label" required></div><div class="field"><label class="label">التصنيف</label><select class="select" name="category_key"><?php foreach($storyCategories as $key=>$label): ?><option value="<?= $key ?>"><?= $label ?></option><?php endforeach; ?></select></div><div class="field"><label class="label">العنوان</label><input class="input" name="title" required></div><div class="field"><label class="label">المدة</label><input class="input" name="duration" placeholder="9 أسابيع" required></div></div>
                <div class="field"><label class="label">المشكلة</label><textarea class="textarea" name="problem_text" required></textarea></div><div class="field"><label class="label">الحل</label><textarea class="textarea" name="solution_text" required></textarea></div>
                <div class="admin-story-metrics"><?php for($i=1;$i<=3;$i++): ?><div><input class="input ltr-input" name="metric_<?= $i ?>_value" placeholder="القيمة" required><input class="input" name="metric_<?= $i ?>_label" placeholder="اسم المؤشر" required></div><?php endfor; ?></div>
                <div class="admin-story-footer"><label class="auth-check"><input type="checkbox" name="is_active" value="1" checked><span>نشر فورًا</span></label><input class="input" type="number" min="0" name="sort_order" value="<?= count($successStories)+1 ?>" aria-label="الترتيب" required><button class="btn btn-primary btn-sm" type="submit">إضافة ونشر</button></div>
              </form>
            </details>
            <div class="admin-story-editor-grid mt-24">
              <?php foreach($successStories as $story): $storyMetrics=json_decode((string)$story['metrics_json'],true); ?>
                <form method="post" class="admin-story-editor-card"><input type="hidden" name="csrf" value="<?= htmlspecialchars(auth_csrf_token()) ?>"><input type="hidden" name="id" value="<?= htmlspecialchars($story['id']) ?>">
                  <div class="admin-story-card-head"><span class="badge"><?= htmlspecialchars($story['id']) ?></span><label class="auth-check"><input type="checkbox" name="is_active" value="1" <?= !empty($story['is_active'])?'checked':'' ?>><span>ظاهرة</span></label></div>
                  <div class="admin-story-fields"><div class="field"><label class="label">القطاع</label><input class="input" name="sector_label" value="<?= htmlspecialchars($story['sector_label']) ?>" required></div><div class="field"><label class="label">التصنيف</label><select class="select" name="category_key"><?php foreach($storyCategories as $key=>$label): ?><option value="<?= $key ?>" <?= $story['category_key']===$key?'selected':'' ?>><?= $label ?></option><?php endforeach; ?></select></div><div class="field"><label class="label">العنوان</label><input class="input" name="title" value="<?= htmlspecialchars($story['title']) ?>" required></div><div class="field"><label class="label">المدة</label><input class="input" name="duration" value="<?= htmlspecialchars($story['duration']) ?>" required></div></div>
                  <div class="field"><label class="label">المشكلة</label><textarea class="textarea" name="problem_text" required><?= htmlspecialchars($story['problem_text']) ?></textarea></div><div class="field"><label class="label">الحل</label><textarea class="textarea" name="solution_text" required><?= htmlspecialchars($story['solution_text']) ?></textarea></div>
                  <div class="admin-story-metrics"><?php for($i=0;$i<3;$i++): $metric=$storyMetrics[$i]??['value'=>'','label'=>'']; ?><div><input class="input ltr-input" name="metric_<?= $i+1 ?>_value" value="<?= htmlspecialchars((string)$metric['value']) ?>" required><input class="input" name="metric_<?= $i+1 ?>_label" value="<?= htmlspecialchars((string)$metric['label']) ?>" required></div><?php endfor; ?></div>
                  <div class="field admin-story-order"><label class="label">الترتيب</label><input class="input" type="number" min="0" name="sort_order" value="<?= (int)$story['sort_order'] ?>" required></div>
                  <div class="admin-sector-editor-actions"><button class="btn btn-primary btn-sm" type="submit" name="action" value="update_success_story">حفظ التعديلات</button><button class="btn btn-sm admin-delete-button" type="submit" name="action" value="delete_success_story" formnovalidate onclick="return confirm('هل أنت متأكد من حذف هذه القصة؟')">حذف القصة</button></div>
                </form>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      <?php elseif ($sectionKey === 'events'): ?>
        <?php $eventStatuses=['draft'=>'مسودة','published'=>'منشورة','completed'=>'مكتملة','cancelled'=>'ملغاة']; ?>
        <div class="panel admin-knowledge-manager" data-admin-news-events-manager>
          <div class="panel-head"><div><h3>إدارة الفعاليات</h3><p class="hint">أضف بيانات التسجيل الحقيقية، ولا يظهر للعامة إلا ما حالته منشورة.</p></div><span class="badge"><?= count($events) ?> فعالية</span></div>
          <div class="panel-body">
            <details class="admin-sector-create admin-knowledge-create"><summary><span>إضافة فعالية</span><small>يمكن نشرها فورًا أو حفظها كمسودة</small></summary>
              <form method="post" class="admin-sector-create-form admin-knowledge-form" data-admin-news-events-form><input type="hidden" name="csrf" value="<?= htmlspecialchars(auth_csrf_token()) ?>"><input type="hidden" name="action" value="create_event">
                <div class="admin-knowledge-fields"><div class="field"><label class="label">اسم الفعالية</label><input class="input" name="title" required></div><div class="field"><label class="label">الموعد</label><input class="input" name="starts_at" type="datetime-local" required></div><div class="field"><label class="label">المكان أو المنصة</label><input class="input" name="location" required></div><div class="field"><label class="label">الحالة</label><select class="select" name="status"><?php foreach($eventStatuses as $value=>$label): ?><option value="<?= $value ?>" <?= $value==='draft'?'selected':'' ?>><?= $label ?></option><?php endforeach; ?></select></div></div>
                <div class="field"><label class="label">الوصف</label><textarea class="textarea" name="description" required></textarea></div>
                <div class="admin-knowledge-fields"><div class="field"><label class="label">السعة</label><input class="input" type="number" min="0" name="capacity"></div><div class="field"><label class="label">عدد المسجلين</label><input class="input" type="number" min="0" name="registered_count" value="0"></div><div class="field"><label class="label">رابط التسجيل</label><input class="input ltr-input" type="url" name="registration_url" placeholder="https://..."></div><div class="field"><label class="label">رابط التقويم</label><input class="input ltr-input" type="url" name="calendar_url" placeholder="https://..."></div><div class="field admin-knowledge-order"><label class="label">الترتيب</label><input class="input" type="number" min="0" name="sort_order" value="<?= count($events)+1 ?>" required></div></div>
                <button class="btn btn-primary btn-sm mt-16" type="submit">إضافة الفعالية</button>
              </form>
            </details>
            <div class="admin-knowledge-editor-grid mt-24">
              <?php foreach($events as $e): ?>
                <form method="post" class="admin-knowledge-editor-card" data-admin-news-events-form><input type="hidden" name="csrf" value="<?= htmlspecialchars(auth_csrf_token()) ?>"><input type="hidden" name="id" value="<?= htmlspecialchars($e['id']) ?>">
                  <div class="admin-knowledge-card-head"><span class="badge"><?= htmlspecialchars($e['id']) ?></span><span class="badge <?= $e['status']==='published'?'badge-success':'' ?>"><?= htmlspecialchars($eventStatuses[$e['status']]??$e['status']) ?></span></div>
                  <div class="admin-knowledge-fields"><div class="field"><label class="label">اسم الفعالية</label><input class="input" name="title" value="<?= htmlspecialchars($e['title']) ?>" required></div><div class="field"><label class="label">الموعد</label><input class="input" name="starts_at" type="datetime-local" value="<?= htmlspecialchars(date('Y-m-d\TH:i',strtotime((string)$e['starts_at']))) ?>" required></div><div class="field"><label class="label">المكان أو المنصة</label><input class="input" name="location" value="<?= htmlspecialchars($e['location']) ?>" required></div><div class="field"><label class="label">الحالة</label><select class="select" name="status"><?php foreach($eventStatuses as $value=>$label): ?><option value="<?= $value ?>" <?= $e['status']===$value?'selected':'' ?>><?= $label ?></option><?php endforeach; ?></select></div></div>
                  <div class="field"><label class="label">الوصف</label><textarea class="textarea" name="description" required><?= htmlspecialchars($e['description']) ?></textarea></div>
                  <div class="admin-knowledge-fields"><div class="field"><label class="label">السعة</label><input class="input" type="number" min="0" name="capacity" value="<?= $e['capacity']===null?'':(int)$e['capacity'] ?>"></div><div class="field"><label class="label">عدد المسجلين</label><input class="input" type="number" min="0" name="registered_count" value="<?= (int)$e['registered_count'] ?>"></div><div class="field"><label class="label">رابط التسجيل</label><input class="input ltr-input" type="url" name="registration_url" value="<?= htmlspecialchars($e['registration_url']) ?>"></div><div class="field"><label class="label">رابط التقويم</label><input class="input ltr-input" type="url" name="calendar_url" value="<?= htmlspecialchars($e['calendar_url']) ?>"></div><div class="field admin-knowledge-order"><label class="label">الترتيب</label><input class="input" type="number" min="0" name="sort_order" value="<?= (int)$e['sort_order'] ?>" required></div></div>
                  <div class="admin-sector-editor-actions"><button class="btn btn-primary btn-sm" type="submit" name="action" value="update_event">حفظ التعديلات</button><button class="btn btn-sm admin-delete-button" type="submit" name="action" value="delete_event" formnovalidate onclick="return confirm('هل أنت متأكد من حذف هذه الفعالية؟')">حذف الفعالية</button></div>
                </form>
              <?php endforeach; ?>
              <?php if(!$events): ?><div class="empty-state"><b>لا توجد بيانات فعلية في هذا القسم بعد</b><p>ستظهر الفعاليات هنا بعد إضافتها.</p></div><?php endif; ?>
            </div>
          </div>
        </div>
      <?php elseif ($sectionKey === 'roles'): ?>
        <div class="panel reveal"><div class="panel-head"><h3>الأدوار الفعلية</h3><span class="badge">3 أدوار</span></div><div class="panel-body"><?php foreach(['admin'=>['إدارة','الوصول لكل أقسام الإدارة'],'investor'=>['مستثمر','لوحة المستثمر وبياناته فقط'],'entrepreneur'=>['رائد أعمال','لوحة رائد الأعمال وبياناته فقط']] as $role=>$meta): $roleCount=count(array_filter($users,fn($u)=>$u['role']===$role)); ?><div class="support-ticket"><div><b><?= $meta[0] ?></b><p class="text-2"><?= $meta[1] ?></p></div><span class="badge"><?= $roleCount ?> مستخدم</span></div><?php endforeach; ?><p class="hint mt-16">يمكن تغيير دور أي مستخدم من صفحة المستخدمين، ويُسجل الإجراء في سجل التدقيق.</p></div></div>
      <?php elseif ($sectionKey === 'audit'): ?>
        <div class="panel reveal">
          <div class="panel-head"><h3>سجل الإجراءات الإدارية</h3><span class="badge"><?= count($auditRows) ?> إجراء</span></div>
          <div class="panel-body">
            <?php foreach($auditRows as $event): ?>
              <div class="feed-item"><div class="avatar sm"><?= htmlspecialchars(auth_initials((string)($event['admin_name'] ?: 'إدارة'))) ?></div><div><div class="ft"><b><?= htmlspecialchars((string)($event['admin_name'] ?: 'حساب إداري محذوف')) ?></b> · <?= htmlspecialchars($event['action']) ?> · <?= htmlspecialchars($event['entity_type']) ?> <?= htmlspecialchars($event['entity_id']) ?></div><div class="fm"><?= htmlspecialchars($event['details']) ?> · <?= htmlspecialchars($event['created_at']) ?></div></div></div>
            <?php endforeach; ?>
            <?php if(!$auditRows): ?><div class="empty-state"><b>لا توجد إجراءات إدارية مسجلة بعد</b><p>سيظهر كل تغيير فعلي هنا.</p></div><?php endif; ?>
          </div>
        </div>
      <?php else: ?>
        <div class="dashboard-main-grid">
          <div class="panel reveal">
            <div class="panel-head"><h3>بيانات حساب الإدارة</h3><span class="badge badge-success">نشط</span></div>
            <div class="panel-body">
              <form method="post" action="admin-settings.php">
                <input type="hidden" name="action" value="update_admin_profile"><input type="hidden" name="csrf" value="<?= htmlspecialchars(auth_csrf_token()) ?>">
                <div class="field"><label class="label" for="admin-name">الاسم</label><input class="input" id="admin-name" name="name" value="<?= $authName ?>" required autocomplete="name"></div>
                <div class="field mt-16"><label class="label" for="admin-email">البريد الإلكتروني</label><input class="input ltr-input" id="admin-email" name="email" type="email" value="<?= $authEmail ?>" required autocomplete="email"></div>
                <div class="field mt-16"><label class="label" for="admin-country">الدولة</label>
                  <select class="select" id="admin-country" name="country" required>
                    <?php foreach (['مصر','السعودية','الإمارات','قطر','الكويت','جنوب أفريقيا'] as $country): ?>
                      <option value="<?= htmlspecialchars($country) ?>" <?= $authCountry === $country ? 'selected' : '' ?>><?= htmlspecialchars($country) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <button class="btn btn-primary btn-sm mt-16" type="submit">حفظ بيانات الحساب</button>
              </form>
            </div>
          </div>
          <div class="panel reveal">
            <div class="panel-head"><h3>بيانات النظام الفعلية</h3></div>
            <div class="panel-body">
              <div class="support-ticket"><div><b>المستخدمون</b><p class="text-2">إجمالي الحسابات المسجلة في النظام.</p></div><span class="badge"><?= count($users) ?></span></div>
              <div class="support-ticket"><div><b>مراجعات KYC/AML</b><p class="text-2">حسابات مستثمرين بانتظار القرار.</p></div><span class="badge badge-warning"><?= count($pendingInvestors) ?></span></div>
              <div class="support-ticket"><div><b>كلمات المرور</b><p class="text-2">تُخزن بصورة مشفرة ولا يمكن للإدارة قراءتها.</p></div><span class="badge badge-success">محمية</span></div>
              <form method="post" class="mt-24">
                <input type="hidden" name="action" value="update_admin_password"><input type="hidden" name="csrf" value="<?= htmlspecialchars(auth_csrf_token()) ?>">
                <h4>تغيير كلمة المرور</h4>
                <input class="input mt-16" type="password" name="current_password" placeholder="كلمة المرور الحالية" autocomplete="current-password" required>
                <input class="input mt-16" type="password" name="new_password" placeholder="كلمة المرور الجديدة" autocomplete="new-password" required>
                <input class="input mt-16" type="password" name="confirm_password" placeholder="تأكيد كلمة المرور" autocomplete="new-password" required>
                <button class="btn btn-primary btn-sm mt-16" type="submit">تغيير كلمة المرور</button>
              </form>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<div class="scrim" onclick="closeOverlays()"></div>
<script src="../assets/js/app.js"></script>
<?php if ($sectionKey === 'stories'): ?><script src="../assets/js/admin-success-stories.js?v=20260804" defer></script><?php endif; ?>
<?php if (in_array($sectionKey, ['content','events'], true)): ?><script src="../assets/js/admin-news-events.js?v=20260804" defer></script><?php endif; ?>
<?php if ($sectionKey === 'about'): ?><script src="../assets/js/admin-about.js?v=20260804" defer></script><?php endif; ?>
<?php if ($sectionKey === 'investors-page'): ?><script src="../assets/js/admin-investors-page.js?v=20260804" defer></script><?php endif; ?>
<?php if ($sectionKey === 'entrepreneurs-page'): ?><script src="../assets/js/admin-entrepreneurs-page.js?v=20260804" defer></script><?php endif; ?>
</body></html>
