<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];

// ดึงข้อมูลผู้ใช้งาน
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

// ดึงข้อมูลสังกัด
$affs = $conn->query("SELECT id, name FROM affiliations");
$subs = $conn->query("SELECT id, name FROM sub_affiliations");

// อัปเดตข้อมูล
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $student_id = $_POST['student_id'];
    $aff_id = $_POST['affiliation_id'];
    $sub_id = $_POST['sub_affiliation_id'];
    $family = $_POST['family_status'];
    $financial = $_POST['financial_status'];
    $drug = $_POST['drug_use'];
    $has_support = $_POST['has_support'];
    $has_pet = $_POST['has_pet'];
    $can_talk_to = $_POST['can_talk_to'];

    $stmt = $conn->prepare("UPDATE users SET fullname=?, phone=?, student_id=?, affiliation_id=?, sub_affiliation_id=?, family_status=?, financial_status=?, drug_use=?, has_support=?, has_pet=?, can_talk_to=? WHERE id=?");
    $stmt->bind_param("sssiiisssssi", $fullname, $phone, $student_id, $aff_id, $sub_id, $family, $financial, $drug, $has_support,$has_pet,$can_talk_to, $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('อัปเดตข้อมูลสำเร็จ'); window.location='dashboard.php';</script>";
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
    <title>แก้ไขข้อมูลส่วนตัว</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; font-size: 20px; background-color: #f9f9f9; }
        .container { max-width: 900px; margin-top: 40px; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        label { font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <h4 class="mb-4 text-center">✏️ แก้ไขข้อมูลส่วนตัว</h4>
    <form method="post">
        <div class="mb-3">
            <label>ชื่อ - นามสกุล</label>
            <input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($data['fullname']) ?>" required>
        </div>
        <div class="mb-3">
            <label>เบอร์โทร</label>
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($data['phone']) ?>" required>
        </div>
        <div class="mb-3">
            <label>รหัสนักศึกษา</label>
            <input type="text" name="student_id" class="form-control" value="<?= htmlspecialchars($data['student_id']) ?>" required>
        </div>
        <div class="mb-3">
            <label>สังกัด</label>
            <select name="affiliation_id" class="form-select" required>
                <?php while ($a = $affs->fetch_assoc()): ?>
                    <option value="<?= $a['id'] ?>" <?= $data['affiliation_id'] == $a['id'] ? 'selected' : '' ?>><?= $a['name'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>สังกัดย่อย</label>
            <select name="sub_affiliation_id" class="form-select" required>
                <?php while ($s = $subs->fetch_assoc()): ?>
                    <option value="<?= $s['id'] ?>" <?= $data['sub_affiliation_id'] == $s['id'] ? 'selected' : '' ?>><?= $s['name'] ?></option>
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
                    <input class="form-check-input" type="radio" name="family_status" value="<?= $v ?>" <?= $data['family_status'] === $v ? 'checked' : '' ?>>
                    <label class="form-check-label"><?= $v ?></label>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mb-3">
            <label>สถานะทางการเงินของท่าน</label><br>
            <?php foreach (['พอใช้', 'ไม่พอใช้'] as $v): ?>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="financial_status" value="<?= $v ?>" <?= $data['financial_status'] === $v ? 'checked' : '' ?>>
                    <label class="form-check-label"><?= $v ?></label>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mb-3">
            <label>ท่านใช้สารเสพติดหรือไม่</label><br>
            <?php foreach (['ไม่ใช้', 'ใช้'] as $v): ?>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="drug_use" value="<?= $v ?>" <?= $data['drug_use'] === $v ? 'checked' : '' ?>>
                    <label class="form-check-label"><?= $v ?></label>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mb-3">
            <label>ท่านมีแฟนหรือไม่</label><br>
            <?php foreach (['มี', 'ไม่มี'] as $v): ?>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="has_support" value="<?= $v ?>" <?= $data['has_support'] === $v ? 'checked' : '' ?>>
                    <label class="form-check-label"><?= $v ?></label>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="mb-3">
            <label>ท่านมีสัตว์เลี้ยงหรือไม่</label><br>
            <?php foreach (['มี', 'ไม่มี'] as $v): ?>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="has_pet" value="<?= $v ?>" <?= $data['has_pet'] === $v ? 'checked' : '' ?> required>
                    <label class="form-check-label"><?= $v ?></label>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="mb-3">
            <label>ถ้าท่านมีปัญหาท่านสามารถพูดคุยกับใคร</label><br>
            <?php foreach (['แฟน', 'เพื่อน', 'ผู้ปกครอง', 'อาจารย์', 'พี่/น้อง', 'ญาติ'] as $v): ?>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="can_talk_to" value="<?= $v ?>" <?= $data['can_talk_to'] === $v ? 'checked' : '' ?> required>
                    <label class="form-check-label"><?= $v ?></label>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary">💾 บันทึก</button>
            <a href="dashboard.php" class="btn btn-secondary">ย้อนกลับ</a>
        </div>
    </form>
</div>
</body>
</html>
