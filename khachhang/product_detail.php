<?php
session_start();
include '../db_connect.php';

if (!isset($_GET['id'])) {
    echo "<p class='text-danger'>Không tìm thấy sản phẩm!</p>";
    exit();
}

$product_id = intval($_GET['id']);
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "<p class='text-danger'>Sản phẩm không tồn tại!</p>";
    exit();
}

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    echo "<p class='text-danger'>Vui lòng đăng nhập để đánh giá sản phẩm!</p>";
    exit();
}

// Xử lý việc đánh giá sản phẩm
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['rating'])) {
    $rating = intval($_POST['rating']);
    $comment = htmlspecialchars(trim($_POST['comment']));
    
    // Kiểm tra đánh giá hợp lệ
    if ($rating >= 1 && $rating <= 5) {
        $user_id = $_SESSION['user_id']; // Giả sử người dùng đã đăng nhập
        $insert_sql = "INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("iiis", $product_id, $user_id, $rating, $comment);
        if ($insert_stmt->execute()) {
            echo "<p class='text-success'>Đánh giá của bạn đã được lưu!</p>";
        } else {
            echo "<p class='text-danger'>Có lỗi xảy ra khi lưu đánh giá.</p>";
        }
    } else {
        echo "<p class='text-danger'>Đánh giá không hợp lệ!</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Chi Tiết Sản Phẩm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../csskhachhang/styles.css">
    <style>
        .product-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 8px;
        }
        .related-products img {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }
        .review-section {
            margin-top: 30px;
        }
        .review-item {
            border-bottom: 1px solid #ddd;
            margin-bottom: 10px;
            padding-bottom: 10px;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-6">
            <img src="../images/dogiadung/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
        </div>
        <div class="col-md-6">
            <h2><?php echo htmlspecialchars($product['name']); ?></h2>
            <p class="text-danger fs-4">Giá: <?php echo number_format($product['price'], 0, ',', '.'); ?> VNĐ</p>
            <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
            <form action="add_to_cart.php" method="post">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <label for="quantity">Số lượng:</label>
                <input type="number" name="quantity" id="quantity" value="1" min="1" class="form-control w-25 mb-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-cart-plus"></i> Thêm vào giỏ hàng</button>
            </form>

            <!-- Nút "Mua ngay" -->
            <form action="checkout.php" method="POST">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" name="buy_now" class="btn btn-success mt-3">
                    <i class="fas fa-shopping-cart"></i> Mua ngay
                </button>
            </form>
        </div>
    </div>

    <!-- Sản phẩm liên quan -->
    <h3 class="mt-5">Sản phẩm liên quan</h3>
    <div class="row related-products">
        <?php
        $related_sql = "SELECT * FROM products WHERE id != ? ORDER BY RAND() LIMIT 4";
        $related_stmt = $conn->prepare($related_sql);
        $related_stmt->bind_param("i", $product_id);
        $related_stmt->execute();
        $related_result = $related_stmt->get_result();
        
        while ($related = $related_result->fetch_assoc()) {
            echo '<div class="col-md-3 text-center">';
            echo '<a href="product_detail.php?id=' . $related['id'] . '">';
            echo '<img src="../images/dogiadung/' . htmlspecialchars($related['image']) . '" alt="' . htmlspecialchars($related['name']) . '" class="img-fluid">';
            echo '<p>' . htmlspecialchars($related['name']) . '</p>';
            echo '</a>';
            echo '</div>';
        }
        ?>
    </div>

    <!-- Đánh giá sản phẩm -->
    <div class="review-section">
        <h3>Đánh giá sản phẩm</h3>
        <form action="" method="POST">
			<label for="rating">Đánh giá (1-5):</label>
			<select name="rating" id="rating" class="form-control w-25 mb-3">
				<option value="1">1 sao</option>
				<option value="2">2 sao</option>
				<option value="3">3 sao</option>
				<option value="4">4 sao</option>
				<option value="5">5 sao</option>
			</select>
			<textarea name="comment" class="form-control mb-3" rows="4" placeholder="Viết nhận xét..."></textarea>
			<button type="submit" class="btn btn-primary">Đánh giá</button>
		</form>
		
        <h4>Những đánh giá khác</h4>
        <?php
        // Lấy danh sách đánh giá từ bảng reviews
        $review_sql = "SELECT * FROM reviews WHERE product_id = ? ORDER BY created_at DESC";
        $review_stmt = $conn->prepare($review_sql);
        $review_stmt->bind_param("i", $product_id);
        $review_stmt->execute();
        $review_result = $review_stmt->get_result();
        
        while ($review = $review_result->fetch_assoc()) {
            $rating = $review['rating'];
            echo '<div class="review-item">';
            echo '<p><strong>Đánh giá: ' . $rating . ' sao</strong></p>';
            echo '<p>' . nl2br(htmlspecialchars($review['comment'])) . '</p>';
            echo '<p><small>Ngày tạo: ' . $review['created_at'] . '</small></p>';
            echo '</div>';
        }
        ?>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
