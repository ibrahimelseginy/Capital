<?php
declare(strict_types=1);

require_once __DIR__ . '/../lib/database.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, max-age=0');
header('X-Content-Type-Options: nosniff');
header('Access-Control-Allow-Origin: *'); // Public GET-only content, without credentials.

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
    http_response_code(405);
    header('Allow: GET');
    echo json_encode(['ok' => false, 'message' => 'هذه الطريقة غير مسموحة.'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $database = app_db();
    if (!$database instanceof PDO) {
        throw new RuntimeException('Database unavailable');
    }

    $statement = $database->query(
        "SELECT id,title,sector,stage,target_amount,currency,created_at
         FROM opportunities
         WHERE status = 'available'
         ORDER BY created_at DESC, id DESC
         LIMIT 100"
    );
    $opportunities = array_map(static function (array $row): array {
        return [
            'id' => (string) $row['id'],
            'title' => (string) $row['title'],
            'sector' => (string) $row['sector'],
            'stage' => (string) $row['stage'],
            'target_amount' => (float) $row['target_amount'],
            'currency' => (string) $row['currency'],
            'created_at' => (string) $row['created_at'],
        ];
    }, $statement->fetchAll());

    echo json_encode([
        'ok' => true,
        'data' => $opportunities,
        'meta' => ['count' => count($opportunities), 'generated_at' => gmdate(DATE_ATOM)],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $error) {
    error_log('Public opportunities API failed: ' . $error->getMessage());
    http_response_code(503);
    echo json_encode(['ok' => false, 'message' => 'تعذر تحميل الفرص حاليًا.'], JSON_UNESCAPED_UNICODE);
}
