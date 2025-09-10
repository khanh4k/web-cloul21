<?php
session_start();
require 'db.php';

// Kiểm tra đăng nhập (role user)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'User';

// Lấy danh sách món ăn
$foods = $pdo->query("SELECT * FROM foods ORDER BY id DESC")->fetchAll();

// Lấy lịch sử đặt bàn
$reservations = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM reservations WHERE user_id = ? ORDER BY datetime DESC");
    $stmt->execute([$user_id]);
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) { $reservations = []; }

// Lấy danh sách đơn hàng
$orders = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) { $orders = []; }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard - Gà Rán Ngon</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { font-family: 'Quicksand', sans-serif; background:#f9f9f9; }
        img.thumb { height: 60px; object-fit: cover; border-radius:8px; }
        .nav-pills .nav-link.active { background-color: #dc3545; }
    </style>
</head>
<body>
<div class="container py-4">
<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg bg-white px-4 mb-4">
  <a class="navbar-brand fw-bold text-danger" href="index.php">🍗 Gà Rán Ngon - Admin</a>
  <div class="ms-auto d-flex gap-2">

    <span class="me-3 text-primary fw-semibold">Xin chào, <?= $_SESSION['username'] ?? 'Admin' ?></span>
    <a href="login.php" class="btn btn-sm btn-danger">
      <i class="bi bi-box-arrow-right"></i> Đăng xuất
    </a>
  </div>
</nav>

    <!-- Tabs -->
    <ul class="nav nav-pills mb-4 justify-content-center" id="pills-tab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" id="foods-tab" data-bs-toggle="pill" data-bs-target="#foods" type="button">
                🍔 Món Ăn
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="reservations-tab" data-bs-toggle="pill" data-bs-target="#reservations" type="button">
                📅 Đặt Bàn
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="orders-tab" data-bs-toggle="pill" data-bs-target="#orders" type="button">
                🧾 Đơn Hàng
            </button>
        </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">

<!-- Danh sách món ăn -->
<div class="tab-pane fade show active" id="foods">
    <h3 class="text-danger mb-3"><i class="bi bi-list-ul me-2"></i>Danh sách Món Ăn</h3>
    <div class="row g-4">
        <?php foreach ($foods as $food): ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0 rounded-3">
                    <?php if (!empty($food['image'])): ?>
                        <a href="food_detail.php?id=<?= $food['id'] ?>">
                            <img src="<?= htmlspecialchars($food['image']) ?>" 
                                 class="card-img-top" 
                                 alt="<?= htmlspecialchars($food['name']) ?>" 
                                 style="height:200px;object-fit:cover;cursor:pointer;">
                        </a>
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-primary fw-bold"><?= htmlspecialchars($food['name']) ?></h5>
                        <p class="card-text text-muted small flex-grow-1">
                            <?= htmlspecialchars($food['description']) ?>
                        </p>
                        <p class="fw-bold text-danger fs-5">
                            <?= number_format($food['price'], 0, ',', '.') ?> VNĐ
                        </p>

                        <!-- Form thêm giỏ -->
                        <form method="post" action="cart.php" class="mt-auto d-flex align-items-center">
                            <input type="hidden" name="food_id" value="<?= $food['id'] ?>">
                            <input type="number" name="quantity" value="1" min="1" 
                                   class="form-control me-2 w-25 text-center">
                            <button type="submit" class="btn btn-sm btn-success fw-bold flex-grow-1">
                                🛒 Thêm vào giỏ
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (count($foods) === 0): ?>
            <p class="text-center text-muted">Chưa có món ăn nào.</p>
        <?php endif; ?>
    </div>
</div>

<style>
.card:hover {
    transform: translateY(-5px);
    transition: 0.3s;
    box-shadow: 0 6px 18px rgba(0,0,0,0.15);
}
</style>

        <!-- Lịch sử đặt bàn -->
        <div class="tab-pane fade" id="reservations">
            <h3 class="text-success mb-3"><i class="bi bi-calendar-check me-2"></i>Lịch sử Đặt Bàn</h3>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Số người</th>
                            <th>Ngày giờ</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservations as $r): ?>
                            <tr>
                                <td><?= htmlspecialchars($r['id']) ?></td>
                                <td><?= htmlspecialchars($r['people']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($r['datetime'])) ?></td>
                                <td><span class="badge bg-success">✅ Đã đặt</span></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (count($reservations) === 0): ?>
                            <tr><td colspan="4" class="text-center text-muted">Bạn chưa đặt bàn lần nào.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Lịch sử đơn hàng -->
        <div class="tab-pane fade" id="orders">
            <h3 class="text-primary mb-3"><i class="bi bi-bag-check me-2"></i>Đơn Hàng Của Bạn</h3>
            <?php if (empty($orders)): ?>
                <div class="alert alert-info">Bạn chưa mua đơn hàng nào.</div>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-dark text-light">
                            Đơn hàng #<?= $order['id'] ?> – ngày <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
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
                                $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($items as $item): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['name']) ?></td>
                                        <td><?= $item['quantity'] ?></td>
                                        <td><?= number_format($item['price'], 0) ?>đ</td>
                                        <td><?= number_format($item['price'] * $item['quantity'], 0) ?>đ</td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>

                            <div class="text-end fw-bold fs-5 text-danger">
                                Tổng cộng: <?= number_format($order['total'], 0) ?>đ
                            </div>

                            <form action="delete_order.php" method="POST" class="text-end mt-2"
                                  onsubmit="return confirm('Bạn có chắc muốn xóa đơn hàng này?');">
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger">🗑️ Xóa đơn hàng</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
   
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
