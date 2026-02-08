<?php
require_once __DIR__ . '/../../../../controllers/controllers-backend/content-seller/SellerController.php';
// Get current bidders (Live auctions) instead of winners
$bidders = SellerController::getCurrentBidders();
?>

<body>
    <div class="overflow-x-auto">
        <table class="table-auto w-full text-sm text-left text-textprimary border-collapse">
            <thead class="bg-gray-200 tracking-wider">
                <tr>
                    <th class="px-4 py-3 w-12">#</th>
                    <th class="px-4 py-3 w-64">ผลงาน</th>
                    <th class="px-4 py-3 w-48">ผู้เสนอราคาล่าสุด</th>
                    <th class="px-4 py-3 w-40">อีเมล</th>
                    <th class="px-4 py-3 w-32 text-center">ราคาปัจจุบัน</th>
                    <th class="px-4 py-3 w-32 text-center">สิ้นสุดเมื่อ</th>
                    <th class="px-4 py-3 w-32 text-center">สถานะ</th>
                </tr>
            </thead>
            <tbody class="[&>tr:nth-child(odd)]:bg-white [&>tr:nth-child(even)]:bg-gray-100">
                <?php if (empty($bidders)): ?>
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                            ยังไม่มีการประมูลในขณะนี้
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($bidders as $i => $bidder): ?>
                        <tr class="hover:bg-gray-100 transition">
                            <td class="px-4 py-3"><?= $i + 1 ?></td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <?php if (!empty($bidder['picture'])): ?>
                                        <img src="<?= htmlspecialchars($bidder['picture']) ?>" width="40" height="40" alt="artwork"
                                            class="object-cover rounded-lg" style="aspect-ratio:1/1;">
                                    <?php endif; ?>
                                    <div>
                                        <p class="font-semibold"><?= htmlspecialchars($bidder['artworkname']) ?></p>
                                        <p class="text-xs text-gray-500"><?= htmlspecialchars($bidder['artist']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <?php if (!empty($bidder['buyer_picture'])): ?>
                                        <img src="<?= htmlspecialchars($bidder['buyer_picture']) ?>" width="32" height="32"
                                            alt="buyer" class="object-cover rounded-full" style="aspect-ratio:1/1;">
                                    <?php else: ?>
                                        <div
                                            class="w-8 h-8 rounded-full bg-textorange text-white flex items-center justify-center text-xs font-semibold">
                                            <?= strtoupper(substr($bidder['buyer_firstname'], 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                    <span class="truncate w-32"
                                        title="<?= htmlspecialchars($bidder['buyer_firstname'] . ' ' . $bidder['buyer_lastname']) ?>">
                                        <?= htmlspecialchars($bidder['buyer_firstname'] . ' ' . $bidder['buyer_lastname']) ?>
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <a href="mailto:<?= htmlspecialchars($bidder['buyer_email']) ?>"
                                    class="text-blue-600 hover:underline">
                                    <?= htmlspecialchars($bidder['buyer_email']) ?>
                                </a>
                            </td>
                            <td class="px-4 py-3 text-center font-medium text-green-600">
                                $<?= number_format($bidder['amount'], 2) ?>
                            </td>
                            <td class="px-4 py-3 text-center text-gray-500">
                                <?php
                                $date = new DateTime($bidder['endbidding']);
                                $thaiMonths = [
                                    '01' => 'ม.ค.',
                                    '02' => 'ก.พ.',
                                    '03' => 'มี.ค.',
                                    '04' => 'เม.ย.',
                                    '05' => 'พ.ค.',
                                    '06' => 'มิ.ย.',
                                    '07' => 'ก.ค.',
                                    '08' => 'ส.ค.',
                                    '09' => 'ก.ย.',
                                    '10' => 'ต.ค.',
                                    '11' => 'พ.ย.',
                                    '12' => 'ธ.ค.'
                                ];
                                $day = $date->format('d');
                                $monthKey = $date->format('m');
                                $year = $date->format('Y') + 543;
                                echo $day . ' ' . $thaiMonths[$monthKey] . ' ' . $year . ', ' . $date->format('H:i');
                                ?>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span style="background-color: #22c55e; color: white;"
                                    class="text-xs font-semibold px-2 py-1 rounded-full">
                                    กำลังประมูล
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>