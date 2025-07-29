<?php
$servername = "localhost";
$username = "root";
$password = "12345678";  // เปลี่ยนให้ตรงกับเครื่องของคุณ
$dbname = "depression_system";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}
?>
