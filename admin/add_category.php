<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);

    if (!empty($name)) {
        $sql = "INSERT INTO categories (name) VALUES ('$name')";
        if ($conn->query($sql)) {
            header("Location: admin_categories.php");
            exit();
        } else {
            $error = "Lỗi: " . $conn->error;
        }
    } else {
        $error = "Vui lòng nhập tên danh mục.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Danh Mục</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .container {
            max-width: 400px;
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
    <h3 class="text-center">📂 Thêm Danh Mục Mới</h3>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Tên danh mục</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Thêm Danh Mục</button>
    </form>

    <div class="mt-3 text-center">
        <a href="admin_categories.php" class="btn btn-outline-secondary">⬅ Quay lại</a>
    </div>
</div>

</body>
</html>
