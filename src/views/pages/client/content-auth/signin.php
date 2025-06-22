<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/../Artbid/public/output.css">
</head>

<body class="bg-gray-100">


    <div class="flex items-center justify-center h-screen">
        <div class="bg-white p-10 rounded-2xl space-y-5 min-w-[40%] shadow-sm">
            <h1 class="text-[24px] font-primaryMedium text-textprimary text-center">
                Sign in
            </h1>
            <form action="" method="post" class="space-y-5">

                <div class="space-y-2">
                    <p class="text-[13px] text-textprimary font-primaryRegular">Username</p>
                    <input
                        class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200"
                        type="text">
                </div>
                <div class="flex items-center justify-center gap-x-5 ">
                    <div class="space-y-2 w-full">
                        <p class="text-[13px] text-textprimary font-primaryRegular">First Name</p>
                        <input
                            class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200"
                            type="text">
                    </div>
                    <div class="space-y-2 w-full">
                        <p class="text-[13px] text-textprimary font-primaryRegular">Last Name</p>
                        <input
                            class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200"
                            type="text">
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="text-[13px] text-textprimary font-primaryRegular">E-mail Address</p>
                    <input
                        class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200"
                        type="text">
                </div>
                <div class="space-y-2">
                    <p class="text-[13px] text-textprimary font-primaryRegular">Password</p>
                    <input
                        class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200"
                        type="text">
                </div>
            </form>
            <div class="space-y-5">
                <button class="bg-textorange text-white w-full text-[14px] font-primaryMedium py-[12px]  rounded-lg ">
                    Sign up
                </button>
                <?php
                require_once 'google-auth.php'
                ?>
            </div>
        </div>
    </div>
</body>

</html>