<?php
require 'config.php';
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll();
$points = 0;
if(isset($_SESSION['user_id'])) {
    $u = $pdo->prepare("SELECT points FROM users WHERE id=?");
    $u->execute([$_SESSION['user_id']]);
    $points = $u->fetchColumn();
}
?>
<!DOCTYPE html>
<html lang="th">
<head><meta charset="UTF-8"><title>เมนูสั่งสินค้าเดลิเวอร์รี่</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="container">
    <div class="header">
        <h2>เมนูสั่งสินค้า</h2>
        <div>
            <?php if(isset($_SESSION['user_id'])): ?>
                <span>แต้มสะสม: <?= $points ?> แต้ม | </span>
                <a href="cart.php" class="btn btn-info">ตะกร้าสินค้า</a>
                <?php if($_SESSION['role'] == 'admin'): ?><a href="admin/index.php" class="btn">จัดการหลังบ้าน</a><?php endif; ?>
                <a href="logout.php" class="btn btn-danger">ออกจากระบบ</a>
            <?php else: ?>
                <a href="login.php" class="btn">เข้าสู่ระบบ</a>
                <a href="register.php" class="btn btn-info">สมัครสมาชิก</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="grid">
        <?php foreach($products as $p): ?>
        <div class="card">
            <?php if($p['image']): ?><img src="images/<?= htmlspecialchars($p['image']) ?>" alt="image"><?php endif; ?>
            <h3><?= htmlspecialchars($p['name']) ?></h3>
            <p><?= htmlspecialchars($p['description']) ?></p>
            <p>ราคา: <?= $p['sell_price'] ?> บาท</p>
            <?php if(isset($_SESSION['user_id'])): ?>
            <form action="cart.php" method="POST">
                <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                <input type="number" name="quantity" value="1" min="1" style="width: 60px; padding: 5px;">
                <button type="submit" name="add_to_cart" class="btn">เพิ่มลงตะกร้า</button>
            </form>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>