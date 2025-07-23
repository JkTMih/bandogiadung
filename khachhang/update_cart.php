<?php
session_start();
include '../db_connect.php';

if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    // Kiểm tra sản phẩm có trong giỏ hàng
    if (isset($_SESSION['cart'][$product_id])) {
        if ($quantity > 0) {
            // Cập nhật số lượng
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        } else {
            // Nếu số lượng nhỏ hơn 1 thì xóa sản phẩm khỏi giỏ
            unset($_SESSION['cart'][$product_id]);
        }

        // Chuyển hướng lại trang giỏ hàng với thông báo
        header('Location: cart.php?success=Cập nhật giỏ hàng thành công');
        exit;
    }
}

// Nếu dữ liệu không hợp lệ, quay lại giỏ hàng
header('Location: cart.php?error=Dữ liệu không hợp lệ');
exit;
?>
