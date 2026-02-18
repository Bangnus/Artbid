<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'ArtBid' ?></title>
    <link rel="icon" type="image/png" href="/icons/artbidlogo.png">
    <link href="/output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-100 ">

    <?php if (!isset($_SESSION['user'])): ?>
        <a href="signup"
            class="fixed flex items-center justify-center gap-x-2 right-0  bottom-8  z-50 bg-bgprimary py-3 px-5 rounded-s-xl text-white font-primaryMedium">
            <div class="animate-pulse">
                <i class="fa-solid fa-angles-right"></i>
            </div>
            <p>สมัครสมาชิก</p>
        </a>
    <?php endif; ?>

    <?php require __DIR__ . '/../../components/content-navbar/navbar.php'; ?>


    <main class="<?= isset($mainClass) ? $mainClass : 'mx-5 md:mx-20 my-5' ?>">
        <?= $content ?>
    </main>


    <?php require __DIR__ . '/../../components/content-footer/footer.php'; ?>



    <!-- countdown-timer -->
    <script>
        let pageReloaded = false;

        function updateCountdowns() {
            const timers = document.querySelectorAll('.countdown-timer');
            let anyExpired = false;

            timers.forEach(function (timer) {
                const startStr = timer.getAttribute('data-startbidding');
                const endStr = timer.getAttribute('data-endbidding');
                const artworkType = timer.getAttribute('data-artwork-type'); // New attribute check

                if (!startStr || !endStr) return;

                const start = new Date(startStr.replace(' ', 'T'));
                const end = new Date(endStr.replace(' ', 'T'));
                const now = new Date();

                // Determine if upcoming based on attribute, fallback to class check if missing
                let isUpcoming = false;
                if (artworkType) {
                    isUpcoming = (artworkType === 'Upcoming');
                } else {
                    isUpcoming = timer.parentElement && timer.parentElement.querySelector('.bg-green-600');
                }

                // Reload logic:
                // 1. Upcoming -> Live: reload when now >= start
                // 2. Live -> Ended: reload when now >= end
                if (isUpcoming) {
                    if (now >= start) anyExpired = true;
                } else {
                    if (now >= end) anyExpired = true;
                }

                let targetTime;
                let isBeforeStart = now < start;

                // Display logic
                if (isBeforeStart) {
                    targetTime = start;
                } else {
                    targetTime = end;
                }

                let diff = targetTime - now;

                if (!isBeforeStart && diff <= 0) {
                    const doneText = 'หมดเวลา';
                    timer.querySelector('.countdown-days').innerText = doneText;
                    timer.querySelector('.countdown-hours').innerText = '--';
                    timer.querySelector('.countdown-minutes').innerText = '--';
                    timer.querySelector('.countdown-seconds').innerText = '--';
                    return;
                }

                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                diff %= (1000 * 60 * 60 * 24);
                const hours = Math.floor(diff / (1000 * 60 * 60));
                diff %= (1000 * 60 * 60);
                const minutes = Math.floor(diff / (1000 * 60));
                diff %= (1000 * 60);
                const seconds = Math.floor(diff / 1000);

                timer.querySelector('.countdown-days').innerText = String(days).padStart(2, '0');
                timer.querySelector('.countdown-hours').innerText = String(hours).padStart(2, '0');
                timer.querySelector('.countdown-minutes').innerText = String(minutes).padStart(2, '0');
                timer.querySelector('.countdown-seconds').innerText = String(seconds).padStart(2, '0');
            });

            if (anyExpired && !pageReloaded) {
                pageReloaded = true;
                setTimeout(function () {
                    location.reload();
                }, 1000);
            }
        }

        setInterval(updateCountdowns, 1000);
        updateCountdowns();
    </script>
</body>


</html>