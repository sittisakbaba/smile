<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'superadmin') {
    header("Location: ../auth/login.php");
    exit();
}

// ดึง logs ทั้งหมด + ชื่อผู้ใช้
$role_filter = $_GET['role'] ?? '';
$where = !empty($role_filter) ? "WHERE l.role = '$role_filter'" : '';

$sql = "
SELECT l.*, u.fullname 
FROM logs l
INNER JOIN users u ON l.user_id = u.id
$where
ORDER BY l.login_time DESC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Log การเข้าใช้งาน</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; font-size: 18px; background-color: #f4f4f4; }
        .container { max-width: 1000px; margin-top: 40px; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
<div class="container">
    <h4 class="mb-4 text-center">📋 Log การเข้าสู่ระบบ</h4>

    <form class="row g-2 mb-4" method="get">
        <div class="col-md-4">
            <select name="role" class="form-select">
                <option value="">-- ทุกบทบาท --</option>
                <option value="user" <?= $role_filter == 'user' ? 'selected' : '' ?>>User</option>
                <option value="doctor" <?= $role_filter == 'doctor' ? 'selected' : '' ?>>Doctor</option>
                <option value="admin" <?= $role_filter == 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="superadmin" <?= $role_filter == 'superadmin' ? 'selected' : '' ?>>Super Admin</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">กรอง</button>
        </div>
    </form>

    <table class="table table-bordered text-center align-middle">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>ชื่อผู้ใช้</th>
                <th>บทบาท</th>
                <th>IP Address</th>
                <th>เวลาเข้าใช้งาน</th>
            </tr>
        </thead>
        <tbody>
        <?php $i=1; while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($row['fullname']) ?></td>
                <td><?= $row['role'] ?></td>
                <td><?= $row['ip_address'] ?></td>
                <td><?= date("d/m/Y H:i:s", strtotime($row['login_time'])) ?></td>
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
