<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VerifyEmail</title>
    <link href="/artbid/public/output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

</head>

<body>
    <?php
    if (!isset($_SESSION['user']['verifyemail']) || $_SESSION['user']['verifyemail'] !== true) {
    ?>
        <?php require __DIR__ . '/../../../../../config/send_email.php' ?>
        <div class="min-h-screen flex items-center justify-center bg-gradient-to-br">
            <div class="text-center space-y-3">
                <i class="text-[60px] text-textorange fa-solid fa-paper-plane"></i>
                <h1 class="text-2xl font-bold mb-4 text-gray-800">กรุณายืนยันตัวตนในอีเมลของคุณ</h1>
                <p class="text-gray-600 mb-6">เราได้ส่งลิงก์ยืนยันไปที่อีเมลของคุณ<br>โปรดตรวจสอบกล่องจดหมายและคลิกลิงก์เพื่อยืนยันตัวตน</p>
                <a href="home" class="inline-block mt-4 px-6 py-2 bg-bgprimary text-white rounded-lg shadow transition">กลับสู่หน้าหลัก</a>
            </div>
        </div>
    <?php
    } else {
        header('Location: seller');
    }
    ?>


</body>

</html>