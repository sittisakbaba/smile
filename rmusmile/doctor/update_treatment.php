<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'doctor') {
    header("Location: ../auth/login.php");
    exit();
}

$doctor_id = $_SESSION['user']['id'];

// บันทึกการรักษา
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['treatment_detail'])) {
    $user_id = $_POST['user_id'];
    $detail = $_POST['treatment_detail'];

    $stmt = $conn->prepare("INSERT INTO treatments (user_id, doctor_id, treatment_detail) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $doctor_id, $detail);
    $stmt->execute();

    echo "<script>alert('บันทึกการรักษาสำเร็จ'); window.location='update_treatment.php';</script>";
    exit();
}

// ดึงรายชื่อผู้ที่ต้องได้รับการรักษา
$users = $conn->query("
    SELECT u.id, u.fullname, a.name AS affiliation, s.name AS sub_affiliation
    FROM users u
    LEFT JOIN affiliations a ON u.affiliation_id = a.id
    LEFT JOIN sub_affiliations s ON u.sub_affiliation_id = s.id
    WHERE u.status = 'ต้องได้รับการรักษา'
    ORDER BY u.fullname ASC
");

// ถ้าคลิกดูประวัติ
$history_user_id = $_GET['history'] ?? null;
$history = [];
if ($history_user_id) {
    $stmt = $conn->prepare("SELECT * FROM treatments WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $history_user_id);
    $stmt->execute();
    $history = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>อัปเดตการรักษา</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; font-size: 18px; background: #f9f9f9; }
        .container { max-width: 1000px; margin-top: 40px; background: white; padding: 30px; border-radius: 10px; }
    </style>
</head>
<body>
<div class="container">
    <h4 class="mb-4">💊 อัปเดตการรักษา</h4>

    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>ชื่อผู้ใช้</th>
                <th>สังกัด</th>
                <th>สังกัดย่อย</th>
                <th>เพิ่มการรักษา</th>
                <th>ดูประวัติ</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($u = $users->fetch_assoc()): ?>
            <tr>
                <form method="post">
                    <td><?= htmlspecialchars($u['fullname']) ?></td>
                    <td><?= htmlspecialchars($u['affiliation']) ?></td>
                    <td><?= htmlspecialchars($u['sub_affiliation']) ?></td>
                    <td>
                        <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                        <textarea name="treatment_detail" class="form-control" rows="2" required></textarea>
                        <button type="submit" class="btn btn-success btn-sm mt-1">บันทึก</button>
                    </td>
                    <td>
                        <a href="?history=<?= $u['id'] ?>" class="btn btn-info btn-sm">🕒 ดูประวัติ</a>
                    </td>
                </form>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <?php if ($history_user_id): ?>
        <hr>
        <h5 class="mt-4">📋 ประวัติการรักษาของผู้ใช้ ID: <?= $history_user_id ?></h5>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>วันที่</th>
                    <th>รายละเอียดการรักษา</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $history->fetch_assoc()): ?>
                    <tr>
                        <td><?= date("d/m/Y H:i", strtotime($row['created_at'])) ?></td>
                        <td><?= nl2br(htmlspecialchars($row['treatment_detail'])) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
    <?php endif; ?>
    <a href="../doctor/dashboard.php" class="btn btn-secondary">🔙 ย้อนกลับ</a>
</div>
</body>
</html>
