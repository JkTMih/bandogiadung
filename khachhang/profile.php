<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../db_connect.php';

// Kiểm tra nếu khách hàng đã đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Lấy thông tin khách hàng từ database
$sql_user = "SELECT name, email, phone, address FROM users WHERE id = ?";
$stmt = $conn->prepare($sql_user);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc() ?? [];
$stmt->close();
$conn->close(); // Đóng kết nối

// Định nghĩa giá trị mặc định để tránh lỗi TypeError
$name = $user['name'] ?? "Chưa cập nhật";
$email = $user['email'] ?? "Chưa cập nhật";
$phone = $user['phone'] ?? "Chưa cập nhật";
$address = $user['address'] ?? "Chưa cập nhật";
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin Khách Hàng</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="container mt-4">
    <!-- Hiển thị thông báo nếu có -->
    <?php
    if (isset($_SESSION['message'])) {
        echo "<div class='alert alert-success text-center'>" . $_SESSION['message'] . "</div>";
        unset($_SESSION['message']); // Xóa thông báo sau khi hiển thị
    }
    ?>

    <h2 class="text-primary">Thông Tin Khách Hàng</h2>
    <div class="card shadow-sm p-4">
        <h4><?php echo htmlspecialchars($name); ?></h4>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($phone); ?></p>
        <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($address); ?></p>
        <a href="edit_profile.php" class="btn btn-warning">Chỉnh sửa thông tin</a>
    </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
