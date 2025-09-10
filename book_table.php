<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'] ?? '';
  $phone = $_POST['phone'] ?? '';
  $people = $_POST['people'] ?? 1;
  $datetime = $_POST['datetime'] ?? '';

  $stmt = $pdo->prepare("INSERT INTO reservations (name, phone, people, datetime) VALUES (?, ?, ?, ?)");
  $stmt->execute([$name, $phone, $people, $datetime]);

  header("Location: index.php?success=1");
  exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Đặt Bàn - Gà Rán Ngon</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #fefefe;
    }
    .booking-wrapper {
      background-color: #fff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .booking-img {
      object-fit: cover;
      height: 100%;
      width: 100%;
    }
    .form-label {
      font-weight: 600;
    }
    .form-control {
      border-radius: 8px;
    }
    .btn-danger {
      border-radius: 8px;
    }
    .section-title {
      font-size: 2rem;
      font-weight: bold;
    }
    @media (max-width: 768px) {
      .booking-img {
        height: 200px;
      }
    }
  </style>
</head>
<body>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current_page = basename($_SERVER['PHP_SELF']); // lấy tên file hiện tại
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container">
    <!-- Logo -->
    <a class="navbar-brand fw-bold text-danger d-flex align-items-center" href="index.php">
      <img src="Orange Red Fried Chicken Logo.png" alt="Logo" height="40" class="me-2">
      Gà Rán Ngon
    </a>

    <!-- Button toggle mobile -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menu -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto fw-semibold">
        <li class="nav-item">
          <a class="nav-link <?= $current_page=='index.php'?'text-danger fw-bold':'' ?>" href="index.php">
            <i class="bi bi-house-door-fill me-1"></i>Trang chủ
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $current_page=='menu.php'?'text-danger fw-bold':'' ?>" href="menu.php">
            <i class="bi bi-card-list me-1"></i>Thực đơn
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $current_page=='intro.php'?'text-danger fw-bold':'' ?>" href="intro.php">
            <i class="bi bi-info-circle-fill me-1"></i>Giới thiệu
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $current_page=='contact.php'?'text-danger fw-bold':'' ?>" href="contac.php">
            <i class="bi bi-telephone-fill me-1"></i>Liên hệ
          </a>
        </li>
      </ul>

      <!-- Nút bên phải -->
      <div class="d-flex gap-2 ms-lg-3 mt-3 mt-lg-0">
        <a href="cart.php" class="btn btn-outline-danger">
          <i class="bi bi-cart-fill me-1"></i>Giỏ hàng
        </a>
        <a href="book_table.php" class="btn btn-danger">
          <i class="bi bi-calendar-check-fill me-1"></i>Đặt bàn
        </a>

        <?php if (isset($_SESSION['user_id'])): ?>
          <a href="logout.php" class="btn btn-secondary">
            <i class="bi bi-box-arrow-right me-1"></i>Đăng xuất
          </a>
        <?php else: ?>
          <a href="login.php" class="btn btn-outline-dark">
            <i class="bi bi-box-arrow-in-right me-1"></i>Đăng nhập
          </a>
          <a href="register.php" class="btn btn-danger">
            <i class="bi bi-person-plus-fill me-1"></i>Đăng ký
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<!-- Container chính -->
<div class="container py-5">
  <div class="text-center mb-5">
    <h2 class="section-title text-danger"><i class="bi bi-calendar-check-fill me-2"></i>Đặt bàn ngay</h2>
    <p class="text-muted">Đặt chỗ trước để không phải chờ đợi khi đến thưởng thức Gà Rán Ngon!</p>
  </div>

  <div class="row booking-wrapper">
    <!-- Ảnh bên trái -->
    <div class="col-md-6 d-none d-md-block">
      <img src="bantaicuahang.jpg" alt="Đặt bàn" class="booking-img">
    </div>

    <!-- Form đặt bàn -->
    <div class="col-md-6 p-4">
      <form action="book_table.php" method="post">
        <div class="mb-3">
          <label class="form-label">Họ và tên</label>
          <input type="text" name="name" class="form-control" placeholder="Nguyễn Văn A" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Số điện thoại</label>
          <input type="tel" name="phone" class="form-control" placeholder="0912345678" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Số người</label>
          <input type="number" name="people" class="form-control" min="1" max="20" value="2" required>
        </div>
        <div class="mb-4">
          <label class="form-label">Ngày & Giờ</label>
          <input type="datetime-local" name="datetime" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-danger w-100 fw-bold"><i class="bi bi-check-circle-fill me-1"></i> Đặt bàn</button>
      </form>
    </div>
  </div>
