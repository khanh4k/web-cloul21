<?php
require 'db.php';
session_start();

// Lấy danh mục món
$categories = $pdo->query("SELECT DISTINCT category FROM foods WHERE category IS NOT NULL")->fetchAll();
$selectedCategory = $_GET['category'] ?? '';

if ($selectedCategory) {
    $stmt = $pdo->prepare("SELECT * FROM foods WHERE category = ? ORDER BY id DESC");
    $stmt->execute([$selectedCategory]);
    $foods = $stmt->fetchAll();
} else {
    $foods = $pdo->query("SELECT * FROM foods ORDER BY id DESC")->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Gà Rán Ngon - Trang chủ</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

  <style>
    body { background-color: #f8f9fa; font-family: 'Roboto', sans-serif; }
    .section-title {
      font-weight: 700;
      font-size: 1.8rem;
      color: #dc3545;
      text-align: center;
      margin-bottom: 2rem;
      text-transform: uppercase;
    }
    .food-card { transition: transform 0.3s; border-radius: 10px; }
    .food-card:hover { transform: scale(1.02); }
    .btn-filter {
      background-color: #fff;
      border: 1px solid #dc3545;
      color: #dc3545;
      font-weight: 500;
    }
    .btn-filter:hover { background-color: #dc3545; color: #fff; }
    section { padding: 60px 0; }
    section.bg-light { background: #fdfdfd; }
    section.bg-gray { background: #f1f1f1; }
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
<?php
require 'db.php';

// Xử lý tìm kiếm
$searchQuery = $_GET['q'] ?? '';
if ($searchQuery) {
    $stmt = $pdo->prepare("SELECT * FROM foods WHERE name LIKE ? ORDER BY id DESC");
    $stmt->execute(["%$searchQuery%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM foods ORDER BY id DESC");
}
$foods = $stmt->fetchAll();

// Lấy danh sách category cho filter nếu cần
$categories = $pdo->query("SELECT DISTINCT category FROM foods")->fetchAll();
$selectedCategory = $_GET['category'] ?? '';
?>
<div class="container py-4">

<?php
require 'db.php';

// Lấy từ khóa tìm kiếm
$searchQuery = $_GET['q'] ?? '';

// Chỉ tìm khi có từ khóa
$foods = [];
if ($searchQuery) {
    $stmt = $pdo->prepare("SELECT * FROM foods WHERE name LIKE ? ORDER BY id DESC");
    $stmt->execute(["%$searchQuery%"]);
    $foods = $stmt->fetchAll();
}
?>

<div class="container py-4">

  <!-- Thanh tìm kiếm -->
  <form class="d-flex mb-3 position-relative" method="get" action="">
    <input class="form-control form-control-sm rounded-pill ps-3 pe-5 shadow-sm"
           type="search" name="q" placeholder="Tìm món ăn..." aria-label="Search" value="<?= htmlspecialchars($searchQuery) ?>">
    <button class="btn btn-primary btn-sm position-absolute top-0 end-0 mt-1 me-1 rounded-circle shadow-sm" type="submit">
      <i class="bi bi-search"></i>
    </button>
  </form>

  <!-- Danh sách món ăn: chỉ hiển thị khi có kết quả -->
  <?php if ($searchQuery): ?>
    <div class="row g-4">
      <?php if (empty($foods)): ?>
        <div class="col-12 text-center text-muted">Không tìm thấy món ăn nào.</div>
      <?php else: foreach ($foods as $food): ?>
        <div class="col-md-4">
          <div class="card h-100 shadow-sm border-0 food-card">
            <?php if ($food['image']): ?>
              <img src="<?= htmlspecialchars($food['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($food['name']) ?>" style="height:220px; object-fit:cover; border-top-left-radius:0.5rem; border-top-right-radius:0.5rem;">
            <?php endif; ?>
            <div class="card-body d-flex flex-column">
              <h5 class="text-danger fw-bold"><?= htmlspecialchars($food['name']) ?></h5>
              <p class="text-muted small flex-grow-1"><?= htmlspecialchars($food['description']) ?></p>
              <p class="fw-bold text-success"><?= number_format($food['price'],0) ?>k</p>

              <form method="post" action="add_to_cart.php" class="d-flex mt-auto gap-2">
                <input type="hidden" name="food_id" value="<?= $food['id'] ?>">
                <input type="number" name="quantity" value="1" min="1" class="form-control w-25">
                <button class="btn btn-success flex-grow-1">🛒 Mua ngay</button>
              </form>

              <a href="food_detail.php?id=<?= $food['id'] ?>" class="btn btn-link text-decoration-none mt-2">📖 Chi tiết</a>
            </div>
          </div>
        </div>
      <?php endforeach; endif; ?>
    </div>
  <?php else: ?>
    <div class="text-center text-muted py-5">
      Nhập từ khóa và nhấn tìm kiếm để xem món ăn.
    </div>
  <?php endif; ?>
</div>

<!-- Hero -->
<section class="hero bg-white">
  <div class="container">
    <div class="mb-4 text-center">
      <img src="123.jpg" class="img-fluid shadow-sm rounded" alt="Gà Rán Ngon"
           style="width:100%; height:300px; object-fit:cover;">
    </div>
    <div class="card border-0 shadow-sm p-4" style="border-left:5px solid #dc3545;">
      <div class="card-body text-center">
        <h2 class="fw-bold text-danger mb-3"><i class="bi bi-award-fill me-2"></i>Gà Rán Ngon</h2>
        <p class="lead text-muted fst-italic">Trải nghiệm ẩm thực giòn rụm, thơm ngon mỗi ngày!</p>
        <p class="text-muted">
          Chào mừng bạn đến với <strong class="text-danger">Gà Rán Ngon</strong> – nơi mang đến trải nghiệm tuyệt vời
          với gà rán giòn rụm, khoai tây chiên và nước uống mát lạnh.
        </p>
        <p><i class="bi bi-geo-alt-fill text-success me-2"></i>
          <strong>Địa chỉ:</strong> 123 Đường Ngon, TP. HCM
        </p>
      </div>
    </div>
  </div>
</section>
<?php
require 'db.php';

// Xử lý tìm kiếm
$searchQuery = $_GET['q'] ?? '';
if ($searchQuery) {
    $stmt = $pdo->prepare("SELECT * FROM foods WHERE name LIKE ?");
    $stmt->execute(["%$searchQuery%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM foods ORDER BY id DESC");
}
$foods = $stmt->fetchAll();
?>

<!-- Danh mục -->
<section class="bg-light">
  <div class="container">
    <h2 class="section-title"><i class="bi bi-grid-3x3-gap-fill me-2"></i>Danh mục món ăn</h2>
    <div class="row g-4">
      <div class="col-md-4"><div class="card shadow-sm h-100"><img src="garan.jpg" class="card-img-top" style="height:200px; object-fit:cover;"><div class="card-body text-center fw-bold">Gà Rán Truyền Thống</div></div></div>
      <div class="col-md-4"><div class="card shadow-sm h-100"><img src="garan.jpg" class="card-img-top" style="height:200px; object-fit:cover;"><div class="card-body text-center fw-bold">Gà Rán Cay Xé Lưỡi</div></div></div>
      <div class="col-md-4"><div class="card shadow-sm h-100"><img src="burgerga.jpg" class="card-img-top" style="height:200px; object-fit:cover;"><div class="card-body text-center fw-bold">Burger Gà Giòn</div></div></div>
      <div class="col-md-4"><div class="card shadow-sm h-100"><img src="comga.jpg" class="card-img-top" style="height:200px; object-fit:cover;"><div class="card-body text-center fw-bold">Cơm Gà Sốt Tiêu</div></div></div>
      <div class="col-md-4"><div class="card shadow-sm h-100"><img src="khoaitaylac.jpg" class="card-img-top" style="height:200px; object-fit:cover;"><div class="card-body text-center fw-bold">Khoai Tây Lắc Phô Mai</div></div></div>
      <div class="col-md-4"><div class="card shadow-sm h-100"><img src="nuocep.jpg" class="card-img-top" style="height:200px; object-fit:cover;"><div class="card-body text-center fw-bold">Nước Ép Trái Cây</div></div></div>
    </div>
  </div>
</section>
<!-- MENU MÓN ĂN -->
<section class="py-5 bg-light">
  <div class="container">
    <h2 class="mb-4 text-center text-danger fw-bold">🍽️ Menu Món Ăn</h2>
    <!-- Danh sách món ăn -->
    <div class="row g-4">
      <?php if (empty($foods)): ?>
        <div class="col-12 text-center text-muted">Không có món ăn nào.</div>
      <?php else: foreach ($foods as $food): ?>
        <div class="col-md-4">
          <div class="card h-100 shadow-sm border-0 food-card">
            <?php if ($food['image']): ?>
              <img src="<?= htmlspecialchars($food['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($food['name']) ?>" style="height:220px; object-fit:cover; border-top-left-radius:0.5rem; border-top-right-radius:0.5rem;">
            <?php endif; ?>
            <div class="card-body d-flex flex-column">
              <h5 class="text-danger fw-bold"><?= htmlspecialchars($food['name']) ?></h5>
              <p class="text-muted small flex-grow-1"><?= htmlspecialchars($food['description']) ?></p>
              <p class="fw-bold text-success"><?= number_format($food['price'],0) ?>k</p>
              
              <!-- Form mua hàng -->
              <form method="post" action="add_to_cart.php" class="d-flex mt-auto gap-2">
                <input type="hidden" name="food_id" value="<?= $food['id'] ?>">
                <input type="number" name="quantity" value="1" min="1" class="form-control w-25">
                <button class="btn btn-success flex-grow-1">🛒 Mua ngay</button>
              </form>
              
              <a href="food_detail.php?id=<?= $food['id'] ?>" class="btn btn-link text-decoration-none mt-2">📖 Chi tiết</a>
            </div>
          </div>
        </div>
      <?php endforeach; endif; ?>
    </div>
  </div>
</section>

<style>
  /* Hiệu ứng hover cho card */
  .food-card:hover {
    transform: translateY(-5px);
    transition: all 0.3s ease;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
  }

  .food-card img {
    transition: all 0.3s;
  }

  .food-card img:hover {
    transform: scale(1.05);
  }
</style>

<!-- Đặt bàn -->
<section class="bg-gray">
  <div class="container">
    <h2 class="section-title"><i class="bi bi-calendar-check-fill me-2"></i>Đặt bàn ngay</h2>
    <div class="card shadow-sm border-0">
      <div class="row g-0">
        <div class="col-md-6 d-none d-md-block">
          <img src="bantaicuahang.jpg" class="img-fluid h-100 rounded-start" style="object-fit:cover;">
        </div>
        <div class="col-md-6">
          <div class="card-body p-4">
            <p class="text-muted">Đặt trước để có trải nghiệm tốt nhất cùng bạn bè và người thân.</p>
            <?php if (isset($_GET['success'])): ?>
              <div class="alert alert-success">✅ Đặt bàn thành công!</div>
            <?php endif; ?>
            <form action="book_table.php" method="post">
              <div class="mb-3"><label class="form-label">Họ và tên</label><input type="text" name="name" class="form-control" required></div>
              <div class="mb-3"><label class="form-label">Số điện thoại</label><input type="tel" name="phone" class="form-control" required></div>
              <div class="mb-3"><label class="form-label">Số người</label><input type="number" name="people" min="1" max="20" value="2" class="form-control" required></div>
              <div class="mb-3"><label class="form-label">Ngày & Giờ</label><input type="datetime-local" name="datetime" class="form-control" required></div>
              <button class="btn btn-danger w-100">📅 Đặt bàn</button>
            </form>
          </div>
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
