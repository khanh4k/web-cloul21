<?php
// admin_dashboard.php
session_start();
require 'db.php';

// Chỉ admin được truy cập
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit;
}

try {
    // Tổng đơn hàng (tất cả)
    $totalOrders = (int)$pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();

    // Tổng doanh thu — dùng cột 'total' (thay vì total_amount)
    $totalRevenue = $pdo->query("SELECT SUM(COALESCE(total,0)) FROM orders")->fetchColumn();
    $totalRevenue = $totalRevenue === null ? 0 : (float)$totalRevenue;

    // Số khách hàng (distinct user_id trong orders)
    $totalCustomers = (int)$pdo->query("SELECT COUNT(DISTINCT user_id) FROM orders")->fetchColumn();

    // Doanh thu theo ngày — 7 ngày gần nhất (inclusive)
    $stmt = $pdo->prepare("
        SELECT DATE(created_at) AS order_date, SUM(COALESCE(total,0)) AS daily_total
        FROM orders
        WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
        GROUP BY DATE(created_at)
        ORDER BY DATE(created_at) ASC
    ");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Build arrays for last 7 days, ensure zero for missing days
    $labels = [];
    $values = [];
    for ($i = 6; $i >= 0; $i--) {
        $day = date('Y-m-d', strtotime("-{$i} days"));
        $labels[] = date('d/m', strtotime($day));
        $values[$day] = 0;
    }
    foreach ($rows as $r) {
        $d = $r['order_date'];
        if (array_key_exists($d, $values)) $values[$d] = (float)$r['daily_total'];
    }
    // prepare arrays for ChartJS
    $chartLabels = array_values($labels);
    $chartValues = array_values($values);

} catch (PDOException $e) {
    // Nếu có lỗi DB, hiển thị thông báo thân thiện
    $errorMsg = "Lỗi truy vấn cơ sở dữ liệu: " . htmlspecialchars($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Dashboard - Gà Rán Ngon (Admin)</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body { background:#f6f7fb; font-family: 'Segoe UI', sans-serif; }
    .card { border-radius:12px; }
    .stat-card { transition: transform .18s ease; }
    .stat-card:hover { transform: translateY(-6px); }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg shadow-sm mb-4" style="background: linear-gradient(90deg, #ff4d4f, #ff7875);">
  <div class="container">
    <!-- Logo / Brand -->
    <a class="navbar-brand text-white fw-bold d-flex align-items-center gap-2" href="admin_dashboard.php" style="font-size:1.25rem;">
      <i class="bi bi-shop-window"></i> Gà Rán Ngon - Admin
    </a>

    <!-- Menu bên phải -->
    <div class="ms-auto d-flex align-items-center gap-3">
      <a class="btn btn-light btn-sm d-flex align-items-center gap-1 shadow-sm" href="admin_panel.php">
        <i class="bi bi-gear-fill"></i> Quản trị
      </a>

      <span class="text-white fw-semibold" style="font-size:0.95rem;">
        Xin chào, <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?>
      </span>

      <a class="btn btn-danger btn-sm d-flex align-items-center gap-1 shadow-sm" href="logout.php">
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
  }

  .btn-danger:hover {
    background-color: #ff7875 !important;
    transform: translateY(-2px);
  }

  /* Text shadow nhẹ cho brand */
  .navbar-brand {
    text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
  }
</style>

<div class="container">
  <?php if (!empty($errorMsg)): ?>
    <div class="alert alert-danger"><?= $errorMsg ?></div>
  <?php endif; ?>

  <h1 class="text-center mb-4">📊 Dashboard - Thống kê</h1>

  <div class="row g-4 mb-4">
    <div class="col-md-4">
      <div class="card stat-card shadow-sm p-3 text-center bg-white">
        <small class="text-muted">TỔNG ĐƠN HÀNG</small>
        <h2 class="mt-2"><?= number_format($totalOrders) ?></h2>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card stat-card shadow-sm p-3 text-center bg-white">
        <small class="text-muted">TỔNG DOANH THU</small>
        <h2 class="mt-2 text-success"><?= number_format($totalRevenue, 0, ',', '.') ?> đ</h2>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card stat-card shadow-sm p-3 text-center bg-white">
        <small class="text-muted">KHÁCH HÀNG</small>
        <h2 class="mt-2"><?= number_format($totalCustomers) ?></h2>
      </div>
    </div>
  </div>

  <div class="card shadow-sm mb-5">
    <div class="card-header bg-dark text-white fw-bold"><i class="bi bi-graph-up"></i> Doanh thu 7 ngày gần nhất</div>
    <div class="card-body">
      <canvas id="revenueChart" height="120"></canvas>
    </div>
  </div>

  <div class="text-center mb-5">
    <a class="btn btn-outline-secondary me-2" href="admin_panel.php"><i class="bi bi-arrow-left"></i> Quay về quản trị</a>
    <a class="btn btn-outline-primary" href="orders.php"><i class="bi bi-receipt"></i> Quản lý đơn hàng</a>
  </div>
</div>

<script>
const labels = <?= json_encode($chartLabels) ?>;
const data = <?= json_encode($chartValues) ?>;
const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels,
        datasets: [{
            label: 'Doanh thu (đ)',
            data,
            fill: true,
            tension: 0.25,
            borderColor: '#198754',
            backgroundColor: 'rgba(25,135,84,0.12)',
            pointRadius: 4
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: true } },
        scales: {
            y: {
                ticks: {
                    callback: function(value){ return value.toLocaleString() + ' đ'; }
                }
            }
        }
    }
});
</script>
</body>
</html>
