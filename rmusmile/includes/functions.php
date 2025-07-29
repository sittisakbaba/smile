<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendWelcomeEmail($email, $name) {
    require '../vendor/autoload.php';

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // หรือ SMTP ของ host คุณ
        $mail->SMTPAuth = true;
        $mail->Username = 'youremail@gmail.com';
        $mail->Password = 'your-app-password'; // ใช้ App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('youremail@gmail.com', 'ระบบประเมินโรคซึมเศร้า');
        $mail->addAddress($email, $name);
        $mail->isHTML(true);
        $mail->Subject = 'ยินดีต้อนรับ';
        $mail->Body = 'สวัสดีคุณ ' . $name . ', คุณสมัครสมาชิกเรียบร้อยแล้ว!';

        $mail->send();
    } catch (Exception $e) {
        error_log("การส่งเมลล้มเหลว: {$mail->ErrorInfo}");
    }
}
?>
