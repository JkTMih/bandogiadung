<?php
session_start();
include '../db_connect.php';

// Kiểm tra nếu có dữ liệu gửi lên
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    // Kiểm tra số lượng hợp lệ
    if ($quantity < 1) {
        header("Location: product_detail.php?id=$product_id&error=Số lượng không hợp lệ");
        exit();
    }

    // Lấy thông tin sản phẩm từ database
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        header("Location: index.php?error=Sản phẩm không tồn tại");
        exit();
    }

    // Khởi tạo giỏ hàng nếu chưa có
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Thêm vào giỏ hàng
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = [
            'name' => $product['name'],
            'price' => $product['price'],
            'image' => $product['image'],
            'quantity' => $quantity
        ];
    }

    // Chuyển hướng đến trang giỏ hàng
    header("Location: cart.php?success=Sản phẩm đã được thêm vào giỏ hàng");
    exit();
} else {
    header("Location: index.php?error=Dữ liệu không hợp lệ");
    exit();
}
?>
