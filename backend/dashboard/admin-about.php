<?php
require_once __DIR__.'/../lib/about.php';
try { about_db(); }
catch (Throwable $error) {
    error_log('About dashboard unavailable: '.$error->getMessage());
    http_response_code(503);
    echo '<!doctype html><html lang="ar" dir="rtl"><meta charset="utf-8"><title>الخدمة غير متاحة</title><h1>قاعدة البيانات غير متاحة</h1><p>شغّل MySQL في MAMP ثم أعد المحاولة. لم يتم تغيير المحتوى.</p><a href="admin-about.php">إعادة المحاولة</a></html>';
    exit;
}
$sectionKey='about'; include __DIR__.'/admin-shell.php';
