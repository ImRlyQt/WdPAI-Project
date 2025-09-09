<?php
// login_action.php - handle login POST via AuthService
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/src/autoload.php';
require_once __DIR__ . '/src/Repository/UserRepository.php';
require_once __DIR__ . '/src/Service/AuthService.php';

session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!$email || !$password) {
        header('Location: login.php?error=missing');
        exit();
    }
    $userRepo = new UserRepository($conn);
    $auth = new AuthService($conn, $userRepo);

    // Bootstrap admin if special credentials
    if ($tmp = $auth->bootstrapAdminIfCredentials($email, $password)) {
        $_SESSION['user_id'] = $tmp['id'];
        $_SESSION['nick'] = $tmp['nick'];
        $_SESSION['is_admin'] = true;
        header('Location: profile.php');
        exit();
    }

    // Standard login
    $user = $auth->login($email, $password);
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nick'] = $user['nick'];
        $_SESSION['is_admin'] = !empty($user['is_admin']);
        header('Location: profile.php');
        exit();
    }
    header('Location: login.php?error=invalid');
    exit();
}
?>
