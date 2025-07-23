<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Xóa người dùng khỏi database
    $sql = "DELETE FROM users WHERE id = $id";
    if ($conn->query($sql)) {
        header("Location: admin_users.php?msg=User deleted successfully");
        exit();
    } else {
        echo "Lỗi: " . $conn->error;
    }
}
?>
