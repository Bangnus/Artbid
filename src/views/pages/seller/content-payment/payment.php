<?php
$payments = $payments ?? [];

function getStatusText($status)
{
    $map = [
        'pending_buyer_payment' => 'รอผู้ซื้อชำระ',
        'pending_admin_verify' => 'รอตรวจสอบ',
        'verified' => 'รอโอนเงิน',
        'completed' => 'เสร็จสิ้น',
        'rejected' => 'ถูกปฏิเสธ'
    ];
    return $map[$status] ?? $status;
}

function getStatusClass($status)
{
    $map = [
        'pending_buyer_payment' => 'bg-gray-100 text-gray-700',
        'pending_admin_verify' => 'bg-yellow-100 text-yellow-700',
        'verified' => 'bg-blue-100 text-blue-700',
        'completed' => 'bg-green-100 text-green-700',
        'rejected' => 'bg-red-100 text-red-700'
    ];
    return $map[$status] ?? 'bg-gray-100 text-gray-700';
}
?>

<div class="p-6" x-data="{ previewImage: null }">
    <h1 class="text-2xl font-primaryBold text-textprimary mb-2">ประวัติการรับเงิน</h1>
    <p class="text-textsecondary font-primaryRegular mb-6">รายการผลงานที่ขายได้และสถานะการชำระเงิน</p>

    <?php if (empty($payments)): ?>
        <div class="bg-white rounded-2xl shadow p-12 text-center">
            <i class="fa-solid fa-inbox text-gray-300 text-5xl mb-4"></i>
            <p class="text-gray-400 font-primaryMedium">ยังไม่มีรายการรับเงิน</p>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-primaryBold text-gray-800 uppercase">ผลงาน</th>
                            <th class="px-6 py-4 text-left text-sm font-primaryBold text-gray-800 uppercase">ผู้ซื้อ</th>
                            <th class="px-6 py-4 text-center text-sm font-primaryBold text-gray-800 uppercase">ยอดขาย</th>
                            <th class="px-6 py-4 text-center text-sm font-primaryBold text-gray-800 uppercase">ค่าบริการ
                            </th>
                            <th class="px-6 py-4 text-center text-sm font-primaryBold text-gray-800 uppercase">ได้รับสุทธิ
                            </th>
                            <th class="px-6 py-4 text-center text-sm font-primaryBold text-gray-800 uppercase">วันที่</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($payments as $payment): ?>
                            <?php
                            $amount = floatval($payment['amount']);
                            $fee = floor($amount * 0.05);
                            $net = floor($amount * 0.95);
                            ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="<?= htmlspecialchars($payment['artwork_image']) ?>"
                                            class="w-10 h-10 rounded-lg object-cover cursor-pointer hover:opacity-80 transition"
                                            @click="previewImage = '<?= htmlspecialchars($payment['artwork_image']) ?>'" alt="">
                                        <span class="font-primaryMedium text-textprimary line-clamp-1 max-w-[150px]">
                                            <?= htmlspecialchars($payment['artwork_title']) ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-primaryMedium text-textprimary">
                                        <?= htmlspecialchars($payment['buyer_firstname'] . ' ' . $payment['buyer_lastname']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-primaryMedium text-textprimary">
                                        ฿<?= number_format($amount) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-primaryMedium text-red-500">
                                        -฿<?= number_format($fee) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-primaryBold text-bgsecondary">
                                        ฿<?= number_format($net) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm text-textsecondary font-primaryRegular">
                                        <?= date('d/m/Y', strtotime($payment['created_at'])) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <!-- Image Preview Modal -->
    <div x-show="previewImage" class="fixed inset-0 z-[60] flex items-center justify-center p-4" style="display: none;">
        <div class="fixed inset-0 bg-black/80" @click="previewImage = null"></div>
        <div class="relative max-w-4xl max-h-[90vh] overflow-auto">
            <button @click="previewImage = null" class="absolute -top-10 right-0 text-white hover:text-gray-300">
                <i class="fa-solid fa-times text-2xl"></i>
            </button>
            <img :src="previewImage" class="max-w-[350px] max-h-[60vh] w-auto h-auto object-contain rounded-lg"
                alt="Preview">
        </div>
    </div>
</div>