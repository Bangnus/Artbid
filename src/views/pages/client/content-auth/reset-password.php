<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รีเซ็ตรหัสผ่าน</title>
    <link rel="stylesheet" href="<?= $_ENV['APP_URL'] ?>/output.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
</head>

<body class="bg-gray-100">
    <?php
    require_once __DIR__ . '/../../../../controllers/controllers-backend/controller-auth/PasswordResetController.php';

    $token = $_GET['token'] ?? '';
    $success = '';
    $error = '';

    // Verify token first
    $tokenValid = PasswordResetController::verifyToken($token);

    if (!$tokenValid['valid']) {
        $error = $tokenValid['message'] ?? 'ลิงก์รีเซ็ตรหัสผ่านไม่ถูกต้องหรือหมดอายุ';
    } else {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if ($password !== $confirmPassword) {
                $error = 'รหัสผ่านไม่ตรงกัน';
            } elseif (strlen($password) < 8) {
                $error = 'รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร';
            } else {
                $result = PasswordResetController::resetPassword($token, $password);

                if ($result['success']) {
                    $success = 'รีเซ็ตรหัสผ่านสำเร็จ กำลังนำคุณไปยังหน้าเข้าสู่ระบบ...';
                    echo '<script>setTimeout(() => window.location.href = "signin", 3000);</script>';
                } else {
                    $error = $result['message'] ?? 'เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง';
                }
            }
        }
    }
    ?>

    <div class="flex items-center justify-center h-screen">
        <div class="bg-white p-10 rounded-2xl space-y-5 min-w-[40rem] shadow-sm">
            <div class="text-center">
                <i class="fas fa-key text-4xl text-bgprimary mb-4"></i>
                <h1 class="text-[24px] font-primaryMedium text-textprimary">
                    รีเซ็ตรหัสผ่าน
                </h1>
                <p class="text-[14px] text-gray-600 font-primaryRegular mt-2">
                    ตั้งรหัสผ่านใหม่สำหรับบัญชีของคุณ
                </p>
            </div>

            <?php if ($success): ?>
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-center">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-center">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if ($tokenValid['valid'] && !$success): ?>
                <form action="" method="post" class="space-y-5">
                    <div class="space-y-2">
                        <p class="text-[13px] text-textprimary font-primaryRegular">รหัสผ่านใหม่</p>
                        <div style="position: relative;">
                            <input id="passwordInput"
                                class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200 pr-10"
                                type="password" name="password" required minlength="8">
                            <button type="button" onclick="togglePassword()"
                                style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #9CA3AF; background: none; border: none; cursor: pointer;">
                                <i class="fa-regular fa-eye" id="passwordIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <p class="text-[13px] text-textprimary font-primaryRegular">ยืนยันรหัสผ่านใหม่</p>
                        <div style="position: relative;">
                            <input id="confirmPasswordInput"
                                class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200 pr-10"
                                type="password" name="confirm_password" required minlength="8">
                            <button type="button" onclick="toggleConfirmPassword()"
                                style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #9CA3AF; background: none; border: none; cursor: pointer;">
                                <i class="fa-regular fa-eye" id="confirmPasswordIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <button id="reset-btn" type="submit"
                            class="bg-textorange text-white w-full text-[14px] font-primaryMedium py-[12px] rounded-lg flex items-center justify-center gap-2">
                            <span id="reset-spinner" class="hidden">
                                <?php require_once __DIR__ . '/../../../../components/content-loading/dot-loading.php'; ?>
                            </span>
                            <span id="reset-text">รีเซ็ตรหัสผ่าน</span>
                        </button>
                    </div>
                </form>
            <?php endif; ?>

            <div class="text-center">
                <a href="signin" class="text-[#1E9CF5] font-primaryMedium text-[14px]">
                    <i class="fas fa-arrow-left mr-2"></i>กลับไปหน้าเข้าสู่ระบบ
                </a>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('form')?.addEventListener('submit', function () {
            document.getElementById('reset-spinner').classList.remove('hidden');
            document.getElementById('reset-text').classList.add('hidden');
        });

        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon = document.getElementById('passwordIcon');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function toggleConfirmPassword() {
            const input = document.getElementById('confirmPasswordInput');
            const icon = document.getElementById('confirmPasswordIcon');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>