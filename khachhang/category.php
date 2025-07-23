<?php
session_start();
include '../db_connect.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Mục Sản Phẩm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../csskhachhang/styles.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="container mt-3">
    <div class="row">
        <!-- Cột danh mục -->
        <div class="col-md-3">
            <div class="category-box p-3 mb-3">
                <h5>DANH MỤC SẢN PHẨM</h5>
                <ul class="list-group">
                    <?php
                    $cat_sql = "SELECT * FROM categories";
                    $cat_result = $conn->query($cat_sql);
                    while ($cat = $cat_result->fetch_assoc()) {
                        echo '<li class="list-group-item">
                                <a href="category.php?category=' . $cat['id'] . '">' . htmlspecialchars($cat['name']) . '</a>
                              </li>';
                    }
                    ?>
                </ul>
            </div>
        </div>

        <!-- Cột sản phẩm -->
        <div class="col-md-9">
            <div class="row">
                <?php
                if (isset($_GET['category']) && is_numeric($_GET['category'])) {
                    $category_id = intval($_GET['category']);
                    $sql = "SELECT * FROM products WHERE category_id = $category_id ORDER BY id DESC";
                } else {
                    $sql = "SELECT * FROM products ORDER BY id DESC LIMIT 6";
                }

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="col-md-4 mb-4">';
                        echo '<div class="product-card">';
                        echo '<a href="product_detail.php?id='.$row['id'].'">';
                        echo '<img src="../images/dogiadung/'.$row['image'].'" alt="'.$row['name'].'" class="product-image">';
                        echo '<p class="product-name">'.$row['name'].'</p>';
                        echo '<p class="product-price">Đơn giá: '.number_format($row['price'], 0, ',', '.').' VNĐ</p>';
                        echo '<button class="buy-button">Chọn mua</button>';
                        echo '</a>';
                        echo '</div>';              
                        echo '</div>';
                    }
                } else {
                    echo "<p>Không có sản phẩm nào trong danh mục này.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
