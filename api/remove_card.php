<?php
// remove_card.php - decrement quantity or delete card if quantity becomes 0 (via repository)
session_start();
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['error'=>'method_not_allowed']); exit(); }
if (!isset($_SESSION['user_id'])) { http_response_code(401); echo json_encode(['error'=>'unauthorized']); exit(); }
require_once dirname(__DIR__) . '/config/db.php';
require_once dirname(__DIR__) . '/src/autoload.php';
require_once dirname(__DIR__) . '/src/Repository/UserCardRepository.php';

$body = file_get_contents('php://input');
$payload = json_decode($body, true);
$cardId = is_array($payload) ? trim($payload['card_id'] ?? '') : '';
$targetUser = (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] && isset($payload['user_id'])) ? (int)$payload['user_id'] : (int)$_SESSION['user_id'];
if ($cardId === '') { http_response_code(400); echo json_encode(['error'=>'missing_card_id']); exit(); }

try {
    $repo = new UserCardRepository($conn);
    $res = $repo->decrementOrDelete($targetUser, $cardId);
    if (!empty($res['error'])) { http_response_code(404); }
    echo json_encode($res);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error'=>'db_error','message'=>$e->getMessage()]);
}
