<?php
// login_action.php - handle login POST
require_once __DIR__ . '/config/db.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!$email || !$password) {
        header('Location: login.php?error=missing');
        exit();
    }
    $isAdminAttempt = (strtolower($email) === 'admin@gmail.com' && $password === '123');
    $stmt = $conn->prepare('SELECT id, nick, email, password, is_admin FROM users WHERE email = :email');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    // Admin bootstrap: allow admin@gmail.com / 123 to log in and (up)grade user row
    if ($isAdminAttempt) {
        $hash = password_hash('123', PASSWORD_DEFAULT);
        if (!$user) {
            $ins = $conn->prepare('INSERT INTO users (nick, email, password, dob, is_admin) VALUES (:n, :e, :p, :dob, TRUE)');
            $ins->execute([':n'=>'Admin', ':e'=>'admin@gmail.com', ':p'=>$hash, ':dob'=>'1970-01-01']);
        } else {
            $up = $conn->prepare('UPDATE users SET password = :p, is_admin = TRUE WHERE email = :e');
            $up->execute([':p'=>$hash, ':e'=>'admin@gmail.com']);
        }
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nick'] = $user['nick'];
            $_SESSION['is_admin'] = true;
            header('Location: profile.php');
            exit();
        }
    }
    if ($user && password_verify($password, $user['password'])) {
        // Elevate admin flag if logging in with the admin email
        if (strtolower($user['email']) === 'admin@gmail.com' && empty($user['is_admin'])) {
            try { $conn->prepare('UPDATE users SET is_admin = TRUE WHERE id = :id')->execute([':id'=>$user['id']]); } catch (Throwable $e) {}
            $user['is_admin'] = true;
        }
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
