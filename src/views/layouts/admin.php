<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Artbid' ?></title>
    <link href="/artbid/public/output.css?v=<?= time() ?>" rel="stylesheet">
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
    require __DIR__ . '/../../middlewares/adminMiddlewares.php';
    ?>
    <!-- Sidebar -->
    <aside x-data="{sidebarOpen: true}" :class="sidebarOpen ? 'w-64' : 'w-16 flex flex-col items-center'"
        class="bg-white shadow-lg px-4 py-3 transition-all duration-300 ease-in-out overflow-hidden"
        style="min-width: 4rem;">
        <div class="flex items-center justify-between gap-x-10 text-textprimary px-2">
            <a x-show="sidebarOpen" href="/Artbid/public/home"
                class="flex flex-col  font-primaryBold transition-opacity duration-300">
                <p class="text-[30px] text-bgsecondary">
                    Artbid
                </p>
                <span class="text-[15px]">
                    ศูนย์ควบคุมผู้ดูแล
                </span>
            </a>
            <i @click="sidebarOpen = !sidebarOpen"
                class="fa-solid fa-angles-left text-[30px] cursor-pointer transition-transform duration-300"
                :class="sidebarOpen ? '' : 'rotate-180'"></i>
        </div>
        <nav class="mt-8">
            <ul class="text-textprimary font-primarySemibold">
                <?php
                $uri = $_SERVER['REQUEST_URI'];
                $dashboardActive = (strpos($uri, '/admin') !== false && strpos($uri, '/admin/users') === false && strpos($uri, '/admin/payment') === false);
                $usersActive = strpos($uri, '/admin/users') !== false;
                $paymentActive = strpos($uri, '/admin/payment') !== false;
                ?>
                <li class="hover:bg-gray-100 p-2 rounded-lg<?= $dashboardActive ? ' bg-gray-100' : '' ?>">
                    <a href="/Artbid/public/admin" class="flex items-center  gap-2 transition-colors">
                        <i
                            :class="sidebarOpen ? 'fa-solid fa-gauge<?= $dashboardActive ? ' text-bgsecondary' : '' ?>' : 'fa-solid fa-gauge text-[20px]<?= $dashboardActive ? ' text-bgsecondary' : '' ?>'"></i>
                        <p x-show="sidebarOpen" class="whitespace-nowrap">
                            แดชบอร์ด
                        </p>
                    </a>
                </li>
                <li class="hover:bg-gray-100 p-2 rounded-lg<?= $usersActive ? ' bg-gray-100' : '' ?>">
                    <a href="/Artbid/public/admin/users" class="flex items-center gap-2 transition-colors">
                        <i
                            :class="sidebarOpen ? 'fa-solid fa-users<?= $usersActive ? ' text-bgsecondary' : '' ?>' : 'fa-solid fa-users text-[20px]<?= $usersActive ? ' text-bgsecondary' : '' ?>'"></i>
                        <p x-show="sidebarOpen" class="whitespace-nowrap">
                            จัดการผู้ใช้
                        </p>
                    </a>
                </li>
                <?php
                $paymentAccountActive = strpos($uri, '/admin/payment/account') !== false;
                $paymentTransactionsActive = strpos($uri, '/admin/payment/transactions') !== false;
                ?>
                <div x-data="{ open: <?= ($paymentActive || $paymentAccountActive || $paymentTransactionsActive) ? 'true' : 'false' ?> }"
                    class="">
                    <li @click="open = !open"
                        class="hover:bg-gray-100 p-2 rounded-lg cursor-pointer<?= ($paymentActive || $paymentAccountActive || $paymentTransactionsActive) ? ' bg-gray-100' : '' ?>">
                        <div class="flex justify-between items-center gap-2 transition-colors">
                            <div class="flex items-center gap-2">
                                <i
                                    :class="sidebarOpen ? 'fa-solid fa-money-bill-transfer<?= ($paymentActive || $paymentAccountActive || $paymentTransactionsActive) ? ' text-bgsecondary' : '' ?>' : 'fa-solid fa-money-bill-transfer text-[20px]<?= ($paymentActive || $paymentAccountActive || $paymentTransactionsActive) ? ' text-bgsecondary' : '' ?>'"></i>
                                <p x-show="sidebarOpen" class="whitespace-nowrap">
                                    การชำระเงิน
                                </p>
                            </div>
                            <i x-show="sidebarOpen" :class="open ? 'fa-solid fa-angle-down' : 'fa-solid fa-angle-right'"
                                class="transition-transform">
                            </i>
                        </div>
                    </li>
                    <div x-show="open" class="pl-4 mt-1 space-y-1" x-transition>
                        <li
                            class="hover:bg-gray-50 p-2 rounded-lg<?= $paymentAccountActive ? ' bg-slate-100 text-bgsecondary' : '' ?>">
                            <a href="/Artbid/public/admin/payment/account"
                                :class="{'flex items-center gap-2 transition-colors': true,'justify-center': !sidebarOpen}">
                                <i class="fa-solid fa-building-columns text-sm"></i>
                                <p x-show="sidebarOpen" class="whitespace-nowrap text-sm">จัดการบัญชีโอนเงิน</p>
                            </a>
                        </li>
                        <li
                            class="hover:bg-gray-50 p-2 rounded-lg<?= $paymentTransactionsActive ? ' bg-slate-100 text-bgsecondary' : '' ?>">
                            <a href="/Artbid/public/admin/payment/transactions"
                                :class="{'flex items-center gap-2 transition-colors': true,'justify-center': !sidebarOpen}">
                                <i class="fa-solid fa-list-check text-sm"></i>
                                <p x-show="sidebarOpen" class="whitespace-nowrap text-sm">จัดการรายการโอนเงิน</p>
                            </a>
                        </li>
                    </div>
                </div>
            </ul>
        </nav>
    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col">

        <!-- Navbar -->
        <header class="bg-white h-16 flex items-center justify-between px-6 shadow">
            <h1 class="text-lg font-semibold"><?= $title ?></h1>
            <div class="flex items-center gap-x-5">
                <a class="p-2 px-4 border border-gray-200 text-bgsecondary font-primarySemibold rounded-lg hover:bg-gray-100"
                    href="">
                    ดูผลงานศิลปะ
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
                                class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-bgsecondary text-white text-lg font-primarySemibold">
                                <?= htmlspecialchars(strtoupper($initials)) ?>
                            </div>
                        <?php endif; ?>
                        <p class="font-primarySemibold text-textprimary">
                            <?= htmlspecialchars($firstname) ?> <?= htmlspecialchars($lastname) ?>
                        </p>
                    </div>
                    <!-- dropdown -->
                    <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
                        class="absolute top-16 left-1/2 -translate-x-1/2 w-[200px] bg-white shadow-lg rounded-lg z-50"
                        style="display: none;">
                        <ul>
                            <li>
                                <a href="/Artbid/public/settings"
                                    class="block py-2 px-4 transition-colors duration-200 hover:bg-gray-100 text-textprimary font-primaryMedium rounded">
                                    ตั้งค่า
                                </a>
                            </li>
                            <li>
                                <a href="/Artbid/public/logout"
                                    class="block py-2 px-4 transition-colors duration-200 hover:bg-gray-100 text-textprimary font-primaryMedium rounded">
                                    ออกจากระบบ
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