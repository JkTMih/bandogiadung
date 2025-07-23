<?php
session_start();
include '../db_connect.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// Kiểm tra và lấy ID đơn hàng cần xóa
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $order_id = $_GET['id'];

    // Kiểm tra xem đơn hàng có tồn tại trong cơ sở dữ liệu không
    $check_sql = "SELECT * FROM orders WHERE id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Đơn hàng tồn tại, thực hiện xóa
        $delete_sql = "DELETE FROM orders WHERE id = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("i", $order_id);
        
        if ($stmt->execute()) {
            // Xóa thành công, chuyển hướng về trang quản lý đơn hàng
            header("Location: admin_orders.php?msg=delete_success");
            exit();
        } else {
            // Lỗi khi xóa
            echo "Lỗi khi xóa đơn hàng: " . $stmt->error;
        }
    } else {
        // Đơn hàng không tồn tại
        echo "Đơn hàng không tồn tại.";
    }
} else {
    // Nếu không có ID hoặc ID không hợp lệ
    echo "ID đơn hàng không hợp lệ.";
}
?>
