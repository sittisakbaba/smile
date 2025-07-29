<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'superadmin') {
    header("Location: ../auth/login.php");
    exit();
}

$result = null;
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = $_POST['sql_query'] ?? '';

    // ห้าม DROP, DELETE ฯลฯ ถ้าต้องการความปลอดภัยเพิ่มเติม
    if (preg_match('/\b(drop|delete|truncate)\b/i', $sql)) {
        $error = "คำสั่งนี้ไม่อนุญาตให้ใช้งาน (DROP, DELETE, TRUNCATE)";
    } else {
        $query = $conn->query($sql);
        if ($query) {
            if ($query instanceof mysqli_result) {
                $result = $query->fetch_all(MYSQLI_ASSOC);
            } else {
                $result = true;
            }
        } else {
            $error = $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>SQL Console</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; font-size: 18px; background-color: #f4f4f4; }
        .container { max-width: 1000px; margin-top: 40px; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        textarea { font-family: monospace; font-size: 16px; }
    </style>
</head>
<body>
<div class="container">
    <h4 class="text-center mb-4">🧪 SQL Console (เฉพาะ Super Admin)</h4>

    <form method="post" class="mb-4">
        <label for="sql_query" class="form-label">พิมพ์คำสั่ง SQL ที่ต้องการ:</label>
        <textarea name="sql_query" id="sql_query" rows="5" class="form-control" required><?= htmlspecialchars($_POST['sql_query'] ?? '') ?></textarea>
        <button type="submit" class="btn btn-primary mt-3">▶ รันคำสั่ง</button>
    </form>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php elseif ($result === true): ?>
        <div class="alert alert-success">✔️ คำสั่ง SQL สำเร็จแล้ว (ไม่มีผลลัพธ์)</div>
    <?php elseif (is_array($result)): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-sm text-center">
                <thead class="table-light">
                    <tr>
                        <?php foreach (array_keys($result[0] ?? []) as $col): ?>
                            <th><?= htmlspecialchars($col) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result as $row): ?>
                        <tr>
                            <?php foreach ($row as $val): ?>
                                <td><?= htmlspecialchars($val) ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="dashboard.php" class="btn btn-secondary">🔙 ย้อนกลับ</a>
    </div>
</div>
</body>
</html>
