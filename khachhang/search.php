<?php
session_start();
include '../db_connect.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả tìm kiếm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../csskhachhang/styles.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="container mt-3">
    <div class="row">
        <!-- Cột trái: Thông tin hỗ trợ và danh mục -->
        <div class="col-md-3">
            <div class="support-box p-3 mb-3">
                <h5>LIÊN HỆ TRỰC TUYẾN</h5>
                <img src="../images/hostline.jpg" alt="Hotline">
                <div class="contact-info">
                    <h4>HOTLINE</h4>
                    <p class="phone"><i class="fas fa-phone"></i> 0123 456 789</p>
                    <p class="email">123456@gmail.com</p>
                </div>
            </div>

            <div class="category-box p-3 mb-3">
                <h5>DANH MỤC SẢN PHẨM</h5>
                <ul class="list-group">
                    <?php
                    $cat_sql = "SELECT * FROM categories";
                    $cat_result = $conn->query($cat_sql);
                    while ($cat = $cat_result->fetch_assoc()) {
                        echo '<li class="list-group-item">
                                <a href="index.php?category=' . $cat['id'] . '">' . htmlspecialchars($cat['name']) . '</a>
                              </li>';
                    }
                    ?>
                </ul>
            </div>
        </div>

        <!-- Cột phải: Kết quả tìm kiếm -->
        <div class="col-md-9">
            <h4 class="section-title">KẾT QUẢ TÌM KIẾM</h4>

            <?php
            $keyword = '';
            if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
                $keyword = trim($_GET['q']);
                $escaped_keyword = mysqli_real_escape_string($conn, $keyword);
                $sql = "SELECT * FROM products WHERE name LIKE '%$escaped_keyword%' ORDER BY id DESC";
                $result = $conn->query($sql);

                echo "<p class='mb-3'>Từ khóa: <strong>" . htmlspecialchars($keyword) . "</strong></p>";

                if ($result->num_rows > 0) {
                    echo '<div class="sanpham-container">';
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="product-card">';
                        echo '<a href="product_detail.php?id=' . $row['id'] . '">';
                        echo '<img src="../images/dogiadung/' . $row['image'] . '" alt="' . $row['name'] . '" class="product-image">';
                        echo '<p class="product-name">' . $row['name'] . '</p>';
                        echo '<p class="product-price">Đơn giá: ' . number_format($row['price'], 0, ',', '.') . ' VNĐ</p>';
                        echo '<button class="buy-button">Chọn mua</button>';
                        echo '</a>';
                        echo '</div>';
                    }
                    echo '</div>';
                } else {
                    echo "<p class='text-danger'>Không tìm thấy sản phẩm nào phù hợp.</p>";
                }
            } else {
                echo "<p class='text-warning'>Vui lòng nhập từ khóa để tìm kiếm.</p>";
            }
            ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
