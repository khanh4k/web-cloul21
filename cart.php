<?php
session_start();
require 'db.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Thêm món vào giỏ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['food_id'];
    $qty = $_POST['quantity'];
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
    header("Location: cart.php");
    exit;
}

$total = 0;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>🛒 Giỏ hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
      body { background-color: #f8f9fa; }
      .cart-header {
        background: linear-gradient(135deg,#ff5722,#ff9800);
        color: white;
        padding: 40px 0;
        text-align: center;
        border-radius: 0 0 20px 20px;
      }
      .cart-table {
        border-radius: 10px;
        overflow: hidden;
      }
      .cart-summary {
        background: #fff8e1;
        border: 2px solid #ffc107;
        border-radius: 10px;
        padding: 20px;
      }
      .footer-custom {
        background: #fff;
        font-family: 'Segoe UI', sans-serif;
      }
      .footer-link {
        color: #000;
        text-decoration: none;
        display: block;
        margin-bottom: 0.5rem;
        transition: color 0.3s, font-weight 0.3s;
      }
      .footer-link:hover {
        color: #dc3545;
        font-weight: bold;
      }
      .social-icon {
        width: 42px; height: 42px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 1.2rem;
        transition: transform 0.3s, box-shadow 0.3s;
      }
      .social-icon:hover {
        transform: scale(1.15);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
      }
    </style>
</head>
<body>

<!-- Navbar -->
<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold text-danger d-flex align-items-center" href="index.php">
      <img src="Orange Red Fried Chicken Logo.png" alt="Logo" height="40" class="me-2"> Gà Rán Ngon
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto fw-semibold">
        <li class="nav-item"><a class="nav-link <?= $current_page=='index.php'?'text-danger fw-bold':'' ?>" href="index.php">Trang chủ</a></li>
        <li class="nav-item"><a class="nav-link <?= $current_page=='menu.php'?'text-danger fw-bold':'' ?>" href="menu.php">Thực đơn</a></li>
        <li class="nav-item"><a class="nav-link <?= $current_page=='intro.php'?'text-danger fw-bold':'' ?>" href="intro.php">Giới thiệu</a></li>
        <li class="nav-item"><a class="nav-link <?= $current_page=='contac.php'?'text-danger fw-bold':'' ?>" href="contac.php">Liên hệ</a></li>
      </ul>
      <div class="d-flex gap-2 ms-lg-3 mt-3 mt-lg-0">
        <a href="cart.php" class="btn btn-outline-danger"><i class="bi bi-cart-fill me-1"></i>Giỏ hàng</a>
        <a href="book_table.php" class="btn btn-danger"><i class="bi bi-calendar-check-fill me-1"></i>Đặt bàn</a>
      </div>
    </div>
  </div>
</nav>

<!-- Header Giỏ hàng -->
<div class="cart-header">
  <h2 class="fw-bold">🛒 Giỏ hàng của bạn</h2>
  <p class="mb-0">Kiểm tra lại các món ăn trước khi thanh toán</p>
</div>

<!-- Nội dung giỏ hàng -->
<div class="container my-5">
  <?php if (empty($_SESSION['cart'])): ?>
    <div class="alert alert-info text-center py-4 shadow-sm rounded">
      <h5 class="fw-bold">Giỏ hàng trống</h5>
      <p>Chưa có món nào, hãy thêm món ngay!</p>
      <a href="menu.php" class="btn btn-warning"><i class="bi bi-plus-circle me-1"></i> Xem thực đơn</a>
    </div>
  <?php else: ?>
    <div class="row g-4">
      <!-- Bảng giỏ hàng -->
      <div class="col-lg-8">
        <div class="table-responsive cart-table shadow-sm">
          <table class="table table-bordered table-hover mb-0 align-middle">
            <thead class="table-warning">
              <tr class="text-center">
                <th>Món ăn</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
                <th>Tạm tính</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($_SESSION['cart'] as $id => $qty): 
                $food = $pdo->query("SELECT * FROM foods WHERE id = $id")->fetch();
                if (!$food) continue;
                $subtotal = $food['price'] * $qty;
                $total += $subtotal;
              ?>
                <tr>
                  <td><strong><?= htmlspecialchars($food['name']) ?></strong></td>
                  <td class="text-center"><?= $qty ?></td>
                  <td class="text-center"><?= number_format($food['price'], 0) ?>k</td>
                  <td class="text-end text-success fw-bold"><?= number_format($subtotal, 0) ?>k</td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Tổng cộng -->
      <div class="col-lg-4">
        <div class="cart-summary shadow-sm">
          <h5 class="fw-bold text-danger mb-3"><i class="bi bi-receipt me-1"></i> Tóm tắt đơn hàng</h5>
          <p class="d-flex justify-content-between">
            <span class="fw-semibold">Tạm tính:</span>
            <span><?= number_format($total, 0) ?>k</span>
          </p>
          <hr>
          <p class="d-flex justify-content-between fs-5 fw-bold text-dark">
            <span>Tổng cộng:</span>
            <span class="text-danger"><?= number_format($total, 0) ?>k</span>
          </p>
          <div class="d-grid gap-2 mt-4">
            <a href="checkout.php" class="btn btn-success btn-lg"><i class="bi bi-credit-card me-1"></i> Thanh toán</a>
            <a href="menu.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-1"></i> Tiếp tục mua</a>
            <a href="orders.php" class="btn btn-outline-dark"><i class="bi bi-clock-history me-1"></i> Lịch sử đơn hàng</a>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>
<!-- Section: Lịch sử mua hàng -->
<div class="container my-5">
  <div class="card shadow-lg border-0">
    <div class="card-body text-center">
      <h4 class="fw-bold text-danger mb-3">
        <i class="bi bi-clock-history me-2"></i> Lịch Sử Mua Hàng
      </h4>
      <p class="text-muted mb-4">
        Xem lại các đơn hàng bạn đã đặt tại <span class="fw-bold text-danger">Gà Rán Ngon</span>.
      </p>
      <a href="orders.php" class="btn btn-outline-danger btn-lg px-4 fw-bold">
        <i class="bi bi-arrow-right-circle me-2"></i> Xem Lịch Sử
      </a>
    </div>
  </div>
</div>

<!-- Footer -->
<footer class="footer-custom text-dark border-top pt-5">
  <div class="container">
    <div class="row text-center text-md-start">
      <div class="col-md-4 mb-4">
        <div class="d-flex align-items-center justify-content-center justify-content-md-start mb-3">
          <img src="Orange Red Fried Chicken Logo.png" width="55" class="me-2 rounded-circle shadow">
          <h5 class="fw-bold text-danger mb-0 fs-5">Gà Rán Ngon</h5>
        </div>
        <p class="small fw-semibold">Trải nghiệm <span class="text-danger">ẩm thực giòn rụm</span>, phục vụ <span class="text-danger">tận tâm</span> mỗi ngày.</p>
      </div>
      <div class="col-md-4 mb-4">
        <h6 class="fw-bold text-uppercase text-danger mb-3">Liên kết nhanh</h6>
        <a href="index.php" class="footer-link">Trang chủ</a>
        <a href="menu.php" class="footer-link">Thực đơn</a>
        <a href="intro.php" class="footer-link">Giới thiệu</a>
        <a href="contact.php" class="footer-link">Liên hệ</a>
      </div>
      <div class="col-md-4 mb-4">
        <h6 class="fw-bold text-uppercase text-danger mb-3">Kết nối</h6>
        <div class="d-flex justify-content-center justify-content-md-start gap-3">
          <a href="#" class="social-icon bg-primary"><i class="bi bi-facebook"></i></a>
          <a href="#" class="social-icon bg-danger"><i class="bi bi-instagram"></i></a>
          <a href="#" class="social-icon bg-info"><i class="bi bi-twitter"></i></a>
          <a href="#" class="social-icon bg-danger"><i class="bi bi-youtube"></i></a>
        </div>
      </div>
    </div>
    <hr class="my-4">
    <div class="text-center pb-3 small fw-semibold">&copy; 2025 <span class="text-danger">Gà Rán Ngon</span>. All rights reserved.</div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
