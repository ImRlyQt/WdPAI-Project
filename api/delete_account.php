<?php
// delete_account.php - deletes the currently authenticated user's account
session_start();
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['error'=>'method_not_allowed']); exit(); }
if (!isset($_SESSION['user_id'])) { http_response_code(401); echo json_encode(['error'=>'unauthorized']); exit(); }
require_once dirname(__DIR__) . '/config/db.php';

try {
    $conn->beginTransaction();
    $payload = json_decode(file_get_contents('php://input'), true) ?: [];
    $isAdmin = !empty($_SESSION['is_admin']);
    $targetId = ($isAdmin && isset($payload['user_id'])) ? (int)$payload['user_id'] : (int)$_SESSION['user_id'];
    $stmt = $conn->prepare('DELETE FROM users WHERE id = :id');
    $stmt->execute([':id' => $targetId]);
    if ($stmt->rowCount() === 0) {
        $conn->rollBack();
        http_response_code(404);
        echo json_encode(['error' => 'not_found']);
        exit();
    }
    $conn->commit();
    // If the current user deleted their own account, end session; otherwise keep admin session active
    if ($targetId === (int)$_SESSION['user_id']) {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'] ?? '', !empty($params['secure']), !empty($params['httponly']));
        }
        session_destroy();
    }
    echo json_encode(['ok' => true]);
} catch (Throwable $e) {
    if ($conn && $conn->inTransaction()) { $conn->rollBack(); }
    http_response_code(500);
    echo json_encode(['error' => 'db_error', 'message' => $e->getMessage()]);
}
