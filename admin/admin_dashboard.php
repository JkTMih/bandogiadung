<?php
function setActive($page) {
        return basename($_SERVER['PHP_SELF']) == $page ? 'active' : '';
    }
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
	 
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
    <link rel="stylesheet" href="../admincss/admin.css">
</head>
<body>

    <div class="sidebar">
        <h2>Chào Mừng, Admin</h2>
        <ul>
            <li class="<?= setActive('admin_dashboard.php') ?>"><a href="admin_dashboard.php">Trang chủ</a></li>
			<li class="<?= setActive('admin_products.php') ?>"><a href="admin_products.php">Quản lý Sản phẩm</a></li>
			<li class="<?= setActive('admin_orders.php') ?>"><a href="admin_orders.php">Quản lý Đơn hàng</a></li>
			<li class="<?= setActive('admin_users.php') ?>"><a href="admin_users.php">Quản lý Người dùng</a></li>
			<li class="<?= setActive('admin_categories.php') ?>"><a href="admin_categories.php">Quản lý Danh mục</a></li>
			<li class="<?= setActive('admin_reviews.php') ?>"><a href="admin_reviews.php">Quản lý Đánh giá</a></li>
			<li class="<?= setActive('admin_contacts.php') ?>"><a href="admin_contacts.php">Liên hệ Khách hàng</a></li>
			<li><a href="../logout.php">Đăng xuất</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h2>Chào mừng, Admin</h2>
        </div>

        <div class="table-container">
            <p>Chọn một danh mục từ menu để quản lý.</p>
        </div>
    </div>

</body>
</html>
