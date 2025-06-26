<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Artbid' ?></title>
    <link href="/artbid/public/output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
</head>

<body class="h-screen bg-gray-100 flex">
    <?php require __DIR__ . '/../../middlewares/seller.php' ?>
    <!-- Sidebar -->
    <aside class="min-w-[16%] bg-white shadow-lg px-4 py-3">
        <div class="flex items-center justify-between text-textprimary px-2">
            <h1 class="text-[30px] font-primaryBold">Artbid</h1>
            <i class="fa-solid fa-angles-left text-[30px]"></i>
        </div>
        <nav class="mt-8">
            <ul class=" text-textprimary font-primarySemibold">
                <li class="hover:bg-gray-100 p-2  rounded-lg">
                    <a href="/Artbid/public/seller/dashboard" class="flex items-center gap-2  transition-colors">
                        <i class="fa-solid fa-gauge"></i>
                        Dashboard
                    </a>
                </li>
                <li class="hover:bg-gray-100 p-2 rounded-lg">
                    <a href="/Artbid/public/seller/art-listings" class="flex items-center gap-2  transition-colors">
                        <i class="fa-solid fa-palette"></i>
                        Art Listings
                    </a>
                </li>
                <li class="hover:bg-gray-100 p-2 rounded-lg">
                    <a href="/Artbid/public/seller/bidders" class="flex items-center gap-2  transition-colors">
                        <i class="fa-solid fa-users"></i>
                        Bidders List
                    </a>
                </li class="hover:bg-gray-100 p-2 rounded-lg">
                <li class="hover:bg-gray-100 p-2 rounded-lg">
                    <a href="/Artbid/public/seller/payment" class="flex items-center gap-2  transition-colors">
                        <i class="fa-solid fa-credit-card"></i>
                        Payment
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col">

        <!-- Navbar -->
        <header class="bg-white h-16 flex items-center justify-between px-6 shadow">
            <h1 class="text-lg font-semibold">Dashboard</h1>
            <div>
                <span class="text-gray-600">Hello, User</span>
            </div>
        </header>

        <!-- Content -->
        <main class="p-6 overflow-y-auto flex-1">
            <div class="bg-white rounded-lg shadow p-4">
                <?= $content ?>
            </div>
        </main>

    </div>
</body>

</html>