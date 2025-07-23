<?php
session_start();

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Kiểm tra nếu sản phẩm tồn tại trong giỏ hàng
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]); // Xóa sản phẩm khỏi giỏ hàng
    }
}

// Quay lại trang giỏ hàng
header("Location: cart.php");
exit();
