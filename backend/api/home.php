<?php
declare(strict_types=1);
require_once __DIR__.'/../lib/home-render.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');
// Public, read-only content. No cookies or admin writes are allowed cross-origin.
header('Access-Control-Allow-Origin: *');
if (($_SERVER['REQUEST_METHOD']??'GET')!=='GET') {
    http_response_code(405); header('Allow: GET'); echo json_encode(['ok'=>false]); exit;
}
try {
    $data=home_public_content(home_read());
    echo json_encode(['ok'=>true,'data'=>$data,'html'=>home_render($data),'version'=>hash('sha256',json_encode($data))],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_THROW_ON_ERROR);
} catch (Throwable $error) {
    error_log('Home API: '.$error->getMessage()); http_response_code(503);
    echo json_encode(['ok'=>false,'message'=>'تعذر تحميل الصفحة الرئيسية. تحقق من تشغيل قاعدة البيانات.'],JSON_UNESCAPED_UNICODE);
}
