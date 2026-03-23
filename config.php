<?php
session_start();
$host = 'localhost';
$db = 'delivery_db';
$user = 'root';
$pass = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->query("SELECT * FROM users WHERE username='admin12'");
    if($stmt->rowCount() == 0) {
        $hashed = password_hash('5555', PASSWORD_DEFAULT);
        $pdo->exec("INSERT INTO users (username, password, role, fullname) VALUES ('admin12', '$hashed', 'admin', 'ผู้ดูแลระบบ')");
    }
} catch(PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>