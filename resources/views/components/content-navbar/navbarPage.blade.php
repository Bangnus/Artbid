<nav id="mainNavbar"
    class="bg-white px-4 md:px-20 py-2 flex items-center justify-between flex-wrap sticky top-0 z-50 transition-transform duration-300"
    x-data="{ open: false }">
    <!-- Left: Logo -->
    <a href="{{ url('/') }}" class="flex items-center gap-2">
        <img src="{{ asset('icons/AerBid Logo.png') }}" alt="Logo" width="60" height="60">
        <h1 class="text-textorange text-[26px] md:text-[30px] font-primaryBold">Artbid</h1>
    </a>

    <!-- Hamburger Button (เฉพาะจอเล็ก) -->
    <button @click="open = !open" class="text-2xl text-textprimary md:hidden ml-auto">
        <i :class="open ? 'fa-solid fa-xmark' : 'fa-solid fa-bars'"></i>
    </button>

    {{-- Seart Box --}}
    <div
        x-data="{ focused: false }"
        :class="focused ? 'border border-textorange' : ''"
        class="hidden bg-[#F0F0F0] px-[30px] py-[15px] rounded-2xl md:w-[350px] lg:w-[450px] xl:w-[872px] md:flex items-center gap-3"
    >
        <i class="fa-solid fa-magnifying-glass text-textsecondary"></i>
        <input
            class="bg-[#F0F0F0] focus:outline-none w-full"
            type="text"
            placeholder="Search your favorite art to bid now"
            @focus="focused = true"
            @blur="focused = false"
        >
    </div>

    {{-- Right Section --}}
    <div class="hidden md:flex items-center gap-x-[30px]">
        <i class="fa-regular fa-heart text-textprimary text-[20px]"></i>
        <x-content-button.fullbutton class="rounded-[50px] text-[16px] px-[30px] py-2">
            Sign in
        </x-content-button.fullbutton>
    </div>



    <!-- Mobile Menu (เฉพาะจอเล็ก) -->
    <div x-show="open" x-transition class="w-full mt-4 space-y-4 md:hidden">
        <div class="bg-[#F0F0F0] px-6 py-3 rounded-2xl flex items-center gap-3">
            <i class="fa-solid fa-magnifying-glass text-textsecondary"></i>
            <input class="bg-[#F0F0F0] focus:outline-none w-full" type="text"
                placeholder="Search your favorite art to bid now">
        </div>
        <div class="flex items-center gap-4">
            <i class="hidden fa-regular fa-heart text-textprimary text-[20px]"></i>
            <x-content-button.fullbutton class="rounded-[50px] text-[16px] px-[30px] py-2">
                Sign in
            </x-content-button.fullbutton>
        </div>
    </div>
</nav>

<script src="//unpkg.com/alpinejs" defer></script>

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