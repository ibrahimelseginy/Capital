<?php
declare(strict_types=1);
require_once __DIR__.'/../lib/about.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, max-age=0');
header('X-Content-Type-Options: nosniff');
header('Access-Control-Allow-Origin: *');
if (($_SERVER['REQUEST_METHOD']??'GET')!=='GET') {
    http_response_code(405); header('Allow: GET'); echo json_encode(['ok'=>false]); exit;
}
try {
    echo json_encode(about_public_payload(about_read()),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_THROW_ON_ERROR);
} catch (Throwable $error) {
    error_log('About public API: '.$error->getMessage());
    http_response_code(503);
    echo json_encode(['ok'=>false,'message'=>'تعذر تحميل صفحة من نحن. تحقق من تشغيل قاعدة البيانات.'],JSON_UNESCAPED_UNICODE);
}
