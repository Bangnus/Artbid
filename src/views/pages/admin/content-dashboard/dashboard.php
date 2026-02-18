<div class="w-full px-6 py-6">

    <!-- Header -->
    <div class="mb-8 pl-1">
        <h2 class="text-3xl font-primaryBold text-textprimary mb-2">แดชบอร์ด</h2>
        <p class="text-textsecondary">ภาพรวมของระบบ Artbid</p>
    </div>

    <!-- Stats Grid (Aligned with Seller - col-4 fixed for now, but user says Seller shrinks correctly, so maybe grid context matters?) -->
    <!-- Seller uses: grid grid-cols-4 gap-6 mb-8 -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div
            class="bg-gradient-to-br from-[#334155] to-[#1e293b] rounded-2xl p-6 shadow-lg text-white hover:scale-105 transition-transform duration-300 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <i class="fa-solid fa-users text-8xl transform translate-x-2"></i>
            </div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-white/10 rounded-xl backdrop-blur-sm">
                        <i class="fa-solid fa-users text-white text-xl"></i>
                    </div>
                    <span class="text-xs font-primaryBold text-slate-200 uppercase">ผู้ใช้งาน</span>
                </div>
                <h3 class="text-3xl font-primaryBold"><?= number_format($totalUsers) ?></h3>
                <p class="text-sm text-slate-300 mt-1">คน (ไม่รวมแอดมิน)</p>
            </div>
        </div>

        <!-- Total Artworks -->
        <div
            class="bg-gradient-to-br from-[#57534e] to-[#44403c] rounded-2xl p-6 shadow-lg text-white hover:scale-105 transition-transform duration-300 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <i class="fa-solid fa-palette text-8xl transform translate-x-2"></i>
            </div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-white/10 rounded-xl backdrop-blur-sm">
                        <i class="fa-solid fa-palette text-white text-xl"></i>
                    </div>
                    <span class="text-xs font-primaryBold text-stone-200 uppercase">ผลงานศิลปะ</span>
                </div>
                <h3 class="text-3xl font-primaryBold"><?= number_format($totalArtworks) ?></h3>
                <p class="text-sm text-stone-300 mt-1">ชิ้น</p>
            </div>
        </div>

        <!-- Total Sales -->
        <div
            class="bg-gradient-to-br from-[#047857] to-[#065f46] rounded-2xl p-6 shadow-lg text-white hover:scale-105 transition-transform duration-300 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <i class="fa-solid fa-money-bill-wave text-8xl transform translate-x-2"></i>
            </div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-white/10 rounded-xl backdrop-blur-sm">
                        <i class="fa-solid fa-money-bill-wave text-white text-xl"></i>
                    </div>
                    <span class="text-xs font-primaryBold text-emerald-100 uppercase">ยอดขายรวม</span>
                </div>
                <h3 class="text-3xl font-primaryBold">฿<?= number_format($totalSales) ?></h3>
                <p class="text-sm text-emerald-100 mt-1">บาท (รายการที่เสร็จสิ้น)</p>
            </div>
        </div>

        <!-- Total Revenue -->
        <div
            class="bg-gradient-to-br from-[#00365D] to-[#002845] rounded-2xl p-6 shadow-lg text-white hover:scale-105 transition-transform duration-300 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <i class="fa-solid fa-coins text-8xl transform translate-x-2"></i>
            </div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-white/10 rounded-xl backdrop-blur-sm">
                        <i class="fa-solid fa-chart-line text-white text-xl"></i>
                    </div>
                    <span class="text-xs font-primaryBold text-blue-100 uppercase">รายได้ (5%)</span>
                </div>
                <h3 class="text-3xl font-primaryBold">฿<?= number_format($totalRevenue) ?></h3>
                <p class="text-sm text-blue-100 mt-1">บาท</p>
            </div>
        </div>
    </div>

    <!-- Chart Section (Wrapped in Grid like Seller) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white rounded-2xl shadow p-6 lg:col-span-2">
            <h3 class="text-lg font-primaryBold text-textprimary mb-6">ยอดขายรายเดือน (6 เดือนย้อนหลัง)</h3>
            <div class="relative w-full" style="height: 450px;">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-primaryBold text-textprimary">รายการซื้อขายล่าสุด</h3>
            <a href="/admin/payment/transactions"
                class="text-sm font-primaryBold text-bgsecondary hover:underline">ดูทั้งหมด</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-primaryBold text-gray-500 uppercase">วันที่</th>
                        <th class="px-6 py-4 text-left text-xs font-primaryBold text-gray-500 uppercase">ผลงาน</th>
                        <th class="px-6 py-4 text-left text-xs font-primaryBold text-gray-500 uppercase">ผู้ซื้อ</th>
                        <th class="px-6 py-4 text-left text-xs font-primaryBold text-gray-500 uppercase">ผู้ขาย</th>
                        <th class="px-6 py-4 text-right text-xs font-primaryBold text-gray-500 uppercase">ยอดเงิน</th>
                        <th class="px-6 py-4 text-center text-xs font-primaryBold text-gray-500 uppercase">สถานะ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($recentTransactions)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                                <p class="font-primaryMedium">ไม่มีรายการล่าสุด</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentTransactions as $t): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-primaryMedium">
                                    <?php
                                    $date = new DateTime($t['seller_paid_at']);
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
                                    echo $date->format('j') . ' ' . $month . ' ' . $year;
                                    ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-textprimary font-primarySemibold">
                                    <?= htmlspecialchars($t['artwork_title']) ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 font-primaryMedium">
                                    <?= htmlspecialchars($t['buyer_firstname'] . ' ' . $t['buyer_lastname']) ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 font-primaryMedium">
                                    <?= htmlspecialchars($t['seller_firstname'] . ' ' . $t['seller_lastname']) ?>
                                </td>
                                <td class="px-6 py-4 text-right text-sm text-gray-900 font-primaryBold">
                                    ฿<?= number_format($t['amount']) ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-primaryBold">
                                        เสร็จสิ้น
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- Shared Options (Matched with Seller Dashboard) ---
        Chart.defaults.font.family = 'Prompt';
        Chart.defaults.color = '#64748b';
        Chart.defaults.scale.grid.color = '#e2e8f0';

        const ctx = document.getElementById('salesChart').getContext('2d');

        // Gradient for Chart - Deep Navy Professional
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(0, 54, 93, 0.5)');
        gradient.addColorStop(1, 'rgba(0, 54, 93, 0.05)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($months) ?>,
                datasets: [{
                    label: 'ยอดขายรวม (บาท)',
                    data: <?= json_encode($chartData) ?>,
                    borderColor: '#00365D',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#00365D',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 8,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => '฿' + value.toLocaleString()
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>