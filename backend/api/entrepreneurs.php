<?php
declare(strict_types=1);
require_once __DIR__ . '/../lib/admin.php';
header('Content-Type: application/json; charset=utf-8');header('Cache-Control: no-store, max-age=0');header('X-Content-Type-Options: nosniff');
if(($_SERVER['REQUEST_METHOD']??'GET')!=='GET'){http_response_code(405);header('Allow: GET');echo json_encode(['ok'=>false,'message'=>'هذه الطريقة غير مسموحة.'],JSON_UNESCAPED_UNICODE);exit;}
try{$rows=admin_rows('SELECT id,section_key,title,subtitle,body,badge_label,badge_style,value_text,value_suffix,icon_key,primary_url,secondary_url,sort_order FROM entrepreneur_page_items WHERE is_active=1 ORDER BY section_key,sort_order,created_at');foreach($rows as &$row)$row['sort_order']=(int)$row['sort_order'];unset($row);echo json_encode(['ok'=>true,'data'=>$rows,'meta'=>['count'=>count($rows),'generated_at'=>gmdate(DATE_ATOM)]],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);}
catch(Throwable $error){error_log('Entrepreneurs page API failed: '.$error->getMessage());http_response_code(503);echo json_encode(['ok'=>false,'message'=>'تعذر تحميل صفحة رواد الأعمال.'],JSON_UNESCAPED_UNICODE);}
