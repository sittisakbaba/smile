<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// ดึงสังกัด
$aff = $conn->query("SELECT id, name FROM affiliations");
$subs = $conn->query("SELECT id, name FROM sub_affiliations");

// เมื่อบันทึก
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $aff_id = $_POST['affiliation_id'];
    $sub_id = $_POST['sub_affiliation_id'];

    $stmt = $conn->prepare("INSERT INTO users (fullname, email, password, role, affiliation_id, sub_affiliation_id) VALUES (?, ?, ?, 'doctor', ?, ?)");
    $stmt->bind_param("sssii", $fullname, $email, $password, $aff_id, $sub_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('เพิ่มหมอสำเร็จ'); window.location='manage_doctor.php';</script>";
        exit();
    } else {
        echo "<script>alert('เกิดข้อผิดพลาด: " . $stmt->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เพิ่มบัญชีหมอ</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; font-size: 18px; background-color: #f9f9f9; }
        .container { max-width: 700px; margin-top: 40px; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
<div class="container">
    <h4 class="mb-4 text-center">➕ เพิ่มบัญชี Doctor</h4>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">ชื่อ - นามสกุล</label>
            <input type="text" name="fullname" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">อีเมล</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">รหัสผ่าน</label>
            <input type="text" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">สังกัด</label>
            <select name="affiliation_id" class="form-select" required>
                <option value="">-- เลือก --</option>
                <?php while ($a = $aff->fetch_assoc()): ?>
                    <option value="<?= $a['id'] ?>"><?= $a['name'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">สังกัดย่อย</label>
            <select name="sub_affiliation_id" class="form-select" required>
                <option value="">-- เลือก --</option>
                <?php while ($s = $subs->fetch_assoc()): ?>
                    <option value="<?= $s['id'] ?>"><?= $s['name'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-success">💾 บันทึก</button>
            <a href="manage_doctor.php" class="btn btn-secondary">ย้อนกลับ</a>
        </div>
    </form>
</div>
</body>
</html>
