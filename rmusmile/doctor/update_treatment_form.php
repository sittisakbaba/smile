<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'doctor') {
    header("Location: ../auth/login.php");
    exit();
}

$doctor_id = $_SESSION['user']['id'];
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if ($user_id <= 0) {
    echo "<script>alert('ไม่พบผู้ป่วย'); window.location='update_treatment.php';</script>";
    exit();
}

// ดึงชื่อผู้ป่วย
$stmt = $conn->prepare("SELECT fullname FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows == 0) {
    echo "<script>alert('ไม่พบผู้ป่วย'); window.location='update_treatment.php';</script>";
    exit();
}
$patient = $res->fetch_assoc();

// เมื่อหมอกดบันทึก
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $details = $_POST['details'];
    $stmt = $conn->prepare("INSERT INTO treatments (user_id, doctor_id, details) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $doctor_id, $details);
    if ($stmt->execute()) {
        echo "<script>alert('บันทึกการรักษาสำเร็จ'); window.location='update_treatment.php';</script>";
        exit();
    } else {
        echo "<script>alert('เกิดข้อผิดพลาด: " . $stmt->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>อัปเดตการรักษา</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            font-size: 18px;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 700px;
            margin-top: 40px;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<div class="container">
    <h4 class="text-center mb-4">อัปเดตการรักษาสำหรับ: <?= htmlspecialchars($patient['fullname']) ?></h4>

    <form method="post">
        <div class="mb-3">
            <label for="details" class="form-label">รายละเอียดการรักษา</label>
            <textarea name="details" rows="5" class="form-control" required></textarea>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-success">💾 บันทึก</button>
            <a href="update_treatment.php" class="btn btn-secondary">ย้อนกลับ</a>
        </div>
    </form>
</div>
</body>
</html>
