<?php
session_start();
include('../config/db.php');

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // ตรวจสอบว่ามี user นี้หรือไม่
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $user = $res->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // ✅ บันทึก session
            $_SESSION['user'] = $user;

            // ✅ บันทึก Log
            $ip = $_SERVER['REMOTE_ADDR'];
            $role = $user['role'];
            $user_id = $user['id'];
            $log = $conn->prepare("INSERT INTO logs (user_id, ip_address, role) VALUES (?, ?, ?)");
            $log->bind_param("iss", $user_id, $ip, $role);
            $log->execute();

            // ✅ redirect ตามบทบาท
            if ($role === 'superadmin') {
                header("Location: ../superadmin/dashboard.php");
            } elseif ($role === 'admin') {
                header("Location: ../admin/dashboard.php");
            } elseif ($role === 'doctor') {
                header("Location: ../doctor/dashboard.php");
            } else {
                header("Location: ../user/dashboard.php");
            }
            exit();
        } else {
            $error = "รหัสผ่านไม่ถูกต้อง";
        }
    } else {
        $error = "ไม่พบอีเมลในระบบ";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เข้าสู่ระบบ</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/css_footer.css">


    <style>
        body {
            background-image: url('../assets/img/bg.png');
            font-family: 'Sarabun', sans-serif;
            background-color: #f8f9fa;
            font-size: 20px;
        }
        
        .login-box {
            max-width: 450px;
            margin: 80px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }


    </style>
</head>
<body><br><br>
<div class="login-box">
    <h4 class="mb-4 text-center">🔐 เข้าสู่ระบบ</h4>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    
    <?php if (isset($_GET['timeout'])): ?>
        <div class="alert alert-warning">⏰ หมดเวลาการใช้งาน กรุณาเข้าสู่ระบบใหม่</div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">อีเมล</label>
            <input type="email" name="email" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label">รหัสผ่าน</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">เข้าสู่ระบบ</button>

        <div class="text-center mt-2">
            <a href="forgot_password.php" class="btn btn-link p-0">ลืมรหัสผ่าน</a>
            <span class="mx-2">|</span>
            <a href="register.php" class="btn btn-link p-0">ลงทะเบียน</a>
        </div>
    </form>
</div>


</body>

</html>
