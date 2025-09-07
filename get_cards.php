<?php
// get_cards.php - return current user's cards as JSON
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['user_id'])) { echo json_encode([]); exit(); }
require_once 'db.php';
$stmt = $conn->prepare('SELECT id, card_id, name, image_url, set_name, multiverseid, quantity FROM user_cards WHERE user_id = :uid ORDER BY id DESC');
$stmt->execute([':uid' => $_SESSION['user_id']]);
$cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($cards);
