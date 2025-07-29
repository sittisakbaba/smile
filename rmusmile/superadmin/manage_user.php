<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'superadmin') {
    header("Location: ../auth/login.php");
    exit();
}

// ลบ user
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id = $id AND role = 'user'");
    header("Location: manage_user.php");
    exit();
}

// ดึงข้อมูล user
$result = $conn->query("
    SELECT u.id, u.fullname, u.email, u.phone, a.name AS affiliation, s.name AS sub_affiliation
    FROM users u
    LEFT JOIN affiliations a ON u.affiliation_id = a.id
    LEFT JOIN sub_affiliations s ON u.sub_affiliation_id = s.id
    WHERE u.role = 'user'
    ORDER BY u.id DESC
");
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการผู้ใช้งาน</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; font-size: 18px; background-color: #f8f9fa; }
        .container { max-width: 1000px; margin-top: 40px; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        th, td { vertical-align: middle !important; }
    </style>
</head>
<body>
<div class="container">
    <h4 class="mb-4 text-center">👥 รายชื่อผู้ใช้งาน (User)</h4>

    <table class="table table-bordered text-center align-middle">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>ชื่อ - นามสกุล</th>
                <th>อีเมล</th>
                <th>เบอร์โทร</th>
                <th>สังกัด</th>
                <th>สังกัดย่อย</th>
                <th>การจัดการ</th>
            </tr>
        </thead>
        <tbody>
        <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($row['fullname']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td><?= htmlspecialchars($row['affiliation']) ?></td>
                <td><?= htmlspecialchars($row['sub_affiliation']) ?></td>
                <td>
                    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('ลบผู้ใช้นี้หรือไม่?')" class="btn btn-sm btn-danger">ลบ</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <div class="text-center mt-4">
        <a href="dashboard.php" class="btn btn-secondary">🔙 ย้อนกลับ</a>
    </div>
</div>
</body>
</html>
