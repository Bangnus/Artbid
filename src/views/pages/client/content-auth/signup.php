<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/../Artbid/public/output.css">
</head>

<body class="bg-gray-100">
    <?php
    require_once __DIR__ . '/../../../../controllers/controllers-backend/controller-auth/AuthController.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $firstname = $_POST['firstname'] ?? '';
        $lastname = $_POST['lastname'] ?? '';
        $email = $_POST['email'] ?? '';
        $role_id = 1;

        $result = AuthController::signup($username, $password, $firstname, $lastname, $email, $role_id);

        if ($result) {
            session_start();
            $_SESSION['sign_up_success'] = true;
            header('Location: home');
            exit;
        }
    }
    ?>

    <div class="flex items-center justify-center h-screen">
        <div class="bg-white p-10 rounded-2xl space-y-5 min-w-[40rem] shadow-sm">
            <h1 class="text-[24px] font-primaryMedium text-textprimary text-center">
                Sign up
            </h1>
            <form method="post" class="space-y-5">

                <div class="space-y-2">
                    <p class="text-[13px] text-textprimary font-primaryRegular">Username</p>
                    <input
                        class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200"
                        type="text"
                        name="username"
                        minlength="6"
                        required>
                </div>
                <div class="flex items-center justify-center gap-x-5 ">
                    <div class="space-y-2 w-full">
                        <p class="text-[13px] text-textprimary font-primaryRegular">First Name</p>
                        <input
                            class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200"
                            type="text"
                            name="firstname"
                            minlength="4"
                            maxlength="8"
                            required>
                    </div>
                    <div class="space-y-2 w-full">
                        <p class="text-[13px] text-textprimary font-primaryRegular">Last Name</p>
                        <input
                            class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200"
                            type="text"
                            name="lastname"
                            minlength="4"
                            maxlength="8"
                            required>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="text-[13px] text-textprimary font-primaryRegular">E-mail Address</p>
                    <input
                        class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200"
                        type="email"
                        name="email"
                        required>
                </div>
                <div class="space-y-2">
                    <p class="text-[13px] text-textprimary font-primaryRegular">Password</p>
                    <input
                        class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200"
                        type="password"
                        name="password"
                        minlength="8"
                        required>
                </div>
                <div class="space-y-5">
                    <button id="signin-btn" type="submit" class="bg-textorange text-white w-full text-[14px] font-primaryMedium py-[12px]  rounded-lg ">
                        <span id="signin-spinner" class="hidden">
                            <?php require_once __DIR__ . '/../../../../components/content-loading/dot-loading.php'; ?>
                        </span>
                        <span id="signin-text">Sign up</span>
                    </button>
                </div>
            </form>
            <div class="space-y-5">
                <?php
                require_once 'google-auth.php'
                ?>
                <div class="text-center">
                    <a href="signin" class="text-[#1E9CF5] font-primaryMedium text-[14px]">
                        Sign in
                    </a>
                </div>
            </div>
        </div>
       
    </div>


    <script>
        document.querySelector('form').addEventListener('submit', function() {
            document.getElementById('signin-spinner').classList.remove('hidden');
            document.getElementById('signin-text').classList.add('hidden');
        });
    </script>
</body>

</html>