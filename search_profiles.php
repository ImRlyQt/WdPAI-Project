<?php
// search_profiles.php - returns JSON array of users matching a search query (by nick)
require_once 'db.php';
header('Content-Type: application/json');
$q = trim($_GET['q'] ?? '');
if ($q === '') {
    echo json_encode([]);
    exit();
}
$stmt = $conn->prepare('SELECT id, nick FROM users WHERE LOWER(nick) LIKE :q LIMIT 10');
$stmt->execute(['q' => '%' . strtolower($q) . '%']);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($results);
