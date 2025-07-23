<?php
session_start();
include '../db_connect.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Lấy danh sách đánh giá
$sql = "SELECT r.id, r.rating, r.comment, r.created_at, p.name AS product_name, u.name AS customer_name, r.status
        FROM reviews r
        JOIN products p ON r.product_id = p.id
        JOIN users u ON r.user_id = u.id
        ORDER BY r.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Đánh giá</title>
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
        <a href="admin_reviews.php" class="active">Quản lý Đánh giá</a>
        <a href="admin_contacts.php">Liên hệ khách hàng</a>
        <a href="../logout.php">Đăng xuất</a>
    </div>
    <div class="main-content">
        <h2>Quản lý Đánh giá Khách hàng</h2>
        
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Sản phẩm</th>
                <th>Khách hàng</th>
                <th>Đánh giá</th>
                <th>Bình luận</th>
                <th>Ngày tạo</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['product_name']) ?></td>
                    <td><?= htmlspecialchars($row['customer_name']) ?></td>
                    <td><?= $row['rating'] ?>/5</td>
                    <td><?= nl2br(htmlspecialchars($row['comment'])) ?></td>
                    <td><?= $row['created_at'] ?></td>
                    <td>
                        <?php if (isset($row['status']) && $row['status'] == 'approved'): ?>
                            <span style="color: green;">Đã duyệt</span>
                        <?php else: ?>
                            <span style="color: red;">Chưa duyệt</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (isset($row['status']) && $row['status'] != 'approved'): ?>
                            <a href="approve_review.php?id=<?= $row['id'] ?>">Duyệt</a> / 
                        <?php endif; ?>
                        <a href="delete_review.php?id=<?= $row['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa đánh giá này?');">Xóa</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
