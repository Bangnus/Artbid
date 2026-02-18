<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
    <link rel="stylesheet" href="/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <?php
    require_once __DIR__ . '/../../../../controllers/controllers-backend/controller-auth/AuthController.php';
    require_once __DIR__ . '/../../../../middlewares/authMiddlewares.php';
    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $firstname = $_POST['firstname'] ?? '';
        $lastname = $_POST['lastname'] ?? '';
        $email = $_POST['email'] ?? '';
        $role_id = 1;

        $result = AuthController::signup($username, $password, $firstname, $lastname, $email, $role_id);

        if ($result) {
            // Session already started in index.php
            $_SESSION['sign_up_success'] = true;
            header('Location: home');
            exit;
        } else {
            $error = 'ชื่อผู้ใช้หรืออีเมลนี้ถูกใช้งานแล้ว';
        }
    }
    ?>

    <div class="flex items-center justify-center h-screen">
        <div class="bg-white p-10 rounded-2xl space-y-5 min-w-[40rem] shadow-sm">
            <h1 class="text-[24px] font-primaryMedium text-textprimary text-center">
                ลงทะเบียน
            </h1>
            <form method="post" class="space-y-5">

                <div class="space-y-2">
                    <p class="text-[13px] text-textprimary font-primaryRegular">ชื่อผู้ใช้</p>
                    <input
                        class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200"
                        type="text" name="username" minlength="3" required>
                </div>
                <div class="flex items-center justify-center gap-x-5 ">
                    <div class="space-y-2 w-full">
                        <p class="text-[13px] text-textprimary font-primaryRegular">ชื่อจริง</p>
                        <input
                            class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200"
                            type="text" name="firstname" minlength="4" maxlength="8" required>
                    </div>
                    <div class="space-y-2 w-full">
                        <p class="text-[13px] text-textprimary font-primaryRegular">นามสกุล</p>
                        <input
                            class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200"
                            type="text" name="lastname" minlength="4" maxlength="8" required>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="text-[13px] text-textprimary font-primaryRegular">อีเมล</p>
                    <input
                        class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200"
                        type="email" name="email" required>
                </div>
                <div class="space-y-2">
                    <p class="text-[13px] text-textprimary font-primaryRegular">รหัสผ่าน</p>
                    <div style="position: relative;">
                        <input id="passwordInput"
                            class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200 pr-10"
                            type="password" name="password" minlength="8" required>
                        <button type="button" onclick="togglePassword()"
                            style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: #9CA3AF; background: none; border: none; cursor: pointer;">
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
                        class="bg-textorange text-white w-full text-[14px] font-primaryMedium py-[12px]  rounded-lg flex items-center justify-center gap-2">
                        <span id="signin-spinner" class="hidden ">
                            <?php require_once __DIR__ . '/../../../../components/content-loading/dot-loading.php'; ?>
                        </span>
                        <span id="signin-text">ลงทะเบียน</span>
                    </button>
                </div>
            </form>
            <div class="space-y-5">
                <?php
                require_once 'google-auth.php'
                    ?>
                <div class="text-center">
                    <a href="signin" class="text-[#1E9CF5] font-primaryMedium text-[14px]">
                        เข้าสู่ระบบ
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
    </script>
</body>

</html>