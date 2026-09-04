<?php
declare(strict_types=1);
require_once __DIR__.'/../lib/success-stories-sections.php';
header('Content-Type: application/json; charset=utf-8');header('Cache-Control: no-store, max-age=0');header('X-Content-Type-Options: nosniff');header('Access-Control-Allow-Origin: *');
if(($_SERVER['REQUEST_METHOD']??'GET')!=='GET'){http_response_code(405);header('Allow: GET');echo json_encode(['ok'=>false,'message'=>'هذه الطريقة غير مسموحة.'],JSON_UNESCAPED_UNICODE);exit;}
try{echo json_encode(success_stories_public_payload(success_stories_sections_read()),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_THROW_ON_ERROR);}
catch(Throwable $error){error_log('Success stories page API failed: '.$error->getMessage());http_response_code(503);echo json_encode(['ok'=>false,'message'=>'تعذر تحميل قصص النجاح.'],JSON_UNESCAPED_UNICODE);}

