<?php
session_start();
include '../db_connect.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// Kiểm tra và lấy ID đơn hàng cần sửa
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $order_id = $_GET['id'];

    // Truy vấn để lấy thông tin đơn hàng
    $sql = "SELECT orders.*, users.name AS customer_name 
            FROM orders 
            JOIN users ON orders.user_id = users.id 
            WHERE orders.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Đơn hàng không tồn tại
        echo "Đơn hàng không tồn tại.";
        exit();
    }

    $order = $result->fetch_assoc();
} else {
    // Nếu không có ID hoặc ID không hợp lệ
    echo "ID đơn hàng không hợp lệ.";
    exit();
}

// Xử lý khi người quản trị cập nhật trạng thái đơn hàng
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['status'];
    $shipping_date = $_POST['shipping_date'];

    // Cập nhật trạng thái và ngày giao hàng cho đơn hàng
    $update_sql = "UPDATE orders SET status = ?, shipping_date = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssi", $status, $shipping_date, $order_id);

    if ($stmt->execute()) {
        // Chuyển hướng về trang quản lý đơn hàng sau khi cập nhật thành công
        header("Location: admin_orders.php?msg=update_success");
        exit();
    } else {
        // Thông báo lỗi khi cập nhật không thành công
        echo "Lỗi cập nhật đơn hàng: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Đơn Hàng</title>
    <link rel="stylesheet" href="../admincss/admin.css">
</head>
<body>
    <div class="sidebar">
        <h3>Chào Mừng, Admin</h3>
        <a href="admin_dashboard.php">Trang chủ</a>
        <a href="admin_products.php">Quản lý Sản phẩm</a>
        <a href="admin_orders.php">Quản lý Đơn hàng</a>
        <a href="admin_users.php">Quản lý Người dùng</a>
        <a href="admin_categories.php">Quản lý Danh mục</a>
        <a href="../logout.php">Đăng xuất</a>
    </div>
    <div class="main-content">
        <h2>Sửa Đơn Hàng - ID: <?= htmlspecialchars($order['id']) ?></h2>

        <form method="POST" action="">
            <label for="customer_name">Khách hàng:</label>
            <input type="text" id="customer_name" name="customer_name" value="<?= htmlspecialchars($order['customer_name']) ?>" disabled><br>

            <label for="total_price">Tổng tiền:</label>
            <input type="text" id="total_price" name="total_price" value="<?= number_format($order['total_price'], 0, ',', '.') ?> VNĐ" disabled><br>

            <label for="status">Trạng thái:</label>
            <select id="status" name="status">
                <option value="Pending" <?= $order['status'] == 'Pending' ? 'selected' : '' ?>>Chờ xử lý</option>
                <option value="Processing" <?= $order['status'] == 'Processing' ? 'selected' : '' ?>>Đang xử lý</option>
                <option value="Shipped" <?= $order['status'] == 'Shipped' ? 'selected' : '' ?>>Đang giao</option>
                <option value="Delivered" <?= $order['status'] == 'Delivered' ? 'selected' : '' ?>>Hoàn thành</option>
            </select><br>

            <label for="shipping_date">Ngày giao hàng:</label>
            <input type="date" id="shipping_date" name="shipping_date" value="<?= $order['shipping_date'] ?>"><br>

            <button type="submit">Cập nhật đơn hàng</button>
        </form>
    </div>
</body>
</html>
