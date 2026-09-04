<?php
require_once __DIR__.'/lib/home-render.php';
$base=''; $title='الرئيسية'; $active='home';
try { $homeContent=home_public_content(home_read()); $homeError=false; }
catch (Throwable $error) { error_log('Home page: '.$error->getMessage()); http_response_code(503); $homeContent=[]; $homeError=true; }
include __DIR__.'/partials/head.php';
include __DIR__.'/partials/nav.php';
?>
<link rel="stylesheet" href="assets/css/home.css?v=1">
<?php if ($homeError): ?><div class="home-load-error" role="status" data-home-load-error>تعذر تحميل الصفحة الرئيسية. تأكد من تشغيل MySQL في MAMP ثم أعد المحاولة.</div><?php endif; ?>
<main id="main" data-home-page data-home-loaded="<?= $homeError?'false':'true' ?>"><?= home_render($homeContent) ?></main>
<script src="assets/js/api-config.js?v=1"></script>
<script src="assets/js/home.js?v=2" defer></script>
<?php include __DIR__.'/partials/footer.php'; ?>
