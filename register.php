<?php
require 'config.php';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $full = $_POST['fullname'];
    $phone = $_POST['phone'];
    $addr = $_POST['address'];
    $stmt = $pdo->prepare("INSERT INTO users (username, password, fullname, phone, address) VALUES (?, ?, ?, ?, ?)");
    if($stmt->execute([$user, $pass, $full, $phone, $addr])) {
        echo "<script>alert('สมัครสมาชิกสำเร็จ'); window.location='login.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head><meta charset="UTF-8"><title>สมัครสมาชิก</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="container" style="max-width: 500px; margin-top: 50px;">
    <h2>สมัครสมาชิกลูกค้า</h2>
    <form method="POST">
        <div class="form-group"><input type="text" name="username" placeholder="ชื่อผู้ใช้ (Username)" required></div>
        <div class="form-group"><input type="password" name="password" placeholder="รหัสผ่าน" required></div>
        <div class="form-group"><input type="text" name="fullname" placeholder="ชื่อ-นามสกุล" required></div>
        <div class="form-group"><input type="text" name="phone" placeholder="เบอร์โทรศัพท์" required></div>
        <div class="form-group"><textarea name="address" placeholder="ที่อยู่จัดส่ง" required></textarea></div>
        <button type="submit" class="btn" style="width:100%">สมัครสมาชิก</button>
    </form>
</div>
</body>
</html>