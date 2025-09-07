<?php
// login_action.php - handle login POST
require_once 'db.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!$email || !$password) {
        header('Location: login.php?error=missing');
        exit();
    }
    $stmt = $conn->prepare('SELECT id, nick, password FROM users WHERE email = :email');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nick'] = $user['nick'];
        header('Location: profile.php');
        exit();
    }
    header('Location: login.php?error=invalid');
    exit();
}
?>
