<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'superadmin') {
    header("Location: ../auth/login.php");
    exit();
}

// เพิ่มสังกัดย่อย
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $name = trim($_POST['name']);
    $aff_id = $_POST['affiliation_id'];
    if ($name !== "" && is_numeric($aff_id)) {
        $stmt = $conn->prepare("INSERT INTO sub_affiliations (name, affiliation_id) VALUES (?, ?)");
        $stmt->bind_param("si", $name, $aff_id);
        $stmt->execute();
    }
}

// ลบสังกัดย่อย
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM sub_affiliations WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// ดึงข้อมูล
$subs = $conn->query("
    SELECT s.id, s.name, a.name AS affiliation 
    FROM sub_affiliations s
    LEFT JOIN affiliations a ON s.affiliation_id = a.id
    ORDER BY s.id DESC
");

$affs = $conn->query("SELECT * FROM affiliations ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการสังกัดย่อย</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; background-color: #f0f2f5; font-size: 20px; }
        .container { margin-top: 50px; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="container">
    <h4 class="mb-4 text-center">📁 จัดการสังกัดย่อย</h4>

    <form method="post" class="row g-3 mb-4">
        <div class="col-md-5">
            <input type="text" name="name" class="form-control" placeholder="ชื่อสังกัดย่อย" required>
        </div>
        <div class="col-md-5">
            <select name="affiliation_id" class="form-select" required>
                <option value="">เลือกสังกัดหลัก</option>
                <?php while ($a = $affs->fetch_assoc()): ?>
                    <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-2 d-grid">
            <button type="submit" name="add" class="btn btn-success">➕ เพิ่ม</button>
        </div>
    </form>

    <table class="table table-bordered text-center align-middle">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>ชื่อสังกัดย่อย</th>
                <th>สังกัดหลัก</th>
                <th>การจัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; while ($row = $subs->fetch_assoc()): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['affiliation']) ?></td>
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


