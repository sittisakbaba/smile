<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'doctor') {
    header("Location: ../auth/login.php");
    exit();
}
// ดึงข้อมูลผู้ใช้ที่ล็อกอิน
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();


// ดึงผลประเมินล่าสุดของแต่ละ user
$sql = "
SELECT e.user_id, e.total_score
FROM evaluations e
JOIN (
    SELECT user_id, MAX(created_at) AS latest_eval
    FROM evaluations
    GROUP BY user_id
) latest ON e.user_id = latest.user_id AND e.created_at = latest.latest_eval
";

$result = $conn->query($sql);

$total_users = 0;
$low = $mild = $moderate = $high = $severe = 0;

while ($row = $result->fetch_assoc()) {
    $total_users++;
    $score = $row['total_score'];
    if ($score >= 0 && $score <= 4) $low++;
    elseif ($score <= 8) $mild++;
    elseif ($score <= 14) $moderate++;
    elseif ($score <= 19) $high++;
    else $severe++;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Dashboard หมอ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; background-color: #f4f6f9; }
        .sidebar {
            height: 100vh;
            position: fixed;
            width: 250px;
            background-color: #1976d2;
            color: white;
            padding-top: 1rem;
        }
        .sidebar a {
            color: white;
            padding: 12px;
            display: block;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #1565c0;
        }
        .content {
            margin-left: 260px;
            padding: 30px;
        }
        .card-box {
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h4 class="text-center mb-4"><i class="fas fa-user-md"></i><?= htmlspecialchars($user['fullname']) ?></h4>
    <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
    <a href="check_results.php"><i class="fas fa-clipboard-list"></i> ผลประเมิน</a>
    <a href="edit_status.php"><i class="fas fa-edit"></i> แก้ไขระดับ</a>
    <a href="update_treatment.php"><i class="fas fa-syringe"></i> อัปเดตการรักษา</a>
    <a href="report.php"><i class="fas fa-chart-bar"></i> รายงาน</a>
    <a href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i> ออกจากระบบ</a>
</div>

<div class="content">
    <h3>📊 Dashboard สรุปผลล่าสุด</h3>

    <div class="row text-center mb-4">
        <div class="col-md-4">
            <div class="card card-box bg-primary text-white">
                <div class="card-body">
                    <h5>👥 ผู้ประเมินทั้งหมด</h5>
                    <h2><?= $total_users ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row text-center">
        <div class="col-md-2">
            <div class="card card-box bg-success text-white">
                <div class="card-body">
                    <h6>น้อยมาก</h6>
                    <h4><?= $low ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-box bg-info text-white">
                <div class="card-body">
                    <h6>เล็กน้อย</h6>
                    <h4><?= $mild ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-box" style="background-color: #ba68c8; color: white;">
                <div class="card-body">
                    <h6>ปานกลาง</h6>
                    <h4><?= $moderate ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-box" style="background-color: #64b5f6; color: white;">
                <div class="card-body">
                    <h6>ค่อนข้างมาก</h6>
                    <h4><?= $high ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-box bg-warning text-dark">
                <div class="card-body">
                    <h6>รุนแรงมาก</h6>
                    <h4><?= $severe ?></h4>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
