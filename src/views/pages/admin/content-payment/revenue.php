<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-3xl font-primaryBold text-textprimary mb-2">รายงานรายได้</h2>
        <p class="text-textsecondary">สรุปรายได้จากค่าธรรมเนียม 5% ของธุรกรรมที่เสร็จสิ้นแล้ว</p>
    </div>

    <!-- Summary Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div
            class="bg-gradient-to-br from-[#00365D] to-[#005a9e] rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <i class="fa-solid fa-coins text-8xl transform translate-x-2 -translate-y-2"></i>
            </div>
            <div class="relative z-10">
                <p class="text-blue-100 font-primaryMedium mb-2">รายได้รวมทั้งหมด (5%)</p>
                <h3 class="text-4xl font-primaryBold">฿<?= number_format($totalRevenue) ?></h3>
                <p class="text-xs text-blue-200 mt-4 bg-white/10 inline-block px-3 py-1 rounded-lg">
                    จาก <?= count($revenues) ?> รายการที่โอนแล้ว
                </p>
            </div>
        </div>
    </div>

    <!-- Revenue Table -->
    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-primaryBold text-textprimary">รายการธุรกรรมล่าสุด</h3>
            <!--            <button class="text-bgsecondary text-sm font-primaryBold hover:underline">-->
            <!--                ดาวน์โหลด CSV-->
            <!--            </button>-->
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-primaryBold text-gray-500 uppercase">
                            วันที่โอนให้ผู้ขาย</th>
                        <th class="px-4 py-3 text-left text-xs font-primaryBold text-gray-500 uppercase">ผลงาน</th>
                        <th class="px-4 py-3 text-left text-xs font-primaryBold text-gray-500 uppercase">ผู้ขาย</th>
                        <th class="px-4 py-3 text-center text-xs font-primaryBold text-gray-500 uppercase">ยอดขายรวม</th>
                        <th class="px-4 py-3 text-center text-xs font-primaryBold text-green-600 uppercase">รายได้ (5%)
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-primaryBold text-gray-500 uppercase">ยอดโอนสุทธิ
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($revenues)): ?>
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-gray-400">
                                <i class="fa-solid fa-file-invoice-dollar text-4xl mb-3"></i>
                                <p class="font-primaryMedium">ยังไม่มีรายได้จากรายการที่เสร็จสิ้น</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($revenues as $r):
                            $amount = $r['amount'];
                            $fee = floor($amount * 0.05);
                            $net = $amount - $fee;
                            ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 font-primaryMedium">
                                    <?php
                                    $date = new DateTime($r['seller_paid_at']);
                                    $thai_months = [
                                        1 => 'ม.ค.',
                                        2 => 'ก.พ.',
                                        3 => 'มี.ค.',
                                        4 => 'เม.ย.',
                                        5 => 'พ.ค.',
                                        6 => 'มิ.ย.',
                                        7 => 'ก.ค.',
                                        8 => 'ส.ค.',
                                        9 => 'ก.ย.',
                                        10 => 'ต.ค.',
                                        11 => 'พ.ย.',
                                        12 => 'ธ.ค.'
                                    ];
                                    $month = $thai_months[(int) $date->format('n')];
                                    $year = $date->format('Y') + 543;
                                    echo $date->format('j') . ' ' . $month . ' ' . $year . ', ' . $date->format('H:i');
                                    ?>
                                </td>
                                <td class="px-4 py-3 text-sm text-textprimary font-primarySemibold">
                                    <?= htmlspecialchars($r['artwork_title']) ?>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600 font-primaryMedium">
                                    <?= htmlspecialchars($r['seller_firstname'] . ' ' . $r['seller_lastname']) ?>
                                </td>
                                <td class="px-4 py-3 text-center text-sm text-gray-900 font-primaryBold">
                                    ฿<?= number_format($amount) ?>
                                </td>
                                <td class="px-4 py-3 text-center text-sm text-green-600 font-primaryBold bg-green-50/50">
                                    +฿<?= number_format($fee) ?>
                                </td>
                                <td class="px-4 py-3 text-center text-sm text-gray-600 font-primaryMedium">
                                    ฿<?= number_format($net) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>