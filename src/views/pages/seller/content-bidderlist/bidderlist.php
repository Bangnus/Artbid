<?php
require_once __DIR__ . '/../../../../controllers/controllers-backend/content-seller/SellerController.php';
$winners = SellerController::getWinningBidders();
?>

<body>
    <div class="overflow-x-auto">
        <table class="table-auto w-full text-sm text-left text-textprimary border-collapse">
            <thead class="bg-gray-200 tracking-wider">
                <tr>
                    <th class="px-4 py-3 w-12">#</th>
                    <th class="px-4 py-3 w-64">ผลงาน</th>
                    <th class="px-4 py-3 w-48">ผู้ชนะการประมูล</th>
                    <th class="px-4 py-3 w-40">อีเมล</th>
                    <th class="px-4 py-3 w-32 text-center">ราคาที่ชนะ</th>
                    <th class="px-4 py-3 w-32 text-center">วันที่สิ้นสุด</th>
                    <th class="px-4 py-3 w-32 text-center">สถานะชำระเงิน</th>
                </tr>
            </thead>
            <tbody class="[&>tr:nth-child(odd)]:bg-white [&>tr:nth-child(even)]:bg-gray-100">
                <?php if (empty($winners)): ?>
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                            ยังไม่มีผู้ประมูลชนะ
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($winners as $i => $winner): ?>
                        <tr class="hover:bg-gray-100 transition">
                            <td class="px-4 py-3"><?= $i + 1 ?></td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <?php if (!empty($winner['picture'])): ?>
                                        <img src="<?= htmlspecialchars($winner['picture']) ?>" width="40" height="40" alt="artwork"
                                            class="object-cover rounded-lg" style="aspect-ratio:1/1;">
                                    <?php endif; ?>
                                    <div>
                                        <p class="font-semibold"><?= htmlspecialchars($winner['artworkname']) ?></p>
                                        <p class="text-xs text-gray-500"><?= htmlspecialchars($winner['artist']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <?php if (!empty($winner['buyer_picture'])): ?>
                                        <img src="<?= htmlspecialchars($winner['buyer_picture']) ?>" width="32" height="32"
                                            alt="buyer" class="object-cover rounded-full" style="aspect-ratio:1/1;">
                                    <?php else: ?>
                                        <div
                                            class="w-8 h-8 rounded-full bg-textorange text-white flex items-center justify-center text-xs font-semibold">
                                            <?= mb_substr($winner['buyer_firstname'], 0, 1) ?>            <?= mb_substr($winner['buyer_lastname'], 0, 1) ?>
                                        </div>
                                    <?php endif; ?>
                                    <span><?= htmlspecialchars($winner['buyer_firstname'] . ' ' . $winner['buyer_lastname']) ?></span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600"><?= htmlspecialchars($winner['buyer_email']) ?></td>
                            <td class="px-4 py-3 text-center font-semibold text-green-600">
                                ฿<?= number_format($winner['amount'], 2) ?>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <?= date('d/m/Y', strtotime($winner['endbidding'])) ?>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <?php
                                switch ($winner['payment_status']) {
                                    case 'pending_buyer_payment':
                                        $statusStyle = 'background-color: #f59e0b; color: white;';
                                        $statusLabel = 'รอชำระเงิน';
                                        break;
                                    case 'pending_seller_confirmation':
                                        $statusStyle = 'background-color: #3b82f6; color: white;';
                                        $statusLabel = 'รอยืนยัน';
                                        break;
                                    case 'paid':
                                        $statusStyle = 'background-color: #8b5cf6; color: white;';
                                        $statusLabel = 'ชำระแล้ว';
                                        break;
                                    case 'verified':
                                        $statusStyle = 'background-color: #22c55e; color: white;';
                                        $statusLabel = 'ยืนยันแล้ว';
                                        break;
                                    case 'completed':
                                        $statusStyle = 'background-color: #22c55e; color: white;';
                                        $statusLabel = 'เสร็จสิ้น';
                                        break;
                                    case 'expired':
                                        $statusStyle = 'background-color: #ef4444; color: white;';
                                        $statusLabel = 'หมดอายุ';
                                        break;
                                    default:
                                        $statusStyle = 'background-color: #9ca3af; color: white;';
                                        $statusLabel = $winner['payment_status'];
                                }
                                ?>
                                <span style="<?= $statusStyle ?>"
                                    class="inline-block text-xs px-2 py-1 rounded-full font-semibold">
                                    <?= htmlspecialchars($statusLabel) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>