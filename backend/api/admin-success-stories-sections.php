<?php
declare(strict_types=1);
require_once __DIR__.'/../lib/success-stories-sections.php';
header('Content-Type: application/json; charset=utf-8');header('Cache-Control: no-store');header('X-Content-Type-Options: nosniff');
try{
    success_stories_sections_db();auth_boot();$user=isset($_SESSION['user_id'])?auth_find_user_by_id((string)$_SESSION['user_id']):null;
    if(!$user){http_response_code(401);echo json_encode(['ok'=>false,'message'=>'يجب تسجيل الدخول أولًا.'],JSON_UNESCAPED_UNICODE);exit;}
    if($user['role']!=='admin'||($_SESSION['role']??'')!=='admin'){http_response_code(403);echo json_encode(['ok'=>false,'message'=>'صلاحية الإدارة مطلوبة.'],JSON_UNESCAPED_UNICODE);exit;}
    $method=$_SERVER['REQUEST_METHOD']??'GET';if($method==='GET'){echo json_encode(['ok'=>true,'data'=>success_stories_sections_read()],JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR);exit;}
    if($method!=='POST'){http_response_code(405);header('Allow: GET, POST');echo json_encode(['ok'=>false]);exit;}
    if(!auth_verify_csrf($_POST['csrf']??null)){http_response_code(419);echo json_encode(['ok'=>false,'message'=>'انتهت صلاحية الطلب. حدّث الصفحة.'],JSON_UNESCAPED_UNICODE);exit;}
    $raw=$_POST['content']??null;if(!is_string($raw)||strlen($raw)>200000)throw new InvalidArgumentException('حجم المحتوى غير صالح.');
    try{$content=json_decode($raw,true,64,JSON_THROW_ON_ERROR);}catch(JsonException){throw new InvalidArgumentException('صيغة المحتوى غير صالحة.');}
    if(!is_array($content))throw new InvalidArgumentException('المحتوى غير صالح.');
    $key=is_string($_POST['section']??null)?$_POST['section']:'';$revision=filter_var($_POST['revision']??null,FILTER_VALIDATE_INT);
    if($revision===false||$revision<1)throw new InvalidArgumentException('إصدار الحفظ غير صالح.');
    $next=success_stories_sections_save($key,$content,$revision,(string)$user['id']);
    echo json_encode(['ok'=>true,'revision'=>$next,'message'=>'تم حفظ القسم ونشره على صفحة قصص النجاح.'],JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR);
}catch(InvalidArgumentException $error){http_response_code(422);echo json_encode(['ok'=>false,'message'=>$error->getMessage()],JSON_UNESCAPED_UNICODE);}
catch(Throwable $error){error_log('Success stories sections: '.$error->getMessage());$conflict=$error->getCode()===409;http_response_code($conflict?409:503);echo json_encode(['ok'=>false,'message'=>$conflict?$error->getMessage():'تعذر الحفظ. تحقق من تشغيل MySQL في MAMP. لم يتم نشر التغييرات.'],JSON_UNESCAPED_UNICODE);}

