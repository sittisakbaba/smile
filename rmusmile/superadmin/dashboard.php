<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'superadmin') {
    header("Location: ../auth/login.php");
    exit();
}

// timeout 5 นาที
$timeout = 300;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
    session_unset();
    session_destroy();
    header("Location: ../auth/login.php?timeout=1");
    exit();
}
$_SESSION['last_activity'] = time();

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แดชบอร์ด Super Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            font-size: 20px;
            background-color: #f5f5f5;
        }
        .dashboard-container {
            max-width: 900px;
            margin: 60px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
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
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Super Admin</a>
        <div class="ms-auto text-white pe-3">
            🧑‍💼 <?= htmlspecialchars($user['fullname']) ?>
        </div>
    </div>
</nav>

<!-- ✅ Dashboard Container -->
<div class="dashboard-container">
    <h4 class="mb-4">📋 เมนูจัดการสำหรับ Super Admin</h4>

    <a href="manage_doctor.php" class="btn btn-outline-primary btn-menu">👨‍⚕️ จัดการ Doctor</a>
    <a href="manage_admin.php" class="btn btn-outline-secondary btn-menu">🛠 จัดการ Admin</a>
    <a href="manage_user.php" class="btn btn-outline-secondary btn-menu">🛠 จัดการ User</a>
    
    <a href="manage_affiliation.php" class="btn btn-outline-info btn-menu">🏛 จัดการสังกัด</a>
    <a href="manage_sub_affiliation.php" class="btn btn-outline-secondary btn-menu">🏢 จัดการสังกัดย่อย</a>
    <a href="report.php" class="btn btn-outline-dark btn-menu">📊 รายงานผล</a>
    <a href="logs.php" class="btn btn-outline-danger btn-menu">📁 ตรวจสอบ Log การใช้งาน</a>
    <a href="sql_console.php" class="btn btn-outline-secondary btn-menu">🧪 SQL Console</a>
    <a href="backup.php" class="btn btn-outline-success btn-menu">🗂 สำรองฐานข้อมูล</a>
    <hr>
    <a href="../auth/logout.php" class="btn btn-outline-danger mt-3">🚪 ออกจากระบบ</a>
</div>


</body>
</html>
