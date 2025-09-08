<?php
// db.php - PDO PostgreSQL connection for user auth
$host = getenv('POSTGRES_HOST') ?: 'db';
$port = getenv('POSTGRES_PORT') ?: '5432';
$db   = getenv('POSTGRES_DB') ?: 'db';
$user = getenv('POSTGRES_USER') ?: 'docker';
$pass = getenv('POSTGRES_PASSWORD') ?: 'docker';
try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}
?>
