<?php
declare(strict_types=1);require_once __DIR__.'/../lib/seven-tech-sections.php';header('Content-Type: application/json; charset=utf-8');header('Cache-Control: no-store');header('X-Content-Type-Options: nosniff');header('Access-Control-Allow-Origin: *');
if(($_SERVER['REQUEST_METHOD']??'GET')!=='GET'){http_response_code(405);header('Allow: GET');echo json_encode(['ok'=>false]);exit;}
try{echo json_encode(seven_tech_public_payload(seven_tech_read()),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_THROW_ON_ERROR);}catch(Throwable $error){error_log('Seven Tech API: '.$error->getMessage());http_response_code(503);echo json_encode(['ok'=>false,'message'=>'تعذر تحميل صفحة Seven Tech.'],JSON_UNESCAPED_UNICODE);}
