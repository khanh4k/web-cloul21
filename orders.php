<?php
session_start();
require 'db.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$role    = $_SESSION['role'] ?? 'user';

// Nếu là admin → lấy tất cả đơn hàng
if ($role === 'admin') {
    $stmt = $pdo->query("
        SELECT o.*, u.username 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        ORDER BY o.created_at DESC
    ");
} else {
    // Nếu là user thường → chỉ lấy đơn hàng của chính họ
    $stmt = $pdo->prepare("
        SELECT o.*, u.username 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        WHERE o.user_id = ? 
        ORDER BY o.created_at DESC
    ");
    $stmt->execute([$user_id]);
}

$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Lịch sử mua hàng</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5">

  <h2 class="mb-4 text-center text-primary">
    <?= $role === 'admin' ? "🧾 Tất cả đơn hàng" : "🧾 Đơn hàng của bạn" ?>
  </h2>

  <?php if (empty($orders)): ?>
    <div class="alert alert-info text-center">
      <?= $role === 'admin' ? "Chưa có đơn hàng nào trong hệ thống." : "Bạn chưa có đơn hàng nào." ?>
    </div>
  <?php else: ?>
    <?php foreach ($orders as $order): ?>
      <div class="card mb-4 shadow-sm">
        <div class="card-header <?= $role === 'admin' ? 'bg-dark text-white' : 'bg-primary text-white' ?>">
          <i class="bi bi-receipt-cutoff me-2"></i>
          Đơn hàng #<?= $order['id'] ?> 
          <?php if ($role === 'admin'): ?>
            – <?= htmlspecialchars($order['username']) ?>
          <?php endif; ?>
          – ngày <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
        </div>
        <div class="card-body">
          <table class="table table-bordered table-hover">
            <thead class="table-light">
              <tr>
                <th>Món ăn</th>
                <th>Số lượng</th>
                <th>Giá</th>
                <th>Tạm tính</th>
              </tr>
            </thead>
            <tbody>
            <?php
            $stmtItems = $pdo->prepare("
                SELECT oi.*, f.name 
                FROM order_items oi 
                JOIN foods f ON oi.food_id = f.id 
                WHERE oi.order_id = ?
            ");
            $stmtItems->execute([$order['id']]);
            $items = $stmtItems->fetchAll();
            foreach ($items as $item):
            ?>
              <tr>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td><?= number_format($item['price'], 0, ',', '.') ?>đ</td>
                <td><?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>đ</td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
          <div class="text-end fw-bold fs-5 text-danger">
            Tổng cộng: <?= number_format($order['total'], 0, ',', '.') ?>đ
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

  <div class="text-center mt-4">
    <a href="<?= $role === 'admin' ? 'admin_panel.php' : 'index.php' ?>" class="btn btn-outline-secondary">
      ⬅ Quay về <?= $role === 'admin' ? 'Trang Quản Trị' : 'Trang Chủ' ?>
    </a>
  </div>
</div>
</body>
</html>
