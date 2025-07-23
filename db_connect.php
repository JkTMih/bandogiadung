<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "household_store";

$connection = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>

