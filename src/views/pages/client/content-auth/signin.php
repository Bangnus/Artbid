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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
</head>

<body class="bg-gray-100">

    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-6 font-primaryBold">เข้าสู่ระบบ</h2>

            <!-- Error Message -->
            <?php if (!empty($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-6">
                <div>
                    <label for="username"
                        class="block text-sm font-medium text-gray-700 mb-1 font-primaryMedium">ชื่อผู้ใช้</label>
                    <input type="text" id="username" name="username" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-bgsecondary focus:border-bgsecondary outline-none transition-all font-primaryMedium"
                        placeholder="กรอกชื่อผู้ใช้ของคุณ">
                </div>

                <div>
                    <label for="password"
                        class="block text-sm font-medium text-gray-700 mb-1 font-primaryMedium">รหัสผ่าน</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-bgsecondary focus:border-bgsecondary outline-none transition-all font-primaryMedium"
                        placeholder="กรอกรหัสผ่านของคุณ">
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember"
                            class="h-4 w-4 text-bgsecondary focus:ring-bgsecondary border-gray-300 rounded">
                        <label for="remember"
                            class="ml-2 block text-sm text-gray-700 font-primaryRegular">จดจำฉันไว้</label>
                    </div>
                    <a href="forgot-password"
                        class="text-sm text-bgsecondary hover:underline font-primaryRegular">ลืมรหัสผ่าน?</a>
                </div>

                <button type="submit"
                    class="w-full bg-bgsecondary text-white py-2 px-4 rounded-lg hover:bg-[#002a4a] transition-colors font-primaryBold text-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 active:translate-y-0 duration-200">
                    เข้าสู่ระบบ
                </button>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500 font-primaryRegular">หรือดำเนินการต่อด้วย</span>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-3">
                    <?php require __DIR__ . '/google-auth.php'; ?>
                </div>
            </div>

            <p class="mt-8 text-center text-sm text-gray-600 font-primaryRegular">
                ยังไม่มีบัญชี?
                <a href="signup" class="font-medium text-bgsecondary hover:underline font-primaryBold">สมัครสมาชิก</a>
            </p>
        </div>
    </div>
    <script>
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