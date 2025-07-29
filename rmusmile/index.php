<?php
// สั่ง redirect ไปยังหน้าที่ต้องการ
header("Location: ../rmusmile/auth/login.php");
exit(); // สำคัญ: หยุดการทำงานของสคริปต์หลัง redirect
?>

<?
 header( "Location: http://www.ireallyhost.com" );
 exit(0);
?>
<!--DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เข้าสู่ระบบ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font (Sarabun) -->
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            background: linear-gradient(to right, #74ebd5, #acb6e5);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            background-color: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            text-align: center;
        }

        .login-box h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .login-btn {
            background-color: #007bff;
            border: none;
            font-size: 18px;
            padding: 10px 30px;
            border-radius: 10px;
            color: white;
            transition: 0.3s;
        }

        .login-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>เข้าสู่ระบบ</h2>
    <a href="../auth/login.php" class="btn login-btn">ไปที่หน้า Login</a>
</div>

</body>
</html-->
