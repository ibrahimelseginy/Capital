<?php
declare(strict_types=1);

require_once __DIR__ . '/../lib/admin.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, max-age=0');
header('X-Content-Type-Options: nosniff');

auth_boot();
$user = !empty($_SESSION['user_id']) ? auth_find_user_by_id((string) $_SESSION['user_id']) : null;
if (!$user) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'message' => 'يجب تسجيل الدخول أولًا.'], JSON_UNESCAPED_UNICODE);
    exit;
}
if (($user['role'] ?? '') !== 'admin' || ($_SESSION['role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo json_encode(['ok' => false, 'message' => 'لا تملك صلاحية تنفيذ هذا الإجراء.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if ($method === 'GET') {
    try {
        $rows = admin_rows('SELECT id,sector_label,category_key,title,problem_text,solution_text,duration,metrics_json,sort_order,is_active,created_at,updated_at FROM success_stories ORDER BY sort_order,created_at');
        $data = array_map(static function (array $row): array {
            $metrics = json_decode((string) $row['metrics_json'], true);
            unset($row['metrics_json']);
            $row['metrics'] = is_array($metrics) ? array_values($metrics) : [];
            $row['sort_order'] = (int) $row['sort_order'];
            $row['is_active'] = (bool) $row['is_active'];
            return $row;
        }, $rows);
        echo json_encode(['ok' => true, 'data' => $data, 'meta' => ['count' => count($data)]], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    } catch (Throwable $error) {
        error_log('Admin success stories API read failed: ' . $error->getMessage());
        http_response_code(503);
        echo json_encode(['ok' => false, 'message' => 'تعذر تحميل قصص النجاح.'], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

if ($method !== 'POST') {
    http_response_code(405);
    header('Allow: GET, POST');
    echo json_encode(['ok' => false, 'message' => 'هذه الطريقة غير مسموحة.'], JSON_UNESCAPED_UNICODE);
    exit;
}

if (!auth_verify_csrf($_POST['csrf'] ?? null)) {
    http_response_code(419);
    echo json_encode(['ok' => false, 'message' => 'انتهت صلاحية الطلب. حدّث الصفحة وحاول مرة أخرى.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$action = (string) ($_POST['action'] ?? '');
if (!in_array($action, ['create_success_story', 'update_success_story', 'delete_success_story'], true)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'الإجراء المطلوب غير صحيح.'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    [$changed, $message] = admin_handle_action($action, $_POST);
    if (!$changed) {
        http_response_code(422);
    }
    echo json_encode(['ok' => $changed, 'message' => $message, 'action' => $action], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} catch (Throwable $error) {
    error_log('Admin success stories API write failed: ' . $error->getMessage());
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'تعذر تنفيذ الطلب. تحقق من البيانات وحاول مرة أخرى.'], JSON_UNESCAPED_UNICODE);
}
