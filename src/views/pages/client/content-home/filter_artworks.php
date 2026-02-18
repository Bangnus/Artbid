<?php
require_once __DIR__ . '/../../../../controllers/controllers-backend/content-client/ClientController.php';

// Get filters from POST request
$data = json_decode(file_get_contents('php://input'), true);
$filters = [
    'type' => $data['types'] ?? [],
    'categories' => $data['categories'] ?? []
];
$page = isset($data['page']) ? (int) $data['page'] : 1;
$limit = 9;

$result = ClientController::getAllArtwork2($filters, $page, $limit);
$artworks = $result['artworks'];
$totalPages = $result['total_pages'];
$currentPage = $result['current_page'];

// Buffer for Grid HTML
ob_start();
if (!empty($artworks)):
    foreach ($artworks as $i => $art): ?>
        <div class="swiper-slide">
            <div
                class="card overflow-visible shadow-[0_2px_15px_rgba(0,0,0,0.10)] pb-0 rounded-2xl mb-5 flex flex-col min-h-[485px] ">
                <div class="relative">
                    <div class="overflow-hidden rounded-t-2xl">
                        <a href="artwork-detail?id=<?= htmlspecialchars($art['id']) ?>">
                            <img src="/image?id=<?= htmlspecialchars($art['id']) ?>" alt="Art"
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
                        <i
                            class="fa-regular fa-heart text-textprimary text-[20px] bg-white rounded-full p-1.5 cursor-pointer"></i>
                    </div>


                    <div data-startbidding="<?= htmlspecialchars($art['startbidding']) ?>"
                        data-endbidding="<?= htmlspecialchars($art['endbidding']) ?>"
                        data-artwork-type="<?= htmlspecialchars($art['type']) ?>"
                        class="countdown-timer absolute bg-white w-[90%] left-1/2 -translate-x-1/2 top-full -translate-y-1/2 shadow-md px-3 py-3 rounded-xl z-10">
                        <div class="flex items-center justify-center text-center gap-x-2 overflow-hidden">
                            <div class="leading-4">
                                <p class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium countdown-days">
                                    00</p>
                                <span class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">วัน</span>
                            </div>
                            <div class="text-[14px] lg:text-[16px] font-bold text-textprimary">:</div>
                            <div class="leading-4">
                                <p class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium countdown-hours">
                                </p>
                                <span class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">ชั่วโมง</span>
                            </div>
                            <div class="text-[14px] lg:text-[16px] font-bold text-textprimary">:</div>
                            <div class="leading-4">
                                <p class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium countdown-minutes">
                                    00</p>
                                <span class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">นาที</span>
                            </div>
                            <div class="text-[14px] lg:text-[16px] font-bold text-textprimary">:</div>
                            <div class="leading-4">
                                <p class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium countdown-seconds">
                                    00</p>
                                <span class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">วินาที</span>
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
                                        $<?= htmlspecialchars($art['startingprice']) ?>
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
        <p class="text-gray-500 text-lg">ไม่พบผลงานศิลปะ</p>
    </div>
<?php endif;
$gridHtml = ob_get_clean();

// Buffer for Pagination HTML
ob_start();
if ($totalPages > 1): ?>
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
<?php endif;
$paginationHtml = ob_get_clean();

// Return JSON
header('Content-Type: application/json');
echo json_encode([
    'html' => $gridHtml,
    'pagination' => $paginationHtml
]);