<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 min-h-screen bg-white">
    <h1 class="text-3xl font-primaryBold text-textprimary">
        <i class="fa-solid fa-gavel text-textorange mr-2"></i>
        การประมูลของฉัน
    </h1>

    <!-- Tabs -->
    <div x-data="{ activeTab: 'won' }" class="mt-6">
        <div class="flex gap-4 border-b border-gray-200">
            <button @click="activeTab = 'won'"
                :class="activeTab === 'won' ? 'border-textorange text-textorange' : 'border-transparent text-gray-500 hover:text-gray-700'"
                class="pb-3 px-1 border-b-2 font-primaryMedium transition-colors">
                <i class="fa-solid fa-trophy mr-2"></i>ชนะประมูล
                <span
                    class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full ml-1"><?= count($wonAuctions) ?></span>
            </button>
            <button @click="activeTab = 'active'"
                :class="activeTab === 'active' ? 'border-textorange text-textorange' : 'border-transparent text-gray-500 hover:text-gray-700'"
                class="pb-3 px-1 border-b-2 font-primaryMedium transition-colors">
                <i class="fa-solid fa-clock mr-2"></i>กำลังประมูล
                <span
                    class="bg-orange-100 text-orange-700 text-xs px-2 py-0.5 rounded-full ml-1"><?= count($activeBids) ?></span>
            </button>
        </div>

        <!-- Won Auctions Tab -->
        <div x-show="activeTab === 'won'" x-transition>
            <?php if (empty($wonAuctions)): ?>
                <div class="text-center py-20">
                    <i class="fa-solid fa-trophy text-gray-300 text-6xl mb-4"></i>
                    <p class="text-xl text-gray-500 font-primaryMedium">ยังไม่มีผลงานที่ชนะประมูล</p>
                    <a href="home"
                        class="inline-block mt-4 bg-textorange text-white px-6 py-3 rounded-xl font-primaryMedium hover:bg-orange-600 transition">
                        เริ่มประมูลงานศิลปะ
                    </a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mt-5">
                    <?php foreach ($wonAuctions as $art): ?>
                        <?php
                        $paymentStatus = $art['payment_status'] ?? 'pending_buyer_payment';
                        $statusConfig = [
                            'pending_buyer_payment' => ['bg-yellow-500', 'รอชำระเงิน'],
                            'pending_admin_verify' => ['bg-blue-500', 'รอตรวจสอบ'],
                            'verified' => ['bg-purple-500', 'กำลังดำเนินการ'],
                            'completed' => ['bg-green-600', 'เสร็จสิ้น'],
                            'rejected' => ['bg-red-500', 'ถูกปฏิเสธ'],
                        ];
                        $statusClass = $statusConfig[$paymentStatus][0] ?? 'bg-gray-500';
                        $statusText = $statusConfig[$paymentStatus][1] ?? $paymentStatus;
                        ?>
                        <div
                            class="card overflow-visible shadow-[0_2px_15px_rgba(0,0,0,0.10)] pb-0 rounded-2xl mb-5 flex flex-col min-h-[420px]">
                            <div class="relative">
                                <div class="overflow-hidden rounded-t-2xl" style="height: 400px;">
                                    <div class="block" style="height: 100%;">
                                        <img src="<?= htmlspecialchars($art['picture']) ?>" alt="Art"
                                            style="width: 100%; height: 100%; object-fit: cover;"
                                            class="transition duration-500 transform hover:scale-105">
                                    </div>
                                </div>

                                <div class="absolute top-5 left-0 w-full px-3 flex items-center justify-between">
                                    <div class="<?= $statusClass ?> text-white px-3 py-1 rounded-md text-sm font-primaryMedium">
                                        <i class="fa-solid fa-trophy mr-1"></i><?= $statusText ?>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body flex-1 flex flex-col">
                                <div class="px-5 py-5 rounded-b-2xl break-words whitespace-normal flex-1">
                                    <div class="space-y-2">
                                        <h3 class="text-lg text-textprimary font-primaryBold line-clamp-2 leading-tight">
                                            <?= htmlspecialchars($art['title']) ?>
                                        </h3>
                                        <div class="space-y-1 text-sm">
                                            <p class="text-textsecondary font-primaryRegular flex items-center gap-2">
                                                <i class="fa-solid fa-palette text-textorange"></i>
                                                <span
                                                    class="text-textprimary font-primaryMedium"><?= htmlspecialchars($art['artist']) ?></span>
                                            </p>
                                            <p class="text-textsecondary font-primaryRegular flex items-center gap-2">
                                                <i class="fa-solid fa-tag text-textorange"></i>
                                                <span
                                                    class="text-textorange font-primaryBold text-base">฿<?= number_format($art['startingprice']) ?></span>
                                            </p>
                                        </div>
                                        <?php if ($paymentStatus === 'pending_buyer_payment'): ?>
                                            <a href="/Artbid/public/pay?artwork_id=<?= htmlspecialchars($art['id']) ?>"
                                                class="block mt-3 bg-textorange text-white px-4 py-2 rounded-lg font-primaryMedium hover:bg-orange-600 transition text-sm text-center">
                                                <i class="fa-solid fa-credit-card mr-1"></i> ชำระเงิน
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Active Bids Tab -->
        <div x-show="activeTab === 'active'" x-transition style="display: none;">
            <?php if (empty($activeBids)): ?>
                <div class="text-center py-20">
                    <i class="fa-solid fa-gavel text-gray-300 text-6xl mb-4"></i>
                    <p class="text-xl text-gray-500 font-primaryMedium">ยังไม่มีการประมูลที่กำลังดำเนินอยู่</p>
                    <a href="home"
                        class="inline-block mt-4 bg-textorange text-white px-6 py-3 rounded-xl font-primaryMedium hover:bg-orange-600 transition">
                        เริ่มประมูลงานศิลปะ
                    </a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mt-5">
                    <?php foreach ($activeBids as $art): ?>
                        <div
                            class="card overflow-visible shadow-[0_2px_15px_rgba(0,0,0,0.10)] pb-0 rounded-2xl mb-5 flex flex-col min-h-[420px]">
                            <div class="relative">
                                <div class="overflow-hidden rounded-t-2xl" style="height: 400px;">
                                    <a href="artwork-detail?id=<?= htmlspecialchars($art['id']) ?>" class="block"
                                        style="height: 100%;">
                                        <img src="<?= htmlspecialchars($art['picture']) ?>" alt="Art"
                                            style="width: 100%; height: 100%; object-fit: cover;"
                                            class="transition duration-500 transform hover:scale-105">
                                    </a>
                                </div>

                                <div class="absolute top-5 left-0 w-full px-3 flex items-center justify-between">
                                    <div class="bg-green-500 text-white px-3 py-1 rounded-md text-sm font-primaryMedium">
                                        <i class="fa-solid fa-arrow-up mr-1"></i>คุณเป็นผู้เสนอสูงสุด
                                    </div>
                                </div>
                            </div>

                            <a href="artwork-detail?id=<?= htmlspecialchars($art['id']) ?>"
                                class="card-body flex-1 flex flex-col">
                                <div class="px-5 py-5 rounded-b-2xl break-words whitespace-normal flex-1">
                                    <div class="space-y-2">
                                        <h3 class="text-lg text-textprimary font-primaryBold line-clamp-2 leading-tight">
                                            <?= htmlspecialchars($art['title']) ?>
                                        </h3>
                                        <div class="space-y-1 text-sm">
                                            <p class="text-textsecondary font-primaryRegular flex items-center gap-2">
                                                <i class="fa-solid fa-palette text-textorange"></i>
                                                <span
                                                    class="text-textprimary font-primaryMedium"><?= htmlspecialchars($art['artist']) ?></span>
                                            </p>
                                            <p class="text-textsecondary font-primaryRegular flex items-center gap-2">
                                                <i class="fa-solid fa-tag text-textorange"></i>
                                                <span
                                                    class="text-textorange font-primaryBold text-base">฿<?= number_format($art['startingprice']) ?></span>
                                            </p>
                                            <p class="text-textsecondary font-primaryRegular flex items-center gap-2">
                                                <i class="fa-regular fa-clock text-textorange"></i>
                                                <span class="text-textprimary font-primaryMedium text-xs">
                                                    สิ้นสุด: <?php
                                                    $endDate = new DateTime($art['endbidding']);
                                                    echo $endDate->format('d/m/Y H:i');
                                                    ?> น.
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>