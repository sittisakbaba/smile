<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header("Location: ../auth/login.php");
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แดชบอร์ดผู้ใช้งาน</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('../assets/img/bg.png');
            background-repeat: no-repeat;
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
            font-family: 'Sarabun', sans-serif;
            background-color: #f8f9fa;
            font-size: 20px;
        }
        .dashboard-box {
            max-width: 650px;
            margin: 60px auto;
            padding: 40px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
        }
        .btn-menu {
            width: 100%;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .navbar-brand {
            font-size: 22px;
        }
    </style>
</head>
<body>

<!-- ✅ Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">ระบบประเมินสุขภาพจิต</a>
        <div class="ms-auto text-white pe-3">
            👤 <?= htmlspecialchars($user['fullname']) ?>
        </div>
    </div>
</nav>
<br><br>

<!-- ✅ กล่องเมนู -->
<div class="dashboard-box">
    <h4 class="mb-4">👋 ยินดีต้อนรับคุณ <?= htmlspecialchars($user['fullname']) ?></h4>

    <a href="evaluate.php" class="btn btn-primary btn-menu">📝 ทำแบบประเมิน</a>
    <a href="results.php" class="btn btn-success btn-menu">📊 ผลการประเมิน</a>
    <a href="edit_profile.php" class="btn btn-warning btn-menu">✏️ แก้ไขข้อมูลส่วนตัว</a>

    <hr>
    <a href="../auth/logout.php" class="btn btn-outline-danger mt-3">🚪 ออกจากระบบ</a>
</div>

</body>
</html>
