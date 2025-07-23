<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$id = $_GET['id'];
$conn->query("DELETE FROM categories WHERE id=$id");
header("Location: admin_categories.php");
?>
