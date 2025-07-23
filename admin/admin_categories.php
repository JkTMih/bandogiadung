<?php
session_start();
include '../db_connect.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Lấy danh sách danh mục
$result = $conn->query("SELECT * FROM categories");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Danh mục</title>
    <link rel="stylesheet" href="../admincss/admin.css">
</head>
<body>
    <div class="sidebar">
        <h3>Chào Mừng, Admin</h3>
        <a href="admin_dashboard.php">Trang chủ</a>
        <a href="admin_products.php">Quản lý Sản phẩm</a>
        <a href="admin_orders.php">Quản lý Đơn hàng</a>
        <a href="admin_users.php">Quản lý Người dùng</a>
        <a href="admin_categories.php" class="active">Quản lý Danh mục</a>
		<a href="admin_reviews.php">Quản lý Đánh giá</a>
		<a href="admin_contacts.php">Liên hệ khách hàng</a>
        <a href="../logout.php">Đăng xuất</a>
    </div>
    <div class="main-content">
        <h2>Quản lý Danh mục</h2>
        
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Tên danh mục</th>
                <th>Hành động</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['name'] ?></td>
                    <td>
                        <a href="edit_category.php?id=<?= $row['id'] ?>">Sửa</a> /
                        <a href="delete_category.php?id=<?= $row['id'] ?>" onclick="return confirm('Xóa danh mục này?');">Xóa</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <a href="add_category.php">Thêm danh mục mới</a>
    </div>
</body>
</html>