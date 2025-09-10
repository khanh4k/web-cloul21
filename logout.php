<?php
session_start();
session_unset();  // Xoá toàn bộ biến session
session_destroy(); // Hủy session

header("Location: login.php"); // Chuyển về trang đăng nhập
exit;
