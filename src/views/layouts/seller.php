<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="ประมูลรูปภาพ">
    <title>
        <?= $title ?? 'Artbid' ?>
    </title>
    <link href="/artbid/public/output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="h-screen bg-gray-100 flex">
    <?php
    require __DIR__ . '/../../middlewares/sellerMiddlewares.php';
    // require __DIR__ . '/../../middlewares/roleMiddlewares.php';
    $basePath = getenv('BASE_PATH');
    ?>
    <!-- Sidebar -->
    <aside x-data="{sidebarOpen: true}" :class="sidebarOpen ? 'w-64' : 'w-16 flex flex-col items-center'"
        class="bg-white shadow-lg px-4 py-3 transition-all duration-300 ease-in-out overflow-hidden flex-shrink-0"
        style="min-width: 4rem;">
        <div class="flex items-center justify-between gap-x-10 text-textprimary px-2">
            <a x-show="sidebarOpen" href="/Artbid/public/home"
                class="flex flex-col font-primaryBold transition-opacity duration-300">
                <p class="text-[30px] text-textorange">
                    Artbid
                </p>
                <span class="text-[20px]">
                    ศูนย์ผู้ขาย
                </span>
            </a>
            <i @click="sidebarOpen = !sidebarOpen"
                class="fa-solid fa-angles-left text-[30px] cursor-pointer transition-transform duration-300"
                :class="sidebarOpen ? '' : 'rotate-180'">
            </i>
        </div>
        <nav class="mt-8">
            <ul class="text-textprimary font-primarySemibold">
                <?php
                $uri = $_SERVER['REQUEST_URI'];
                $dashboardActive = $uri === '/Artbid/public/seller/dashboard';
                $artActive = $uri === '/Artbid/public/seller/art-listings' || $uri === '/Artbid/public/seller/art-listings/form';
                $biddersActive = $uri === '/Artbid/public/seller/bidders-list';
                $paymentActive = $uri === '/Artbid/public/seller/payment';
                ?>
                <li class="hover:bg-gray-100 p-2 rounded-lg<?= $dashboardActive ? ' bg-gray-100' : '' ?>">
                    <a href="/Artbid/public/seller/dashboard" class="flex items-center  gap-2 transition-colors">
                        <i
                            :class="sidebarOpen ? 'fa-solid fa-gauge<?= $dashboardActive ? ' text-textorange' : '' ?>' : 'fa-solid fa-gauge text-[20px]<?= $dashboardActive ? ' text-textorange' : '' ?>'"></i>
                        <p x-show="sidebarOpen" class="whitespace-nowrap">
                            แดชบอร์ด
                        </p>
                    </a>
                </li>
                <li class="hover:bg-gray-100 p-2 rounded-lg<?= $artActive ? ' bg-gray-100' : '' ?>">
                    <a href="/Artbid/public/seller/art-listings" class="flex items-center gap-2 transition-colors">
                        <i
                            :class="sidebarOpen ? 'fa-solid fa-palette<?= $artActive ? ' text-textorange' : '' ?>' : 'fa-solid fa-palette text-[20px]<?= $artActive ? ' text-textorange' : '' ?>'"></i>
                        <p x-show="sidebarOpen" class="whitespace-nowrap">
                            รายการศิลปะ
                        </p>
                    </a>
                </li>
                <?php $historyActive = $uri === '/Artbid/public/seller/sales-history'; ?>
                <li class="hover:bg-gray-100 p-2 rounded-lg<?= $historyActive ? ' bg-gray-100' : '' ?>">
                    <a href="/Artbid/public/seller/sales-history" class="flex items-center gap-2 transition-colors">
                        <i
                            :class="sidebarOpen ? 'fa-solid fa-clock-rotate-left<?= $historyActive ? ' text-textorange' : '' ?>' : 'fa-solid fa-clock-rotate-left text-[20px]<?= $historyActive ? ' text-textorange' : '' ?>'"></i>
                        <p x-show="sidebarOpen" class="whitespace-nowrap">
                            ประวัติการขาย
                        </p>
                    </a>
                </li>
                <li class="hover:bg-gray-100 p-2 rounded-lg<?= $biddersActive ? ' bg-gray-100' : '' ?>">
                    <a href="/Artbid/public/seller/bidders-list" class="flex items-center gap-2 transition-colors">
                        <i
                            :class="sidebarOpen ? 'fa-solid fa-users<?= $biddersActive ? ' text-textorange' : '' ?>' : 'fa-solid fa-users text-[20px]<?= $biddersActive ? ' text-textorange' : '' ?>'"></i>
                        <p x-show="sidebarOpen" class="whitespace-nowrap">
                            รายชื่อผู้ประมูล
                        </p>
                    </a>
                </li>
                <li class="hover:bg-gray-100 p-2 rounded-lg<?= $paymentActive ? ' bg-gray-100' : '' ?>">
                    <a href="/Artbid/public/seller/payment" class="flex items-center gap-2 transition-colors">
                        <i
                            :class="sidebarOpen ? 'fa-solid fa-credit-card<?= $paymentActive ? ' text-textorange' : '' ?>' : 'fa-solid fa-credit-card text-[20px]<?= $paymentActive ? ' text-textorange' : '' ?>'"></i>
                        <p x-show="sidebarOpen" class="whitespace-nowrap">
                            การชำระเงิน
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col">

        <!-- Navbar -->
        <header class="bg-white h-16 flex items-center justify-between px-10 shadow"
            style="padding-left: 40px; padding-right: 40px;">
            <h1 class="text-lg font-semibold">
                <?= $title ?>
            </h1>
            <div class="flex items-center gap-x-8" style="gap: 32px;">
                <a class="p-2 px-4 border border-gray-200 text-textorange font-primarySemibold rounded-lg hover:bg-gray-100"
                    href="<?php echo $basePath ?>">
                    สำรวจงานศิลปะ
                </a>
                <?php
                $firstname = $_SESSION['user']['firstname'] ?? '';
                $lastname = $_SESSION['user']['lastname'] ?? '';
                $profileImg = $_SESSION['user']['picture'] ?? '';
                $hasImg = !empty($profileImg);
                $initials = mb_substr($firstname, 0, 1) . mb_substr($lastname, 0, 1);
                ?>
                <div x-data="{ dropdownOpen: false }" class="relative">
                    <div @click="dropdownOpen = !dropdownOpen"
                        class=" flex gap-x-2 items-center overflow-hidden cursor-pointer ">
                        <?php if ($hasImg): ?>
                            <img class="inline-block h-10 w-10 rounded-full object-cover"
                                src="<?= htmlspecialchars($_SESSION['user']['picture']) ?>" alt="profile"
                                onerror="<?= htmlspecialchars($firstname) ?> <?= htmlspecialchars($lastname) ?>" />
                        <?php else: ?>
                            <div
                                class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-textorange text-white text-lg font-primarySemibold">
                                <?= htmlspecialchars(strtoupper($initials)) ?>
                            </div>
                        <?php endif; ?>
                        <p class="font-primarySemibold text-textprimary">
                            <?= htmlspecialchars($firstname) ?>
                            <?= htmlspecialchars($lastname) ?>
                        </p>
                    </div>
                    <!-- dropdown -->
                    <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
                        class="absolute top-16 right-0 min-w-[180px] bg-white shadow-lg rounded-lg z-10"
                        style="display: none;">
                        <ul>
                            <li>
                                <a href="/Artbid/public/settings"
                                    class="block py-2 px-4 transition-colors duration-200 hover:bg-gray-100 text-textprimary font-primaryMedium rounded">
                                    <i class="fa-solid fa-gear mr-2"></i>การตั้งค่า
                                </a>
                            </li>
                            <li>
                                <a href="/Artbid/public/logout"
                                    class="block py-2 px-4 transition-colors duration-200 hover:bg-red-50 text-red-500 font-primaryMedium rounded">
                                    <i class="fa-solid fa-right-from-bracket mr-2"></i>ออกจากระบบ
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
        </header>

        <!-- Content -->
        <main class="p-6 overflow-y-auto flex-1">
            <div class="bg-white rounded-xl shadow py-5">
                <?= $content ?>
            </div>
        </main>

    </div>

</body>

</html>