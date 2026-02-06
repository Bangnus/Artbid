<nav id="mainNavbar"
    class="bg-white px-4 md:px-20 py-2 flex items-center justify-between flex-wrap sticky top-0 z-50 transition-transform duration-300"
    x-data="{ open: false }">

    <!-- Left: Logo -->
    <a href="/Artbid/public/" class="flex items-center ">
        <img src="/../Artbid/public/icons/AerBid Logo.png" alt="Logo" width="60" height="60">
        <h1 class="text-textorange text-[26px] md:text-[30px] font-primaryBold">Artbid</h1>
    </a>

    <!-- Hamburger Button (เฉพาะจอเล็ก) -->
    <button @click="open = !open" class="text-2xl text-textprimary md:hidden ml-auto">
        <i :class="open ? 'fa-solid fa-xmark' : 'fa-solid fa-bars'"></i>
    </button>

    <!-- Search Box -->
    <form action="home" method="GET" x-data="{ focused: false }" :class="focused ? 'border border-textorange' : ''"
        class="hidden bg-[#F0F0F0] px-[30px] py-[15px] rounded-2xl md:w-[350px] lg:w-[450px] xl:w-[872px] md:flex items-center gap-3">
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
                    class=" flex gap-x-2 items-center overflow-hidden cursor-pointer ">
                    <?php if ($hasImg): ?>
                        <img class="inline-block h-10 w-10 rounded-full object-cover"
                            src="<?= htmlspecialchars($_SESSION['user']['picture']) ?>" alt="profile" onerror="
                        <?= htmlspecialchars($firstname) ?>
                        <?= htmlspecialchars($lastname) ?>" />
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
                    class="absolute top-16 left-1/2 -translate-x-1/2 w-[200px] bg-white shadow-lg rounded-lg z-50"
                    style="display: none;">
                    <ul>
                        <li>
                            <a href="verify_email"
                                class="block py-2 px-4 transition-colors duration-200 hover:bg-gray-100 text-textprimary font-primaryMedium rounded">
                                ศูนย์ผู้ขาย
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="block py-2 px-4 transition-colors duration-200 hover:bg-gray-100 text-textprimary font-primaryMedium rounded">
                                การตั้งค่า
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="block py-2 px-4 transition-colors duration-200 hover:bg-gray-100 text-textprimary font-primaryMedium rounded">
                                การประมูลของคุณ
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="block py-2 px-4 transition-colors duration-200 hover:bg-gray-100 text-textprimary font-primaryMedium rounded">
                                คอลเลกชันของคุณ
                            </a>
                        </li>
                        <li>
                            <a href="favorites"
                                class="block py-2 px-4 transition-colors duration-200 hover:bg-gray-100 text-textprimary font-primaryMedium rounded">
                                รายการที่น่าสนใจของคุณ
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="block py-2 px-4 transition-colors duration-200 hover:bg-gray-100 text-textprimary font-primaryMedium rounded">
                                การชำระเงินของคุณ
                            </a>
                        </li>
                        <li>
                            <a href="logout"
                                class="block py-2 px-4 transition-colors duration-200 hover:bg-gray-100 text-textprimary font-primaryMedium rounded">
                                ออกจากระบบ
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
                placeholder="ค้นหางานศิลปะที่คุณชื่นชอบเพื่อประมูลตอนนี้">
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
                        <img class="inline-block h-10 w-10 rounded-full object-cover "
                            src="<?= htmlspecialchars($_SESSION['user']['picture'] ?? '') ?>" alt="profile" />
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