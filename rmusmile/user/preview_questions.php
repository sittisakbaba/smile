<?php
include('../config/db.php');

// ดึงคำถามทั้งหมด
$qres = $conn->query("SELECT * FROM questions ORDER BY id ASC");
$questions = $qres->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แบบประเมิน (สำหรับตรวจสอบ)</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h4 class="mb-4">🔍 แบบประเมินโรคซึมเศร้า (ตัวอย่างสำหรับผู้เชี่ยวชาญตรวจสอบ)</h4>

    <div class="alert alert-info">
        แสดงตัวอย่างคำถามทั้ง 9 ข้อ พร้อมตัวเลือก 4 ตัวเลือก สำหรับการตรวจสอบเนื้อหา
    </div>

    <form>
        <?php foreach ($questions as $i => $q): ?>
            <div class="mb-4">
                <strong><?= $q['id'] ?>. <?= $q['question_text'] ?></strong>
                <ul class="list-group mt-2">
                    <li class="list-group-item">1. ไม่มีอาการเลย (0 คะแนน)</li>
                    <li class="list-group-item">2. มีอาการนั้นเป็นบางวัน (1 คะแนน)</li>
                    <li class="list-group-item">3. มีอาการนั้นบ่อย (2 คะแนน)</li>
                    <li class="list-group-item">4. มีอาการนั้นทุกวัน (3 คะแนน)</li>
                </ul>
            </div>
        <?php endforeach; ?>
    </form>
</div>
</body>
</html>
