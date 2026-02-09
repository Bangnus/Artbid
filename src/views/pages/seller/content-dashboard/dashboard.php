<div class="w-full px-6 py-6">

    <!-- Header -->
    <div class="mb-8 pl-1">
        <h2 class="text-3xl font-primaryBold text-textprimary mb-2">แดชบอร์ดผู้ขาย</h2>
        <p class="text-textsecondary">ภาพรวมและสถิติยอดขายของคุณ</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-4 gap-6 mb-8">
        <!-- Total Artworks -->
        <div class="rounded-2xl p-6 shadow-lg text-white hover:scale-105 transition-transform duration-300 relative overflow-hidden"
            style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%);">
            <div style="position: absolute; top: 0; right: 0; opacity: 0.1;">
                <i style="transform: translate(10px, 10px); font-size: 6rem;" class="fa-solid fa-palette "></i>
            </div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div style="background-color: rgba(255, 255, 255, 0.2);" class="p-3 rounded-xl backdrop-blur-sm">
                        <i class="fa-solid fa-palette text-white text-xl"></i>
                    </div>
                    <span class="text-xs font-primaryBold text-slate-200 uppercase">ผลงานทั้งหมด</span>
                </div>
                <h3 class="text-3xl font-primaryBold"><?= number_format($totalArtworks) ?></h3>
                <p class="text-sm text-slate-300 mt-1">ชิ้น</p>
            </div>
        </div>

        <!-- Total Sales (Gross) -->
        <div class="rounded-2xl p-6 shadow-lg text-white hover:scale-105 transition-transform duration-300 relative overflow-hidden"
            style="background: linear-gradient(135deg, #065f46 0%, #047857 100%);">
            <div style="position: absolute; top: 0; right: 0; opacity: 0.1;">
                <i style="transform: translate(10px, 10px); font-size: 6rem;" class="fa-solid fa-money-bill-wave"></i>
            </div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div style="background-color: rgba(255, 255, 255, 0.2);" class="p-3 rounded-xl backdrop-blur-sm">
                        <i class="fa-solid fa-money-bill-wave text-white text-xl"></i>
                    </div>
                    <span class="text-xs font-primaryBold text-emerald-100 uppercase">ยอดขายรวม (Gross)</span>
                </div>
                <h3 class="text-3xl font-primaryBold">฿<?= number_format($totalSales) ?></h3>
                <p class="text-sm text-emerald-100 mt-1"><?= number_format($totalSold) ?> รายการที่เสร็จสิ้น</p>
            </div>
        </div>

        <!-- Total Earnings (Net 95%) -->
        <div class="rounded-2xl p-6 shadow-lg text-white hover:scale-105 transition-transform duration-300 relative overflow-hidden"
            style="background: linear-gradient(135deg, #00365D 0%, #002845 100%);">
            <div style="position: absolute; top: 0; right: 0; opacity: 0.1;">
                <i style="transform: translate(10px, 10px); font-size: 6rem;" class="fa-solid fa-wallet"></i>
            </div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div style="background-color: rgba(255, 255, 255, 0.2);" class="p-3 rounded-xl backdrop-blur-sm">
                        <i class="fa-solid fa-wallet text-white text-xl"></i>
                    </div>
                    <span class="text-xs font-primaryBold text-blue-100 uppercase">รายได้สุทธิ (95%)</span>
                </div>
                <h3 class="text-3xl font-primaryBold">฿<?= number_format($totalEarnings) ?></h3>
                <p class="text-sm text-blue-100 mt-1">บาท</p>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="rounded-2xl p-6 shadow-lg text-white hover:scale-105 transition-transform duration-300 relative overflow-hidden"
            style="background: linear-gradient(135deg, #b45309 0%, #78350f 100%);">
            <div style="position: absolute; top: 0; right: 0; opacity: 0.1;">
                <i style="transform: translate(10px, 10px); font-size: 6rem;" class="fa-solid fa-clock"></i>
            </div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div style="background-color: rgba(255, 255, 255, 0.2);" class="p-3 rounded-xl backdrop-blur-sm">
                        <i class="fa-solid fa-clock text-white text-xl"></i>
                    </div>
                    <span class="text-xs font-primaryBold text-amber-100 uppercase">รอดำเนินการ</span>
                </div>
                <h3 class="text-3xl font-primaryBold"><?= number_format($pendingOrders) ?></h3>
                <p class="text-sm text-amber-100 mt-1">รายการ (รอตรวจสอบ/โอน)</p>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Monthly Sales Line Chart -->
        <div class="bg-white rounded-2xl shadow p-6 lg:col-span-2">
            <h3 class="text-lg font-primaryBold text-textprimary mb-6">ยอดขายรายเดือน (6 เดือนย้อนหลัง)</h3>
            <div class="relative w-full" style="height: 450px;">
                <canvas id="salesLineChart"></canvas>
            </div>
        </div>

        <!-- Sales by Category Bar Chart -->
        <div class="bg-white rounded-2xl shadow p-6">
            <h3 class="text-lg font-primaryBold text-textprimary mb-6">ยอดขายแยกตามหมวดหมู่</h3>
            <div class="relative w-full" style="height: 400px;">
                <canvas id="categoryBarChart"></canvas>
            </div>
        </div>

        <!-- Artwork Status Pie Chart -->
        <div class="bg-white rounded-2xl shadow p-6">
            <h3 class="text-lg font-primaryBold text-textprimary mb-6">สถานะผลงานศิลปะ</h3>
            <div class="relative w-full flex justify-center" style="height: 400px;">
                <canvas id="statusPieChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-primaryBold text-textprimary">รายการขายล่าสุด</h3>
            <a href="/Artbid/public/seller/art-listings"
                class="text-sm font-primaryBold text-bgsecondary hover:underline">ดูประวัติการขาย</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-primaryBold text-gray-500 uppercase">วันที่</th>
                        <th class="px-6 py-4 text-left text-xs font-primaryBold text-gray-500 uppercase">ผลงาน</th>
                        <th class="px-6 py-4 text-left text-xs font-primaryBold text-gray-500 uppercase">ผู้ซื้อ</th>
                        <th class="px-6 py-4 text-center text-xs font-primaryBold text-gray-500 uppercase">ยอดเงิน</th>
                        <th class="px-6 py-4 text-center text-xs font-primaryBold text-gray-500 uppercase">สถานะ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($recentOrders)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                                <p class="font-primaryMedium">ไม่มีรายการล่าสุด</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentOrders as $t): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-primaryMedium">
                                    <?php
                                    $date = new DateTime($t['created_at']);
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
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="<?= htmlspecialchars($t['artwork_image']) ?>"
                                            class="w-10 h-10 rounded object-cover" alt="">
                                        <span class="text-sm text-textprimary font-primarySemibold line-clamp-1 max-w-[150px]">
                                            <?= htmlspecialchars($t['artwork_title']) ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 font-primaryMedium">
                                    <?= htmlspecialchars($t['buyer_firstname'] . ' ' . $t['buyer_lastname']) ?>
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-gray-900 font-primaryBold">
                                    ฿<?= number_format($t['amount']) ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <?php if ($t['status'] == 'completed'): ?>
                                        <span
                                            class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-primaryBold">เสร็จสิ้น</span>
                                    <?php elseif ($t['status'] == 'verified'): ?>
                                        <span
                                            class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-primaryBold">รอโอนเงิน</span>
                                    <?php elseif ($t['status'] == 'pending_buyer_payment'): ?>
                                        <span
                                            class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-primaryBold">รอชำระเงิน</span>
                                    <?php elseif ($t['status'] == 'pending_admin_verify'): ?>
                                        <span
                                            class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-primaryBold">รอตรวจสอบ</span>
                                    <?php elseif ($t['status'] == 'expired'): ?>
                                        <span
                                            class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-primaryBold">หมดอายุ</span>
                                    <?php else: ?>
                                        <span
                                            class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-primaryBold"><?= $t['status'] ?></span>
                                    <?php endif; ?>
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
        // --- Shared Options ---
        Chart.defaults.font.family = 'Prompt';
        Chart.defaults.color = '#64748b';
        Chart.defaults.scale.grid.color = '#e2e8f0';

        // 1. Line Chart: Monthly Sales
        const lineCtx = document.getElementById('salesLineChart').getContext('2d');
        const lineGradient = lineCtx.createLinearGradient(0, 0, 0, 400);
        lineGradient.addColorStop(0, 'rgba(0, 54, 93, 0.5)'); // Navy
        lineGradient.addColorStop(1, 'rgba(0, 54, 93, 0.05)');

        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode($months) ?>,
                datasets: [{
                    label: 'ยอดขาย (บาท)',
                    data: <?= json_encode($lineChartData) ?>,
                    borderColor: '#00365D',
                    backgroundColor: lineGradient,
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

        // 2. Bar Chart: Sales by Category
        const barCtx = document.getElementById('categoryBarChart').getContext('2d');
        // Colors palette (Theme Aligned: Navy, Emerald, Gold, Red, Slate)
        const palette = [
            '#00365D', // Navy (Brand)
            '#059669', // Emerald
            '#C5A059', // Gold (Secondary)
            '#dc2626', // Red
            '#475569'  // Slate
        ];

        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($barChartLabels) ?>,
                datasets: [{
                    label: 'จำนวนที่ขายได้',
                    data: <?= json_encode($barChartData) ?>,
                    backgroundColor: palette,
                    borderRadius: 6,
                    barPercentage: 0.6
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
                        ticks: { stepSize: 1 }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // 3. Pie Chart: Artwork Status
        const pieCtx = document.getElementById('statusPieChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($pieChartLabels) ?>,
                datasets: [{
                    data: <?= json_encode($pieChartData) ?>,
                    backgroundColor: [
                        '#00365D', // Live (Navy)
                        '#C5A059', // Sold (Gold)
                        '#94a3b8'  // Ended/Other (Gray)
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    }
                },
                cutout: '65%'
            }
        });
    });
</script>