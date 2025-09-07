<?php
// public_profile.php - show public info for a user by id
require_once 'db.php';
$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
if (!$user_id) {
    http_response_code(404);
    echo 'User not found.';
    exit();
}
$stmt = $conn->prepare('SELECT nick, dob FROM users WHERE id = :id');
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    http_response_code(404);
    echo 'User not found.';
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile of <?php echo htmlspecialchars($user['nick']); ?></title>
    <link rel="stylesheet" href="./public/css/globals.css" />
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #101010;
            color: white;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            padding: 1rem 2rem;
            font-size: 2rem;
            font-family: 'UnifrakturCook', cursive;
            background-color: #000;
            flex-shrink: 0;
        }
        header .logo {
            flex: 1;
        }
        .layout {
            display: flex;
            flex: 1;
            height: calc(100vh - 4rem);
            overflow: hidden;
        }
        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, #8497e9, #8497e9, #980597);
            padding: 2rem 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            flex-shrink: 0;
        }
        .sidebar img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin-bottom: 1rem;
        }
        .sidebar h2 {
            margin: 0.5rem 0;
            font-size: 1.25rem;
        }
        .sidebar p {
            text-align: center;
            font-size: 0.9rem;
            color: #eee;
            margin-bottom: 1.5rem;
        }
        .content {
            flex: 1;
            background-color: #1a1a1a;
            padding: 2rem;
            overflow-y: auto;
        }
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                align-items: flex-start;
            }
            .layout {
                flex-direction: column;
                height: auto;
            }
            .sidebar {
                width: 100%;
                flex-direction: column;
                align-items: center;
            }
            .content {
                border-radius: 0;
                padding: 1rem;
            }
        }
        @media (max-width: 480px) {
            .sidebar img {
                width: 90px;
                height: 90px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1 class="logo">DeckHeaven</h1>
    </header>
    <div class="layout">
        <aside class="sidebar">
            <img src="https://avatars.githubusercontent.com/u/583231?v=4" alt="Profile picture">
            <h2><?php echo htmlspecialchars($user['nick']); ?></h2>
            <p>This is a public profile.</p>
        </aside>
        <main class="content">
            <div style="color:#aaa;font-size:1.1rem;">Date of birth: <?php echo htmlspecialchars($user['dob']); ?></div>
            <a href="profile.php" style="color:#2d63ff;display:inline-block;margin-top:1rem;">Back to my profile</a>
        </main>
    </div>
</body>
</html>
