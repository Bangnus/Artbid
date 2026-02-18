<?php
// Data is passed from CollectionController
$collection = $collection ?? [];

// Calculate counts for each category
$categoryCounts = ['all' => count($collection)];
foreach ($collection as $item) {
    $catId = $item['categories_id'] ?? 'other';
    if (!isset($categoryCounts[$catId])) {
        $categoryCounts[$catId] = 0;
    }
    $categoryCounts[$catId]++;
}
?>

<div class="min-h-screen bg-gray-50 py-12 p-6" x-data="{ 
        previewImage: null, 
        selectedCategory: 'all',
        counts: <?= htmlspecialchars(json_encode($categoryCounts)) ?>
    }">
    <div class=" mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-primaryBold text-textprimary mb-2">
                <i class="fa-solid fa-images mr-2 text-textorange"></i>คอลเลกชันของฉัน
            </h1>
            <p class="text-textsecondary font-primaryMedium">ผลงานศิลปะที่คุณประมูลได้และเป็นเจ้าของ</p>
        </div>

        <?php if (empty($collection)): ?>
            <!-- Empty State -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-16 text-center">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-images text-gray-300 text-3xl"></i>
                </div>
                <h3 class="text-xl font-primaryBold text-gray-900 mb-2">ยังไม่มีผลงานในคอลเลกชัน</h3>
                <p class="text-gray-500 font-primaryMedium mb-8">
                    คุณยังไม่มีผลงานที่ประมูลได้ ลองไปร่วมประมูลผลงานที่คุณชื่นชอบ
                </p>
                <a href="/home"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-textorange text-white rounded-xl font-primaryBold hover:bg-orange-600 transition-all shadow-sm hover:shadow-md">
                    <i class="fa-solid fa-gavel"></i>
                    ไปดูผลงานประมูล
                </a>
            </div>
        <?php else: ?>
            <!-- Filter Buttons -->
            <div class="flex flex-wrap gap-3 mb-8 mt-8">
                <button @click="selectedCategory = 'all'"
                    class="px-5 py-2 rounded-full text-sm font-primaryBold transition-all border shadow-sm" :class="selectedCategory === 'all' 
                        ? 'bg-textorange text-white border-textorange' 
                        : 'bg-white text-textsecondary border-gray-200 hover:bg-gray-50'">
                    ทั้งหมด (<span x-text="counts['all']"></span>)
                </button>
                <button @click="selectedCategory = '1'"
                    class="px-5 py-2 rounded-full text-sm font-primaryBold transition-all border shadow-sm" :class="selectedCategory === '1' 
                        ? 'bg-textorange text-white border-textorange' 
                        : 'bg-white text-textsecondary border-gray-200 hover:bg-gray-50'">
                    Prints & Multiples Art (<span x-text="counts['1'] || 0"></span>)
                </button>
                <button @click="selectedCategory = '2'"
                    class="px-5 py-2 rounded-full text-sm font-primaryBold transition-all border shadow-sm" :class="selectedCategory === '2' 
                        ? 'bg-textorange text-white border-textorange' 
                        : 'bg-white text-textsecondary border-gray-200 hover:bg-gray-50'">
                    Photography Art (<span x-text="counts['2'] || 0"></span>)
                </button>
                <button @click="selectedCategory = '3'"
                    class="px-5 py-2 rounded-full text-sm font-primaryBold transition-all border shadow-sm" :class="selectedCategory === '3' 
                        ? 'bg-textorange text-white border-textorange' 
                        : 'bg-white text-textsecondary border-gray-200 hover:bg-gray-50'">
                    Classical Art (<span x-text="counts['3'] || 0"></span>)
                </button>
                <button @click="selectedCategory = '4'"
                    class="px-5 py-2 rounded-full text-sm font-primaryBold transition-all border shadow-sm" :class="selectedCategory === '4' 
                        ? 'bg-textorange text-white border-textorange' 
                        : 'bg-white text-textsecondary border-gray-200 hover:bg-gray-50'">
                    Modern & Contemporary Art (<span x-text="counts['4'] || 0"></span>)
                </button>
            </div>

            <!-- Collection Stats -->
            <div class="flex justify-between mb-6 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-trophy text-textorange text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-textsecondary font-primaryMedium">ผลงานที่สะสม</p>
                            <p class="text-2xl font-primaryBold text-textprimary">
                                <span x-text="counts[selectedCategory] || 0"></span> ชิ้น
                            </p>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-textsecondary font-primaryMedium">มูลค่ารวม</p>
                    <p class="text-2xl font-primaryBold text-textorange">
                        ฿<?= number_format(array_sum(array_column($collection, 'amount'))) ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Collection Grid -->
        <div class="gap-4" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));">
            <?php foreach ($collection as $item): ?>
                <div x-show="selectedCategory === 'all' || selectedCategory == '<?= $item['categories_id'] ?>'"
                    class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all">
                    <!-- Artwork Image -->
                    <div class="relative cursor-pointer" style="height: 200px;"
                        @click="previewImage = '<?= htmlspecialchars($item['artwork_image']) ?>'">
                        <img src="<?= htmlspecialchars($item['artwork_image']) ?>"
                            class="w-full h-full object-cover hover:opacity-90 transition"
                            alt="<?= htmlspecialchars($item['artwork_title']) ?>">
                        <!-- Owned Badge -->
                        <div class="absolute top-3 left-3">
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-primaryBold bg-green-500 text-white shadow-lg">
                                <i class="fa-solid fa-check-circle"></i>
                                เป็นเจ้าของแล้ว
                            </span>
                        </div>
                        <!-- Hover Overlay -->
                        <div class="absolute inset-0 opacity-0 hover:opacity-100 transition bg-black/30"></div>
                    </div>

                    <!-- Info -->
                    <div class="p-4">
                        <h3 class="font-primaryBold text-textprimary text-base mb-1 truncate">
                            <?= htmlspecialchars($item['artwork_title']) ?>
                        </h3>
                        <p class="text-sm text-textsecondary font-primaryMedium mb-3">
                            โดย
                            <?= htmlspecialchars($item['artist'] ?? ($item['seller_firstname'] . ' ' . $item['seller_lastname'])) ?>
                        </p>

                        <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                            <div>
                                <p class="text-xs text-textsecondary">ราคาที่ประมูลได้</p>
                                <p class="text-base font-primaryBold text-textorange">
                                    ฿<?= number_format($item['amount']) ?>
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-textsecondary">วันที่ได้รับ</p>
                                <p class="text-sm font-primaryMedium text-textprimary">
                                    <?= date('d/m/Y', strtotime($item['purchase_date'])) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Image Preview Modal -->
<div x-show="previewImage" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/80"
    style="display: none;" @click="previewImage = null">
    <div class="relative max-w-4xl max-h-[90vh]" @click.stop>
        <img :src="previewImage" class="max-w-full max-h-[80vh] w-auto h-auto object-contain rounded-lg" alt="Preview">
    </div>
</div>
</main>