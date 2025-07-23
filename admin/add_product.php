<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $image = "";

    // Kiểm tra và tạo thư mục uploads nếu chưa có
		$target_dir = "../uploads/";
		if (!is_dir($target_dir)) {
			mkdir($target_dir, 0777, true);
		}

		$target_file = $target_dir . basename($_FILES["image"]["name"]);
		$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

		// Kiểm tra định dạng ảnh
		$allowed_types = ["jpg", "jpeg", "png", "gif"];
		if (in_array($imageFileType, $allowed_types)) {
			if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
				$image = basename($_FILES["image"]["name"]);
			} else {
				$error = "Lỗi khi tải ảnh lên.";
			}
		} else {
			$error = "Chỉ chấp nhận file JPG, JPEG, PNG, GIF.";
		}

    if (!$error) {
        $sql = "INSERT INTO products (name, price, category_id, image) VALUES ('$name', '$price', '$category_id', '$image')";
        if ($conn->query($sql)) {
            header("Location: admin_products.php");
            exit();
        } else {
            $error = "Lỗi: " . $conn->error;
        }
    }
}

$categories = $conn->query("SELECT * FROM categories");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sản Phẩm</title>
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
    <h3 class="text-center">➕ Thêm Sản Phẩm Mới</h3>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Tên sản phẩm</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Giá sản phẩm</label>
            <input type="number" name="price" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Danh mục</label>
            <select name="category_id" class="form-select">
                <?php while ($cat = $categories->fetch_assoc()) { ?>
                    <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Ảnh sản phẩm</label>
            <input type="file" name="image" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Thêm Sản Phẩm</button>
    </form>

    <div class="mt-3 text-center">
        <a href="admin_products.php" class="btn btn-outline-secondary">⬅ Quay lại</a>
    </div>
</div>

</body>
</html>
