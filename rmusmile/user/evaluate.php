<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit();
}

// รับ user id จาก session
$user_id = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $answers = [];
    for ($i = 1; $i <= 9; $i++) {
        $key = 'score' . $i;
        if (!isset($_POST[$key])) {
            echo "<script>alert('⚠️ กรุณาตอบแบบประเมินให้ครบทุกข้อ'); window.history.back();</script>";
            exit();
        }
        $answers[$i] = (int) $_POST[$key];
    }

    // คำนวณคะแนนรวม
    $total_score = array_sum($answers);

    // ระดับความรุนแรง
    if ($total_score >= 0 && $total_score <= 4) {
        $level = "น้อยมาก";
    } elseif ($total_score <= 8) {
        $level = "เล็กน้อย";
    } elseif ($total_score <= 14) {
        $level = "ปานกลาง";
    } elseif ($total_score <= 19) {
        $level = "ค่อนข้างมาก";
    } else {
        $level = "รุนแรงมาก";
    }

    // บันทึกลงฐานข้อมูล
    $stmt = $conn->prepare("INSERT INTO evaluations 
        (user_id, q1, q2, q3, q4, q5, q6, q7, q8, q9, total_score, danger_level) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("SQL Prepare Failed: " . $conn->error);
    }
$stmt->bind_param("iiiiiiiiiiis",
    $user_id,
    $answers[1], $answers[2], $answers[3], $answers[4], $answers[5],
    $answers[6], $answers[7], $answers[8], $answers[9],
    $total_score, $level
);


    if ($stmt->execute()) {
        echo "<script>alert('✅ ส่งแบบประเมินสำเร็จ'); window.location='results.php';</script>";
    } else {
        echo "<script>alert('❌ บันทึกไม่สำเร็จ: " . $stmt->error . "');</script>";
    }
    exit();
}
?>

<!-- แบบฟอร์มประเมิน -->
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แบบประเมิน</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; font-size: 18px; background-color: #f8f9fa; }
        .container { max-width: 900px; margin-top: 40px; background: white; padding: 30px; border-radius: 12px; }
    </style>
</head>
<body>
<div class="container">
    <h4 class="text-center mb-4">📝 แบบประเมินสุขภาพจิต (โรคซึมเศร้า)</h4>
    <form method="post">
        <?php
        $questions = [
            "1. เบื่อ ไม่สนใจอยากทำอะไร",
            "2. ไม่สบายใจ ซึมเศร้า ท้อแท้",
            "3. หลับยาก หรือหลับๆ ตื่นๆ หรือหลับมากไป",
            "4. เหนื่อยง่าย หรือ ไม่ค่อยมีแรง",
            "5. เบื่ออาหาร หรือกินมากเกินไป",
            "6. รู้สึกไม่ดีกับตัวเอง คิดว่าตัวเองล้มเหลว หรือทำให้ครอบครัวผิดหวัง",
            "7. สมาธิไม่ดีเวลาทำอะไร เช่น ดูทีวี ฟังวิทยุ หรือทำงานที่ต้องใช้สมาธิ",
            "8. พูดช้าลง หรือกระสับกระส่ายอยู่นิ่งไม่ได้เหมือนที่เคย",
            "9. คิดทำร้ายตนเอง หรือคิดว่าถ้าตายไปคงจะดี"
        ];

        $choices = [
            0 => "ไม่มีอาการเลย",
            1 => "มีอาการเป็นบางวัน",
            2 => "มีอาการบ่อย",
            3 => "มีอาการทุกวัน"
        ];

        foreach ($questions as $i => $q) {
            $index = $i + 1;
            echo "<div class='mb-3'><label class='form-label'>$q</label>";
            foreach ($choices as $val => $label) {
                echo "<div class='form-check'>
                        <input class='form-check-input' type='radio' name='score$index' value='$val' required>
                        <label class='form-check-label'>$label</label>
                      </div>";
            }
            echo "</div>";
        }
        ?>
        <div class="text-center mt-4">
            <button type="submit" class="btn btn-success">✅ ส่งแบบประเมิน</button>
            <a href="dashboard.php" class="btn btn-secondary">ย้อนกลับ</a>
        </div>
    </form>
</div>
</body>
</html>
