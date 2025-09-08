<?php
// remove_card.php - decrement quantity or delete card if quantity becomes 0
session_start();
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['error'=>'method_not_allowed']); exit(); }
if (!isset($_SESSION['user_id'])) { http_response_code(401); echo json_encode(['error'=>'unauthorized']); exit(); }
require_once dirname(__DIR__) . '/config/db.php';

$body = file_get_contents('php://input');
$payload = json_decode($body, true);
$cardId = is_array($payload) ? trim($payload['card_id'] ?? '') : '';
if ($cardId === '') { http_response_code(400); echo json_encode(['error'=>'missing_card_id']); exit(); }

try {
    // Try to decrement if quantity > 1
    $dec = $conn->prepare('UPDATE user_cards SET quantity = quantity - 1 WHERE user_id = :uid AND card_id = :cid AND quantity > 1');
    $dec->execute([':uid' => $_SESSION['user_id'], ':cid' => $cardId]);
    if ($dec->rowCount() > 0) {
        // Fetch new quantity for response
        $q = $conn->prepare('SELECT quantity FROM user_cards WHERE user_id = :uid AND card_id = :cid');
        $q->execute([':uid' => $_SESSION['user_id'], ':cid' => $cardId]);
        $row = $q->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['ok'=>true, 'quantity'=>(int)($row['quantity'] ?? 1)]);
        exit();
    }
    // Else delete if quantity is 1 (or record exists with 1)
    $del = $conn->prepare('DELETE FROM user_cards WHERE user_id = :uid AND card_id = :cid');
    $del->execute([':uid' => $_SESSION['user_id'], ':cid' => $cardId]);
    if ($del->rowCount() > 0) {
        echo json_encode(['ok'=>true, 'deleted'=>true]);
        exit();
    }
    http_response_code(404);
    echo json_encode(['error'=>'not_found']);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error'=>'db_error','message'=>$e->getMessage()]);
}
