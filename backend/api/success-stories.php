<?php
declare(strict_types=1);

require_once __DIR__ . '/../lib/admin.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, max-age=0');
header('X-Content-Type-Options: nosniff');

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
    http_response_code(405);
    header('Allow: GET');
    echo json_encode(['ok' => false, 'message' => 'هذه الطريقة غير مسموحة.'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $rows = admin_db()->query(
        'SELECT id,sector_label,category_key,title,problem_text,solution_text,duration,metrics_json,sort_order
         FROM success_stories WHERE is_active=1 ORDER BY sort_order,created_at'
    )->fetchAll();

    $filters = [];
    $data = array_map(static function (array $row) use (&$filters): array {
        $metrics = json_decode((string) $row['metrics_json'], true);
        $category = (string) $row['category_key'];
        if (!isset($filters[$category])) {
            $filters[$category] = (string) $row['sector_label'];
        }
        return [
            'id' => (string) $row['id'],
            'sector_label' => (string) $row['sector_label'],
            'category_key' => $category,
            'title' => (string) $row['title'],
            'problem' => (string) $row['problem_text'],
            'solution' => (string) $row['solution_text'],
            'duration' => (string) $row['duration'],
            'metrics' => is_array($metrics) ? array_values($metrics) : [],
            'sort_order' => (int) $row['sort_order'],
        ];
    }, $rows);

    $filterData = [];
    foreach ($filters as $key => $label) {
        $filterData[] = ['key' => $key, 'label' => $label];
    }

    echo json_encode([
        'ok' => true,
        'data' => $data,
        'filters' => $filterData,
        'meta' => ['count' => count($data), 'generated_at' => gmdate(DATE_ATOM)],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $error) {
    error_log('Success stories API failed: ' . $error->getMessage());
    http_response_code(503);
    echo json_encode(['ok' => false, 'message' => 'تعذر تحميل قصص النجاح.'], JSON_UNESCAPED_UNICODE);
}
