<?php
/**
 * RESTful API Handler for Timetable Management
 * TimeTable (D+)
 */

header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';
init_secure_session();

// Check user authentication
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'نشست کاربری نامعتبر یا منقضی شده است']);
    exit;
}

$user_id = (int)$_SESSION['user_id'];

/**
 * Validate and sanitize a course item
 */
function sanitizeCourseItem($item) {
    if (!is_array($item)) return null;

    $name = mb_substr(strip_tags(trim($item['name'] ?? 'بدون عنوان')), 0, 100, 'UTF-8');
    if ($name === '') $name = 'بدون عنوان';

    $category = trim($item['category'] ?? 'other');
    $allowedCategories = ['uni', 'nonuni', 'plus', 'sport', 'book', 'movie', 'work', 'other'];
    if (!in_array($category, $allowedCategories, true)) {
        $category = 'other';
    }

    $day = (int)($item['day'] ?? 1);
    if ($day < 1 || $day > 7) $day = 1;

    $sTime = trim($item['sTime'] ?? '08:00');
    $eTime = trim($item['eTime'] ?? '09:30');
    if (!preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $sTime)) $sTime = '08:00';
    if (!preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $eTime)) $eTime = '09:30';

    $prof = mb_substr(strip_tags(trim($item['prof'] ?? '-')), 0, 80, 'UTF-8');
    $loc = mb_substr(strip_tags(trim($item['loc'] ?? '-')), 0, 80, 'UTF-8');

    return [
        'name'     => $name,
        'category' => $category,
        'day'      => $day,
        'sTime'    => $sTime,
        'eTime'    => $eTime,
        'prof'     => $prof ?: '-',
        'loc'      => $loc ?: '-'
    ];
}

try {
    $method = $_SERVER['REQUEST_METHOD'];
    $action = $_GET['action'] ?? '';

    // ==========================================
    // GET Requests (Read data)
    // ==========================================
    if ($method === 'GET') {
        // List all schedules for current user
        if ($action === 'list_schedules') {
            $stmt = $pdo->prepare("SELECT id, name, is_default, updated_at FROM schedules WHERE user_id = ? ORDER BY is_default DESC, updated_at DESC");
            $stmt->execute([$user_id]);
            echo json_encode($stmt->fetchAll());
            exit;
        }

        // Get single schedule data
        $schedule_id = (int)($_GET['schedule_id'] ?? 0);
        if ($schedule_id > 0) {
            $stmt = $pdo->prepare("SELECT data, name FROM schedules WHERE id = ? AND user_id = ? LIMIT 1");
            $stmt->execute([$schedule_id, $user_id]);
            $result = $stmt->fetch();
            echo $result ? $result['data'] : json_encode([]);
        } else {
            // Get default schedule
            $stmt = $pdo->prepare("SELECT data, name FROM schedules WHERE user_id = ? AND is_default = 1 LIMIT 1");
            $stmt->execute([$user_id]);
            $result = $stmt->fetch();
            echo $result ? $result['data'] : json_encode([]);
        }
        exit;
    }

    // ==========================================
    // POST Requests (Write / Modify data)
    // ==========================================
    if ($method === 'POST') {
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'قالب داده ارسالی معتبر نیست']);
            exit;
        }

        // Verify CSRF Protection
        $csrfToken = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? ($data['csrf_token'] ?? '');
        if (!verify_csrf_token($csrfToken)) {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'اعتبارسنجی امنیتی نشست منقضی شده است']);
            exit;
        }

        // 1. Create Schedule
        if ($action === 'create_schedule') {
            $name = mb_substr(strip_tags(trim($data['name'] ?? 'جدول جدید')), 0, 50, 'UTF-8');
            if ($name === '') $name = 'جدول جدید';

            $stmt = $pdo->prepare("INSERT INTO schedules (user_id, name, data, is_default) VALUES (?, ?, '[]', 0)");
            $stmt->execute([$user_id, $name]);
            echo json_encode(['status' => 'success', 'id' => (int)$pdo->lastInsertId()]);
            exit;
        }

        // 2. Delete Schedule (never allow deleting is_default=1)
        if ($action === 'delete_schedule') {
            $schedule_id = (int)($data['schedule_id'] ?? 0);
            if ($schedule_id > 0) {
                $stmt = $pdo->prepare("DELETE FROM schedules WHERE id = ? AND user_id = ? AND is_default = 0");
                $stmt->execute([$schedule_id, $user_id]);
            }
            echo json_encode(['status' => 'success']);
            exit;
        }

        // 3. Rename Schedule
        if ($action === 'rename_schedule') {
            $schedule_id = (int)($data['schedule_id'] ?? 0);
            $name = mb_substr(strip_tags(trim($data['name'] ?? 'جدول')), 0, 50, 'UTF-8');
            if ($name === '') $name = 'جدول';

            if ($schedule_id > 0) {
                $stmt = $pdo->prepare("UPDATE schedules SET name = ? WHERE id = ? AND user_id = ?");
                $stmt->execute([$name, $schedule_id, $user_id]);
            }
            echo json_encode(['status' => 'success']);
            exit;
        }

        // 4. Save Courses to Schedule
        $schedule_id = (int)($data['schedule_id'] ?? 0);
        $rawCourses = $data['courses'] ?? [];
        $sanitizedCourses = [];

        if (is_array($rawCourses)) {
            // Limit courses to max 250 items for stability
            $rawCourses = array_slice($rawCourses, 0, 250);
            foreach ($rawCourses as $course) {
                $sanitized = sanitizeCourseItem($course);
                if ($sanitized !== null) {
                    $sanitizedCourses[] = $sanitized;
                }
            }
        }

        $courses_json = json_encode($sanitizedCourses, JSON_UNESCAPED_UNICODE);

        if ($schedule_id > 0) {
            $stmt = $pdo->prepare("UPDATE schedules SET data = ?, updated_at = NOW() WHERE id = ? AND user_id = ?");
            $stmt->execute([$courses_json, $schedule_id, $user_id]);
        } else {
            $stmt = $pdo->prepare("UPDATE schedules SET data = ?, updated_at = NOW() WHERE user_id = ? AND is_default = 1");
            $stmt->execute([$courses_json, $user_id]);
        }

        echo json_encode(['status' => 'success', 'count' => count($sanitizedCourses)]);
        exit;
    }

    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'متد ارسالی پشتیبانی نمی‌شود']);
    exit;

} catch (PDOException $e) {
    error_log("API Database Error: " . $e->getMessage());
    http_response_code(500);
    // Generic error to avoid exposing database schema or details
    echo json_encode(['status' => 'error', 'message' => 'خطایی در پردازش اطلاعات پایگاه داده رخ داد']);
    exit;
}
?>