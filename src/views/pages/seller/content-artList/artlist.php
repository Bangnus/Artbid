<?php
require_once __DIR__ . '/../../../../controllers/controllers-backend/content-seller/SellerController.php';
$artworks = SellerController::getAllArtwork();
if (isset($_GET['id'])) {
    SellerController::deleteArtwork($_GET['id']);
    header('Location: /Artbid/public/seller/art-listings');
    exit;
}
?>

<div x-data="{openModal : false, selectedId: null, artName: ''}">
    <div class="overflow-x-auto">
        <div class="flex justify-end pr-5 pb-2">
            <a href="/Artbid/public/seller/art-listings/form"
                class="py-2 px-3 bg-bgprimary text-white font-primaryMedium rounded-lg">เพิ่มผลงาน</a>
        </div>
        <table x-data="{ openIndex: null }" id="artTable"
            class="table-auto w-full text-sm text-left text-textprimary border-collapse">
            <thead class="bg-gray-200   tracking-wider">
                <tr>
                    <th class="px-4 py-3 w-12">#</th>
                    <th class="px-4 py-3 w-64">ชื่อผลงาน</th>
                    <th class="px-4 py-3 w-40">ศิลปิน</th>
                    <th class="px-4 py-3 w-44 text-center">ประเภท</th>
                    <th class="px-4 py-3 w-32 text-center">ราคาบิดล่าสุด</th>
                    <th class="px-4 py-3 w-32 text-center">วันที่สร้าง</th>
                    <th class="px-4 py-3 w-28 text-center">สถานะ</th>
                    <th class="px-4 py-3 w-24 text-center">จัดการ</th>
                </tr>
            </thead>
            <tbody class="[&>tr:nth-child(odd)]:bg-white [&>tr:nth-child(even)]:bg-gray-100">
                <?php foreach ($artworks as $i => $art): ?>
                    <tr class="hover:bg-gray-100 transition">
                        <td class="px-4 py-3"><?= $i + 1 ?></td>
                        <td class="px-4 py-3 flex items-center gap-2 ">
                            <?php if (!empty($art['picture'])): ?>
                                <div><img src="<?= htmlspecialchars($art['picture']) ?>" width="32" height="32" alt="artwork"
                                        class="object-cover rounded-lg mt-2" style="aspect-ratio:1/1; width:32px; height:32px;">
                                </div>
                            <?php endif; ?>
                            <?= htmlspecialchars($art['artworkname']) ?>
                        </td>
                        <td class="px-4 py-3"><?= htmlspecialchars($art['artist']) ?></td>
                        <td class="px-4 py-3 text-center">
                            <?php
                            switch ($art['type']) {
                                case 'Live':
                                    $typeStyle = 'background-color: #ef4444; color: white;';
                                    $typeLabel = 'กำลังประมูล';
                                    break;
                                case 'Timed':
                                case 'Upcoming':
                                    $typeStyle = 'background-color: #22c55e; color: white;';
                                    $typeLabel = 'กำลังจะมา';
                                    break;
                                case 'Sold':
                                    if ($art['payment_status'] == 'completed' || $art['payment_status'] == 'verified') {
                                        $typeStyle = 'background-color: #3b82f6; color: white;';
                                        $typeLabel = 'ขายแล้ว';
                                    } elseif ($art['payment_status'] == 'pending_admin_verify') {
                                        $typeStyle = 'background-color: #eab308; color: white;';
                                        $typeLabel = 'รอตรวจสอบ';
                                    } else {
                                        $typeStyle = 'background-color: #fb923c; color: white;';
                                        $typeLabel = 'รอชำระเงิน';
                                    }
                                    break;
                                case 'Unsold':
                                    $typeStyle = 'background-color: #9ca3af; color: white;';
                                    $typeLabel = 'ยังไม่ขาย';
                                    break;
                                default:
                                    $typeStyle = 'background-color: #e5e7eb; color: #1f2937;';
                                    $typeLabel = $art['type'];
                            }
                            ?>
                            <span style="<?= $typeStyle ?>"
                                class="inline-block text-xs px-2 py-1 rounded-full font-semibold">
                                <?= htmlspecialchars($typeLabel) ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center"><?= htmlspecialchars($art['startingprice']) ?></td>
                        <td class="px-4 py-3 text-center"><?= date('m/d/Y', strtotime($art['createat'])) ?></td>
                        <td class="px-4 py-3 text-center">
                            <span
                                class="inline-block <?= $art['status'] == 1 ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-800' ?> text-xs px-2 py-1 rounded-full font-semibold">
                                <?= $art['status'] == 1 ? 'ใช้งาน' : 'ไม่ใช้งาน' ?>
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button"
                                    @click="openIndex === $el.closest('tr').rowIndex ? openIndex = null : openIndex = $el.closest('tr').rowIndex"
                                    class="cursor-pointer">
                                    <i class="fa-regular fa-eye text-purple-600"></i>
                                </button>
                                <?php if ($art['type'] === 'Unsold'): ?>
                                    <a href="/Artbid/public/seller/art-listings/update-artList?id=<?= $art['id'] ?>"
                                        class="cursor-pointer">
                                        <i class="fa-regular fa-pen-to-square text-yellow-600"></i>
                                    </a>
                                <?php else: ?>
                                    <!-- Locked: No Edit -->
                                    <span class="text-gray-400 cursor-not-allowed"
                                        title="เฉพาะรายการที่ยังไม่ขายจึงจะสามารถแก้ไขได้">
                                        <i class="fa-solid fa-lock"></i>
                                    </span>
                                <?php endif; ?>
                                <button
                                    @click="openModal = !openModal; selectedId = '<?= htmlspecialchars($art['id'], ENT_QUOTES) ?>'; artName = '<?= $art['artworkname'] ?>' "
                                    class="cursor-pointer">
                                    <i class="fa-regular fa-trash-can text-red-600"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr x-show="openIndex === $el.previousElementSibling.rowIndex" x-transition>
                        <td colspan="8" class="px-4 py-3">
                            <div class="bg-white p-4 rounded-lg  shadow flex gap-5">
                                <div>
                                    <img src="<?= htmlspecialchars($art['picture']) ?>" width="100" height="100"
                                        alt="artwork" class="object-cover rounded-lg ">
                                </div>
                                <div class="space-y-2">
                                    <p class="font-primaryBold">
                                        ชื่อผลงาน:
                                        <span class="font-primaryRegular">
                                            <?= htmlspecialchars($art['title']) ?>
                                        </span>
                                    </p>
                                    <p class="font-primaryBold">
                                        สถานะ:
                                        <?php
                                        switch ($art['type']) {
                                            case 'Live':
                                                $detailTypeStyle = 'background-color: #ef4444; color: white;';
                                                $detailTypeLabel = 'กำลังประมูล';
                                                break;
                                            case 'Timed':
                                            case 'Upcoming':
                                                $detailTypeStyle = 'background-color: #22c55e; color: white;';
                                                $detailTypeLabel = 'กำลังจะมา';
                                                break;
                                            case 'Sold':
                                                if ($art['payment_status'] == 'completed' || $art['payment_status'] == 'verified') {
                                                    $detailTypeStyle = 'background-color: #3b82f6; color: white;';
                                                    $detailTypeLabel = 'ขายแล้ว';
                                                } elseif ($art['payment_status'] == 'pending_admin_verify') {
                                                    $detailTypeStyle = 'background-color: #eab308; color: white;';
                                                    $detailTypeLabel = 'รอตรวจสอบ';
                                                } else {
                                                    $detailTypeStyle = 'background-color: #fb923c; color: white;';
                                                    $detailTypeLabel = 'รอชำระเงิน';
                                                }
                                                break;
                                            case 'Unsold':
                                                $detailTypeStyle = 'background-color: #9ca3af; color: white;';
                                                $detailTypeLabel = 'ยังไม่ขาย';
                                                break;
                                            default:
                                                $detailTypeStyle = 'background-color: #e5e7eb; color: #1f2937;';
                                                $detailTypeLabel = $art['type'];
                                        }
                                        ?>
                                        <span style="<?= $detailTypeStyle ?>" class="font-primaryRegular p-1 rounded-md">
                                            <?= htmlspecialchars($detailTypeLabel) ?>
                                        </span>
                                    </p>
                                    <p class="font-primaryBold">
                                        ปีที่สร้าง:
                                        <span class="font-primaryRegular">
                                            <?= htmlspecialchars($art['yearcreated']) ?>
                                        </span>
                                    </p>
                                    <p class="font-primaryBold">
                                        ขนาด:
                                        <span class="font-primaryRegular">
                                            <?= htmlspecialchars($art['dimensions']) ?>
                                        </span>
                                    </p>
                                </div>
                                <div class="space-y-2">
                                    <p class="font-primaryBold">
                                        รายละเอียด:
                                        <span class="font-primaryRegular">
                                            <?= htmlspecialchars($art['description']) ?>
                                        </span>
                                    </p>
                                    <p class="font-primaryBold">
                                        เริ่มประมูล:
                                        <span class="font-primaryRegular">
                                            <?php
                                            $startDate = new DateTime($art['startbidding']);
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
                                            echo $startDate->format('d') . ' ' . $thaiMonths[$startDate->format('m')] . ' ' . ($startDate->format('Y') + 543) . ', ' . $startDate->format('H:i');
                                            ?>
                                        </span>
                                    </p>
                                    <p class="font-primaryBold">
                                        จบประมูล:
                                        <span class="font-primaryRegular">
                                            <?php
                                            $endDate = new DateTime($art['endbidding']);
                                            echo $endDate->format('d') . ' ' . $thaiMonths[$endDate->format('m')] . ' ' . ($endDate->format('Y') + 543) . ', ' . $endDate->format('H:i');
                                            ?>
                                        </span>
                                    </p>
                                    <p class="font-primaryBold">
                                        เวลาที่เหลือ:
                                        <span class="countdown-timer font-primaryRegular"
                                            data-startbidding="<?= htmlspecialchars($art['startbidding']) ?>"
                                            data-endbidding="<?= htmlspecialchars($art['endbidding']) ?>">
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div x-show="openModal" class="fixed inset-0 z-50 flex justify-center items-center bg-black bg-opacity-30">
        <div x-transition @click.away="openModal = false"
            class="bg-white flex flex-col items-center p-5 rounded-lg shadow-lg">
            <p class="text-[24px] font-primaryBold">Delete <span x-text="artName"></span></p>
            <p class="text-gray-500">Are you sure you would like to delete?</p>
            <div class="flex items-center justify-between gap-5 mt-5">
                <button @click="openModal = false"
                    class="bg-white text-14px] border border-gray-300 py-1 px-10 rounded-lg font-primaryMedium">Cancel</button>
                <a :href="'?id=' + selectedId"
                    class="bg-red-500 text-white text-[14px]  py-1 px-10 rounded-lg font-primaryMedium">Confirm</a>
            </div>
        </div>
    </div>

    <!-- countdown-timer -->
    <script>
        let pageReloaded = false;

        function updateCountdowns() {
            const timers = document.querySelectorAll('.countdown-timer');
            let anyExpired = false;

            timers.forEach(function (timer) {
                const startStr = timer.getAttribute('data-startbidding');
                const endStr = timer.getAttribute('data-endbidding');
                if (!startStr || !endStr) return;

                const start = new Date(startStr.replace(' ', 'T'));
                const end = new Date(endStr.replace(' ', 'T'));
                const now = new Date();

                if (now < start) {
                    let diff = start - now;

                    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                    diff %= (1000 * 60 * 60 * 24);
                    const hours = Math.floor(diff / (1000 * 60 * 60));
                    diff %= (1000 * 60 * 60);
                    const minutes = Math.floor(diff / (1000 * 60));
                    diff %= (1000 * 60);
                    const seconds = Math.floor(diff / 1000);

                    const daysStr = String(days).padStart(2, '0');
                    const hoursStr = String(hours).padStart(2, '0');
                    const minutesStr = String(minutes).padStart(2, '0');
                    const secondsStr = String(seconds).padStart(2, '0');

                    timer.textContent = 'จะเริ่มใน ' + daysStr + ' days ' + hoursStr + ' hrs ' + minutesStr + ' mins ' + secondsStr + ' secs';
                    return;
                }

                let diff = end - now;

                if (diff <= 0) {
                    timer.textContent = 'หมดเวลา';
                    anyExpired = false;
                    return;
                }

                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                diff %= (1000 * 60 * 60 * 24);
                const hours = Math.floor(diff / (1000 * 60 * 60));
                diff %= (1000 * 60 * 60);
                const minutes = Math.floor(diff / (1000 * 60));
                diff %= (1000 * 60);
                const seconds = Math.floor(diff / 1000);

                const daysStr = String(days).padStart(2, '0');
                const hoursStr = String(hours).padStart(2, '0');
                const minutesStr = String(minutes).padStart(2, '0');
                const secondsStr = String(seconds).padStart(2, '0');

                let str = daysStr + ' days ' + hoursStr + ' hrs ' + minutesStr + ' mins ' + secondsStr + ' secs';

                timer.textContent = str;
            });

            if (anyExpired && !pageReloaded) {
                pageReloaded = true;
                setTimeout(function () {
                    location.reload();
                }, 1000);
            }
        }

        setInterval(updateCountdowns, 1000);
        updateCountdowns();
    </script>