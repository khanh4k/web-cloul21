<?php
require 'db.php';

// Truy vấn tất cả thông tin đặt bàn
$stmt = $pdo->query("SELECT * FROM reservations ORDER BY datetime DESC");
$reservations = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Danh sách Đặt Bàn</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
  <h2 class="text-center mb-4 text-primary"><i class="bi bi-people-fill me-2"></i>Danh sách Đặt Bàn</h2>
  <?php if (isset($_GET['success'])): ?>
      <div style="max-width:600px; margin:10px auto; padding:15px; border:2px solid #28a745; border-radius:8px; background:#e9ffe9;">
        <h5 style="color:#28a745;">✅ Đặt bàn thành công!</h5>
      </div>
    <?php endif; ?>

  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Họ tên</th>
          <th>SĐT</th>
          <th>Số người</th>
          <th>Ngày giờ</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($reservations as $r): ?>
          <tr>
            <td><?= htmlspecialchars($r['id']) ?></td>
            <td><?= htmlspecialchars($r['name']) ?></td>
            <td><?= htmlspecialchars($r['phone']) ?></td>
            <td><?= htmlspecialchars($r['people']) ?></td>
            <td><?= date('d/m/Y H:i', strtotime($r['datetime'])) ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (count($reservations) === 0): ?>
          <tr>
            <td colspan="5" class="text-center text-muted">Chưa có lượt đặt bàn nào.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <a href="index.php" class="btn btn-outline-secondary mt-3"><i class="bi bi-arrow-left-circle"></i> Về trang chủ</a>
</div>
</body>
</html>
