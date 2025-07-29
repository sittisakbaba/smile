
<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['doctor', 'admin', 'superadmin'])) {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['status'])) {
    $user_id = $_POST['user_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE users SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $user_id);
    $stmt->execute();

    echo "<script>alert('อัปเดตสถานะสำเร็จ'); window.location='manage_patients.php';</script>";
    exit();
}

$sql = "
SELECT u.id, u.fullname, u.status, a.name AS affiliation, s.name AS sub_affiliation
FROM users u
LEFT JOIN affiliations a ON u.affiliation_id = a.id
LEFT JOIN sub_affiliations s ON u.sub_affiliation_id = s.id
WHERE u.role = 'user'
ORDER BY u.fullname ASC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการสถานะผู้ป่วย</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { font-family: 'Sarabun', sans-serif; font-size: 18px; }</style>
</head>
<body class="bg-light">
<div class="container mt-4 p-4 bg-white rounded">
    <h4 class="mb-4">🩺 จัดการสถานะผู้ป่วย</h4>
    <table class="table table-bordered text-center">
        <thead class="table-light">
            <tr><th>ชื่อ</th><th>สังกัด</th><th>สังกัดย่อย</th><th>สถานะปัจจุบัน</th><th>เปลี่ยนสถานะ</th><th>อัปเดต</th></tr>
        </thead>
        <tbody>
        <?php while ($u = $result->fetch_assoc()): ?>
        <tr>
            <form method="post">
                <td><?= htmlspecialchars($u['fullname']) ?></td>
                <td><?= htmlspecialchars($u['affiliation']) ?></td>
                <td><?= htmlspecialchars($u['sub_affiliation']) ?></td>
                <td><?= $u['status'] ?: '-' ?></td>
                <td>
                    <select name="status" class="form-select" required>
                        <option value="">-- เลือก --</option>
                        <?php
                        $statuses = ['ปกติ','ต้องได้รับการรักษา','รักษาเสร็จแล้ว','ติดต่อไม่ได้'];
                        foreach ($statuses as $s) {
                            $sel = ($u['status'] === $s) ? 'selected' : '';
                            echo "<option value='$s' $sel>$s</option>";
                        }
                        ?>
                    </select>
                    <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                </td>
                <td><button type="submit" class="btn btn-primary btn-sm">อัปเดต</button></td>
            </form>
        </tr>
        <?php endwhile; ?>
        
        </tbody>
        <a href="../doctor/dashboard.php" class="btn btn-secondary">🔙 ย้อนกลับ</a>
    </table>
</div>
</body>
</html>
