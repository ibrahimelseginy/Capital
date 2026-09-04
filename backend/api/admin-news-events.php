<?php
declare(strict_types=1);

require_once __DIR__ . '/../lib/admin.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, max-age=0');
header('X-Content-Type-Options: nosniff');

auth_boot();
$user=!empty($_SESSION['user_id'])?auth_find_user_by_id((string)$_SESSION['user_id']):null;
if(!$user){http_response_code(401);echo json_encode(['ok'=>false,'message'=>'يجب تسجيل الدخول أولًا.'],JSON_UNESCAPED_UNICODE);exit;}
if(($user['role']??'')!=='admin'||($_SESSION['role']??'')!=='admin'){http_response_code(403);echo json_encode(['ok'=>false,'message'=>'لا تملك صلاحية تنفيذ هذا الإجراء.'],JSON_UNESCAPED_UNICODE);exit;}

$method=$_SERVER['REQUEST_METHOD']??'GET';
if($method==='GET'){
    try{
        $content=admin_rows('SELECT id,title,content_type,category_label,excerpt,reading_time,cover_image,external_url,is_featured,sort_order,status,published_at,created_at,updated_at FROM content_items ORDER BY sort_order,published_at DESC');
        $events=admin_rows('SELECT id,title,starts_at,location,description,capacity,registered_count,registration_url,calendar_url,sort_order,status,created_at,updated_at FROM events ORDER BY sort_order,starts_at');
        foreach($content as &$item){$item['is_featured']=(bool)$item['is_featured'];$item['sort_order']=(int)$item['sort_order'];}unset($item);
        foreach($events as &$event){$event['capacity']=$event['capacity']===null?null:(int)$event['capacity'];$event['registered_count']=(int)$event['registered_count'];$event['sort_order']=(int)$event['sort_order'];}unset($event);
        echo json_encode(['ok'=>true,'content'=>$content,'events'=>$events],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }catch(Throwable $error){error_log('Admin news and events API read failed: '.$error->getMessage());http_response_code(503);echo json_encode(['ok'=>false,'message'=>'تعذر تحميل البيانات.'],JSON_UNESCAPED_UNICODE);}
    exit;
}
if($method!=='POST'){http_response_code(405);header('Allow: GET, POST');echo json_encode(['ok'=>false,'message'=>'هذه الطريقة غير مسموحة.'],JSON_UNESCAPED_UNICODE);exit;}
if(!auth_verify_csrf($_POST['csrf']??null)){http_response_code(419);echo json_encode(['ok'=>false,'message'=>'انتهت صلاحية الطلب. حدّث الصفحة وحاول مرة أخرى.'],JSON_UNESCAPED_UNICODE);exit;}
$action=(string)($_POST['action']??'');
$allowed=['create_news_item','update_news_item','delete_news_item','create_event','update_event','delete_event'];
if(!in_array($action,$allowed,true)){http_response_code(400);echo json_encode(['ok'=>false,'message'=>'الإجراء المطلوب غير صحيح.'],JSON_UNESCAPED_UNICODE);exit;}
try{
    [$changed,$message]=admin_handle_action($action,$_POST);
    if(!$changed)http_response_code(422);
    echo json_encode(['ok'=>$changed,'message'=>$message,'action'=>$action],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
}catch(Throwable $error){error_log('Admin news and events API write failed: '.$error->getMessage());http_response_code(500);echo json_encode(['ok'=>false,'message'=>'تعذر تنفيذ الطلب. تحقق من البيانات وحاول مرة أخرى.'],JSON_UNESCAPED_UNICODE);}
