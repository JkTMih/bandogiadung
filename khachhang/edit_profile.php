<?php
// Hiển thị lỗi để debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include '../db_connect.php';

// Kiểm tra nếu khách hàng chưa đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Xử lý khi người dùng gửi form cập nhật
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form, đảm bảo không có lỗi undefined index
    $name = isset($_POST['name']) ? trim($_POST['name']) : "";
    $email = isset($_POST['email']) ? trim($_POST['email']) : "";
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : "";
    $address = isset($_POST['address']) ? trim($_POST['address']) : "";

    // Kiểm tra dữ liệu hợp lệ
    if (empty($name) || empty($email) || empty($phone) || empty($address)) {
        $_SESSION['message'] = "Vui lòng nhập đầy đủ thông tin.";
        header("Location: edit_profile.php");
        exit();
    }

    // Cập nhật thông tin người dùng trong database
    $sql_update = "UPDATE users SET name=?, email=?, phone=?, address=? WHERE id=?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("ssssi", $name, $email, $phone, $address, $user_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Cập nhật thông tin thành công!";
    } else {
        $_SESSION['message'] = "Lỗi cập nhật: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();

    // Chuyển hướng về trang thông tin cá nhân
    header("Location: profile.php");
    exit();
}

// Lấy dữ liệu hiện tại của khách hàng
$sql_user = "SELECT name, email, phone, address FROM users WHERE id=?";
$stmt = $conn->prepare($sql_user);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();

// Kiểm tra nếu không có dữ liệu (trường hợp lỗi)
if (!$user) {
    $_SESSION['message'] = "Không tìm thấy thông tin người dùng.";
    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh Sửa Thông Tin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="container mt-4">
    <h2 class="text-primary">Chỉnh Sửa Thông Tin Cá Nhân</h2>

    <!-- Hiển thị thông báo nếu có -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info">
            <?php 
                echo $_SESSION['message']; 
                unset($_SESSION['message']); // Xóa thông báo sau khi hiển thị
            ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm p-4">
        <form method="post" action="">
            <div class="mb-3">
                <label for="name" class="form-label">Họ và Tên</label>
                <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Số Điện Thoại</label>
                <input type="text" name="phone" id="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Địa Chỉ</label>
                <input type="text" name="address" id="address" class="form-control" value="<?= htmlspecialchars($user['address'] ?? '') ?>" required>
            </div>

            <button type="submit" class="btn btn-success">Lưu Thay Đổi</button>
            <a href="profile.php" class="btn btn-secondary">Hủy</a>
        </form>
    </
