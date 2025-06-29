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
                <h1 class="text-2xl font-bold mb-4 text-gray-800">Please confirm your identity through the email we've sent.</h1>
                <p class="text-gray-600 mb-6">We’ve sent a verification link to your email.<br>Please check your inbox and click the link to confirm your identity.</p>
                <a href="home" class="inline-block mt-4 px-6 py-2 bg-bgprimary text-white rounded-lg shadow transition">Go to Homepage</a>
            </div>
        </div>
    <?php
    } else {
        require __DIR__ . '/../../../../components/content-loading/page-loading.php';
        echo '<script>
            setTimeout(function() {
            window.location.href = "seller";
            }, 2000); // 2000 milliseconds = 2 seconds
        </script>';
    }
    ?>


</body>

</html>