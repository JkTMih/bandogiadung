<?php
session_start();
include '../db_connect.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ - Điện Gia Dụng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../csskhachhang/styles.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="container mt-3">
    <div class="row">
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

        <!-- Cột phải: Nội dung chính -->
        <div class="col-md-9">
            <!-- Sản phẩm bán chạy -->
            <h4 class="section-title">SẢN PHẨM BÁN CHẠY</h4>
            <div class="sanpham-container">
                <?php
                // Số sản phẩm mỗi trang
                $products_per_page = 12;

                // Lấy số trang từ URL, mặc định là trang 1
                $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $offset = ($current_page - 1) * $products_per_page;

                // Lọc theo danh mục nếu có
                $category_filter = "";
                if (isset($_GET['category']) && is_numeric($_GET['category'])) {
                    $category_id = intval($_GET['category']);
                    $category_filter = " WHERE category_id = $category_id";
                }

                // Truy vấn để lấy sản phẩm theo phân trang
                $sql = "SELECT * FROM products $category_filter ORDER BY id DESC LIMIT $offset, $products_per_page";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
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
                } else {
                    echo "Chưa có sản phẩm nào.";
                }

                // Truy vấn tổng số sản phẩm để tính số trang
                $sql_count = "SELECT COUNT(*) AS total FROM products $category_filter";
                $count_result = $conn->query($sql_count);
                $total_products = $count_result->fetch_assoc()['total'];

                // Tính tổng số trang
                $total_pages = ceil($total_products / $products_per_page);

                // Hiển thị phân trang
                echo '<nav aria-label="Page navigation example">';
                echo '<ul class="pagination justify-content-center">';
                
                // Trang trước
                if ($current_page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="?page=' . ($current_page - 1) . '" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                          </a></li>';
                } else {
                    echo '<li class="page-item disabled"><span class="page-link">&laquo;</span></li>';
                }

                // Các trang
                for ($i = 1; $i <= $total_pages; $i++) {
                    $active_class = ($i == $current_page) ? ' active' : '';
                    echo '<li class="page-item' . $active_class . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                }

                // Trang sau
                if ($current_page < $total_pages) {
                    echo '<li class="page-item"><a class="page-link" href="?page=' . ($current_page + 1) . '" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                          </a></li>';
                } else {
                    echo '<li class="page-item disabled"><span class="page-link">&raquo;</span></li>';
                }

                echo '</ul>';
                echo '</nav>';
                ?>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<?php include 'footer.php'; ?>

</body>
</html>
