<?php
session_start();
require 'db.php';

// Lấy danh sách món ăn
$stmt = $pdo->query("SELECT * FROM foods");
$foods = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>🍽 Thực đơn món ăn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
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
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-5 text-center fw-bold text-danger">
        🍽 Thực đơn đặc biệt hôm nay
    </h2>
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

<!-- Thanh tìm kiếm -->
<form class="d-flex mb-3 position-relative" method="get" action="">
  <input class="form-control form-control-sm rounded-pill ps-3 pe-5 shadow-sm"
         type="search" name="q" placeholder="Tìm món ăn..." aria-label="Search" value="<?= htmlspecialchars($searchQuery) ?>">
  <button class="btn btn-primary btn-sm position-absolute top-0 end-0 mt-1 me-1 rounded-circle shadow-sm" type="submit">
    <i class="bi bi-search"></i>
  </button>
</form>
        <tr>
          <td colspan="6" class="text-center text-muted">Không tìm thấy món ăn nào.</td>
        </tr>
    </tbody>
  </table>
</div>

<style>
/* Input tìm kiếm */
form.d-flex input[type="search"] {
  border: none;
  height: 38px;
  font-size: 0.9rem;
  transition: all 0.3s;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

form.d-flex input[type="search"]:focus {
  box-shadow: 0 0 12px rgba(255,77,79,0.5);
  outline: none;
}

/* Nút search */
form.d-flex button {
  border: none;
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
}

form.d-flex button:hover {
  background-color: #ff7875;
  transform: translateY(-2px);
}

/* Placeholder */
form.d-flex input[type="search"]::placeholder {
  color: #888;
  font-style: italic;
}
</style>

    <div class="row g-4">
        <?php if (empty($foods)): ?>
            <div class="col-12 text-center text-muted">Không có món ăn nào.</div>
        <?php else: ?>
            <?php foreach ($foods as $food): ?>
                <div class="col-md-4">
                    <div class="card h-100 shadow-lg border-0 rounded-4 overflow-hidden hover-card">
                        <!-- Ảnh món ăn -->
                        <?php if ($food['image']): ?>
                            <img src="<?= htmlspecialchars($food['image']) ?>" 
                                 class="card-img-top" 
                                 alt="<?= htmlspecialchars($food['name']) ?>" 
                                 style="height: 220px; object-fit: cover;">
                        <?php else: ?>
                            <div class="bg-secondary d-flex align-items-center justify-content-center" 
                                 style="height: 220px; color: white;">
                                Không có ảnh
                            </div>
                        <?php endif; ?>

                        <!-- Thông tin -->
                        <div class="card-body d-flex flex-column p-4">
                            <h5 class="card-title fw-bold text-dark mb-2">
                                <?= htmlspecialchars($food['name']) ?>
                            </h5>
                            <p class="card-text text-muted small flex-grow-1">
                                <?= htmlspecialchars($food['description']) ?>
                            </p>
                            <p class="fw-bold fs-5 text-success mb-3">
                                <?= number_format($food['price'], 0) ?>đ
                            </p>

                            <!-- Form thêm giỏ -->
                            <form method="post" action="cart.php" class="d-flex align-items-center">
                                <input type="hidden" name="food_id" value="<?= $food['id'] ?>">
                                <input type="number" name="quantity" value="1" min="1" 
                                       class="form-control me-2 w-25 text-center">
                                <button type="submit" class="btn btn-danger fw-bold flex-grow-1">
                                    🛒 Thêm vào giỏ
                                </button>
                            </form>

                            <!-- Link chi tiết -->
                            <a href="food_detail.php?id=<?= $food['id'] ?>" 
                               class="btn btn-link text-decoration-none text-primary mt-3 fw-semibold">
                                📖 Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<style>
  .hover-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  .hover-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
  }
</style>


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
