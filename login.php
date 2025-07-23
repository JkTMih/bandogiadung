<?php
session_start();
include 'db_connect.php';

$error = ""; // Khởi tạo biến trước

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Truy vấn kiểm tra tài khoản
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();
	if ($user) {
    echo "Mật khẩu trong database: " . $user['password']; // Kiểm tra mật khẩu lấy từ database
}

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role']; // Lưu vai trò người dùng (admin hoặc khách)

        // Điều hướng dựa trên vai trò
        if ($user['role'] == 'admin') {
            header("Location: admin/admin_dashboard.php");
        } else {
            header("Location: khachhang/index.php");
        }
    } else {
        $error = "Sai thông tin đăng nhập!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng Nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .container {
            max-width: 400px; margin: 50px auto; background: white;
            padding: 20px; border-radius: 10px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<div class="container">
    <h3 class="text-center">🔑 Đăng Nhập</h3>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mật khẩu</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Đăng Nhập</button>
    </form>

    <div class="mt-3 text-center">
        <a href="register.php">Chưa có tài khoản? Đăng ký ngay</a>
    </div>
</div>

</body>
</html>
