<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['food_id'];
    $qty = (int)$_POST['quantity'] ?? 1;

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Cộng dồn nếu đã có
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;

    // Chuyển đến giỏ hàng để thanh toán
    header("Location: cart.php");
    exit;
}
