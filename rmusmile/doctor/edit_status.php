<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'doctor') {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eval_id'], $_POST['adjusted_level'], $_POST['user_id'], $_POST['status'])) {
    $eval_id = $_POST['eval_id'];
    $level = $_POST['adjusted_level'];
    $user_id = $_POST['user_id'];
    $status = $_POST['status'];

    $stmt1 = $conn->prepare("UPDATE evaluations SET adjusted_level = ? WHERE id = ?");
    $stmt1->bind_param("si", $level, $eval_id);
    $stmt1->execute();

    $stmt2 = $conn->prepare("UPDATE users SET status = ? WHERE id = ?");
    $stmt2->bind_param("si", $status, $user_id);
    $stmt2->execute();

    echo "<script>alert('อัปเดตสำเร็จ'); window.location='edit_status.php';</script>";
    exit();
}

$sql = "
SELECT e.id AS eval_id, u.id AS user_id, u.fullname, u.status, a.name AS affiliation, s.name AS sub_affiliation,
       e.total_score, e.danger_level, e.adjusted_level, e.created_at
FROM evaluations e
JOIN (
    SELECT user_id, MAX(created_at) AS latest
    FROM evaluations
    GROUP BY user_id
) latest ON e.user_id = latest.user_id AND e.created_at = latest.latest
JOIN users u ON e.user_id = u.id
LEFT JOIN affiliations a ON u.affiliation_id = a.id
LEFT JOIN sub_affiliations s ON u.sub_affiliation_id = s.id
ORDER BY e.created_at DESC
";
$results = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขระดับและสถานะผู้ป่วย</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { font-family: 'Sarabun', sans-serif; font-size: 18px; }</style>
</head>
<body class="bg-light">
<div class="container mt-4 p-4 bg-white rounded">
    <h4 class="mb-4">แก้ไขระดับและสถานะผู้ป่วย</h4>
    <table class="table table-bordered text-center">
        <thead class="table-light">
            <tr>
                <th>ชื่อ</th><th>สังกัด</th><th>คะแนน</th><th>ระดับ</th><th>แก้ไขระดับ</th><th>สถานะ</th><th>จัดการ</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($r = $results->fetch_assoc()): ?>
        <tr>
            <form method="post">
                <td><?= htmlspecialchars($r['fullname']) ?></td>
                <td><?= htmlspecialchars($r['affiliation'] . '/' . $r['sub_affiliation']) ?></td>
                <td><?= $r['total_score'] ?></td>
                <td><?= $r['danger_level'] ?></td>
                <td>
                    <select name="adjusted_level" class="form-select" required>
                        <?php
                        $levels = ['น้อยมาก','เล็กน้อย','ปานกลาง','ค่อนข้างมาก','รุนแรงมาก'];
                        foreach ($levels as $l) {
                            $sel = ($r['adjusted_level'] === $l) ? 'selected' : '';
                            echo "<option value='$l' $sel>$l</option>";
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <select name="status" class="form-select" required>
                        <?php
                        $statuses = ['ปกติ','ต้องได้รับการรักษา','รักษาเสร็จแล้ว','ติดต่อไม่ได้'];
                        foreach ($statuses as $s) {
                            $sel = ($r['status'] === $s) ? 'selected' : '';
                            echo "<option value='$s' $sel>$s</option>";
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <input type="hidden" name="eval_id" value="<?= $r['eval_id'] ?>">
                    <input type="hidden" name="user_id" value="<?= $r['user_id'] ?>">
                    <button type="submit" class="btn btn-primary btn-sm">อัปเดต</button>
                </td>
            </form>
        </tr>
        <?php endwhile; ?>
        
        </tbody>
        <a href="../doctor/dashboard.php" class="btn btn-secondary">🔙 ย้อนกลับ</a>
    </table>
</div>
</body>
</html>
