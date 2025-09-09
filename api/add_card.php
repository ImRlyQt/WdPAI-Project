<?php
// add_card.php - add a selected card to the current user's collection (via repository)
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['user_id'])) { http_response_code(401); echo json_encode(['error'=>'unauthorized']); exit(); }
require_once dirname(__DIR__) . '/config/db.php';
require_once dirname(__DIR__) . '/src/autoload.php';
require_once dirname(__DIR__) . '/src/Repository/UserCardRepository.php';

$body = file_get_contents('php://input');
$payload = json_decode($body, true);
if (!$payload) { http_response_code(400); echo json_encode(['error'=>'invalid_json']); exit(); }
$targetUser = (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] && isset($payload['user_id'])) ? (int)$payload['user_id'] : (int)$_SESSION['user_id'];
$name = trim($payload['name'] ?? '');
$cardId = trim($payload['id'] ?? '');
$image = trim($payload['image'] ?? '');
$setName = trim($payload['setName'] ?? '');
$multiverseid = $payload['multiverseid'] ?? null;
if (!$name || !$cardId) { http_response_code(400); echo json_encode(['error'=>'missing_fields']); exit(); }
try {
    $repo = new UserCardRepository($conn);
    $repo->addOrIncrement($targetUser, $cardId, $name, $image ?: null, $setName ?: null, $multiverseid);
    echo json_encode(['ok'=>true]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error'=>'db_error','message'=>$e->getMessage()]);
}
