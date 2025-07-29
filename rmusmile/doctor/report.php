<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'doctor') {
    header("Location: ../auth/login.php");
    exit();
}

// ดึงข้อมูลล่าสุดของแต่ละ user
$sql = "
SELECT e.user_id, e.total_score,
       CASE
           WHEN e.total_score <= 4 THEN 'น้อยมาก'
           WHEN e.total_score <= 8 THEN 'เล็กน้อย'
           WHEN e.total_score <= 14 THEN 'ปานกลาง'
           WHEN e.total_score <= 19 THEN 'ค่อนข้างมาก'
           ELSE 'รุนแรงมาก'
       END AS severity
FROM evaluations e
JOIN (
    SELECT user_id, MAX(created_at) AS latest
    FROM evaluations
    GROUP BY user_id
) latest ON e.user_id = latest.user_id AND e.created_at = latest.latest
";

// ดึงรายการผู้ที่เคยได้รับการรักษา
$treat_result = $conn->query("SELECT DISTINCT user_id FROM treatments");
$treatment_users = [];
while ($row = $treat_result->fetch_assoc()) {
    $treatment_users[] = $row['user_id'];
}

// เตรียมสรุปข้อมูล
$summary = [
    'น้อยมาก' => 0,
    'เล็กน้อย' => 0,
    'ปานกลาง' => 0,
    'ค่อนข้างมาก' => 0,
    'รุนแรงมาก' => 0
];
$total = 0;
$total_treated = 0;

