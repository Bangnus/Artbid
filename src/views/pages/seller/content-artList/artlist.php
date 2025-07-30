<?php
require_once __DIR__ . '/../../../../controllers/controllers-backend/content-seller/SellerController.php';
$artworks = SellerController::getAllArtwork();
?>

<body>
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
                        <td class="px-4 py-3"><?= date('m/d/Y', strtotime($art['created_at'] ?? $art['startbidding'])) ?></td>
                        <td class="px-4 py-3">
                            <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-semibold">
                                <?= $art['status'] == 1 ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <button type="button" @click="openIndex === $el.closest('tr').rowIndex ? openIndex = null : openIndex = $el.closest('tr').rowIndex" class="cursor-pointer">
                                    <i class="fa-regular fa-eye text-purple-600"></i>
                                </button>
                                <a href="">
                                    <i class="fa-regular fa-pen-to-square text-yellow-600"></i>
                                </a>
                                <a href="">
                                    <i class="fa-regular fa-trash-can text-red-600"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr x-show="openIndex === $el.previousElementSibling.rowIndex" x-transition>
                        <td colspan="7" class="px-4 py-3">
                            <div class="bg-white p-4 rounded-lg ">
                                <h2 class="text-lg font-semibold mb-2">Artwork Details</h2>
                                <p><strong>Title:</strong> <?= htmlspecialchars($art['title']) ?></p>
                                <p><strong>Year Created:</strong> <?= htmlspecialchars($art['yearcreated']) ?></p>
                                <p><strong>Dimensions:</strong> <?= htmlspecialchars($art['dimensions']) ?></p>
                                <p><strong>Description:</strong> <?= htmlspecialchars($art['description']) ?></p>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>