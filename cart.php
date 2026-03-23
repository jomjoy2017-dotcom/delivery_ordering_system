<?php
require 'config.php';
if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
if(isset($_POST['add_to_cart'])) {
    $id = $_POST['product_id'];
    $qty = $_POST['quantity'];
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
    header("Location: index.php"); exit;
}
if(isset($_POST['checkout']) && !empty($_SESSION['cart'])) {
    $total = 0;
    foreach($_SESSION['cart'] as $id => $qty) {
        $stmt = $pdo->prepare("SELECT sell_price FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $total += $stmt->fetchColumn() * $qty;
    }
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user_id'], $total]);
    $order_id = $pdo->lastInsertId();
    foreach($_SESSION['cart'] as $id => $qty) {
        $stmt = $pdo->prepare("SELECT sell_price FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $price = $stmt->fetchColumn();
        $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)")->execute([$order_id, $id, $qty, $price]);
    }
    $pdo->query("UPDATE users SET points = points + 10 WHERE id = ".$_SESSION['user_id']);
    unset($_SESSION['cart']);
    echo "<script>alert('ยืนยันการสั่งซื้อสำเร็จ จำนวนเงินที่สั่งซื้อ $total บาท รอเจ้าหน้าที่มาส่ง'); window.location='index.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head><meta charset="UTF-8"><title>ตะกร้าสินค้า</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="container">
    <h2>รายการสั่งซื้อของคุณ</h2>
    <form method="POST">
        <table>
            <tr><th>รายการสินค้า</th><th>จำนวน</th><th>ราคา/หน่วย</th><th>รวม</th></tr>
            <?php
            $sum = 0;
            if(!empty($_SESSION['cart'])): 
                foreach($_SESSION['cart'] as $id => $qty):
                    $stmt = $pdo->prepare("SELECT name, sell_price FROM products WHERE id=?");
                    $stmt->execute([$id]);
                    $p = $stmt->fetch();
                    $row_total = $p['sell_price'] * $qty;
                    $sum += $row_total;
            ?>
            <tr><td><?= htmlspecialchars($p['name']) ?></td><td><?= $qty ?></td><td><?= $p['sell_price'] ?></td><td><?= $row_total ?></td></tr>
            <?php endforeach; else: ?>
            <tr><td colspan="4" style="text-align:center;">ไม่มีสินค้าในตะกร้า</td></tr>
            <?php endif; ?>
            <tr><td colspan="3" style="text-align:right;"><strong>จำนวนเงินรวมทั้งหมด</strong></td><td><strong><?= $sum ?> บาท</strong></td></tr>
        </table><br>
        <?php if(!empty($_SESSION['cart'])): ?>
        <button type="submit" name="checkout" class="btn">ยืนยันการสั่งซื้อ</button>
        <?php endif; ?>
        <a href="index.php" class="btn btn-danger">กลับไปเลือกสินค้า</a>
    </form>
</div>
</body>
</html>