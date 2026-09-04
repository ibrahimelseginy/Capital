<?php
declare(strict_types=1);
require_once __DIR__ . '/../lib/admin.php';
header('Content-Type: application/json; charset=utf-8');header('Cache-Control: no-store, max-age=0');header('X-Content-Type-Options: nosn');
auth_boot();$user=!empty($_SESSION['user_id'])?auth_find_user_by_id((string)$_SESSION['user_id']):null;
if(!$user){http_response_code(401);echo json_encode(['ok'=>false,'message'=>'يجب تسجيل الدخول أولًا.'],JSON_UNESCAPED_UNICODE);exit;}
if(($user['role']??'')!=='admin'||($_SESSION['role']??'')!=='admin'){http_response_code(403);echo json_encode(['ok'=>false,'message'=>'لا تملك صلاحية تنفيذ هذا الإجراء.'],JSON_UNESCAPED_UNICODE);exit;}
$method=$_SERVER['REQUEST_METHOD']??'GET';
if($method==='GET'){try{$rows=admin_rows('SELECT * FROM entrepreneur_page_items ORDER BY section_key,sort_order,created_at');foreach($rows as &$row){$row['sort_order']=(int)$row['sort_order'];$row['is_active']=(bool)$row['is_active'];}unset($row);echo json_encode(['ok'=>true,'data'=>$rows,'meta'=>['count'=>count($rows)]],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);}catch(Throwable $error){error_log('Admin entrepreneurs page API read failed: '.$error->getMessage());http_response_code(503);echo json_encode(['ok'=>false,'message'=>'تعذر تحميل بيانات صفحة رواد الأعمال.'],JSON_UNESCAPED_UNICODE);}exit;}
if($method!=='POST'){http_response_code(405);header('Allow: GET, POST');echo json_encode(['ok'=>false,'message'=>'هذه الطريقة غير مسموحة.'],JSON_UNESCAPED_UNICODE);exit;}
if(!auth_verify_csrf($_POST['csrf']??null)){http_response_code(419);echo json_encode(['ok'=>false,'message'=>'انتهت صلاحية الطلب. حدّث الصفحة وحاول مرة أخرى.'],JSON_UNESCAPED_UNICODE);exit;}
$action=(string)($_POST['action']??'');if(!in_array($action,['create_entrepreneur_page_item','update_entrepreneur_page_item','delete_entrepreneur_page_item'],true)){http_response_code(400);echo json_encode(['ok'=>false,'message'=>'الإجراء المطلوب غير صحيح.'],JSON_UNESCAPED_UNICODE);exit;}
try{[$changed,$message]=admin_handle_action($action,$_POST);if(!$changed)http_response_code(422);echo json_encode(['ok'=>$changed,'message'=>$message,'action'=>$action],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);}
catch(Throwable $error){error_log('Admin entrepreneurs page API write failed: '.$error->getMessage());http_response_code(500);echo json_encode(['ok'=>false,'message'=>'تعذر تنفيذ الطلب. تحقق من البيانات وحاول مرة أخرى.'],JSON_UNESCAPED_UNICODE);}
