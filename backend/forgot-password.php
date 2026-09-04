<?php
require_once __DIR__ . '/lib/auth.php';
auth_boot();

$error = '';
$sent = false;
$developmentResetUrl = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!auth_verify_csrf($_POST['csrf'] ?? null)) {
    $error = 'انتهت صلاحية الطلب. حدّث الصفحة وحاول مرة أخرى.';
  } else {
    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error = 'يرجى إدخال بريد إلكتروني صحيح.';
    } elseif (!empty($_SESSION['last_reset_request']) && time() - (int) $_SESSION['last_reset_request'] < 30) {
      $error = 'انتظر قليلًا قبل طلب رابط جديد.';
    } else {
      $_SESSION['last_reset_request'] = time();
      $token = auth_create_password_reset($email);
      if ($token !== null) {
        $scriptDirectory = rtrim(str_replace('\\', '/', dirname((string) ($_SERVER['SCRIPT_NAME'] ?? '/'))), '/');
        $path = ($scriptDirectory === '' ? '' : $scriptDirectory) . '/reset-password.php?token=' . urlencode($token);
        $host = (string) ($_SERVER['HTTP_HOST'] ?? '127.0.0.1:8000');
        $safeHost = preg_match('/^(localhost|127\.0\.0\.1)(:\d+)?$/', $host) ? $host : '';
        if ($safeHost !== '') {
          $developmentResetUrl = 'http://' . $safeHost . $path;
        } else {
          $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
          $serverName = preg_replace('/[^A-Za-z0-9.-]/', '', (string) ($_SERVER['SERVER_NAME'] ?? ''));
          $resetUrl = $scheme . '://' . $serverName . $path;
          $subject = 'استعادة كلمة المرور - Seven Tech Capital';
          $message = "استخدم الرابط التالي خلال ساعة واحدة لتعيين كلمة مرور جديدة:\n" . $resetUrl;
          @mail($email, $subject, $message, "Content-Type: text/plain; charset=UTF-8\r\n");
        }
      }
      $sent = true;
    }
  }
}

$base = '';
$title = 'استعادة كلمة المرور';
include 'partials/head.php';
?>
<main class="auth-form-side auth-standalone">
  <div class="auth-card">
    <div class="auth-card-top">
      <a href="login.php" class="auth-back"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M19 12H5M12 5l-7 7 7 7"/></svg> العودة لتسجيل الدخول</a>
    </div>
    <div class="auth-panel">
      <h2>استعادة كلمة المرور</h2>
      <p>أدخل بريد حسابك وسنرسل لك رابطًا صالحًا لمدة ساعة واحدة.</p>
      <?php if ($error): ?><div class="auth-message auth-message-error mt-24" role="alert"><?= htmlspecialchars($error) ?></div><?php endif; ?>
      <?php if ($sent): ?>
        <div class="auth-message auth-message-success mt-24" role="status">إذا كان البريد مسجلًا، تم إنشاء رابط الاستعادة وإرساله.</div>
        <?php if ($developmentResetUrl): ?>
          <a class="btn btn-primary btn-block" href="<?= htmlspecialchars($developmentResetUrl) ?>">فتح رابط الاستعادة محليًا</a>
        <?php endif; ?>
      <?php else: ?>
        <form class="mt-24" method="post">
          <input type="hidden" name="csrf" value="<?= htmlspecialchars(auth_csrf_token()) ?>">
          <div class="field"><label class="label" for="reset-email">البريد الإلكتروني</label><input class="input" id="reset-email" name="email" type="email" autocomplete="email" required placeholder="you@example.com"></div>
          <button class="btn btn-primary btn-block btn-lg" type="submit">إرسال رابط الاستعادة</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</main>
<script src="assets/js/app.js"></script>
</body></html>
