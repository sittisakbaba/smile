<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'superadmin') {
    header("Location: ../auth/login.php");
    exit();
}

// ดึง doctor ทั้งหมด
$result = $conn->query("
    SELECT u.id, u.fullname, u.email, a.name AS affiliation, s.name AS sub_affiliation
    FROM users u
    LEFT JOIN affiliations a ON u.affiliation_id = a.id
    LEFT JOIN sub_affiliations s ON u.sub_affiliation_id = s.id
    WHERE u.role = 'doctor'
    ORDER BY u.id DESC
");

// ลบหมอ
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id = $id AND role = 'doctor'");
    header("Location: manage_doctor.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการบัญชีหมอ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; font-size: 18px; background-color: #f8f9fa; }
        .container { max-width: 1000px; margin-top: 40px; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        th, td { vertical-align: middle !important; }
    </style>
</head>
<body>
<div class="container">
    <h4 class="mb-4">👨‍⚕️ จัดการบัญชี Doctor</h4>

    <a href="add_doctor.php" class="btn btn-success mb-3">➕ เพิ่มหมอใหม่</a>

    <table class="table table-bordered text-center align-middle">
        <thead class="table-light">
            <tr>
                <th>ชื่อ - นามสกุล</th>
                <th>อีเมล</th>
                <th>สังกัด</th>
                <th>สังกัดย่อย</th>
                <th>การจัดการ</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['fullname']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= $row['affiliation'] ?></td>
                <td><?= $row['sub_affiliation'] ?></td>
                <td>
                    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('ยืนยันลบหมอคนนี้?')" class="btn btn-sm btn-danger">ลบ</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <div class="text-center mt-3">
        <a href="dashboard.php" class="btn btn-secondary">ย้อนกลับ</a>
    </div>
</div>
</body>
</html>
