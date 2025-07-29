<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'doctor') {
    header("Location: ../auth/login.php");
    exit();
}

// ดึงเฉพาะการประเมินล่าสุดของแต่ละ user ที่ระดับ >= ค่อนข้างมาก
$sql = "
SELECT u.fullname, e.total_score, e.danger_level, e.created_at, u.id AS user_id
FROM evaluations e
INNER JOIN (
    SELECT user_id, MAX(created_at) AS latest
    FROM evaluations
    GROUP BY user_id
) latest_eval ON e.user_id = latest_eval.user_id AND e.created_at = latest_eval.latest
INNER JOIN users u ON u.id = e.user_id
WHERE e.danger_level IN ('ค่อนข้างมาก', 'รุนแรงมาก')
ORDER BY e.created_at DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ผู้ป่วยที่ต้องเฝ้าระวัง</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            font-size: 18px;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 960px;
            margin-top: 40px;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .badge {
            font-size: 16px;
        }
    </style>
</head>
<body>
<div class="container">
    <h4 class="mb-4 text-center">รายชื่อผู้ป่วยที่มีความเสี่ยงสูง</h4>
    <table class="table table-bordered text-center align-middle">
        <thead class="table-light">
            <tr>
                <th>ชื่อผู้ป่วย</th>
                <th>คะแนน</th>
                <th>ระดับ</th>
                <th>วันที่ประเมิน</th>
                <th>การจัดการ</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['fullname']) ?></td>
                <td><?= $row['total_score'] ?></td>
                <td>
                    <span class="badge px-3 py-2" style="
                        <?php
                            switch ($row['danger_level']) {
                                case 'ค่อนข้างมาก': echo 'background-color:#1e90ff; color:white;'; break;
                                case 'รุนแรงมาก': echo 'background-color:#ffa500; color:black;'; break;
                            }
                        ?>">
                        <?= $row['danger_level'] ?>
                    </span>
                </td>
                <td><?= date("d/m/Y H:i", strtotime($row['created_at'])) ?></td>
                <td>
                    <a href="edit_status.php?user_id=<?= $row['user_id'] ?>" class="btn btn-sm btn-warning">แก้ไขสถานะ</a>
                    <a href="update_treatment.php?user_id=<?= $row['user_id'] ?>" class="btn btn-sm btn-primary">อัปเดตการรักษา</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <div class="text-center">
        <a href="dashboard.php" class="btn btn-secondary">ย้อนกลับ</a>
    </div>
</div>
</body>
</html>
