<!-- doctor/layout.php -->
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Doctor Panel</title>
    <link rel="stylesheet" href="../assets/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/fontawesome.min.css">
    <style>
        body { font-family: 'TH Sarabun New', sans-serif; font-size: 20px; }
        .sidebar {
            height: 100vh;
            background: #f8f9fa;
            padding: 1rem;
            position: fixed;
            width: 250px;
        }
        .sidebar a {
            display: block;
            padding: 10px;
            margin-bottom: 8px;
            color: #000;
            text-decoration: none;
            border-radius: 5px;
        }
        .sidebar a:hover {
            background: #e2e6ea;
        }
        .content {
            margin-left: 270px;
            padding: 2rem;
        }
                body {
            font-family: 'Sarabun', sans-serif;
            font-size: 20px;
            background-color: #f5f5f5;
        }
        .dashboard-container {
            max-width: 800px;
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

<div class="sidebar">
    <h4>สวัสดี <?= htmlspecialchars($user['fullname']) ?></h4>
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="check_results.php">📋 ผลการประเมิน</a>
    <a href="edit_status.php">🛠 แก้ไขระดับ</a>
    <a href="update_treatment.php">💊 อัปเดตการรักษา</a>
    <a href="report.php">📈 รายงาน</a>
    <a href="../auth/logout.php">🔒 ออกจากระบบ</a>
</div>

<div class="content">
