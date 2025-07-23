<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// Lấy thông tin người dùng cần chỉnh sửa
$id = $_GET['id'];
$user = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    
    // Cập nhật thông tin người dùng
    $sql = "UPDATE users SET name='$name', email='$email', role='$role' WHERE id=$id";
    if ($conn->query($sql)) {
        header("Location: admin_users.php");
        exit();
    } else {
        $error = "Lỗi: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh Sửa Người Dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .container {
            max-width: 500px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-primary { width: 100%; }
    </style>
</head>
<body>

<div class="container">
    <h3 class="text-center">✏️ Chỉnh Sửa Người Dùng</h3>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Tên người dùng</label>
            <input type="text" name="name" class="form-control" value="<?= $user['name'] ?>" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= $user['email'] ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Vai trò</label>
            <select name="role" class="form-select">
                <option value="admin" <?= ($user['role'] == 'admin') ? 'selected' : '' ?>>Admin</option>
                <option value="customer" <?= ($user['role'] == 'customer') ? 'selected' : '' ?>>Khách hàng</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Cập Nhật</button>
    </form>

    <div class="mt-3 text-center">
        <a href="admin_users.php" class="btn btn-outline-secondary">⬅ Quay lại</a>
    </div>
</div>

</body>
</html>
