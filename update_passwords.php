<?php
include 'db_connect.php'; // Kết nối database

// Lấy tất cả user có mật khẩu chưa mã hóa
$result = $conn->query("SELECT id, password FROM users");
while ($user = $result->fetch_assoc()) {
    // Kiểm tra nếu mật khẩu chưa được mã hóa (chưa phải dạng hash)
    if (!password_get_info($user['password'])['algo']) {
        $hashed_password = password_hash($user['password'], PASSWORD_DEFAULT);
        $conn->query("UPDATE users SET password='$hashed_password' WHERE id=" . $user['id']);
        echo "Đã mã hóa mật khẩu cho user ID: " . $user['id'] . "<br>";
    }
}

echo "Cập nhật mật khẩu hoàn tất!";
?>
