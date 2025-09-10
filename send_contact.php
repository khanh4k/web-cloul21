<?php
session_start();
require 'db.php'; // file kết nối CSDL

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    if ($name && $email && $message) {
        // Lưu vào database
        $stmt = $pdo->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $message]);

        $success = "Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất.";
    } else {
        $error = "Vui lòng nhập đầy đủ thông tin.";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Gửi liên hệ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">

  <?php if (!empty($success)): ?>
    <div class="alert alert-success text-center fw-bold"><?= $success ?></div>
    <div class="text-center mt-4">
      <a href="contac.php" class="btn btn-outline-primary">⬅ Quay lại trang Liên hệ</a>
      <a href="index.php" class="btn btn-danger">🏠 Về Trang chủ</a>
    </div>
  <?php elseif (!empty($error)): ?>
    <div class="alert alert-danger text-center"><?= $error ?></div>
    <div class="text-center mt-4">
      <a href="contact.php" class="btn btn-warning">⬅ Thử lại</a>
    </div>
  <?php else: ?>
    <div class="alert alert-info text-center">Không có dữ liệu để gửi.</div>
    <div class="text-center mt-4">
      <a href="contac.php" class="btn btn-outline-secondary">⬅ Quay lại trang Liên hệ</a>
    </div>
  <?php endif; ?>

</div>
</body>
</html>
