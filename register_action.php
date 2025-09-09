<?php
// register_action.php - handle registration POST via AuthService
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/src/autoload.php';
require_once __DIR__ . '/src/Repository/UserRepository.php';
require_once __DIR__ . '/src/Service/AuthService.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nick = trim($_POST['nick'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $dob = trim($_POST['dob'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!$nick || !$email || !$dob || !$password) {
        header('Location: registration.php?error=missing');
        exit();
    }
    try {
        $userRepo = new UserRepository($conn);
        $auth = new AuthService($conn, $userRepo);
        $auth->register($nick, $email, $dob, $password);
        header('Location: login.php?registered=1');
        exit();
    } catch (PDOException $e) {
        if ($e->getCode() == '23505') {
            header('Location: registration.php?error=exists');
            exit();
        }
        header('Location: registration.php?error=unknown');
        exit();
    }
}
?>
