<?php
session_start();
require 'db.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'] ?? null;

    // Kiểm tra xem đơn hàng thuộc người dùng hiện tại không
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
    $stmt->execute([$order_id, $user_id]);
    $order = $stmt->fetch();

    if ($order) {
        // Xóa các món trong đơn hàng trước
        $pdo->prepare("DELETE FROM order_items WHERE order_id = ?")->execute([$order_id]);

        // Sau đó xóa đơn hàng
        $pdo->prepare("DELETE FROM orders WHERE id = ?")->execute([$order_id]);
    }
}

header("Location: orders.php"); // hoặc tên file đơn hàng của bạn
exit;
