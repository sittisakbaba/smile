<?php
session_start();
include('../config/db.php');

$step = 1; // ขั้นตอนที่ 1: กรอกอีเมล + เลขบัตร
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['step1'])) {
        $email = $_POST['email'];
        $student_id = $_POST['student_id'];

        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND student_id = ?");
        $stmt->bind_param("ss", $email, $student_id);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 1) {
            $_SESSION['reset_user'] = $res->fetch_assoc()['id'];
            $step = 2; // ไปหน้าตั้งรหัสใหม่
        } else {
            $error = "ไม่พบข้อมูลผู้ใช้งาน โปรดตรวจสอบอีเมลหรือเลขบัตรประชาชน";
        }
    }

    if (isset($_POST['step2'])) {
        $newpass = $_POST['new_password'];
        $hash = password_hash($newpass, PASSWORD_DEFAULT);
        $uid = $_SESSION['reset_user'] ?? 0;

        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hash, $uid);
        $stmt->execute();

        unset($_SESSION['reset_user']);
        echo "<script>alert('เปลี่ยนรหัสผ่านสำเร็จ'); window.location='login.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ลืมรหัสผ่าน</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; background-color: #f5f5f5; font-size: 20px; }
        .container { max-width: 550px; margin-top: 50px; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
<div class="container">
    <h4 class="text-center mb-4">🔐 ลืมรหัสผ่าน</h4>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <?php if ($step === 1): ?>
        <form method="post">
            <div class="mb-3">
                <label>อีเมล</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>เลขบัตรประชาชน (13 หลัก)</label>
                <input type="text" name="student_id" class="form-control" pattern="\d{13}" required>
            </div>
            <div class="text-center">
                <button type="submit" name="step1" class="btn btn-primary">ตรวจสอบ</button>
                <a href="login.php" class="btn btn-secondary">ย้อนกลับ</a>
            </div>
        </form>
    <?php else: ?>
        <form method="post">
            <div class="mb-3">
                <label>ตั้งรหัสผ่านใหม่</label>
                <input type="password" name="new_password" class="form-control" pattern=".{6,}" title="อย่างน้อย 6 ตัวอักษร" required>
            </div>
            <div class="text-center">
                <button type="submit" name="step2" class="btn btn-success">บันทึกรหัสผ่านใหม่</button>
                <a href="login.php" class="btn btn-secondary">ยกเลิก</a>
            </div>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
