<?php
declare(strict_types=1);

require_once __DIR__ . '/../lib/admin.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, max-age=0');
header('X-Content-Type-Options: nosniff');

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
    http_response_code(405);
    header('Allow: GET');
    echo json_encode(['ok'=>false,'message'=>'هذه الطريقة غير مسموحة.'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $database=admin_db();
    $contentRows=$database->query("SELECT id,title,content_type,category_label,excerpt,reading_time,cover_image,external_url,is_featured,sort_order,published_at FROM content_items WHERE status='published' ORDER BY is_featured DESC,sort_order,published_at DESC")->fetchAll();
    $eventRows=$database->query("SELECT id,title,starts_at,location,description,capacity,registered_count,registration_url,calendar_url,sort_order FROM events WHERE status='published' ORDER BY sort_order,starts_at")->fetchAll();
    $content=array_map(static fn(array $row):array => [
        'id'=>(string)$row['id'],'title'=>(string)$row['title'],'content_type'=>(string)$row['content_type'],
        'category_label'=>(string)$row['category_label'],'excerpt'=>(string)$row['excerpt'],'reading_time'=>(string)$row['reading_time'],
        'cover_image'=>(string)$row['cover_image'],'external_url'=>(string)$row['external_url'],'is_featured'=>(bool)$row['is_featured'],
        'sort_order'=>(int)$row['sort_order'],'published_at'=>str_replace(' ','T',(string)$row['published_at']),
    ],$contentRows);
    $events=array_map(static fn(array $row):array => [
        'id'=>(string)$row['id'],'title'=>(string)$row['title'],'starts_at'=>str_replace(' ','T',(string)$row['starts_at']),
        'location'=>(string)$row['location'],'description'=>(string)$row['description'],
        'capacity'=>$row['capacity']===null?null:(int)$row['capacity'],'registered_count'=>(int)$row['registered_count'],
        'registration_url'=>(string)$row['registration_url'],'calendar_url'=>(string)$row['calendar_url'],'sort_order'=>(int)$row['sort_order'],
    ],$eventRows);
    echo json_encode(['ok'=>true,'content'=>$content,'events'=>$events,'meta'=>['content_count'=>count($content),'event_count'=>count($events),'generated_at'=>gmdate(DATE_ATOM)]],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
} catch(Throwable $error) {
    error_log('News and events API failed: '.$error->getMessage());
    http_response_code(503);
    echo json_encode(['ok'=>false,'message'=>'تعذر تحميل الأخبار والفعاليات.'],JSON_UNESCAPED_UNICODE);
}
