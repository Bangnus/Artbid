<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fetch notification count (pending payments)
$pendingPaymentCount = 0;
// Ensure APP_URL is available
$app_url = '';

if (isset($_SESSION['user']['id'])) {
    // Check if dbConnect exists, if not try to include database.php
    if (!function_exists('dbConnect')) {
        $dbPath = __DIR__ . '/../../../../config/database.php';
        if (file_exists($dbPath)) {
            require_once $dbPath;
        }
    }

    if (function_exists('dbConnect')) {
        try {
            $pdo = dbConnect();
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM payments WHERE buyer_id = ? AND status = 'pending_buyer_payment'");
            $stmt->execute([$_SESSION['user']['id']]);
            $pendingPaymentCount = $stmt->fetchColumn();
        } catch (Exception $e) {
            // Silent error
        }
    }
}
?>
<nav id="mainNavbar"
    class="bg-white gap-x-5 px-4 md:px-20 py-2 flex items-center justify-between flex-wrap sticky top-0 z-50 transition-transform duration-300"
    x-data="{ open: false }">

    <!-- Left: Logo -->
    <a href="/" class="flex items-center ">
        <img src="/icons/AerBid Logo.png" alt="Logo" width="60" height="60">
        <h1 class="text-textorange text-[26px] md:text-[30px] font-primaryBold">Artbid</h1>
    </a>

    <!-- Hamburger Button (เฉพาะจอเล็ก) -->
    <button @click="open = !open" class="text-2xl text-textprimary md:hidden ml-auto">
        <i :class="open ? 'fa-solid fa-xmark' : 'fa-solid fa-bars'"></i>
    </button>

    <!-- Search Box -->
    <form action="home" method="GET" x-data="{ focused: false }" :class="focused ? 'border border-textorange' : ''"
        class="hidden bg-[#F0F0F0] px-[30px] py-[15px] rounded-2xl w-auto flex-1 md:flex items-center  gap-3">
        <button type="submit">
            <i class="fa-solid fa-magnifying-glass text-textsecondary"></i>
        </button>
        <input class="bg-[#F0F0F0] focus:outline-none w-full" type="text" name="search"
            value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
            placeholder="ค้นหางานศิลปะที่คุณชื่นชอบเพื่อประมูลตอนนี้" @focus="focused = true" @blur="focused = false">
    </form>

    <!-- Right Section -->
    <div class="hidden md:flex items-center gap-x-[30px] relative">
        <a href="favorites" class="hover:scale-110 transition-transform">
            <i class="fa-regular fa-heart text-textprimary text-[20px] hover:text-red-500 transition-colors"></i>
        </a>
        <?php if (!isset($_SESSION['user'])): ?>
            <a href="signin"
                class="bg-textorange text-white w-full font-primaryMedium rounded-[50px] text-[16px] px-[30px] py-2 text-center">
                เข้าสู่ระบบ
            </a>
        <?php else: ?>
            <?php
            $firstname = $_SESSION['user']['firstname'] ?? '';
            $lastname = $_SESSION['user']['lastname'] ?? '';
            $profileImg = $_SESSION['user']['picture'] ?? '';
            $hasImg = !empty($profileImg);
            $initials = mb_substr($firstname, 0, 1) . mb_substr($lastname, 0, 1);
            ?>
            <div x-data="{ dropdownOpen: false }" class="relative">
                <div @click="dropdownOpen = !dropdownOpen"
                    class=" flex gap-x-2 items-center overflow-hidden cursor-pointer relative">
                    <?php if ($hasImg): ?>
                        <img class="inline-block h-10 w-10 rounded-full object-cover"
                            src="/image?type=user&id=<?= htmlspecialchars($_SESSION['user']['id']) ?>" alt="profile" />
                    <?php else: ?>
                        <div
                            class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-textorange text-white text-lg font-primarySemibold">
                            <?= htmlspecialchars(strtoupper($initials)) ?>
                        </div>
                    <?php endif; ?>

                    <!-- Notification Dot on Profile -->
                    <?php if ($pendingPaymentCount > 0): ?>
                        <span class="absolute top-0 right-0 block w-3 h-3 bg-red-500 rounded-full ring-2 ring-white"></span>
                    <?php endif; ?>

                    <p class="font-primarySemibold text-textprimary">
                        <?= htmlspecialchars($firstname) ?>
                        <?= htmlspecialchars($lastname) ?>
                    </p>
                </div>
                <!-- dropdown -->
                <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
                    class="absolute top-16 left-1/2 -translate-x-1/2 w-[200px] bg-white shadow-lg rounded-lg z-50"
                    style="display: none;">
                    <ul>
                        <li>
                            <?php if (isset($_SESSION['user']['role_id']) && $_SESSION['user']['role_id'] == 3): ?>
                                <a href="/admin"
                                    class="block py-2 px-4 transition-colors duration-200 hover:bg-gray-100 text-textprimary font-primaryMedium rounded">
                                    <i class="fa-solid fa-user-shield mr-2"></i>ผู้ดูแลระบบ
                                </a>
                            <?php else: ?>
                                <a href="verify_email"
                                    class="block py-2 px-4 transition-colors duration-200 hover:bg-gray-100 text-textprimary font-primaryMedium rounded">
                                    <i class="fa-solid fa-store mr-2"></i>ศูนย์ผู้ขาย
                                </a>
                            <?php endif; ?>
                        </li>
                        <li>
                            <a href="settings"
                                class="block py-2 px-4 transition-colors duration-200 hover:bg-gray-100 text-textprimary font-primaryMedium rounded">
                                <i class="fa-solid fa-gear mr-2"></i>การตั้งค่า
                            </a>
                        </li>
                        <li>
                            <a href="my-auctions"
                                class="block py-2 px-4 transition-colors duration-200 hover:bg-gray-100 text-textprimary font-primaryMedium rounded flex items-center justify-between">
                                <span><i class="fa-solid fa-gavel mr-2"></i>การประมูล</span>
                                <?php if ($pendingPaymentCount > 0): ?>
                                    <span
                                        class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full"><?= $pendingPaymentCount ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li>
                            <a href="collection"
                                class="block py-2 px-4 transition-colors duration-200 hover:bg-gray-100 text-textprimary font-primaryMedium rounded">
                                <i class="fa-solid fa-images mr-2"></i>คอลเลกชัน
                            </a>
                        </li>
                        <li>
                            <a href="favorites"
                                class="block py-2 px-4 transition-colors duration-200 hover:bg-gray-100 text-textprimary font-primaryMedium rounded">
                                <i class="fa-solid fa-heart mr-2"></i>รายการที่น่าสนใจ
                            </a>
                        </li>
                        <li>
                            <a href="my-payments"
                                class="block py-2 px-4 transition-colors duration-200 hover:bg-gray-100 text-textprimary font-primaryMedium rounded">
                                <i class="fa-solid fa-receipt mr-2"></i>ประวัติการชำระ
                            </a>
                        </li>
                        <li>
                            <a href="logout"
                                class="block py-2 px-4 transition-colors duration-200 hover:bg-gray-100 text-red-500 font-primaryMedium rounded">
                                <i class="fa-solid fa-right-from-bracket mr-2"></i>ออกจากระบบ
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Mobile Menu (เฉพาะจอเล็ก) -->
    <div x-show="open" x-transition class="w-full mt-4 space-y-4 md:hidden">
        <div class="bg-[#F0F0F0] px-6 py-3 rounded-2xl flex items-center gap-3">
            <i class="fa-solid fa-magnifying-glass text-textsecondary"></i>
            <input class="bg-[#F0F0F0] focus:outline-none w-full" type="text"
                placeholder="ค้นหางานศิลปะที่ชื่นชอบเพื่อประมูลตอนนี้">
        </div>
        <div class="flex items-center gap-4 justify-center">
            <i class="hidden fa-regular fa-heart text-textprimary text-[20px]"></i>
            <?php if (!isset($_SESSION['user'])): ?>
                <a href="signin"
                    class="bg-textorange text-white w-full font-primaryMedium rounded-[50px] text-[16px] px-[30px] py-2 text-center">
                    เข้าสู่ระบบ
                </a>
            <?php else: ?>
                <?php
                $firstname = $_SESSION['user']['firstname'] ?? '';
                $lastname = $_SESSION['user']['lastname'] ?? '';
                $profileImg = $_SESSION['user']['picture'] ?? '';
                $hasImg = !empty($profileImg);
                $initials = mb_substr($firstname, 0, 1) . mb_substr($lastname, 0, 1);
                ?>
                <div class="flex gap-x-2 items-center overflow-hidden">
                    <?php if ($hasImg): ?>
                        <img class="inline-block h-10 w-10 rounded-full object-cover"
                            src="/image?type=user&id=<?= htmlspecialchars($_SESSION['user']['id']) ?>" alt="profile" />
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
            <?php endif; ?>
        </div>
    </div>
</nav>

<script>
    let lastScrollTop = 0;
    const navbar = document.querySelector('#mainNavbar');

    window.addEventListener('scroll', function () {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollTop > lastScrollTop) {
            // เลื่อนลง — ซ่อน navbar
            navbar.style.transform = 'translateY(-100%)';
        } else {
            // เลื่อนขึ้น — แสดง navbar
            navbar.style.transform = 'translateY(0)';
        }

        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop; // สำหรับ iOS Safari
    });
</script>