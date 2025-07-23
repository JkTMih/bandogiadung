<?php
session_start();
include '../db_connect.php';

// Chỉ cho phép admin truy cập
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$sql = "SELECT * FROM contacts ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Liên hệ</title>
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
		<a href="admin_reviews.php">Quản lý Đánh giá</a>
        <a href="admin_contacts.php" class="active">Liên hệ khách hàng</a>
        <a href="../logout.php">Đăng xuất</a>
    </div>
    <div class="main-content">
        <h2>Phản hồi từ khách hàng</h2>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Nội dung</th>
                <th>Ngày gửi</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                    <td><?= $row['created_at'] ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
