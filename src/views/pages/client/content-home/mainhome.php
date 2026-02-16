<?php
require_once __DIR__ . '/../../../../controllers/controllers-backend/content-client/ClientController.php';
require_once __DIR__ . '/../../../../controllers/controllers-backend/content-client/FavoriteController.php';

// Get page from query string, default to 1
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
// Call getAllArtwork2 with correct parameters: filters, page, limit
$category = isset($_GET['category']) ? (int) $_GET['category'] : null;
$search = isset($_GET['search']) ? $_GET['search'] : null;
$initialFilters = [];
if ($category) {
    $initialFilters['categories'] = [$category];
}
if ($search) {
    $initialFilters['search'] = $search;
}
$result = ClientController::getAllArtwork2($initialFilters, $page, 9);
$artworks = $result['artworks'];
$totalPages = $result['total_pages'];
$currentPage = $result['current_page'];
?>

<div class="relative">
    <img src="<?= $_ENV['APP_URL'] ?>/images/hero.jpg" alt="" class="h-[400px] w-full object-cover blur-[3px]">
    <div class="absolute top-1/2 left-0 transform -translate-y-1/2 mx-5 md:mx-20 z-10">
        <h1 class="text-[4rem] font-primaryBold text-white">การประมูล</h1>
        <p class="text-white text-[20px] font-primaryMedium">แคตตาล็อกงานศิลปะคือชุดผลงานที่ได้รับการคัดสรรมาอย่างดี
            <br> เพื่อสะท้อนถึงธีม สไตล์
            หรือรสนิยมอันเป็นเอกลักษณ์ของนักสะสม
        </p>
    </div>
