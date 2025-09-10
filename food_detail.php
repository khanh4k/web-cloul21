<?php
require 'db.php';
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}
$stmt = $pdo->prepare("SELECT * FROM foods WHERE id = ?");
$stmt->execute([$id]);
$food = $stmt->fetch();
if (!$food) {
    echo "Không tìm thấy món ăn.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Chi tiết món ăn - <?= htmlspecialchars($food['name']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .food-card {
      background: #fff;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    }
    .food-image img {
      border-radius: 15px 0 0 15px;
      object-fit: cover;
      height: 100%;
      width: 100%;
    }
    .food-info h2 {
      font-weight: bold;
      color: #dc3545;
    }
    .food-price {
      font-size: 1.8rem;
      font-weight: bold;
      color: #28a745;
    }
    .btn-cart {
      font-size: 1.1rem;
      padding: 10px 20px;
    }
  </style>
</head>
<body>
<div class="container my-5">
  <a href="index.php" class="btn btn-outline-secondary mb-4">
    <i class="bi bi-arrow-left-circle me-1"></i> Quay lại
  </a>

  <div class="row food-card">
    <!-- Ảnh món ăn -->
    <div class="col-md-6 food-image p-0">
      <img src="<?= $food['image'] ?>" alt="Ảnh món ăn" class="img-fluid">
    </div>

    <!-- Thông tin món ăn -->
    <div class="col-md-6 p-4 food-info d-flex flex-column justify-content-center">
      <h2><?= htmlspecialchars($food['name']) ?></h2>
      <p class="text-muted"><?= htmlspecialchars($food['description']) ?></p>
      <div class="food-price mb-3">
        <?= number_format($food['price'], 0) ?>đ
      </div>
      <p><i class="bi bi-tags-fill text-danger me-2"></i><strong>Loại:</strong> <?= htmlspecialchars($food['category']) ?: 'Không có' ?></p>
      
      <!-- Form thêm vào giỏ -->
      <form method="post" action="cart.php" class="d-flex align-items-center mt-3">
        <input type="hidden" name="food_id" value="<?= $food['id'] ?>">
        <input type="number" name="quantity" value="1" min="1" class="form-control me-2 w-25 text-center">
        <button type="submit" class="btn btn-danger btn-cart">
          <i class="bi bi-cart-plus-fill me-1"></i> Thêm vào giỏ
        </button>
      </form>
    </div>
  </div>
</div>
</body>
</html>