</div>
<div class="d-grid gap-2">
</div>

<!-- Footer -->
<footer class="footer-custom text-dark border-top pt-4">
  <div class="container">
    <div class="row align-items-start text-center text-md-start">
      
      <!-- Cột 1: Logo + Thông tin -->
      <div class="col-md-4 mb-4 mb-md-0">
        <div class="d-flex align-items-center justify-content-center justify-content-md-start mb-3">
          <img src="Orange Red Fried Chicken Logo.png" width="55" class="me-2 rounded-circle shadow">
          <h5 class="fw-bold text-danger mb-0 fs-5">Gà Rán Ngon</h5>
        </div>
        <p class="small text-dark fw-semibold">
          Trải nghiệm <span class="text-danger">ẩm thực giòn rụm</span>, 
          phục vụ <span class="text-danger">tận tâm</span> mỗi ngày.
        </p>
        <ul class="list-unstyled small fw-semibold text-dark">
          <li><i class="bi bi-geo-alt-fill me-2 text-danger"></i>123 Đường Ngon, TP.HCM</li>
          <li><i class="bi bi-telephone-fill me-2 text-danger"></i>0123 456 789</li>
          <li><i class="bi bi-envelope-fill me-2 text-danger"></i>info@garanngon.vn</li>
        </ul>
      </div>

      <!-- Cột 2: Liên kết nhanh -->
      <div class="col-md-4 mb-4 mb-md-0">
        <h6 class="fw-bold text-uppercase text-danger mb-3"><i class="bi bi-link-45deg me-1"></i>Liên kết nhanh</h6>
        <ul class="list-unstyled">
          <li><a href="index.php" class="footer-link fw-semibold">Trang chủ</a></li>
          <li><a href="menu.php" class="footer-link fw-semibold">Thực đơn</a></li>
          <li><a href="intro.php" class="footer-link fw-semibold">Giới thiệu</a></li>
          <li><a href="contac.php" class="footer-link fw-semibold">Liên hệ</a></li>
        </ul>
      </div>

      <!-- Cột 3: Mạng xã hội -->
      <div class="col-md-4">
        <h6 class="fw-bold text-uppercase text-danger mb-3"><i class="bi bi-share-fill me-1"></i>Kết nối</h6>
        <div class="d-flex justify-content-center justify-content-md-start gap-3">
          <a href="#" class="social-icon bg-primary"><i class="bi bi-facebook"></i></a>
          <a href="#" class="social-icon bg-danger"><i class="bi bi-instagram"></i></a>
          <a href="#" class="social-icon bg-info"><i class="bi bi-twitter"></i></a>
          <a href="#" class="social-icon bg-danger"><i class="bi bi-youtube"></i></a>
        </div>
      </div>

    </div>

    <hr class="my-4">

    <!-- Bản quyền -->
    <div class="text-center pb-3 small fw-semibold text-dark">
      &copy; 2025 <span class="text-danger fw-bold">Gà Rán Ngon</span>. All rights reserved.
    </div>
  </div>
</footer>

<!-- CSS tùy chỉnh -->
<style>
  .footer-custom {
    background: #fdfdfd;
    font-family: 'Segoe UI', sans-serif;
  }
  .footer-link {
    display: inline-block;
    color: #000;
    text-decoration: none;
    margin-bottom: 0.5rem;
    position: relative;
    transition: color 0.3s, font-weight 0.3s;
  }
  .footer-link:hover {
    color: #dc3545;
    font-weight: bold;
  }
  .social-icon {
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    color: #fff;
    font-size: 1.2rem;
    transition: transform 0.3s, box-shadow 0.3s;
  }
  .social-icon:hover {
    transform: scale(1.15);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
  }
</style>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

