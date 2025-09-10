<?php
session_start();
require 'db.php';

// Chỉ cho phép Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$msg = "";

// Xử lý thêm/sửa/xóa món ăn
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_food'])) {
        $stmt = $pdo->prepare("INSERT INTO foods (name, price, category, image, description) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_POST['name'], $_POST['price'], $_POST['category'], $_POST['image'], $_POST['description']]);
        $msg = "✅ Đã thêm món ăn!";
    }
    if (isset($_POST['update_food'])) {
        $stmt = $pdo->prepare("UPDATE foods SET name=?, price=?, category=?, image=?, description=? WHERE id=?");
        $stmt->execute([$_POST['name'], $_POST['price'], $_POST['category'], $_POST['image'], $_POST['description'], $_POST['id']]);
        $msg = "✏️ Đã cập nhật món ăn!";
    }
    if (isset($_POST['delete_food'])) {
        $stmt = $pdo->prepare("DELETE FROM foods WHERE id=?");
        $stmt->execute([$_POST['id']]);
        $msg = "🗑️ Đã xóa món ăn!";
    }
}

// Lấy dữ liệu
$foods = $pdo->query("SELECT * FROM foods ORDER BY id DESC")->fetchAll();
$reservations = $pdo->query("SELECT * FROM reservations ORDER BY datetime DESC")->fetchAll();
$contacts = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Trang Quản Trị Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
    .navbar { box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .card { border-radius: 12px; }
    .thumb { height: 55px; width: 70px; object-fit: cover; border-radius: 6px; }
    .table-hover tbody tr:hover { background: #f1f1f1; }
  </style>
</head>
<body>
<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg shadow-sm mb-4" style="background: linear-gradient(90deg, #ff4d4f, #ff7875);">
  <div class="container">
    <!-- Logo / Brand -->
    <a class="navbar-brand text-white fw-bold d-flex align-items-center gap-2" href="index.php" style="font-size:1.25rem;">
      <i class="bi bi-shop-window-fill"></i> Gà Rán Ngon - Admin
    </a>

    <!-- Menu bên phải -->
    <div class="ms-auto d-flex align-items-center gap-3">
      <a href="admin_dashboard.php" class="btn btn-light btn-sm d-flex align-items-center gap-1 shadow-sm">
        <i class="bi bi-bar-chart-line-fill"></i> Dashboard
      </a>

      <span class="text-white fw-semibold" style="font-size:0.95rem;">
        Xin chào, <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?>
      </span>

      <a href="login.php" class="btn btn-danger btn-sm d-flex align-items-center gap-1 shadow-sm">
        <i class="bi bi-box-arrow-right"></i> Đăng xuất
      </a>
    </div>
  </div>
</nav>

<style>
  /* Hover hiệu ứng cho các nút */
  .btn-light:hover {
    background-color: #ffe4e1 !important;
    color: #ff4d4f !important;
    transform: translateY(-2px);
    transition: all 0.2s;
  }

  .btn-danger:hover {
    background-color: #ff7875 !important;
    transform: translateY(-2px);
    transition: all 0.2s;
  }

  /* Text shadow nhẹ cho brand */
  .navbar-brand {
    text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
  }
</style>

<div class="container">
  <!-- THÔNG BÁO -->
  <?php if (!empty($msg)): ?>
    <div class="alert alert-success text-center shadow-sm"><?= $msg ?></div>
  <?php endif; ?>

  <!-- QUẢN LÝ MÓN ĂN -->
  <div class="card mb-5 shadow-sm">
    <div class="card-header bg-primary text-white"><i class="bi bi-egg-fried me-2"></i>Quản lý Món Ăn</div>
    <div class="card-body">
      <!-- Form thêm món -->
      <form method="post" class="row g-2 mb-4">
        <div class="col-md-3"><input name="name" class="form-control" placeholder="Tên món" required></div>
        <div class="col-md-2"><input name="price" type="number" class="form-control" placeholder="Giá" required></div>
        <div class="col-md-2"><input name="category" class="form-control" placeholder="Danh mục"></div>
        <div class="col-md-3"><input name="image" class="form-control" placeholder="Link ảnh"></div>
        <div class="col-md-12"><textarea name="description" class="form-control" placeholder="Mô tả"></textarea></div>
        <div class="col-md-12 text-end"><button class="btn btn-success" name="add_food">➕ Thêm món</button></div>
      </form>

      <!-- Danh sách món -->
      <?php foreach ($foods as $food): ?>
        <form method="post" class="row g-2 align-items-center mb-2 p-2 bg-white border rounded">
          <input type="hidden" name="id" value="<?= $food['id'] ?>">
          <div class="col-md-1"><?php if ($food['image']): ?><img src="<?= $food['image'] ?>" class="thumb"><?php endif; ?></div>
          <div class="col-md-2"><input name="name" value="<?= $food['name'] ?>" class="form-control"></div>
          <div class="col-md-1"><input name="price" type="number" value="<?= $food['price'] ?>" class="form-control"></div>
          <div class="col-md-2"><input name="category" value="<?= $food['category'] ?>" class="form-control"></div>
          <div class="col-md-2"><input name="image" value="<?= $food['image'] ?>" class="form-control"></div>
          <div class="col-md-2"><input name="description" value="<?= $food['description'] ?>" class="form-control"></div>
          <div class="col-md-1 d-flex gap-1">
            <button name="update_food" class="btn btn-warning btn-sm">✏️</button>
            <button name="delete_food" class="btn btn-danger btn-sm" onclick="return confirm('Xóa món này?')">🗑️</button>
          </div>
        </form>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- QUẢN LÝ ĐẶT BÀN -->
  <div class="card mb-5 shadow-sm">
    <div class="card-header bg-success text-white"><i class="bi bi-calendar2-check me-2"></i>Danh sách Đặt Bàn</div>
    <div class="card-body table-responsive">
      <table class="table table-bordered table-hover text-center">
        <thead class="table-dark">
          <tr>
            <th>ID</th><th>Họ tên</th><th>SĐT</th><th>Số người</th><th>Ngày giờ</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($reservations as $r): ?>
            <tr>
              <td><?= $r['id'] ?></td>
              <td><?= htmlspecialchars($r['name']) ?></td>
              <td><?= htmlspecialchars($r['phone']) ?></td>
              <td><?= $r['people'] ?></td>
              <td><?= date('d/m/Y H:i', strtotime($r['datetime'])) ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (count($reservations) === 0): ?>
            <tr><td colspan="5" class="text-muted">Chưa có đặt bàn nào</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- QUẢN LÝ LIÊN HỆ -->
  <div class="card mb-5 shadow-sm">
    <div class="card-header bg-danger text-white"><i class="bi bi-envelope-fill me-2"></i>Danh sách Liên Hệ</div>
    <div class="card-body table-responsive">
      <table class="table table-bordered table-hover">
        <thead class="table-danger text-center">
          <tr>
            <th>#</th><th>Họ tên</th><th>Email</th><th>Nội dung</th><th>Ngày gửi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($contacts as $c): ?>
            <tr>
              <td><?= $c['id'] ?></td>
              <td><strong><?= htmlspecialchars($c['name']) ?></strong></td>
              <td><?= htmlspecialchars($c['email']) ?></td>
              <td><?= nl2br(htmlspecialchars($c['message'])) ?></td>
              <td><?= date('d/m/Y H:i', strtotime($c['created_at'])) ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (count($contacts) === 0): ?>
            <tr><td colspan="5" class="text-muted text-center">Chưa có liên hệ nào</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Nút chuyển trang -->
  <div class="text-center my-4">
    <a href="index.php" class="btn btn-outline-secondary px-4"><i class="bi bi-house-door"></i> Trang chủ</a>
    <a href="orders.php" class="btn btn-outline-secondary px-4"><i class="bi bi-clock-history"></i> Lịch sử Mua Hàng</a>
  </div>
</div>

</body>
</html>