</div>
<div class="mx-5 md:mx-20 my-10 flex flex-col lg:flex-row gap-6 items-start">

    <div class="w-full lg:w-1/4 space-y-8 bg-white p-5 rounded-2xl h-fit border border-gray-100 shadow-sm sticky top-4">
        <div>
            <h3 class="text-2xl font-bold text-gray-800 mb-4 inline-block pb-1 border-b-2 border-blue-500">
                สำรวจการประมูล
            </h3>
            <div class="space-y-3">
                <label class="flex items-center space-x-3 cursor-pointer group">
                    <input type="checkbox" value="Live"
                        class="form-checkbox h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 filter-checkbox">
                    <span class="text-gray-600 group-hover:text-blue-600 transition">กำลังประมูล</span>
                </label>
                <label class="flex items-center space-x-3 cursor-pointer group">
                    <input type="checkbox" value="Upcoming"
                        class="form-checkbox h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 filter-checkbox">
                    <span class="text-gray-600 group-hover:text-blue-600 transition">การประมูลที่จะมาถึง</span>
                </label>
            </div>
        </div>

        <div>
            <h3 class="text-2xl font-bold text-gray-800 mb-4">หมวดหมู่</h3>
            <div class="space-y-3">
                <?php
                $categories = [
                    1 => 'ภาพพิมพ์',
                    2 => 'ภาพถ่าย',
                    3 => 'ศิลปะคลาสสิก',
                    4 => 'ศิลปะร่วมสมัย'
                ];
                foreach ($categories as $id => $name): ?>
                    <label class="flex items-center space-x-3 cursor-pointer group">
                        <input type="checkbox" value="<?= $id ?>"
                            class="form-checkbox h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 category-checkbox"
                            <?= (isset($_GET['category']) && $_GET['category'] == $id) ? 'checked' : '' ?>>
                        <span class="text-gray-600 group-hover:text-blue-600 transition"><?= $name ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="flex-1 w-full bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 artwork-grid-container">
            <?php if (!empty($artworks)): ?>
                <?php foreach ($artworks as $i => $art): ?>
                    <div class="swiper-slide">
                        <div
                            class="card overflow-visible shadow-[0_2px_15px_rgba(0,0,0,0.10)] pb-0 rounded-2xl mb-5 flex flex-col min-h-[485px] ">
                            <div class="relative">
                                <div class="overflow-hidden rounded-t-2xl">
                                    <a href="artwork-detail?id=<?= htmlspecialchars($art['id']) ?>">
                                        <img src="<?= htmlspecialchars($art['picture']) ?>" alt="Art"
                                            class="w-full h-[300px] object-cover object-[0%_10%] transition duration-500 transform hover:scale-105">
                                    </a>
                                </div>

                                <div class="absolute top-5 left-0 w-full px-3 flex items-center justify-between ">
                                    <div
                                        class=" <?= $art['type'] === 'Live' ? 'bg-red-600' : 'bg-green-600' ?> text-white px-3 py-1 rounded-md text-sm">
                                        <?php
                                        if ($art['type'] === 'Live') {
                                            echo 'กำลังประมูล';
                                        } else {
                                            echo 'การประมูลที่จะมาถึง';
                                        }
                                        ?>
                                    </div>
                                    <?php $isMainFav = isset($_SESSION['user']) ? FavoriteController::isFavorited($art['id']) : false; ?>
                                    <i onclick="toggleFavoriteCard('<?= $art['id'] ?>', this)"
                                        class="<?= $isMainFav ? 'fa-solid text-red-500' : 'fa-regular text-textprimary' ?> fa-heart text-[20px] bg-white rounded-full p-1.5 cursor-pointer hover:scale-110 transition-all"></i>
                                </div>


                                <div data-startbidding="<?= htmlspecialchars($art['startbidding']) ?>"
                                    data-endbidding="<?= htmlspecialchars($art['endbidding']) ?>"
                                    data-artwork-type="<?= htmlspecialchars($art['type']) ?>"
                                    class="countdown-timer absolute bg-white w-[90%] left-1/2 -translate-x-1/2 top-full -translate-y-1/2 shadow-md px-3 py-3 rounded-xl z-10">
                                    <div class="flex items-center justify-center text-center gap-x-2 overflow-hidden">
                                        <div class="leading-4">
                                            <p
                                                class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium countdown-days">
                                                00</p>
                                            <span
                                                class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">วัน</span>
                                        </div>
                                        <div class="text-[14px] lg:text-[16px] font-bold text-textprimary">:</div>
                                        <div class="leading-4">
                                            <p
                                                class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium countdown-hours">
                                            </p>
                                            <span
                                                class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">ชั่วโมง</span>
                                        </div>
                                        <div class="text-[14px] lg:text-[16px] font-bold text-textprimary">:</div>
                                        <div class="leading-4">
                                            <p
                                                class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium countdown-minutes">
                                                00</p>
                                            <span
                                                class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">นาที</span>
                                        </div>
                                        <div class="text-[14px] lg:text-[16px] font-bold text-textprimary">:</div>
                                        <div class="leading-4">
                                            <p
                                                class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium countdown-seconds">
                                                00</p>
                                            <span
                                                class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">วินาที</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body flex-1 flex flex-col">
                                <div class=" px-6 pt-10 rounded-b-2xl break-words whitespace-normal flex-1 ">
                                    <div class="space-y-1 ">
                                        <p class="text-[18px] text-textprimary font-primaryMedium">
                                            <?= htmlspecialchars($art['title']) ?>
                                        </p>
                                        <div class="text-[16px] font-primaryMedium">
                                            <p class="text-textsecondary">ศิลปิน :
                                                <span class="text-textprimary"><?= htmlspecialchars($art['artist']) ?></span>
                                            </p>
                                            <p class="text-textsecondary">ราคาปัจจุบัน :<span class="text-textprimary">
                                                    ฿<?= htmlspecialchars($art['startingprice']) ?>
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div
                    class="col-span-full flex flex-col items-center justify-center py-20 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                    <p class="text-gray-500 text-lg">No artworks found.</p>
                </div>
            <?php endif; ?>
        </div>

        <div id="pagination-container" class="mt-12 flex justify-center space-x-2">
            <?php if ($totalPages > 1): ?>
                <button onclick="changePage(<?= max(1, $currentPage - 1) ?>)"
                    class="w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-gray-800 transition <?= $currentPage == 1 ? 'opacity-50 cursor-not-allowed' : '' ?>"
                    <?= $currentPage == 1 ? 'disabled' : '' ?>>
                    <i class="fa-solid fa-chevron-left"></i>
                </button>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php if ($i == $currentPage): ?>
                        <button
                            class="w-10 h-10 rounded-full bg-textorange text-white flex items-center justify-center shadow-md"><?= $i ?></button>
                    <?php else: ?>
                        <button onclick="changePage(<?= $i ?>)"
                            class="w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-gray-800 transition"><?= $i ?></button>
                    <?php endif; ?>
                <?php endfor; ?>

                <button onclick="changePage(<?= min($totalPages, $currentPage + 1) ?>)"
                    class="w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-gray-800 transition <?= $currentPage == $totalPages ? 'opacity-50 cursor-not-allowed' : '' ?>"
                    <?= $currentPage == $totalPages ? 'disabled' : '' ?>>
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    let currentPage = 1;

    function fetchArtworks(page = 1) {
        currentPage = page;
        const filterCheckboxes = document.querySelectorAll('.filter-checkbox');
        const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
        const artworkGrid = document.querySelector('.artwork-grid-container') || document.querySelector('.grid');
        const paginationContainer = document.getElementById('pagination-container');

        const types = Array.from(filterCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);

        const categories = Array.from(categoryCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);

        fetch('<?= $_ENV['APP_URL'] ?>/filter-artworks', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ types, categories, page })
        })
            .then(response => response.json())
            .then(data => {
                if (data.html) artworkGrid.innerHTML = data.html;
                if (data.pagination) paginationContainer.innerHTML = data.pagination;
            })
            .catch(error => console.error('Error:', error));
    }

    function changePage(page) {
        fetchArtworks(page);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const filterCheckboxes = document.querySelectorAll('.filter-checkbox');
        const categoryCheckboxes = document.querySelectorAll('.category-checkbox');

        filterCheckboxes.forEach(cb => cb.addEventListener('change', () => fetchArtworks(1)));
        categoryCheckboxes.forEach(cb => cb.addEventListener('change', () => fetchArtworks(1)));
    });

    function toggleFavoriteCard(artworkId, btn) {
        // เปลี่ยนสีทันที (optimistic UI)
        if (btn.classList.contains('fa-regular')) {
            btn.classList.remove('fa-regular', 'text-textprimary');
            btn.classList.add('fa-solid', 'text-red-500');
        } else {
            btn.classList.remove('fa-solid', 'text-red-500');
            btn.classList.add('fa-regular', 'text-textprimary');
        }

        // ส่ง API เบื้องหลัง
        fetch('<?= $_ENV['APP_URL'] ?>/toggle-favorite', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'artwork_id=' + artworkId
        }).catch(error => console.error('Error:', error));
    }
</script>