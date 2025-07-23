<?php
session_start();
include '../db_connect.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// Truy vấn lấy đơn hàng từ cơ sở dữ liệu
$sql = "SELECT orders.*, users.name AS customer_name 
        FROM orders 
        JOIN users ON orders.user_id = users.id 
        ORDER BY orders.created_at DESC";

$result = $conn->query($sql);

if (!$result) {
    echo "Lỗi truy vấn: " . $conn->error;
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Đơn Hàng</title>
    <link rel="stylesheet" href="../admincss/admin.css">
</head>
<body>
    <div class="sidebar">
        <h3>Chào Mừng, Admin</h3>
        <a href="admin_dashboard.php">Trang chủ</a>
        <a href="admin_products.php">Quản lý Sản phẩm</a>
        <a href="admin_orders.php" class="active">Quản lý Đơn hàng</a>
        <a href="admin_users.php">Quản lý Người dùng</a>
        <a href="admin_categories.php">Quản lý Danh mục</a>
		<a href="admin_reviews.php">Quản lý Đánh giá</a>
		<a href="admin_contacts.php">Liên hệ khách hàng</a>
        <a href="../logout.php">Đăng xuất</a>
    </div>
    <div class="main-content">
        <h2>Quản lý Đơn Hàng</h2>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Khách hàng</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
            <?php if ($result->num_rows > 0) { ?>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['customer_name'] ?? 'Không có tên khách hàng') ?></td>
                        <td><?= number_format($row['total_price'], 0, ',', '.') ?> VNĐ</td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                        <td>
                            <a href="edit_order.php?id=<?= $row['id'] ?>">Sửa</a> |
                            <a href="delete_order.php?id=<?= $row['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?')">Xóa</a>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="5">Không có đơn hàng nào.</td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
