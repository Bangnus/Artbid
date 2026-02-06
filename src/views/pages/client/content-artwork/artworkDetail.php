<?php if (!$artwork): ?>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
        <h2 class="text-2xl font-primaryBold text-gray-700">ไม่พบผลงานศิลปะ</h2>
        <a href="home" class="text-textorange hover:underline mt-4 inline-block font-primaryMedium">กลับสู่หน้าหลัก</a>
    </div>
<?php else: ?>
    <?php
    require_once __DIR__ . '/../../../../controllers/controllers-backend/content-client/FavoriteController.php';
    $isFavorited = FavoriteController::isFavorited($artwork['id']);
    ?>
    <div class="bg-white rounded-2xl px-10 py-5 flex gap-10">
        <div class="w-[40%]">
            <img src="<?= htmlspecialchars($artwork['picture']) ?>" alt="Art"
                class="w-full h-[600px] object-cover object-[0%_10%] rounded-2xl">
        </div>
        <div class="w-[60%] space-y-5">
            <div class="flex items-center justify-between">
                <p class="text-3xl font-primarySemibold text-textprimary"><?= htmlspecialchars($artwork['title']) ?></p>
                <?php if (isset($_SESSION['user'])): ?>
                    <button id="favorite-btn" onclick="toggleFavorite('<?= $artwork['id'] ?>')"
                        class="w-12 h-12 rounded-full flex items-center justify-center transition-all duration-200 hover:scale-110 <?= $isFavorited ? 'bg-red-100' : 'bg-gray-100 hover:bg-red-50' ?>">
                        <i
                            class="<?= $isFavorited ? 'fa-solid text-red-500' : 'fa-regular text-gray-400' ?> fa-heart text-2xl"></i>
                    </button>
                <?php endif; ?>
            </div>
            <div class="space-y-2">
                <p class="text-xl text-textsecondary font-primaryMedium">ศิลปิน :<span
                        class="text-textprimary font-primaryMedium"> <?= htmlspecialchars($artwork['artist']) ?></span></p>
                <p class="text-xl text-textsecondary font-primaryMedium">
                    <?= $artwork['type'] === 'Live' ? 'ราคาปัจจุบัน' : 'ราคาเริ่มต้น' ?> :<span
                        class="text-textprimary font-primaryMedium">
                        ฿<?= htmlspecialchars($artwork['startingprice']) ?></span>
                </p>
                <?php if ($artwork['type'] === 'Live'): ?>
                    <p class="text-xl text-textsecondary font-primaryMedium">ผู้ประมูลสูงสุด :<span
                            class="text-textprimary font-primaryMedium">
                            <?= htmlspecialchars(isset($artwork['bidder_firstname']) ? $artwork['bidder_firstname'] . ' ' . $artwork['bidder_lastname'] : '-') ?></span>
                    </p>
                <?php endif; ?>
                <div class="flex flex-col gap-2 items-start">
                    <p class="text-xl text-textsecondary font-primaryMedium">
                        <?= $artwork['type'] === 'Live' ? 'การประมูลจะจบลงภายใน' : 'การประมูลจะเริ่มภายใน' ?> :
                    </p>

                    <div data-startbidding="<?= htmlspecialchars($artwork['startbidding']) ?>"
                        data-endbidding="<?= htmlspecialchars($artwork['endbidding']) ?>"
                        data-artwork-type="<?= htmlspecialchars($artwork['type']) ?>"
                        class="countdown-timer bg-white px-4 py-3 rounded-xl shadow">
                        <div class="flex items-center justify-center text-center gap-x-5">
                            <!-- Days -->
                            <div class="leading-4">
                                <p class="text-xl lg:text-xl text-textprimary font-primaryMedium countdown-days">00</p>
                                <span class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">วัน</span>
                            </div>
                            <div class="text-xl lg:text-xl font-bold text-textprimary">:</div>
                            <!-- Hours -->
                            <div class="leading-4">
                                <p class="text-xl lg:text-xl text-textprimary font-primaryMedium countdown-hours">00</p>
                                <span
                                    class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">ชั่วโมง</span>
                            </div>
                            <div class="text-xl lg:text-xl font-bold text-textprimary">:</div>
                            <!-- Minutes -->
                            <div class="leading-4">
                                <p class="text-xl lg:text-xl text-textprimary font-primaryMedium countdown-minutes">00</p>
                                <span class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">นาที</span>
                            </div>
                            <div class="text-xl lg:text-xl font-bold text-textprimary">:</div>
                            <!-- Seconds -->
                            <div class="leading-4">
                                <p class="text-xl lg:text-xl text-textprimary font-primaryMedium countdown-seconds">00</p>
                                <span class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">วินาที</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-2 text-textsecondary font-primaryMedium text-sm">
                        <?php
                        $targetDate = $artwork['type'] === 'Live' ? $artwork['endbidding'] : $artwork['startbidding'];
                        $date = new DateTime($targetDate);
                        $thai_months = [
                            1 => 'มกราคม',
                            2 => 'กุมภาพันธ์',
                            3 => 'มีนาคม',
                            4 => 'เมษายน',
                            5 => 'พฤษภาคม',
                            6 => 'มิถุนายน',
                            7 => 'กรกฎาคม',
                            8 => 'สิงหาคม',
                            9 => 'กันยายน',
                            10 => 'ตุลาคม',
                            11 => 'พฤศจิกายน',
                            12 => 'ธันวาคม'
                        ];
                        $day = $date->format('j');
                        $month = $thai_months[(int) $date->format('n')];
                        $year = $date->format('Y') + 543;
                        $time = $date->format('H:i');
                        $thai_date = "$day $month $year เวลา $time น.";
                        ?>
                        <p><?= $artwork['type'] === 'Live' ? 'จบภายในวันที่' : 'เริ่มภายในวันที่' ?> : <?= $thai_date ?></p>
                    </div>
                </div>
            </div>


            <?php if ($artwork['type'] === 'Live'): ?>
                <?php if (isset($_SESSION['user'])): ?>
                    <?php
                    $currentPrice = floatval(str_replace(',', '', $artwork['startingprice']));
                    $minBid = $currentPrice + 100;
                    ?>
                    <form action="place-bid" method="POST" class="flex items-center gap-4 mt-5">
                        <input type="hidden" name="artwork_id" value="<?= $artwork['id'] ?>">
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-primaryMedium">฿</span>
                            <input
                                class="bg-white border border-gray-300 shadow rounded-xl pl-10 pr-5 py-3 focus:outline-none focus:ring-1 focus:ring-bgprimary"
                                type="number" name="bid_amount" value="<?= htmlspecialchars($minBid) ?>" min="<?= $minBid ?>"
                                step="1" required>
                        </div>
                        <button type="submit"
                            class="bg-textorange text-white px-6 py-3 rounded-xl font-primaryMedium hover:bg-orange-600 transition whitespace-nowrap">ประมูล</button>
                    </form>
                    <p class="text-sm text-gray-500 mt-2">*ราคาประมูลขั้นต่ำ: ฿<?= number_format($minBid, 0) ?>
                        (ต้องมากกว่าราคาปัจจุบันอย่างน้อย 100 บาท)</p>
                    <?php if (isset($_GET['status'])): ?>
                        <div id="alert-message" class="transition-opacity duration-500 mt-3">
                            <?php if ($_GET['status'] === 'success'): ?>
                                <div class="p-3 bg-green-100 text-green-700 rounded-lg">
                                    ประมูลสำเร็จ!
                                </div>
                            <?php elseif ($_GET['status'] === 'error'): ?>
                                <div class="p-3 bg-red-100 text-red-700 rounded-lg">
                                    <?= htmlspecialchars($_GET['message'] ?? 'เกิดข้อผิดพลาด') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <script>
                            setTimeout(() => {
                                const alert = document.getElementById('alert-message');
                                if (alert) {
                                    alert.style.opacity = '0';
                                    setTimeout(() => alert.remove(), 500);
                                }
                            }, 4000);
                        </script>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="mt-5">
                        <a href="signin"
                            class="inline-block bg-textorange text-white px-6 py-3 rounded-lg font-primaryMedium hover:bg-orange-600 transition">
                            เข้าสู่ระบบเพื่อประมูล
                        </a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

    </div>
    <div class="flex gap-6 mt-5">
        <div style="width: 50%;" class="bg-white  p-5 rounded-xl">
            <h1 class="font-primaryBold text-2xl">ข้อมูลพื้นฐานของงานศิลปะ</h1>
            <div class="grid grid-cols-2 gap-5 mt-5">
                <div class="bg-white rounded-xl">
                    <p class="font-primaryBold text-lg">ชื่อผลงาน</p>
                    <span class="font-primaryRegular"><?= htmlspecialchars($artwork['artworkname'] ?? '-') ?></span>
                </div>
                <div class="bg-white rounded-xl">
                    <p class="font-primaryBold text-lg">ปีที่สร้าง</p>
                    <span class="font-primaryRegular"><?= htmlspecialchars($artwork['yearcreated'] ?? '-') ?></span>
                </div>
                <div class="bg-white rounded-xl">
                    <p class="font-primaryBold text-lg">ศิลปิน</p>
                    <span class="font-primaryRegular"><?= htmlspecialchars($artwork['artist'] ?? '-') ?></span>
                </div>
                <div class="bg-white rounded-xl">
                    <p class="font-primaryBold text-lg">ขนาด</p>
                    <span class="font-primaryRegular"><?= htmlspecialchars($artwork['dimensions'] ?? '-') ?></span>
                </div>
            </div>
            <div class="bg-white rounded-xl mt-5">
                <p class="font-primaryBold text-lg">รายละเอียด</p>
                <span class="font-primaryRegular"><?= nl2br(htmlspecialchars($artwork['description'] ?? '-')) ?></span>
            </div>
        </div>
        <div style="width: 50%;" class="bg-white p-5 rounded-xl">
            <h1 class="font-primaryBold text-2xl mb-5">การคุ้มครองผู้ซื้อของ <span class="text-textorange">Artbid</span></h1>
            <div class="space-y-4">
                <div>
                    <h3 class="font-primaryBold text-lg">การชำระเงินของคุณปลอดภัย</h3>
                    <p class="text-sm font-primaryRegular text-gray-500 mt-1">
                        เรารับรองความปลอดภัยของเงินของคุณ เราจะโอนเงินให้ผู้ขายหลังจากส่งมอบสินค้าเรียบร้อยแล้ว 3 วัน เพื่อให้คุณมีเวลาตรวจสอบสินค้า
                    </p>
                </div>
                <div>
                    <h3 class="font-primaryBold text-lg">สินค้าทุกชิ้นผ่านการตรวจสอบคุณภาพ</h3>
                    <p class="text-sm font-primaryRegular text-gray-500 mt-1">
                        ผู้เชี่ยวชาญของ Artbid ตรวจสอบและอนุมัติสินค้าทุกชิ้นในการประมูล เพื่อให้แน่ใจว่ามีเพียงสินค้าที่พิเศษและมีคุณภาพเท่านั้นที่ถูกวางจำหน่าย
                    </p>
                </div>
                <div>
                    <h3 class="font-primaryBold text-lg">ผู้ขายทุกรายผ่านการยืนยันตัวตน</h3>
                    <p class="text-sm font-primaryRegular text-gray-500 mt-1">
                        เราและพันธมิตรที่เชื่อถือได้ตรวจสอบผู้ขายอย่างละเอียด ช่วยให้มั่นใจว่าคุณกำลังซื้อจากผู้ขายที่ถูกต้องตามกฎหมายและมุ่งมั่นที่จะส่งมอบสินค้าคุณภาพสูง
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleFavorite(artworkId) {
            const btn = document.getElementById('favorite-btn');
            const icon = btn.querySelector('i');

            // เปลี่ยนสีทันที (optimistic UI)
            if (icon.classList.contains('fa-regular')) {
                btn.classList.remove('bg-gray-100', 'hover:bg-red-50');
                btn.classList.add('bg-red-100');
                icon.classList.remove('fa-regular', 'text-gray-400');
                icon.classList.add('fa-solid', 'text-red-500');
            } else {
                btn.classList.remove('bg-red-100');
                btn.classList.add('bg-gray-100', 'hover:bg-red-50');
                icon.classList.remove('fa-solid', 'text-red-500');
                icon.classList.add('fa-regular', 'text-gray-400');
            }

            // ส่ง API เบื้องหลัง
            fetch('toggle-favorite', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'artwork_id=' + artworkId
            }).catch(error => console.error('Error:', error));
        }

        function showToast(message, type) {
            const toast = document.createElement('div');
            toast.className = `fixed top-5 right-5 z-50 px-5 py-3 rounded-xl shadow-lg text-white font-primaryMedium transition-all duration-300 transform translate-x-full opacity-0 ${type === 'success' ? 'bg-green-500' : 'bg-blue-500'}`;
            toast.textContent = message;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
            }, 100);

            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 2000);
        }
    </script>
<?php endif; ?>