$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $level = $row['severity'];
    $summary[$level]++;
    $total++;
    if (in_array($row['user_id'], $treatment_users)) {
        $total_treated++;
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายงานผลการประเมิน</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body { font-family: 'Sarabun', sans-serif; font-size: 18px; background-color: #f0f2f5; }
        .container { margin-top: 40px; background: white; padding: 30px; border-radius: 10px; max-width: 1000px; }
        canvas { margin: 0 auto; display: block; max-width: 600px; }
    </style>
</head>
<body>
<div class="container">
    <h4 class="mb-4 text-center">📊 รายงานผลการประเมินสุขภาพจิต</h4>

    <div class="mb-4 text-center">
        <strong>จำนวนผู้ประเมินทั้งหมด:</strong> <?= $total ?> คน<br>
        <strong>จำนวนผู้ที่ได้รับการรักษา:</strong> <?= $total_treated ?> คน
    </div>

    <canvas id="barChart"></canvas>

    <table class="table table-bordered mt-4 text-center align-middle">
        <thead class="table-light">
            <tr>
                <th>ระดับความรุนแรง</th>
                <th>จำนวน (คน)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($summary as $level => $count): ?>
                <tr>
                    <td><?= $level ?></td>
                    <td><?= $count ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th>รวม</th>
                <th><?= $total ?></th>
            </tr>
        </tfoot>
    </table>

    <div class="text-center">
        <a href="dashboard.php" class="btn btn-secondary">🔙 ย้อนกลับ</a>
    </div>
</div>

<script>
    const ctx = document.getElementById('barChart').getContext('2d');
    const barChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['น้อยมาก', 'เล็กน้อย', 'ปานกลาง', 'ค่อนข้างมาก', 'รุนแรงมาก'],
            datasets: [{
                label: 'จำนวน (คน)',
                data: [
                    <?= $summary['น้อยมาก'] ?>,
                    <?= $summary['เล็กน้อย'] ?>,
                    <?= $summary['ปานกลาง'] ?>,
                    <?= $summary['ค่อนข้างมาก'] ?>,
                    <?= $summary['รุนแรงมาก'] ?>
                ],
                backgroundColor: [
                    '#006400', '#90ee90', '#dda0dd', '#1e90ff', '#ff8c00'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'สรุประดับความรุนแรงจากผลประเมินล่าสุด'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'doctor') {
    header("Location: ../auth/login.php");
    exit();
}

// รับค่าสังกัดย่อยจากตัวกรอง
$sub_id = isset($_GET['sub_affiliation_id']) ? $_GET['sub_affiliation_id'] : "";

// ดึงรายการสังกัดย่อยทั้งหมด
$sub_list = $conn->query("SELECT * FROM sub_affiliations ORDER BY name ASC");

// เงื่อนไขการกรอง
$filter_sql = "";
if ($sub_id !== "") {
    $filter_sql = " AND u.sub_affiliation_id = " . intval($sub_id);
}

// ดึงผลประเมินล่าสุดต่อ user + สังกัดย่อย
$sql = "
SELECT e.user_id, e.total_score,
       CASE
           WHEN e.total_score <= 4 THEN 'น้อยมาก'
           WHEN e.total_score <= 8 THEN 'เล็กน้อย'
           WHEN e.total_score <= 14 THEN 'ปานกลาง'
           WHEN e.total_score <= 19 THEN 'ค่อนข้างมาก'
           ELSE 'รุนแรงมาก'
       END AS severity
FROM evaluations e
JOIN (
    SELECT user_id, MAX(created_at) AS latest
    FROM evaluations
    GROUP BY user_id
) latest ON e.user_id = latest.user_id AND e.created_at = latest.latest
JOIN users u ON e.user_id = u.id
WHERE 1=1 $filter_sql
";

// ดึงรายการ user_id ที่เคยถูกรักษา
$treat_result = $conn->query("SELECT DISTINCT user_id FROM treatments");
$treatment_users = [];
while ($row = $treat_result->fetch_assoc()) {
    $treatment_users[] = $row['user_id'];
}

// เตรียมสรุป
$summary = [
    'น้อยมาก' => 0,
    'เล็กน้อย' => 0,
    'ปานกลาง' => 0,
    'ค่อนข้างมาก' => 0,
    'รุนแรงมาก' => 0
];
$total = 0;
$total_treated = 0;

$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $level = $row['severity'];
    $summary[$level]++;
    $total++;
    if (in_array($row['user_id'], $treatment_users)) {
        $total_treated++;
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายงานผลการประเมิน</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body { font-family: 'Sarabun', sans-serif; font-size: 18px; background-color: #f0f2f5; }
        .container { margin-top: 40px; background: white; padding: 30px; border-radius: 10px; max-width: 1000px; }
        canvas { margin: 0 auto; display: block; max-width: 600px; }
    </style>
</head>
<body>
<div class="container">
    <h4 class="mb-4 text-center">📊 รายงานผลการประเมินสุขภาพจิต</h4>

    <!-- ตัวกรองสังกัดย่อย -->
    <form method="get" class="row g-3 mb-4">
        <div class="col-md-9">
            <select name="sub_affiliation_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- เลือกสังกัดย่อย --</option>
                <?php while ($sub = $sub_list->fetch_assoc()): ?>
                    <option value="<?= $sub['id'] ?>" <?= $sub_id == $sub['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($sub['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-3">
            <a href="report.php" class="btn btn-secondary w-100">รีเซ็ต</a>
        </div>
    </form>

    <!-- กราฟระดับความรุนแรง -->
    <canvas id="barChart" class="mb-4"></canvas>

    <!-- กราฟเปรียบเทียบยอดรวม vs รักษา -->
    <canvas id="compareChart" class="mb-4"></canvas>

    <!-- ตารางแนวนอน -->
    <table class="table table-bordered text-center mt-4">
        <thead class="table-light">
            <tr>
                <th>ทั้งหมด</th>
                <th>ได้รับการรักษา</th>
                <th>น้อยมาก</th>
                <th>เล็กน้อย</th>
                <th>ปานกลาง</th>
                <th>ค่อนข้างมาก</th>
                <th>รุนแรงมาก</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= $total ?></td>
                <td><?= $total_treated ?></td>
                <td><?= $summary['น้อยมาก'] ?></td>
                <td><?= $summary['เล็กน้อย'] ?></td>
                <td><?= $summary['ปานกลาง'] ?></td>
                <td><?= $summary['ค่อนข้างมาก'] ?></td>
                <td><?= $summary['รุนแรงมาก'] ?></td>
            </tr>
        </tbody>
    </table>

    <div class="text-center mt-4">
        <a href="dashboard.php" class="btn btn-secondary">🔙 ย้อนกลับ</a>
    </div>
</div>

<script>
    // กราฟระดับความรุนแรง
    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: ['น้อยมาก', 'เล็กน้อย', 'ปานกลาง', 'ค่อนข้างมาก', 'รุนแรงมาก'],
            datasets: [{
                label: 'จำนวน (คน)',
                data: [
                    <?= $summary['น้อยมาก'] ?>,
                    <?= $summary['เล็กน้อย'] ?>,
                    <?= $summary['ปานกลาง'] ?>,
                    <?= $summary['ค่อนข้างมาก'] ?>,
                    <?= $summary['รุนแรงมาก'] ?>
                ],
                backgroundColor: ['#006400', '#90ee90', '#dda0dd', '#1e90ff', '#ff8c00']
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'จำนวนผู้ประเมินตามระดับความรุนแรง'
                },
                legend: { display: false }
            },
            scales: { y: { beginAtZero: true } }
        }
    });

    // กราฟเปรียบเทียบยอดรวม vs รักษา
    new Chart(document.getElementById('compareChart'), {
        type: 'bar',
        data: {
            labels: ['จำนวนทั้งหมด', 'ได้รับการรักษา'],
            datasets: [{
                label: 'จำนวน (คน)',
                data: [<?= $total ?>, <?= $total_treated ?>],
                backgroundColor: ['#1e90ff', '#28a745']
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'เปรียบเทียบจำนวนผู้ประเมินทั้งหมดและที่ได้รับการรักษา'
                },
                legend: { display: false }
            },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
</body>
</html>

        }
    });
</script>
</body>
</html>
