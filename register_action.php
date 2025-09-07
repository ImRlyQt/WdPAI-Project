<?php
// register_action.php - handle registration POST
require_once 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nick = trim($_POST['nick'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $dob = trim($_POST['dob'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!$nick || !$email || !$dob || !$password) {
        header('Location: registration.php?error=missing');
        exit();
    }
    $hash = password_hash($password, PASSWORD_DEFAULT);
    try {
        $stmt = $conn->prepare('INSERT INTO users (nick, email, password, dob) VALUES (:nick, :email, :password, :dob)');
        $stmt->execute([
            ':nick' => $nick,
            ':email' => $email,
            ':password' => $hash,
            ':dob' => $dob
        ]);
        header('Location: login.php?registered=1');
        exit();
    } catch (PDOException $e) {
        // Unique violation (email exists)
        if ($e->getCode() == '23505') {
            header('Location: registration.php?error=exists');
            exit();
        }
        header('Location: registration.php?error=unknown');
        exit();
    }
}
?>
