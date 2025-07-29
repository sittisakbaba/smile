<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$results = $conn->query("SELECT * FROM evaluations WHERE user_id = $user_id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ผลการประเมินย้อนหลัง</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            font-size: 20px;
            background-color: #f5dce0;
        }
        .container {
            max-width: 900px;
            margin-top: 40px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .badge {
            font-size: 18px;
        }
    </style>
</head>
<body>
<div class="container">
    <h4 class="text-center mb-4"><b>ผลการประเมินโรคซึมเศร้าย้อนหลัง</b></h4>

    <table class="table table-bordered text-center align-middle">
        <thead class="table-light">
            <tr>
                <th>วันที่</th>
                <th>คะแนนรวม</th>
                <th>ระดับความรุนแรง</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $results->fetch_assoc()): ?>
            <tr>
                <td><?= date("d/m/Y H:i", strtotime($row['created_at'])) ?></td>
                <td><?= $row['total_score'] ?></td>
                <td>
                    <span class="badge px-3 py-2" style="
                        <?php
                            switch ($row['danger_level']) {
                                case 'น้อยมาก': echo 'background-color:#006400; color:white;'; break;
                                case 'เล็กน้อย': echo 'background-color:#90ee90; color:black;'; break;
                                case 'ปานกลาง': echo 'background-color:#dda0dd; color:black;'; break;
                                case 'ค่อนข้างมาก': echo 'background-color:#1e90ff; color:white;'; break;
                                case 'รุนแรงมาก': echo 'background-color:#ffa500; color:black;'; break;
                                default: echo 'background-color:gray; color:white;'; break;
                            }
                        ?>">
                        <?= $row['danger_level'] ?>
                    </span>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <div class="text-center">
        <a href="dashboard.php" class="btn btn-secondary mt-3">ย้อนกลับ</a>
    </div>
</div>
</body>
</html>
