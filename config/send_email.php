<?php
// ส่งอีเมลด้วย PHPMailer ผ่าน Gmail SMTP
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php'; // Composer autoload หลังติดตั้ง PHPMailer

$userEmail = $_SESSION['user']['email'];
$userId = $_SESSION['user']['id'];
$verifyUrl = $_ENV['APP_URL'] . '/verify';

$mail = new PHPMailer(true);

// Set UTF-8 encoding for Thai language support
$mail->CharSet = 'UTF-8';
$mail->Encoding = 'base64';

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = $_ENV['SMTP_HOST'];          // Gmail SMTP
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['SMTP_USER'];          // ← ใส่อีเมลของคุณ
    $mail->Password = $_ENV['SMTP_PASS'];       // ← ใส่รหัส App Password (16 ตัวอักษร)
    $mail->SMTPSecure = 'tls';
    $mail->Port = $_ENV['SMTP_PORT'];

    // Recipients
    $mail->setFrom($_ENV['SMTP_USER'], 'Artbid.site'); // ชื่อผู้ส่ง
    $mail->addAddress($userEmail); // ผู้รับ

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Verified_email';
    $mail->Body = "
        <h3>ยืนยันการสมัครสมาชิก</h3>
        <p>คลิกปุ่มด้านล่างเพื่อยืนยัน:</p>
        <a href='$verifyUrl' style='
            display:inline-block;
            background:#4CAF50;
            color:white;
            padding:5px 20px;
            text-decoration:none;
            border-radius:5px;
        '>ยืนยันข้อมูล</a>
    ";

    $mail->send();
    // echo '✅ ส่งอีเมลเรียบร้อยแล้ว';
} catch (Exception $e) {
    echo "❌ ไม่สามารถส่งอีเมลได้: {$mail->ErrorInfo}";
}
