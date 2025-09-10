<?php
$pdo = new PDO('mysql:host=localhost;dbname=food_shop;charset=utf8', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
session_start();

// Xử lý đăng xuất
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // ✅ Đăng nhập thành công
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // ➤ Phân quyền chuyển trang
        if ($user['role'] === 'admin') {
            header("Location: admin_panel.php");
        } else {
            header("Location: user_home.php"); // trang dành cho user
        }
        exit;
    } else {
        $login_error = "❌ Sai tên đăng nhập hoặc mật khẩu.";
    }
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập quản trị</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 & Google Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;600&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background: linear-gradient(135deg, #74ebd5, #acb6e5);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            background: #fff;
            padding: 2rem;
            border-radius: 1.5rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 420px;
        }

        .login-card h3 {
            font-weight: 600;
            color: #007bff;
        }

        .form-control {
            border-radius: 0.75rem;
        }

        .btn-primary {
            border-radius: 2rem;
            font-weight: 600;
            transition: background 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .alert {
            font-size: 0.95rem;
        }
    </style>
</head>
<body>

<div class="login-card">
    <h3 class="text-center mb-4">🔐 Đăng Nhập Quản Trị</h3>

    <?php if (isset($login_error)): ?>
        <div class="alert alert-danger text-center">
            <?= $login_error ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label">👤 Tên đăng nhập</label>
            <input type="text" name="username" class="form-control" required placeholder="Nhập username...">
        </div>
        <div class="mb-3">
            <label class="form-label">🔒 Mật khẩu</label>
            <input type="password" name="password" class="form-control" required placeholder="Nhập mật khẩu...">
        </div>
        <button type="submit" name="login" class="btn btn-primary w-100">Đăng nhập</button>
        <div class="text-center mt-3">
            <a href="register.php" class="text-decoration-none">📋 Tạo tài Khoản 
    </form>
</div>
</body>
</html>
