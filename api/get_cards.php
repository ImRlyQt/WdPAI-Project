<?php
// get_cards.php - return user's cards as JSON
session_start();
header('Content-Type: application/json');
require_once dirname(__DIR__) . '/config/db.php';

// Allow public access when a user_id is explicitly provided; otherwise require session
$requestedUserId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
if ($requestedUserId <= 0) {
    if (!isset($_SESSION['user_id'])) { echo json_encode([]); exit(); }
    $requestedUserId = (int)$_SESSION['user_id'];
}

$stmt = $conn->prepare('SELECT id, card_id, name, image_url, set_name, multiverseid, quantity FROM user_cards WHERE user_id = :uid ORDER BY id DESC');
$stmt->execute([':uid' => $requestedUserId]);
$cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($cards);
