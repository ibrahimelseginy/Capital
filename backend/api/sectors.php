<?php
declare(strict_types=1);

require_once __DIR__ . '/../lib/admin.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, max-age=0');
header('X-Content-Type-Options: nosniff');

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
    http_response_code(405); header('Allow: GET');
    echo json_encode(['ok'=>false,'message'=>'هذه الطريقة غير مسموحة.'],JSON_UNESCAPED_UNICODE); exit;
}

try {
    $database=admin_db();
    $settings=[];
    foreach($database->query("SELECT setting_key,setting_value FROM site_settings WHERE setting_key IN ('sectors_eyebrow','sectors_title','sectors_description')") as $row) $settings[$row['setting_key']]=$row['setting_value'];
    $rows=$database->query('SELECT code,name,description,tags_json,icon_key,sort_order FROM sector_map WHERE is_active=1 ORDER BY sort_order,code')->fetchAll();
    $data=array_map(static function(array $row):array {
        $tags=json_decode((string)$row['tags_json'],true);
        return ['code'=>(string)$row['code'],'name'=>(string)$row['name'],'description'=>(string)$row['description'],'tags'=>is_array($tags)?array_values($tags):[],'icon_key'=>(string)$row['icon_key'],'sort_order'=>(int)$row['sort_order']];
    },$rows);
    echo json_encode(['ok'=>true,'intro'=>['eyebrow'=>$settings['sectors_eyebrow']??'','title'=>$settings['sectors_title']??'','description'=>$settings['sectors_description']??''],'data'=>$data,'meta'=>['count'=>count($data),'generated_at'=>gmdate(DATE_ATOM)]],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
} catch(Throwable $error) {
    error_log('Sectors API failed: '.$error->getMessage()); http_response_code(503);
    echo json_encode(['ok'=>false,'message'=>'تعذر تحميل خريطة الفرص.'],JSON_UNESCAPED_UNICODE);
}
