<?php
include('../db_connect.php');

// Lấy ID đánh giá từ URL
$review_id = $_GET['id'];

// Xóa đánh giá khỏi cơ sở dữ liệu
$sql = "DELETE FROM reviews WHERE id = $review_id";
if (mysqli_query($conn, $sql)) {
    header('Location: admin_reviews.php');
} else {
    echo "Lỗi khi xóa đánh giá: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
