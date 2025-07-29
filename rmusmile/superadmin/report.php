<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'superadmin') {
    header("Location: ../auth/login.php");
    exit();
}



// ดึงสังกัดหลัก
$affRes = $conn->query("SELECT id, name FROM affiliations");
$affiliations = $affRes->fetch_all(MYSQLI_ASSOC);

// ดึงสังกัดย่อย
$subRes = $conn->query("SELECT id, name FROM sub_affiliations");
$sub_affiliations = $subRes->fetch_all(MYSQLI_ASSOC);

// รับค่าฟอร์ม
$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-t');
$aff_id = $_GET['affiliation_id'] ?? '';
$sub_id = $_GET['sub_affiliation_id'] ?? '';

// WHERE เงื่อนไข
$where = "e.created_at BETWEEN '$start_date' AND '$end_date'";
if (!empty($aff_id)) {
    $where .= " AND u.affiliation_id = '$aff_id'";
}
if (!empty($sub_id)) {
    $where .= " AND u.sub_affiliation_id = '$sub_id'";
}

// ดึงระดับ
$sql = "
SELECT e.danger_level, COUNT(*) AS count 
FROM evaluations e
INNER JOIN users u ON u.id = e.user_id
WHERE $where
GROUP BY e.danger_level
";
$level_data = [];
$res = $conn->query($sql);
while ($row = $res->fetch_assoc()) {
    $level_data[$row['danger_level']] = $row['count'];
}

// ดึงจำนวนรักษา
$sql2 = "
SELECT COUNT(*) AS treated_count 
FROM treatments t
INNER JOIN users u ON u.id = t.user_id
WHERE t.treated_at BETWEEN '$start_date' AND '$end_date'"
. (!empty($aff_id) ? " AND u.affiliation_id = '$aff_id'" : '')
. (!empty($sub_id) ? " AND u.sub_affiliation_id = '$sub_id'" : '');
$treated = $conn->query($sql2)->fetch_assoc()['treated_count'] ?? 0;
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายงานการประเมิน</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            font-size: 18px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1100px;
            margin-top: 40px;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .form-label {
            font-weight: bold;
        }
        canvas {
    height: 400px !important;
}
    </style>
</head>
<body>
<div class="container">
    <h4 class="text-center mb-4">รายงานการประเมินและการรักษา</h4>

    <!-- ฟอร์มกรอง -->
    <form class="row g-2 mb-4" method="get">
        <div class="col-md-3">
            <label class="form-label">จากวันที่</label>
            <input type="date" name="start_date" value="<?= $start_date ?>" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">ถึงวันที่</label>
            <input type="date" name="end_date" value="<?= $end_date ?>" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">สังกัด</label>
            <select name="affiliation_id" class="form-select">
                <option value="">-- ทุกสังกัด --</option>
                <?php foreach ($affiliations as $a): ?>
                    <option value="<?= $a['id'] ?>" <?= ($aff_id == $a['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($a['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">สังกัดย่อย</label>
            <select name="sub_affiliation_id" class="form-select">
                <option value="">-- ทุกสังกัดย่อย --</option>
                <?php foreach ($sub_affiliations as $s): ?>
                    <option value="<?= $s['id'] ?>" <?= ($sub_id == $s['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($s['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-12 text-end">
            <button type="submit" class="btn btn-primary">กรอง</button>
            <a href="report_excel.php?start_date=<?= $start_date ?>&end_date=<?= $end_date ?>&affiliation_id=<?= $aff_id ?>&sub_affiliation_id=<?= $sub_id ?>" class="btn btn-success ms-2">
                📥 ส่งออก Excel
            </a>
        </div>
    </form>

    <!-- ตารางรายงาน -->
    <table class="table table-bordered text-center align-middle">
        <thead class="table-light">
            <tr>
                <th>ช่วงวันที่</th>
                <th>น้อยมาก</th>
                <th>เล็กน้อย</th>
                <th>ปานกลาง</th>
                <th>ค่อนข้างมาก</th>
                <th>รุนแรงมาก</th>
                <th>ได้รับการรักษา</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= date("d/m/Y", strtotime($start_date)) ?> - <?= date("d/m/Y", strtotime($end_date)) ?></td>
                <td><?= $level_data['น้อยมาก'] ?? 0 ?></td>
                <td><?= $level_data['เล็กน้อย'] ?? 0 ?></td>
                <td><?= $level_data['ปานกลาง'] ?? 0 ?></td>
                <td><?= $level_data['ค่อนข้างมาก'] ?? 0 ?></td>
                <td><?= $level_data['รุนแรงมาก'] ?? 0 ?></td>
                <td><?= $treated ?></td>
            </tr>
        </tbody>
    </table>

    <div class="mt-5 text-center">
        
        <div style="max-width: 700px; margin: auto;">
            <canvas id="barChart"></canvas>
        </div>
    </div>

    <div class="text-center mt-3">
        <a href="dashboard.php" class="btn btn-secondary">ย้อนกลับ</a>
    </div>
</div>
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('barChart').getContext('2d');

const levelData = {
    "น้อยมาก": <?= $level_data['น้อยมาก'] ?? 0 ?>,
    "เล็กน้อย": <?= $level_data['เล็กน้อย'] ?? 0 ?>,
    "ปานกลาง": <?= $level_data['ปานกลาง'] ?? 0 ?>,
    "ค่อนข้างมาก": <?= $level_data['ค่อนข้างมาก'] ?? 0 ?>,
    "รุนแรงมาก": <?= $level_data['รุนแรงมาก'] ?? 0 ?>
};

const labels = Object.keys(levelData);
const data = Object.values(levelData);

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'จำนวนผู้ประเมิน',
            data: data,
            backgroundColor: [
                '#006400',
                '#90ee90',
                '#dda0dd',
                '#1e90ff',
                '#ffa500'
            ],
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            title: {
                display: true,
                text: 'ระดับความรุนแรงจากผลประเมิน',
                font: { size: 20 }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});

</script>

</body>
</html>
