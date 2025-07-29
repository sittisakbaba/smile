<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// เพิ่มสังกัด
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $name = trim($_POST['name']);
    if ($name !== "") {
        $stmt = $conn->prepare("INSERT INTO affiliations (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
    }
}

// ลบสังกัด
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM affiliations WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// ดึงข้อมูลทั้งหมด
$result = $conn->query("SELECT * FROM affiliations ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการสังกัด</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; background-color: #f0f2f5; font-size: 20px; }
        .container { margin-top: 50px; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="container">
    <h4 class="mb-4 text-center">📁 จัดการสังกัด</h4>

    <form method="post" class="row g-3 mb-4">
        <div class="col-md-10">
            <input type="text" name="name" class="form-control" placeholder="กรอกชื่อสังกัดใหม่..." required>
        </div>
        <div class="col-md-2 d-grid">
            <button type="submit" name="add" class="btn btn-success">➕ เพิ่ม</button>
        </div>
    </form>

    <table class="table table-bordered text-center align-middle">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>ชื่อสังกัด</th>
                <th>การจัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td>
                        <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('ยืนยันการลบ?')" class="btn btn-sm btn-danger">🗑 ลบ</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="text-center mt-3">
        <a href="dashboard.php" class="btn btn-secondary">ย้อนกลับ</a>
    </div>
</div>

</body>
</html>
