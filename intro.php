<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Giới Thiệu - Gà Rán Ngon</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    /* Banner */
    .about-header {
      background: url('fried-chicken-banner.jpg') no-repeat center center;
      background-size: cover;
      color: white;
      padding: 120px 0;
      position: relative;
    }
    .about-header::after {
      content: "";
      position: absolute;
      top:0; left:0; right:0; bottom:0;
      background: rgba(0,0,0,0.5);
    }
    .about-header .container {
      position: relative;
      z-index: 2;
    }

    /* Icon box */
    .icon-box i {
      font-size: 2.5rem;
      color: #dc3545;
    }
    .icon-box h5 {
      margin-top: 12px;
      font-weight: bold;
    }

    /* Stats */
    .stats {
      background: #dc3545;
      color: white;
      padding: 50px 0;
    }
    .stats h2 {
      font-size: 2.5rem;
      font-weight: bold;
    }

    /* Testimonials */
    .testimonial {
      background: #fff;
      border: 1px solid #eee;
      border-radius: 12px;
      padding: 25px;
      text-align: center;
      transition: all 0.3s;
      height: 100%;
    }
    .testimonial:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    }
    .testimonial img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 50%;
      margin-bottom: 15px;
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

<!-- Header -->
<header class="about-header text-center">
  <div class="container">
    <h1 class="display-4 fw-bold">Về Gà Rán Ngon</h1>
    <p class="lead">Hương vị tuyệt vời - Dịch vụ tận tâm</p>
  </div>
</header>

<!-- Giới thiệu -->
<section class="about-content py-5 bg-light">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-md-6">
        <img src="anh/bantaicuahang.jpg" class="img-fluid rounded shadow" alt="Cửa hàng">
      </div>
      <div class="col-md-6">
        <h2 class="text-danger fw-bold mb-3">Chúng tôi là ai?</h2>
        <p class="text-muted">
          Gà Rán Ngon là chuỗi cửa hàng chuyên cung cấp các món gà rán giòn tan với công thức độc quyền. 
          Mỗi miếng gà đều được chế biến từ nguyên liệu tươi sạch, mang đến cho khách hàng trải nghiệm ẩm thực khó quên.
        </p>
        <ul class="list-unstyled mt-3">
          <li><i class="bi bi-check-circle-fill text-danger me-2"></i>Nguyên liệu tươi ngon 100%</li>
          <li><i class="bi bi-check-circle-fill text-danger me-2"></i>Dịch vụ nhanh chóng, chuyên nghiệp</li>
          <li><i class="bi bi-check-circle-fill text-danger me-2"></i>Không gian thoải mái, hiện đại</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- Thế mạnh -->
<section class="py-5">
  <div class="container">
    <div class="row text-center g-4">
      <div class="col-md-4">
        <div class="icon-box">
          <i class="bi bi-star-fill"></i>
          <h5>Chất lượng hàng đầu</h5>
          <p class="text-muted">Luôn giữ vững tiêu chuẩn vệ sinh an toàn thực phẩm.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="icon-box">
          <i class="bi bi-clock-fill"></i>
          <h5>Phục vụ nhanh chóng</h5>
          <p class="text-muted">Đặt hàng dễ dàng – nhận món chỉ trong 15 phút.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="icon-box">
          <i class="bi bi-emoji-smile-fill"></i>
          <h5>Khách hàng là số 1</h5>
          <p class="text-muted">Luôn lắng nghe, cải tiến để mang lại trải nghiệm tốt hơn.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Số liệu thống kê -->
<section class="stats text-center">
  <div class="container">
    <div class="row g-4">
      <div class="col-md-3">
        <h2>50+</h2>
        <p>Cửa hàng toàn quốc</p>
      </div>
      <div class="col-md-3">
        <h2>500K+</h2>
        <p>Khách hàng thân thiết</p>
      </div>
      <div class="col-md-3">
        <h2>1M+</h2>
        <p>Phần gà đã bán</p>
      </div>
      <div class="col-md-3">
        <h2>10+</h2>
        <p>Năm kinh nghiệm</p>
      </div>
    </div>
  </div>
</section>

<!-- Đánh giá khách hàng -->
<section class="py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold text-danger">Khách hàng nói gì?</h2>
      <p class="text-muted">Những chia sẻ thật từ khách hàng thân yêu</p>
    </div>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="testimonial">
          <img src="customer1.jpg" alt="Khách hàng 1">
          <p class="fst-italic">"Gà giòn, thơm ngon, giao hàng nhanh. Tôi sẽ tiếp tục ủng hộ!"</p>
          <h6 class="fw-bold text-dark mb-0">Ngọc Anh</h6>
          <small class="text-muted">Khách hàng thân thiết</small>
        </div>
      </div>
      <div class="col-md-4">
        <div class="testimonial">
          <img src="customer2.jpg" alt="Khách hàng 2">
          <p class="fst-italic">"Không gian quán đẹp, nhân viên thân thiện. Rất hài lòng."</p>
          <h6 class="fw-bold text-dark mb-0">Minh Hoàng</h6>
          <small class="text-muted">Doanh nhân</small>
        </div>
      </div>
      <div class="col-md-4">
        <div class="testimonial">
          <img src="customer3.jpg" alt="Khách hàng 3">
          <p class="fst-italic">"Món ăn ngon và giá hợp lý. Phù hợp cho gia đình."</p>
          <h6 class="fw-bold text-dark mb-0">Thu Hà</h6>
          <small class="text-muted">Mẹ bỉm</small>
        </div>
      </div>
    </div>
  </div>
</section>
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
          <li><a href="contact.php" class="footer-link fw-semibold">Liên hệ</a></li>
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

