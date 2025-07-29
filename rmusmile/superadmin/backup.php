<?php
session_start();

// ตรวจสอบสิทธิ์
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'superadmin') {
    header("Location: ../auth/login.php");
    exit();
}

// กำหนดค่าการเชื่อมต่อ
$dbHost = "localhost";
$dbUser = "root";
$dbPass = "12345678";
$dbName = "ชื่อฐานข้อมูลของคุณ"; // 🔁 เปลี่ยนชื่อตรงนี้ให้ตรง

// ไฟล์ที่จะสร้าง
$date = date("Y-m-d_H-i-s");
$backupFile = "backup_{$dbName}_{$date}.sql";

// คำสั่ง backup
$cmd = "mysqldump --user={$dbUser} --password={$dbPass} --host={$dbHost} {$dbName} > {$backupFile}";
system($cmd);

// ส่งไฟล์ให้ดาวน์โหลด
if (file_exists($backupFile)) {
    header("Content-Disposition: attachment; filename=\"$backupFile\"");
    header("Content-Type: application/octet-stream");
    readfile($backupFile);
    unlink($backupFile); // ลบหลังโหลด
    exit();
} else {
    echo "<script>alert('ไม่สามารถสำรองข้อมูลได้'); window.location='dashboard.php';</script>";
}
