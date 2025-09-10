<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "Vui lòng <a href='login.php'>đăng nhập</a> để thanh toán.";
    exit;
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    echo "Giỏ hàng của bạn đang trống.";
    exit;
}

$total = 0;
$items = [];

// Chuẩn bị dữ liệu từ giỏ hàng
foreach ($cart as $id => $qty) {
    $stmt = $pdo->prepare("SELECT * FROM foods WHERE id = ?");
    $stmt->execute([$id]);
    $food = $stmt->fetch();
    if ($food) {
        $subtotal = $food['price'] * $qty;
        $total += $subtotal;
        $items[] = [
            'id' => $food['id'],
            'name' => $food['name'],
            'price' => $food['price'],
            'quantity' => $qty,
            'subtotal' => $subtotal
        ];
    }
}

// Lưu đơn hàng
$stmt = $pdo->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
$stmt->execute([$_SESSION['user_id'], $total]);
$order_id = $pdo->lastInsertId();

// Lưu từng món vào order_items
foreach ($items as $item) {
    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, food_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt->execute([$order_id, $item['id'], $item['quantity'], $item['price']]);
}

// Xóa giỏ hàng sau khi đặt
unset($_SESSION['cart']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đặt hàng thành công</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="text-center mb-5">
    <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
    <h2 class="text-success mt-3">🎉 Đặt hàng thành công!</h2>
    <p class="text-muted">Cảm ơn bạn đã tin tưởng và đặt món tại <strong>Food Shop</strong>.</p>
  </div>

  <div class="card shadow-lg border-0">
    <div class="card-header bg-success text-white fw-bold">
      <i class="bi bi-receipt me-2"></i>Chi tiết đơn hàng #<?= $order_id ?>
    </div>
    <ul class="list-group list-group-flush">
      <?php foreach ($items as $item): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <div>
            <span class="fw-semibold"><?= htmlspecialchars($item['name']) ?></span>
            <small class="text-muted">x <?= $item['quantity'] ?></small>
          </div>
          <span class="text-success fw-bold"><?= number_format($item['subtotal'], 0, ',', '.') ?>đ</span>
        </li>
      <?php endforeach; ?>
      <li class="list-group-item d-flex justify-content-between fw-bold fs-5">
        <span>Tổng cộng:</span>
        <span class="text-danger"><?= number_format($total, 0, ',', '.') ?>đ</span>
      </li>
    </ul>
  </div>

  <div class="mt-4 d-flex justify-content-center gap-3">
    <a href="index.php" class="btn btn-lg btn-outline-primary">
      <i class="bi bi-house-door me-1"></i> Về Trang chủ
    </a>
    <a href="menu.php" class="btn btn-lg btn-outline-success">
      <i class="bi bi-basket2 me-1"></i> Tiếp tục đặt món
    </a>
    <a href="orders.php" class="btn btn-lg btn-warning text-white">
      <i class="bi bi-clock-history me-1"></i> Xem lịch sử đơn hàng
    </a>
  </div>
</div>
</body>
</html>
