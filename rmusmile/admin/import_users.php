<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// ฟังก์ชันเข้ารหัสรหัสผ่าน
function hashPassword($citizen_id) {
    return password_hash($citizen_id, PASSWORD_DEFAULT);
}

// อัปโหลด CSV
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file']['tmp_name'];
    $handle = fopen($file, 'r');

    if ($handle !== FALSE) {
        $row = 0;
        $inserted = 0;
        $skipped = 0;

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if ($row == 0) { $row++; continue; } // skip header

            $fullname = $data[0];
            $email = $data[1];
            $phone = $data[2];
            $citizen_id = $data[3];
            $affiliation_id = $data[4];
            $sub_affiliation_id = $data[5];
            $password = hashPassword($citizen_id);

            // ตรวจสอบซ้ำจาก email หรือ citizen_id
            $check = $conn->prepare("SELECT id FROM users WHERE email = ? OR citizen_id = ?");
            $check->bind_param("ss", $email, $citizen_id);
            $check->execute();
            $check->store_result();

            if ($check->num_rows === 0) {
                $stmt = $conn->prepare("INSERT INTO users (fullname, email, phone, citizen_id, password, role, affiliation_id, sub_affiliation_id) VALUES (?, ?, ?, ?, ?, 'user', ?, ?)");
                $stmt->bind_param("sssssss", $fullname, $email, $phone, $citizen_id, $password, $affiliation_id, $sub_affiliation_id);
                $stmt->execute();
                $inserted++;
            } else {
                $skipped++;
            }
            $row++;
        }
        fclose($handle);

        echo "<script>alert('✅ เพิ่มสำเร็จ $inserted คน | ข้าม $skipped คน'); window.location='import_users.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>นำเข้าผู้ใช้งาน (.csv)</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; background: #f8f9fa; font-size: 18px; }
        .container { max-width: 600px; margin-top: 40px; background: white; padding: 30px; border-radius: 10px; }
    </style>
</head>
<body>
<div class="container">
    <h4 class="mb-4 text-center">📥 นำเข้าผู้ใช้งาน (ไฟล์ .CSV)</h4>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="csv_file" class="form-label">เลือกไฟล์ CSV</label>
            <input type="file" name="csv_file" id="csv_file" class="form-control" accept=".csv" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">📤 อัปโหลดและเพิ่มผู้ใช้งาน</button>
    </form>

    <div class="mt-4">
        <strong>รูปแบบไฟล์ต้องมีหัวตารางแบบนี้:</strong>
        <div class="mt-2">
<pre>
fullname,email,phone,citizen_id,affiliation_id,sub_affiliation_id
นายสมชาย,example@gmail.com,0812345678,1234567890123,1,2<?php
require_once '../connect.php';

if (isset($_POST['import'])) {
    $filename = $_FILES["file"]["tmp_name"];
    if ($_FILES["file"]["size"] > 0) {
        $file = fopen($filename, "r");
        $auto_num = 1;
        $row = 0;

        while (($data = fgetcsv($file, 10000, ",")) !== FALSE) {
            if ($row == 0) { $row++; continue; } // ข้ามหัวตาราง

            $fullname = $data[0];
            $email = $data[1];
            $phone = $data[2];
            $affiliation = $data[3];
            $sub_affiliation = $data[4];

            $citizen_id = 'AUTO' . str_pad($auto_num, 5, '0', STR_PAD_LEFT);
            $auto_num++;

            $password = password_hash($citizen_id, PASSWORD_DEFAULT); // ตั้งรหัสผ่านเริ่มต้น = citizen_id

            $stmt = $conn->prepare("INSERT INTO users (fullname, email, phone, citizen_id, password, role, affiliation_id, sub_affiliation_id)
                                    VALUES (?, ?, ?, ?, ?, 'user', ?, ?)");
            $stmt->bind_param("sssssss", $fullname, $email, $phone, $citizen_id, $password, $affiliation, $sub_affiliation);

            if (!$stmt->execute()) {
                echo "❌ Error: " . $stmt->error;
            }
        }

        fclose($file);
        echo "<script>alert('✅ นำเข้าข้อมูลเรียบร้อยแล้ว'); window.location='dashboard.php';</script>";
    } else {
        echo "<script>alert('⚠️ กรุณาเลือกไฟล์ก่อน');</script>";
    }
}
?>

<!-- HTML Form สำหรับอัปโหลดไฟล์ -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>นำเข้าผู้ใช้</title>
</head>
<body>
    <h3>นำเข้าผู้ใช้งานจากไฟล์ CSV</h3>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="file" accept=".csv" required>
        <button type="submit" name="import">นำเข้า</button>
    </form>
</body>
</html>

...</pre>
        </div>
    </div>
</div>
</body>
</html>
