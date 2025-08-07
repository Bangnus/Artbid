<?php
require_once __DIR__ . '/../../../../controllers/controllers-backend/content-seller/SellerController.php';
$artworks = SellerController::getAllArtwork();
if (isset($_GET['id'])) {
    SellerController::deleteArtwork($_GET['id']);
    header('Location: /Artbid/public/seller/art-listings');
    exit;
}
?>

<body x-data="{openModal : false, selectedId: null, artName: ''}">
    <div class="overflow-x-auto">
        <div class="flex justify-end pr-5 pb-2">
            <a href="/Artbid/public/seller/art-listings/form" class="py-1 px-3 bg-bgprimary text-white font-semibold rounded-lg">Create</a>
        </div>
        <table x-data="{ openIndex: null }" id="artTable" class="table-auto w-full text-sm text-left text-textprimary border-collapse">
            <thead class="bg-gray-200   tracking-wider">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Artworks Name</th>
                    <th class="px-4 py-3">Artist</th>
                    <th class="px-4 py-3">Start Bid</th>
                    <th class="px-4 py-3">Created At</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Action</th>
                </tr>
            </thead>
            <tbody class="[&>tr:nth-child(odd)]:bg-white [&>tr:nth-child(even)]:bg-gray-100">
                <?php foreach ($artworks as $i => $art): ?>
                    <tr class="hover:bg-gray-100 transition">
                        <td class="px-4 py-3"><?= $i + 1 ?></td>
                        <td class="px-4 py-3 flex items-center gap-2 ">
                            <?php if (!empty($art['picture'])): ?>
                                <div><img src="/Artbid/public/uploads/products/<?= htmlspecialchars($art['picture']) ?>" width="32" height="32" alt="artwork" class="object-cover rounded-lg mt-2" style="aspect-ratio:1/1; width:32px; height:32px;"></div>
                            <?php endif; ?>
                            <?= htmlspecialchars($art['artworkname']) ?>
                        </td>
                        <td class="px-4 py-3"><?= htmlspecialchars($art['artist']) ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($art['startingprice']) ?></td>
                        <td class="px-4 py-3"><?= date('m/d/Y', strtotime($art['createat'])) ?></td>
                        <td class="px-4 py-3">
                            <span class="inline-block <?= $art['status'] == 1 ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-800' ?> text-xs px-2 py-1 rounded-full font-semibold">
                                <?= $art['status'] == 1 ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <button type="button" @click="openIndex === $el.closest('tr').rowIndex ? openIndex = null : openIndex = $el.closest('tr').rowIndex" class="cursor-pointer">
                                    <i class="fa-regular fa-eye text-purple-600"></i>
                                </button>
                                <a href="art-listings/update-artList?id=<?= $art['id'] ?>" class="cursor-pointer">
                                    <i class="fa-regular fa-pen-to-square text-yellow-600"></i>
                                </a>
                                <button @click="openModal = !openModal; selectedId = '<?= htmlspecialchars($art['id'], ENT_QUOTES) ?>'; artName = '<?= $art['artworkname'] ?>' " class="cursor-pointer">
                                    <i class="fa-regular fa-trash-can text-red-600"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr x-show="openIndex === $el.previousElementSibling.rowIndex" x-transition>
                        <td colspan="7" class="px-4 py-3">
                            <div class="bg-white p-4 rounded-lg  shadow flex gap-5">
                                <div>
                                    <img src="/Artbid/public/uploads/products/<?= htmlspecialchars($art['picture']) ?>" width="100" height="100" alt="artwork"
                                        class="object-cover rounded-lg ">
                                </div>
                                <div class="space-y-2">
                                    <p class="font-primaryBold">
                                        Title:
                                        <span class="font-primaryRegular">
                                            <?= htmlspecialchars($art['title']) ?>
                                        </span>
                                    </p>
                                    <p class="font-primaryBold">
                                        Type:
                                        <span class="font-primaryRegular text-white p-1 <?= $art['type'] === 'Live' ? 'bg-red-500  rounded-md' : 'bg-green-600  rounded-md' ?>">
                                            <?= htmlspecialchars($art['type']) ?>
                                        </span>
                                    </p>
                                    <p class="font-primaryBold">
                                        Year Created:
                                        <span class="font-primaryRegular">
                                            <?= htmlspecialchars($art['yearcreated']) ?>
                                        </span>
                                    </p>
                                    <p class="font-primaryBold">
                                        Dimensions:
                                        <span class="font-primaryRegular">
                                            <?= htmlspecialchars($art['dimensions']) ?>
                                        </span>
                                    </p>
                                </div>
                                <div class="space-y-2">
                                    <p class="font-primaryBold">
                                        Description:
                                        <span class="font-primaryRegular">
                                            <?= htmlspecialchars($art['description']) ?>
                                        </span>
                                    </p>
                                    <p class="font-primaryBold">
                                        Start Bidding:
                                        <span class="font-primaryRegular">
                                            <?= htmlspecialchars($art['startbidding']) ?>
                                        </span>
                                    </p>
                                    <p class="font-primaryBold">
                                        End Bidding:
                                        <span class="font-primaryRegular">
                                            <?= htmlspecialchars($art['endbidding']) ?>
                                        </span>
                                    </p>
                                    <p class="font-primaryBold">
                                        Remaining Time:
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
        <div x-transition @click.away="openModal = false" class="bg-white flex flex-col items-center p-5 rounded-lg shadow-lg">
            <p class="text-[24px] font-primaryBold">Delete <span x-text="artName"></span></p>
            <p class="text-gray-500">Are you sure you would like to delete?</p>
            <div class="flex items-center justify-between gap-5 mt-5">
                <button @click="openModal = false" class="bg-white text-14px] border border-gray-300 py-1 px-10 rounded-lg font-primaryMedium">Cancel</button>
                <a :href="'?id=' + selectedId" class="bg-red-500 text-white text-[14px]  py-1 px-10 rounded-lg font-primaryMedium">Confirm</a>
            </div>
        </div>
    </div>

    <!-- countdown-timer -->
    <script>
        let pageReloaded = false;

        function updateCountdowns() {
            const timers = document.querySelectorAll('.countdown-timer');
            let anyExpired = false;

            timers.forEach(function(timer) {
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
                setTimeout(function() {
                    location.reload();
                }, 1000);
            }
        }

        setInterval(updateCountdowns, 1000);
        updateCountdowns();
    </script>

</body>