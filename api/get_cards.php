<?php
// get_cards.php - return user's cards as JSON (via repository)
session_start();
header('Content-Type: application/json');
require_once dirname(__DIR__) . '/config/db.php';
require_once dirname(__DIR__) . '/src/autoload.php';
require_once dirname(__DIR__) . '/src/Repository/UserCardRepository.php';

$requestedUserId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
if ($requestedUserId <= 0) {
    if (!isset($_SESSION['user_id'])) { echo json_encode([]); exit(); }
    $requestedUserId = (int)$_SESSION['user_id'];
}

$repo = new UserCardRepository($conn);
$cards = $repo->listByUser($requestedUserId);
echo json_encode($cards);
