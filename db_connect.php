<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "household_store";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>

