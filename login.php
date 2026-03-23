<?php
require 'config.php';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header('Location: ' . ($user['role'] == 'admin' ? 'admin/index.php' : 'index.php'));
        exit;
    } else {
        $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head><meta charset="UTF-8"><title>เข้าสู่ระบบ</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="container" style="max-width: 400px; margin-top: 50px;">
    <h2>เข้าสู่ระบบ</h2>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <div class="form-group"><input type="text" name="username" placeholder="ชื่อผู้ใช้" required></div>
        <div class="form-group"><input type="password" name="password" placeholder="รหัสผ่าน" required></div>
        <button type="submit" class="btn" style="width:100%">เข้าสู่ระบบ</button>
    </form>
    <p><a href="register.php">สมัครสมาชิกใหม่</a></p>
</div>
</body>
</html>