<?php
include('../db_connect.php');

// Lấy ID đánh giá từ URL
$review_id = $_GET['id'];

// Cập nhật trạng thái đánh giá thành "Đã duyệt" (hoặc trạng thái khác tùy nhu cầu)
$sql = "UPDATE reviews SET status = 'approved' WHERE id = $review_id";
if (mysqli_query($conn, $sql)) {
    header('Location: admin_reviews.php');
} else {
    echo "Lỗi khi duyệt đánh giá: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
