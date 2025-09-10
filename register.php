<?php
session_start();
require 'db.php'; // Kết nối CSDL

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);

    $defaultPassword = '123'; // Mật khẩu mặc định
    $hash = password_hash($defaultPassword, PASSWORD_DEFAULT);

    if (empty($username)) {
        $msg = "❌ Vui lòng nhập tên đăng nhập.";
    } else {
        // Kiểm tra trùng username
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $msg = "❌ Tên đăng nhập đã tồn tại.";
        } else {
            // Thêm user mới với mật khẩu mặc định
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $hash]);

            $msg = "✅ Đăng ký thành công!<br>🔑 Mật khẩu mặc định là: <strong>123</strong><br><a href='login.php'>Đăng nhập</a>";
        }
    }
}

?>


<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng Ký Tài Khoản</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 500px">
  <h2 class="text-center text-primary mb-4">📝 Đăng Ký</h2>

  <?php if (!empty($msg)): ?>
    <div class="alert alert-warning"><?= $msg ?></div>
  <?php endif; ?>

  <form method="post" class="card card-body shadow-sm">
    <div class="mb-3">
      <label>Tên đăng nhập</label>
      <input type="text" name="username" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Email</label>
      <input type="email" name="email" class="form-control">
    </div>
    <div class="mb-3">
      <label>Mật khẩu</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Nhập lại mật khẩu</label>
      <input type="password" name="confirm_password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success w-100">Đăng ký</button>
    <p class="text-center mt-3">Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
  </form>
</div>
</body>
</html>
