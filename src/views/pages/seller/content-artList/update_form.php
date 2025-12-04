<body>
    <?php
    require_once __DIR__ . '/../../../../controllers/controllers-backend/content-seller/SellerController.php';
    $artId = $_GET['id'] ?? null;
    $artwork = $artId ? SellerController::getArtworkById($artId) : null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $artId) {
        $data = $_POST;
        SellerController::updateArtwork($artId, $data);
        header('Location: /Artbid/public/seller/art-listings');
        exit;
    }
    ?>
    <div class="overflow-auto py-5 px-10">
        <h1 class="text-xl font-bold mb-4">Update Artwork</h1>
        <form action="" method="post" enctype="multipart/form-data" class="space-y-5">
            <div class="flex items-center justify-center gap-x-5 ">
                <div class="space-y-2 w-full">
                    <p class="text-[13px] text-textprimary font-primaryRegular">Title</p>
                    <input
                        class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200"
                        type="text"
                        name="title"
                        minlength="8"
                        maxlength="50"
                        required
                        value="<?= htmlspecialchars($artwork['title'] ?? '') ?>">
                </div>

                <div class="space-y-2 w-full">
                    <p class="text-[13px] text-textprimary font-primaryRegular">Artwork Name</p>
                    <input
                        class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200"
                        type="text"
                        name="artworkname"
                        minlength="10"
                        maxlength="50"
                        required
                        value="<?= htmlspecialchars($artwork['artworkname'] ?? '') ?>">
                </div>
            </div>
            <div class="flex items-center justify-center gap-x-5 ">
                <div class="space-y-2 w-full">
                    <p class="text-[13px] text-textprimary font-primaryRegular">Artist</p>
                    <input
                        class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200"
                        type="text"
                        name="artist"
                        minlength="4"
                        maxlength="50"
                        required
                        value="<?= htmlspecialchars($artwork['artist'] ?? '') ?>">
                </div>
                <div class="space-y-2 w-full">
                    <p class="text-[13px] text-textprimary font-primaryRegular">Year Created</p>
                    <input
                        class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200"
                        type="number"
                        name="yearcreated"
                        minlength="4"
                        maxlength="5"
                        required
                        value="<?= htmlspecialchars($artwork['yearcreated'] ?? '') ?>">
                </div>
            </div>
            <div class="flex items-center justify-center gap-x-5 ">
                <div class="space-y-2 w-full">
                    <p class="text-[13px] text-textprimary font-primaryRegular">Dimensions</p>
                    <input
                        class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200"
                        type="text"
                        name="dimensions"
                        minlength="4"
                        required
                        value="<?= htmlspecialchars($artwork['dimensions'] ?? '') ?>">
                </div>
                <div class="space-y-2 w-full">
                    <p class="text-[13px] text-textprimary font-primaryRegular">Starting Price</p>
                    <input
                        class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200"
                        type="text"
                        name="startingprice"
                        required
                        value="<?= htmlspecialchars($artwork['startingprice'] ?? '') ?>">
                </div>
            </div>
            <div class="flex items-center justify-center gap-x-5 ">
                <div class="space-y-2 w-full">
                    <p class="text-[13px] text-textprimary font-primaryRegular">Start bid</p>
                    <input
                        class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200"
                        type="datetime-local"
                        name="startbidding"
                        required
                        value="<?= isset($artwork['startbidding']) ? date('Y-m-d\TH:i', strtotime($artwork['startbidding'])) : '' ?>">
                </div>
                <div class="space-y-2 w-full">
                    <p class="text-[13px] text-textprimary font-primaryRegular">End bid</p>
                    <input
                        class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200"
                        type="datetime-local"
                        name="endbidding"
                        required
                        value="<?= isset($artwork['endbidding']) ? date('Y-m-d\TH:i', strtotime($artwork['endbidding'])) : '' ?>">
                </div>
            </div>
            <div class="flex  justify- gap-x-5 ">
                <div class="space-y-2 w-full">
                    <p class="text-[13px] text-textprimary font-primaryRegular">Category</p>
                    <select name="categories_id" class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200">
                        <option value="1" <?= ($artwork['categories_id'] ?? '') == 1 ? 'selected' : '' ?>>Prints & Multiples Art</option>
                        <option value="2" <?= ($artwork['categories_id'] ?? '') == 2 ? 'selected' : '' ?>>Photography Art</option>
                        <option value="3" <?= ($artwork['categories_id'] ?? '') == 3 ? 'selected' : '' ?>>Classical Art</option>
                        <option value="4" <?= ($artwork['categories_id'] ?? '') == 4 ? 'selected' : '' ?>>Modern & Contemporary Art</option>
                    </select>
                </div>
                <div class="space-y-2 w-full">
                    <p class="text-[13px] text-textprimary font-primaryRegular">Description</p>
                    <textarea
                        name="description"
                        minlength="10"
                        required
                        class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary "><?= htmlspecialchars($artwork['description'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="space-y-2 w-full">
                <label
                    for="image"
                    class="flex space-y-2 flex-col items-center justify-center w-full h-40 p-4 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-textorange transition">

                    <i class="fa-solid fa-arrow-up-from-bracket text-[36px] text-gray-500"></i>

                    <div class="flex flex-col items-center text-center">
                        <span class="text-textorange text-[16px]">"Tap to upload"</span>
                        <span class="text-gray-500 text-[14px]">SVG, PNG, or JPG (max. 800x400px)</span>
                    </div>

                    <input type="file" name="image" id="image" class="hidden" accept="image/*" onchange="previewImage(event)">
                </label>
                <!-- Preview -->
                <div id="preview">
                    <?php if (!empty($artwork['picture'])): ?>
                        <div class="flex items-center gap-2 border border-gray-300 p-2 rounded-lg bg-white shadow-sm">
                            <img src="<?= htmlspecialchars($artwork['picture']) ?>" class="max-h-16 rounded-lg order-1">
                            <span class="text-xs text-gray-500 order-2 ml-2">Current Image</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="flex items-start justify-start gap-10 mt-4">
                <!-- Active -->
                <label class="flex flex-col items-start gap-1 p-4  rounded-lg cursor-pointer  transition w-full max-w-xs">
                    <div class="flex items-center gap-2">
                        <input
                            type="radio"
                            name="status"
                            value="1"
                            required
                            <?= ($artwork['status'] ?? 1) == 1 ? 'checked' : '' ?>
                            class="w-4 h-4 rounded-full cursor-pointer">
                        <span class="text-sm font-medium text-gray-700">Active</span>
                    </div>
                    <p class="text-[14px] text-gray-500">
                        The active status indicates that the menu item is currently available and listed in the restaurant's offerings.
                    </p>
                </label>


                <!-- Inactive -->
                <label class="flex flex-col items-start gap-1 p-4  rounded-lg cursor-pointertransition w-full max-w-xs">
                    <div class="flex items-center gap-2">
                        <input
                            type="radio"
                            name="status"
                            value="0"
                            <?= ($artwork['status'] ?? 1) == 0 ? 'checked' : '' ?>
                            class="w-4 h-4  rounded-full cursor-pointer">
                        <span class="text-sm font-medium text-gray-700">InActive</span>
                    </div>
                    <p class="text-[14px] text-gray-500">
                        The active status indicates that the menu item is currently available and listed in the restaurant's offerings.
                    </p>
                </label>

            </div>

            <button id="signin-btn" type="submit" class="bg-textorange text-white w-full text-[14px] font-primaryMedium py-[12px] rounded-lg flex items-center justify-center gap-2">
                <span id="signin-spinner" class="hidden">
                    <?php require_once __DIR__ . '/../../../../components/content-loading/dot-loading.php'; ?>
                </span>
                <span id="signin-text">Update Artwork</span>
            </button>
        </form>
    </div>
</body>
<script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('preview');
        preview.innerHTML = '';

        if (input.files && input.files[0]) {
            const wrapper = document.createElement('div');
            wrapper.className = 'flex items-center gap-2 border border-gray-300 p-2 rounded-lg bg-white shadow-sm';

            // Show image preview
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'max-h-16 rounded-lg order-1';
                wrapper.appendChild(img);

                // Show file name next to image (right side)
                const fileName = document.createElement('span');
                fileName.className = 'text-xs text-gray-500 order-2 ml-2';
                fileName.textContent = input.files[0].name;
                wrapper.appendChild(fileName);
            };
            reader.readAsDataURL(input.files[0]);

            preview.appendChild(wrapper);
        }
    }
</script>

<script>
    document.querySelector('form').addEventListener('submit', function() {
        document.getElementById('signin-spinner').classList.remove('hidden');
        document.getElementById('signin-text').classList.add('hidden');
    });
</script>