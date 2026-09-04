<?php
require_once __DIR__ . '/lib/auth.php';
auth_boot();

$token = (string) ($_POST['token'] ?? $_GET['token'] ?? '');
$error = '';
$completed = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!auth_verify_csrf($_POST['csrf'] ?? null)) {
    $error = 'انتهت صلاحية الطلب. حدّث الصفحة وحاول مرة أخرى.';
  } elseif ((string) ($_POST['password'] ?? '') !== (string) ($_POST['password_confirmation'] ?? '')) {
    $error = 'كلمتا المرور غير متطابقتين.';
  } else {
    [$completed, $error] = auth_reset_password($token, (string) ($_POST['password'] ?? ''));
  }
}

$base = '';
$title = 'تعيين كلمة مرور جديدة';
include 'partials/head.php';
?>
<main class="auth-form-side auth-standalone">
  <div class="auth-card">
    <div class="auth-card-top">
      <a href="login.php" class="auth-back"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 12H5M12 5l-7 7 7 7"/></svg> العودة لتسجيل الدخول</a>
    </div>
    <div class="auth-panel">
      <h2>كلمة مرور جديدة</h2>
      <p>استخدم 8 أحرف على الأقل، تشمل حرفًا ورقمًا ورمزًا.</p>
      <?php if ($completed): ?>
        <div class="auth-message auth-message-success mt-24" role="status">تم تحديث كلمة المرور بنجاح. يمكنك تسجيل الدخول الآن.</div>
        <a class="btn btn-primary btn-block" href="login.php">تسجيل الدخول</a>
      <?php else: ?>
        <?php if ($error): ?><div class="auth-message auth-message-error mt-24" role="alert"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form class="mt-24" method="post">
          <input type="hidden" name="csrf" value="<?= htmlspecialchars(auth_csrf_token()) ?>">
          <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
          <div class="field"><label class="label" for="new-password">كلمة المرور الجديدة</label><input class="input" id="new-password" name="password" type="password" autocomplete="new-password" required></div>
          <div class="field"><label class="label" for="confirm-password">تأكيد كلمة المرور</label><input class="input" id="confirm-password" name="password_confirmation" type="password" autocomplete="new-password" required></div>
          <button class="btn btn-primary btn-block btn-lg" type="submit">حفظ كلمة المرور</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</main>
<script src="assets/js/app.js"></script>
</body></html>
