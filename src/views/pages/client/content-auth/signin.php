<?php
require_once __DIR__ . '/../../../../controllers/controllers-backend/controller-auth/AuthController.php';
require_once __DIR__ . '/../../../../middlewares/authMiddlewares.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $result = AuthController::signin($username, $password);

    if ($result) {
        $_SESSION['sign_in_success'] = true;

        // Check if user is admin (role_id = 3) and redirect to admin page
        if (isset($_SESSION['user']['role_id']) && $_SESSION['user']['role_id'] == 3) {
            header('Location: admin');
        } else {
            header('Location: home');
        }
        exit;
    } else {
        $error = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in</title>
    <link rel="stylesheet" href="/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100">

    <div class="flex items-center justify-center h-screen">
        <div class="bg-white p-10 rounded-2xl space-y-5 min-w-[40rem] shadow-sm">
            <h1 class="text-[24px] font-primaryMedium text-textprimary text-center">
                เข้าสู่ระบบ
            </h1>

            <form action="" method="POST" class="space-y-5">
                <div class="space-y-2">
                    <p class="text-[13px] text-textprimary font-primaryRegular">ชื่อผู้ใช้</p>
                    <input type="text" id="username" name="username" required
                        class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200"
                        placeholder="กรอกชื่อผู้ใช้ของคุณ">
                </div>

                <div class="space-y-2">
                    <p class="text-[13px] text-textprimary font-primaryRegular">รหัสผ่าน</p>
                    <div style="position: relative;">
                        <input id="passwordInput"
                            class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200 pr-10"
                            type="password" name="password" required placeholder="กรอกรหัสผ่านของคุณ">
                        <button type="button" onclick="togglePassword()"
                            style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: #9CA3AF; background: none; border: none; cursor: pointer;">
                            <i class="fa-regular fa-eye" id="passwordIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember"
                            class="h-4 w-4 text-bgprimary focus:ring-bgprimary border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700 font-primaryRegular">จดจำฉันไว้</label>
                    </div>
                    <a href="forgot-password" class="text-sm text-bgprimary hover:underline font-primaryRegular">ลืมรหัสผ่าน?</a>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="text-[14px] text-red-500 text-center font-primaryRegular"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <div class="space-y-5">
                    <button type="submit"
                        class="bg-textorange text-white w-full text-[14px] font-primaryMedium py-[12px] rounded-lg flex items-center justify-center shadow-md hover:shadow-lg transform hover:-translate-y-0.5 active:translate-y-0 duration-200">
                        เข้าสู่ระบบ
                    </button>
                </div>
            </form>

            <div class="space-y-5">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500 font-primaryRegular">หรือดำเนินการต่อด้วย</span>
                    </div>
                </div>

                <?php require __DIR__ . '/google-auth.php'; ?>

                <div class="text-center">
                    <p class="text-sm text-gray-600 font-primaryRegular">
                        ยังไม่มีบัญชี?
                        <a href="signup" class="text-[#1E9CF5] font-primaryMedium text-[14px]">สมัครสมาชิก</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('passwordInput');
            const passwordIcon = document.getElementById('passwordIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        }

        // Check if sign in was successfull
        <?php if (isset($_SESSION['sign_in_success']) && $_SESSION['sign_in_success']): ?>
            <?php unset($_SESSION['sign_in_success']); // Clear the flag 
            ?>
            const notyf = new Notyf({
                position: {
                    x: 'right',
                    y: 'top',
                },
                duration: 3000,
                ripple: true,
                dismissible: true
            });
            notyf.success('เข้าสู่ระบบสำเร็จ!');
        <?php endif; ?>
    </script>
</body>

</html>