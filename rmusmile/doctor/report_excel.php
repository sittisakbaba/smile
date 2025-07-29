<?php
include('../config/db.php');
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=report_" . date('Ymd_His') . ".xls");

// รับค่ากรองจาก query string
$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-t');
$aff_id = $_GET['affiliation_id'] ?? '';

// สร้าง WHERE
$where = "e.created_at BETWEEN '$start_date' AND '$end_date'";
if (!empty($aff_id)) {
    $where .= " AND u.affiliation_id = '$aff_id'";
}

// ดึงข้อมูลสังกัด
$affName = "ทุกสังกัด";
if (!empty($aff_id)) {
    $res = $conn->query("SELECT name FROM affiliations WHERE id = '$aff_id'");
    if ($res->num_rows) {
        $affName = $res->fetch_assoc()['name'];
    }
}

// ระดับความรุนแรง
$sql = "
SELECT e.danger_level, COUNT(*) AS count 
FROM evaluations e
INNER JOIN users u ON u.id = e.user_id
WHERE $where
GROUP BY e.danger_level
";
$levels = [];
$res = $conn->query($sql);
while ($row = $res->fetch_assoc()) {
    $levels[$row['danger_level']] = $row['count'];
}

// จำนวนรักษา
$sql2 = "
SELECT COUNT(*) AS treated_count 
FROM treatments t
INNER JOIN users u ON u.id = t.user_id
WHERE t.treated_at BETWEEN '$start_date' AND '$end_date'"
. (!empty($aff_id) ? " AND u.affiliation_id = '$aff_id'" : "");
$treated = $conn->query($sql2)->fetch_assoc()['treated_count'] ?? 0;
?>

<table border="1" cellpadding="5">
    <tr><th colspan="7">รายงานประเมินความเสี่ยงโรคซึมเศร้า</th></tr>
    <tr><td colspan="7">ช่วงวันที่: <?= $start_date ?> ถึง <?= $end_date ?></td></tr>
    <tr><td colspan="7">สังกัด: <?= $affName ?></td></tr>
    <tr style="background-color:#f2f2f2;">
        <th>น้อยมาก</th>
        <th>เล็กน้อย</th>
        <th>ปานกลาง</th>
        <th>ค่อนข้างมาก</th>
        <th>รุนแรงมาก</th>
        <th>ได้รับการรักษา</th>
    </tr>
    <tr>
        <td><?= $levels['น้อยมาก'] ?? 0 ?></td>
        <td><?= $levels['เล็กน้อย'] ?? 0 ?></td>
        <td><?= $levels['ปานกลาง'] ?? 0 ?></td>
        <td><?= $levels['ค่อนข้างมาก'] ?? 0 ?></td>
        <td><?= $levels['รุนแรงมาก'] ?? 0 ?></td>
        <td><?= $treated ?></td>
    </tr>
</table>
