<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include('../config/db.php');

// ดึงข้อมูลสังกัด
$aff = $conn->query("SELECT id, name FROM affiliations");
$subs = $conn->query("SELECT id, name FROM sub_affiliations");

// เมื่อผู้ใช้กด "สมัครสมาชิก"
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $student_id = $_POST['student_id'];
    $dob = $_POST['dob'];
    $age = $_POST['age'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    // $citizen_id = $_POST['citizen_id'];
    $aff_id = $_POST['affiliation_id'];
    $sub_id = $_POST['sub_affiliation_id'];
    $family = $_POST['family_status'];
    $financial = $_POST['financial_status'];
    $drug = $_POST['drug_use'];
    $has_support = $_POST['has_support'];
    $has_pet = $_POST['has_pet'];
    $can_talk_to = $_POST['can_talk_to'];

    $stmt = $conn->prepare("INSERT INTO users (
        fullname, student_id, dob, age, phone, email, password,  role,
        affiliation_id, sub_affiliation_id, family_status,
        financial_status, drug_use, has_support, has_pet, can_talk_to
    ) VALUES (?, ?, ?, ?, ?,?,?, 'user', ?, ?, ?, ?, ?, ?,?,?)");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssssssiissssss", $fullname,$student_id, $dob,$age, $phone, $email, $password, $aff_id, $sub_id, $family, $financial, $drug, $has_support, $has_pet, $can_talk_to);


    if ($stmt->execute()) {
        echo "<script>alert('สมัครสมาชิกสำเร็จ'); window.location='login.php';</script>";
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
    <title>สมัครสมาชิก</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; background-color: #f5f5f5; font-size: 20px; }
        .container { max-width: 850px; margin-top: 40px; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        label { font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <h4 class="mb-4 text-center">📝 สมัครสมาชิก (สำหรับผู้ใช้งานทั่วไป)</h4>
    <form method="post">
        <div class="mb-3">
            <label>ชื่อ - นามสกุล</label>
            <input type="text" name="fullname" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>รหัสนักศึกษา / นักเรียน</label>
            <input type="text" name="student_id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>วันเดือนปีเกิด</label>
            <input type="date" name="dob" id="dob" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>อายุ</label>
            <input type="text" name="age" id="age" class="form-control" readonly>
        </div>


        <div class="mb-3">
            <label>เบอร์โทร</label>
            <input type="text" name="phone" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>อีเมล(จะใช้เป็นusernameในการเข้าใช้งาน)</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>รหัสผ่าน </label>
            <input type="password" name="password" class="form-control"  required>
        </div>
        <!--div class="mb-3">
            <label>เลขบัตรประชาชน (13 หลัก)</label>
            <input type="text" name="citizen_id" class="form-control" pattern="\d{13}" required>
        </!--div -->
        <div class="mb-3">
            <label>สังกัด</label>
            <select name="affiliation_id" class="form-select" required>
                <option value="">-- เลือกสังกัด --</option>
                <?php while ($a = $aff->fetch_assoc()): ?>
                    <option value="<?= $a['id'] ?>"><?= $a['name'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>สังกัดย่อย</label>
            <select name="sub_affiliation_id" class="form-select" required>
                <option value="">-- เลือกสังกัดย่อย --</option>
                <?php while ($s = $subs->fetch_assoc()): ?>
                    <option value="<?= $s['id'] ?>"><?= $s['name'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>สถานภาพครอบครัว</label><br>
            <?php
            $family_statuses = ['บิดามารดาอยู่ด้วยกัน', 'บิดามารดาหย่าร้าง', 'บิดาเสียชีวิต', 'มารดาเสียชีวิต', 'บิดาและมารดาเสียชีวิต'];
            foreach ($family_statuses as $v):
            ?>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="family_status" value="<?= $v ?>" required>
                    <label class="form-check-label"><?= $v ?></label>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mb-3">
            <label>สถานะทางการเงินของท่าน</label><br>
            <?php foreach (['พอใช้', 'ไม่พอใช้'] as $v): ?>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="financial_status" value="<?= $v ?>" required>
                    <label class="form-check-label"><?= $v ?></label>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mb-3">
            <label>ท่านใช้สารเสพติดหรือไม่</label><br>
            <?php foreach (['ไม่ใช้', 'ใช้'] as $v): ?>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="drug_use" value="<?= $v ?>" required>
                    <label class="form-check-label"><?= $v ?></label>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mb-3">
            <label>ท่านมีแฟนหรือไม่</label><br>
            <?php foreach (['มี', 'ไม่มี'] as $v): ?>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="has_support" value="<?= $v ?>" required>
                    <label class="form-check-label"><?= $v ?></label>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="mb-3">
            <label>ท่านมีสัตว์เลี้ยงหรือไม่</label><br>
            <?php foreach (['มี', 'ไม่มี'] as $v): ?>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="has_pet" value="<?= $v ?>" required>
                    <label class="form-check-label"><?= $v ?></label>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="mb-3">
            <label>ถ้าท่านมีปัญหาท่านสามารถพูดคุยกับใคร</label><br>
            <?php foreach (['แฟน', 'เพื่อน', 'ผู้ปกครอง', 'อาจารย์', 'พี่/น้อง', 'ญาติ'] as $v): ?>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="can_talk_to" value="<?= $v ?>" required>
                    <label class="form-check-label"><?= $v ?></label>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-success">✅ สมัครสมาชิก</button>
            <a href="login.php" class="btn btn-secondary">ย้อนกลับ</a>
        </div>
    </form>
</div>
<script>
    document.getElementById("dob").addEventListener("change", function () {
        const dob = new Date(this.value);
        const today = new Date();
        let age = today.getFullYear() - dob.getFullYear();
        const m = today.getMonth() - dob.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
            age--;
        }
        document.getElementById("age").value = age;
    });
</script>
</body>
</html>
