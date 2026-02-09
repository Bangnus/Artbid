<?php
require_once __DIR__ . '/../../../../controllers/controllers-backend/content-seller/SellerController.php';
$artworks = SellerController::getSalesHistory();
?>

<div x-data="{ expanded: null }" class="overflow-x-auto">
    <table id="salesHistoryTable" class="table-auto w-full text-sm text-left text-textprimary border-collapse">
        <thead class="bg-gray-200 tracking-wider">
            <tr>
                <th class="px-4 py-3 w-12 text-center">#</th>
                <th class="px-4 py-3 w-64">ชื่อผลงาน</th>
                <th class="px-4 py-3 w-40">ศิลปิน</th>
                <th class="px-4 py-3 w-32 text-center">ราคาจบประมูล</th>
                <th class="px-4 py-3 w-32 text-center">วันที่สร้าง</th>
                <th class="px-4 py-3 w-28 text-center">สถานะ</th>
                <th class="px-4 py-3 w-24 text-center">ดูรายละเอียด</th>
            </tr>
        </thead>
        <tbody class="[&>tr:nth-child(4n-3)]:bg-white [&>tr:nth-child(4n-1)]:bg-gray-50">
            <?php if (empty($artworks)): ?>
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                        ยังไม่มีประวัติการขายที่เสร็จสิ้น
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($artworks as $i => $art): ?>
                    <tr class="hover:bg-gray-100 transition border-b border-gray-100">
                        <td class="px-4 py-3 text-center"><?= $i + 1 ?></td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <?php if (!empty($art['picture'])): ?>
                                    <img src="<?= htmlspecialchars($art['picture']) ?>" width="32" height="32" alt="artwork"
                                        class="object-cover rounded-lg w-8 h-8 shrink-0">
                                <?php endif; ?>
                                <span class="line-clamp-1" title="<?= htmlspecialchars($art['artworkname'] ?? '') ?>">
                                    <?= htmlspecialchars($art['artworkname'] ?? '-') ?>
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-3"><?= htmlspecialchars($art['artist'] ?? '-') ?></td>
                        <td class="px-4 py-3 text-center text-green-600 font-bold">
                            <?= number_format($art['startingprice'] ?? 0, 2) ?> ฿
                        </td>
                        <td class="px-4 py-3 text-center"><?= date('d/m/Y', strtotime($art['createat'])) ?></td>
                        <td class="px-4 py-3 text-center">
                            <span
                                class="inline-block bg-green-100 text-green-700 border border-green-200 text-xs px-2 py-1 rounded-full font-semibold">
                                ขายแล้ว
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <button type="button" @click="expanded === <?= $i ?> ? expanded = null : expanded = <?= $i ?>"
                                class="cursor-pointer text-purple-600 hover:text-purple-800 transition-colors p-2 rounded-full hover:bg-gray-100">
                                <i class="fa-solid text-lg"
                                    :class="expanded === <?= $i ?> ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                            </button>
                        </td>
                    </tr>
                    <!-- Modal / Details Row -->
                    <tr x-show="expanded === <?= $i ?>" x-transition.origin.top.duration.300ms
                        class="bg-gray-50/50 border-b border-gray-100">
                        <td colspan="7" class="px-4 py-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-14">
                                <div class="space-y-2">
                                    <h4 class="font-bold text-gray-700 border-b pb-2 mb-2">ข้อมูลผลงาน</h4>
                                    <p><span class="font-semibold text-gray-600">รายละเอียด:</span>
                                        <span
                                            class="text-gray-800"><?= nl2br(htmlspecialchars($art['description'] ?? '-')) ?></span>
                                    </p>
                                    <p><span class="font-semibold text-gray-600">ขนาด:</span>
                                        <span class="text-gray-800"><?= htmlspecialchars($art['dimensions'] ?? '-') ?></span>
                                    </p>
                                    <p><span class="font-semibold text-gray-600">ปีที่สร้าง:</span>
                                        <span class="text-gray-800"><?= htmlspecialchars($art['yearcreated'] ?? '-') ?></span>
                                    </p>
                                </div>
                                <div class="space-y-2">
                                    <h4 class="font-bold text-gray-700 border-b pb-2 mb-2">ข้อมูลการประมูล</h4>
                                    <p><span class="font-semibold text-gray-600">ผู้ชนะประมูล:</span>
                                        <span class="text-gray-800">
                                            <?= htmlspecialchars(($art['bidby_firstname'] ?? '') . ' ' . ($art['bidby_lastname'] ?? '')) ?>
                                            (ID: <?= htmlspecialchars($art['bidby'] ?? '-') ?>)
                                        </span>
                                    </p>
                                    <p><span class="font-semibold text-gray-600">วันจบประมูล:</span>
                                        <span
                                            class="text-gray-800"><?= date('d/m/Y H:i', strtotime($art['endbidding'])) ?></span>
                                    </p>
                                    <p><span class="font-semibold text-gray-600">สถานะการชำระเงิน:</span>
                                        <?php
                                        $statusMap = [
                                            'pending_buyer_payment' => ['text' => 'รอผู้ซื้อโอนเงิน', 'color' => 'text-orange-600'],
                                            'pending_admin_verify' => ['text' => 'รอตรวจสอบ', 'color' => 'text-yellow-600'],
                                            'verified' => ['text' => 'ชำระเงินแล้ว (รอโอนให้ผู้ขาย)', 'color' => 'text-blue-600'],
                                            'completed' => ['text' => 'เสร็จสิ้น', 'color' => 'text-green-600'],
                                            'rejected' => ['text' => 'ถูกปฏิเสธ', 'color' => 'text-red-600']
                                        ];
                                        $statusInfo = $statusMap[$art['payment_status'] ?? ''] ?? ['text' => 'รอชำระเงิน', 'color' => 'text-gray-500'];
                                        ?>
                                        <span class="font-bold <?= $statusInfo['color'] ?>">
                                            <?= $statusInfo['text'] ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>