<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'superadmin') {
    header("Location: ../auth/login.php");
    exit();
}

// ดึงรายชื่อ Admin
$result = $conn->query("
    SELECT u.id, u.fullname, u.email, a.name AS affiliation, s.name AS sub_affiliation
    FROM users u
    LEFT JOIN affiliations a ON u.affiliation_id = a.id
    LEFT JOIN sub_affiliations s ON u.sub_affiliation_id = s.id
    WHERE u.role = 'admin'
    ORDER BY u.id DESC
");

// ลบ admin
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id = $id AND role = 'admin'");
    header("Location: manage_admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการบัญชี Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body { font-family: 'Sarabun', sans-serif; font-size: 18px; background-color: #f8f9fa; }
        .container { max-width: 1000px; margin-top: 40px; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        th, td { vertical-align: middle !important; }
    </style>
</head>
<body>
<div class="container">
    <h4 class="mb-4">🛠 จัดการบัญชี Admin</h4>

    <a href="add_admin.php" class="btn btn-success mb-3">➕ เพิ่ม Admin ใหม่</a>

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
                    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('ยืนยันลบบัญชีนี้?')" class="btn btn-sm btn-danger">ลบ</a>
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
