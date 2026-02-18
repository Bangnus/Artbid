<div class=" mx-auto px-4 sm:px-6 lg:px-8 py-10 min-h-screen bg-white">
    <h1 class="text-3xl font-primaryBold text-textprimary">
        <i class="fa-solid fa-heart text-red-500 mr-2"></i>
        รายการที่น่าสนใจของคุณ
    </h1>

    <?php if (empty($favorites)): ?>
        <div class="text-center py-20">
            <i class="fa-regular fa-heart text-gray-300 text-6xl mb-4"></i>
            <p class="text-xl text-gray-500 font-primaryMedium">ยังไม่มีรายการที่ชอบ</p>
            <a href="home"
                class="inline-block mt-4 bg-textorange text-white px-6 py-3 rounded-xl font-primaryMedium hover:bg-orange-600 transition">
                เริ่มสำรวจงานศิลปะ
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mt-5">
            <?php foreach ($favorites as $art): ?>
                <div
                    class="card overflow-visible shadow-[0_2px_15px_rgba(0,0,0,0.10)] pb-0 rounded-2xl mb-5 flex flex-col min-h-[420px]">
                    <div class="relative">
                        <div class="overflow-hidden rounded-t-2xl" style="height: 400px;">
                            <a href="artwork-detail?id=<?= htmlspecialchars($art['id']) ?>" class="block" style="height: 100%;">
                                <img src="/image?id=<?= htmlspecialchars($art['id']) ?>" alt="Art"
                                    style="width: 100%; height: 100%; object-fit: cover;"
                                    class="transition duration-500 transform hover:scale-105">
                            </a>
                        </div>

                        <div class="absolute top-5 left-0 w-full px-3 flex items-center justify-between">
                            <div
                                class="<?= $art['type'] === 'Live' ? 'bg-red-600' : 'bg-green-600' ?> text-white px-3 py-1 rounded-md text-sm">
                                <?= $art['type'] === 'Live' ? 'กำลังประมูล' : 'กำลังจะมา' ?>
                            </div>
                            <i onclick="event.stopPropagation(); toggleFavorite('<?= $art['id'] ?>', this)"
                                class="fa-solid fa-heart text-red-500 text-[20px] bg-white rounded-full p-1.5 cursor-pointer hover:scale-110 transition-all"></i>
                        </div>
                    </div>

                    <a href="artwork-detail?id=<?= htmlspecialchars($art['id']) ?>" class="card-body flex-1 flex flex-col">
                        <div class="px-5 py-5 rounded-b-2xl break-words whitespace-normal flex-1">
                            <div class="space-y-2">
                                <h3 class="text-lg text-textprimary font-primaryBold line-clamp-2 leading-tight">
                                    <?= htmlspecialchars($art['title']) ?>
                                </h3>
                                <div class="space-y-1 text-sm">
                                    <p class="text-textsecondary font-primaryRegular flex items-center gap-2">
                                        <i class="fa-solid fa-palette text-textorange"></i>
                                        <span
                                            class="text-textprimary font-primaryMedium"><?= htmlspecialchars($art['artist']) ?></span>
                                    </p>
                                    <p class="text-textsecondary font-primaryRegular flex items-center gap-2">
                                        <i class="fa-solid fa-tag text-textorange"></i>
                                        <span
                                            class="text-textorange font-primaryBold text-base">฿<?= number_format($art['startingprice']) ?></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
    function toggleFavorite(artworkId, btn) {
        // Animation
        btn.classList.add('scale-0');

        setTimeout(() => {
            const card = btn.closest('.card');
            card.style.transform = 'scale(0.9)';
            card.style.opacity = '0';
            card.style.transition = 'all 0.3s ease';

            setTimeout(() => {
                card.remove();
                const grid = document.querySelector('.grid');
                if (!grid || grid.children.length === 0) {
                    location.reload();
                }
            }, 300);
        }, 150);

        fetch('toggle-favorite', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'artwork_id=' + artworkId
        }).catch(error => console.error('Error:', error));
    }
</script>