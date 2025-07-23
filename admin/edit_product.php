<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$id = $_GET['id'];
$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
$categories = $conn->query("SELECT * FROM categories");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    
    // Xử lý ảnh nếu có tải lên mới
    if ($_FILES['image']['name']) {
        $target_dir = "../uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        
        $image = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

        // Cập nhật cả ảnh
        $sql = "UPDATE products SET name='$name', price='$price', category_id='$category_id', image='$image' WHERE id=$id";
    } else {
        // Không cập nhật ảnh
        $sql = "UPDATE products SET name='$name', price='$price', category_id='$category_id' WHERE id=$id";
    }

    if ($conn->query($sql)) {
        header("Location: admin_products.php");
    } else {
        echo "Lỗi: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh Sửa Sản Phẩm</title>
    <link rel="stylesheet" href="../admincss/editproduct.css">
</head>
<body>
    <div class="container">
        <h2>Chỉnh Sửa Sản Phẩm</h2>
        <form method="POST" enctype="multipart/form-data">
            <label>Tên sản phẩm</label>
            <input type="text" name="name" value="<?= $product['name'] ?>" required>
            
            <label>Giá sản phẩm</label>
            <input type="number" name="price" value="<?= $product['price'] ?>" required>
            
            <label>Danh mục</label>
            <select name="category_id">
                <?php while ($cat = $categories->fetch_assoc()) { ?>
                    <option value="<?= $cat['id'] ?>" <?= ($product['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                        <?= $cat['name'] ?>
                    </option>
                <?php } ?>
            </select>

            <label>Hình ảnh sản phẩm</label>
            <input type="file" name="image" accept="image/*" onchange="previewImage(event)">
            <div class="image-preview">
                <img id="preview" src="../uploads/<?= $product['image'] ?>" alt="Hình ảnh sản phẩm">
            </div>

            <button type="submit">Cập Nhật</button>
        </form>
        <a href="admin_products.php" class="back-link">Quay lại danh sách</a>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('preview');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>
