<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in</title>
    <link rel="stylesheet" href="/../Artbid/public/output.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
</head>

<body class="bg-gray-100">
    <?php
    require_once __DIR__ . '/../../../../controllers/controllers-backend/controller-auth/AuthController.php';
    require_once __DIR__ . '/../../../../middlewares/authMiddlewares.php';

    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $result = AuthController::signin($username, $password);

        if ($result) {
            session_start();
            $_SESSION['sign_in_success'] = true;
            header('Location: home');
            exit;
        } else {
            $error = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
        }
    }
    ?>

    <div class="flex items-center justify-center h-screen">
        <div class="bg-white p-10 rounded-2xl space-y-5 min-w-[40rem] shadow-sm">
            <h1 class="text-[24px] font-primaryMedium text-textprimary text-center">
                เข้าสู่ระบบ
            </h1>
            <form action="" method="post" class="space-y-5">

                <div class="space-y-2">
                    <p class="text-[13px] text-textprimary font-primaryRegular">ชื่อผู้ใช้</p>
                    <input
                        class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200"
                        type="text" name="username" required>
                </div>
                <div class="space-y-2">
                    <p class="text-[13px] text-textprimary font-primaryRegular">รหัสผ่าน</p>
                    <div style="position: relative;">
                        <input id="passwordInput"
                            class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200 pr-10"
                            type="password" name="password" required>
                        <button type="button" onclick="togglePassword()"
                            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #9CA3AF; background: none; border: none; cursor: pointer;">
                            <i class="fa-regular fa-eye" id="passwordIcon"></i>
                        </button>
                    </div>
                </div>
                <?php if ($error): ?>
                    <div class="text-[14px] text-red-500 text-center font-primaryRegular"><?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
                <div class="space-y-5">
                    <button id="signin-btn" type="submit"
                        class="bg-textorange text-white w-full text-[14px] font-primaryMedium py-[12px] rounded-lg flex items-center justify-center gap-2">
                        <span id="signin-spinner" class="hidden">
                            <?php require_once __DIR__ . '/../../../../components/content-loading/dot-loading.php'; ?>
                        </span>
                        <span id="signin-text">เข้าสู่ระบบ</span>
                    </button>
                </div>
            </form>
            <div class="space-y-5">
                <?php
                require_once 'google-auth.php'
                    ?>
                <div class="text-center">
                    <a href="signup" class="text-[#1E9CF5] font-primaryMedium text-[14px]">
                        ลงทะเบียน
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function () {
            document.getElementById('signin-spinner').classList.remove('hidden');
            document.getElementById('signin-text').classList.add('hidden');
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
    </script>
</body>

</html>