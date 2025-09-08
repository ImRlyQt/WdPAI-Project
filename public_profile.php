<?php
// Redirect to unified profile view, preserving user_id.
$uid = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
header('Location: profile.php' . ($uid ? ('?user_id=' . $uid) : ''));
exit();
