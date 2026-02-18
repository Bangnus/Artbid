<body>
    <?php
    require_once __DIR__ . '/../../../../controllers/controllers-backend/content-seller/SellerController.php';
    $artId = $_GET['id'] ?? null;
    $artwork = $artId ? SellerController::getArtworkById($artId) : null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $artId) {
        $data = $_POST;
        $result = SellerController::updateArtwork($artId, $data);
        
        switch ($result) {
            case 1: // Success
                header('Location: /Artbid/public/seller/art-listings');
                exit;
            case 2: // Date Error
                echo "<script>
                    alert('End bid time must be greater than Start bid time.');
                </script>";
                break;
            case 3: // Locked (Sold & Verified)
                echo "<script>
                    alert('Cannot edit artwork that is Sold and Verified.');
                </script>";
                break;
            case 0: // Generic Fail
            default:
                echo "<script>
                    alert('Failed to update artwork. Please try again.');
                </script>";
                break;
        }
    }
    ?>
    <div class="overflow-auto py-5 px-10">
        <h1 class="text-xl font-bold mb-4">แก้ไขผลงาน</h1>
        <form action="" method="post" enctype="multipart/form-data" class="space-y-5">
            <div class="flex items-center justify-center gap-x-5 ">
                <div class="space-y-2 w-full">
                    <p class="text-[13px] text-textprimary font-primaryRegular">หัวข้อ</p>
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
                    <p class="text-[13px] text-textprimary font-primaryRegular">ชื่อผลงาน</p>
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
                    <p class="text-[13px] text-textprimary font-primaryRegular">ศิลปิน</p>
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
                    <p class="text-[13px] text-textprimary font-primaryRegular">ปีที่สร้าง</p>
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
                    <p class="text-[13px] text-textprimary font-primaryRegular">ขนาด</p>
                    <input
                        class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200"
                        type="text"
                        name="dimensions"
                        minlength="4"
                        required
                        value="<?= htmlspecialchars($artwork['dimensions'] ?? '') ?>">
                </div>
                <div class="space-y-2 w-full">
                    <p class="text-[13px] text-textprimary font-primaryRegular">ราคาเริ่มต้น</p>
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
                    <p class="text-[13px] text-textprimary font-primaryRegular">เริ่มประมูล</p>
                    <input
                        class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200"
                        type="datetime-local"
                        name="startbidding"
                        value="<?= date('Y-m-d\TH:i', strtotime($artwork['startbidding'] ?? '')) ?>"
                        required>
                </div>
                <div class="space-y-2 w-full">
                    <p class="text-[13px] text-textprimary font-primaryRegular">จบประมูล</p>
                    <input
                        class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200"
                        type="datetime-local"
                        name="endbidding"
                        value="<?= date('Y-m-d\TH:i', strtotime($artwork['endbidding'] ?? '')) ?>"
                        required>
                </div>
            </div>
            <div class="flex flex-col md:flex-row justify-between gap-5 ">
                <div class="space-y-2 w-full">
                    <p class="text-[13px] text-textprimary font-primaryRegular">ประเภท</p>
                    <select name="categories_id" class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200">
                        <option value="1" <?= ($artwork['categories_id'] ?? '') == 1 ? 'selected' : '' ?>
ภาพพิมพ์ (Prints & Multiples)</option>
                        <option value="2" <?= ($artwork['categories_id'] ?? '') == 2 ? 'selected' : '' ?>
ภาพถ่าย (Photography Art)</option>
                        <option value="3" <?= ($artwork['categories_id'] ?? '') == 3 ? 'selected' : '' ?>
ศิลปะคลาสสิก (Classical Art)</option>
                        <option value="4" <?= ($artwork['categories_id'] ?? '') == 4 ? 'selected' : '' ?>
ศิลปะสมัยใหม่ (Modern & Contemporary Art)</option>
                    </select>
                </div>
                <div class="space-y-2 w-full">
                    <p class="text-[13px] text-textprimary font-primaryRegular">รายละเอียด</p>
                    <textarea
                        name="description"
                        minlength="10"
                        required
                        class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary min-h-[42px]"><?= htmlspecialchars($artwork['description'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="space-y-2 w-full">
                <label
                    for="image"
                    class="flex space-y-2 flex-col items-center justify-center w-full h-40 p-4 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-textorange transition text-center">

                    <i class="fa-solid fa-arrow-up-from-bracket text-[36px] text-gray-500"></i>

                    <div class="flex flex-col items-center">
                        <span class="text-textorange text-[16px]">"กดเพื่ออัปโหลดรูปภาพใหม่"</span>
                        <span class="text-gray-500 text-[14px]">รองรับไฟล์ SVG, PNG หรือ JPG (ขนาดแนะนำ 800x400px)</span>
                    </div>

                    <input type="file" name="image" id="image" class="hidden" accept="image/*" onchange="previewImage(event)">
                </label>
                <!-- Preview -->
                <div id="preview">
                    <?php if (!empty($artwork['picture'])): ?>
                        <div class="flex items-center gap-2 border border-gray-300 p-2 rounded-lg bg-white shadow-sm">
                            <img src="<?= htmlspecialchars($artwork['picture']) ?>" class="max-h-16 rounded-lg order-1">
                            <span class="text-xs text-gray-500 order-2 ml-2">รูปภาพปัจจุบัน</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="flex items-start justify-start gap-10 mt-4">
                <!-- Active -->
                <label class="flex flex-col items-start gap-1 p-4 rounded-lg cursor-pointer transition w-full max-w-xs">
                    <div class="flex items-center gap-2">
                        <input
                            type="radio"
                            name="status"
                            value="1"
                            required
                            <?= ($artwork['status'] ?? 1) == 1 ? 'checked' : '' ?>
                            class="w-4 h-4 rounded-full cursor-pointer">
                        <span class="text-sm font-medium text-gray-700">ใช้งาน</span>
                    </div>
                    <p class="text-[14px] text-gray-500">
                        สถานะใช้งานหมายความว่าผลงานนี้จะแสดงผลและพร้อมให้เข้าชมหรือประมูลบนเว็บไซต์
                    </p>
                </label>


                <!-- Inactive -->
                <label class="flex flex-col items-start gap-1 p-4 rounded-lg cursor-pointer transition w-full max-w-xs">
                    <div class="flex items-center gap-2">
                        <input
                            type="radio"
                            name="status"
                            value="0"
                            <?= ($artwork['status'] ?? 1) == 0 ? 'checked' : '' ?>
                            class="w-4 h-4 rounded-full cursor-pointer">
                        <span class="text-sm font-medium text-gray-700">ไม่ใช้งาน</span>
                    </div>
                    <p class="text-[14px] text-gray-500">
                        สถานะไม่ใช้งานหมายความว่าผลงานนี้จะไม่แสดงผลต่อผู้ใช้งานทั่วไป
                    </p>
                </label>

            </div>

            <button id="signin-btn" type="submit" class="bg-textorange text-white w-full text-[14px] font-primaryMedium py-[12px] rounded-lg flex items-center justify-center gap-2">
                <span id="signin-spinner" class="hidden">
                    <?php require_once __DIR__ . '/../../../../components/content-loading/dot-loading.php'; ?>
                </span>
                <span id="signin-text">บันทึกการแก้ไข</span>
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
    document.querySelector('form').addEventListener('submit', function(e) {
        const start = document.querySelector('input[name="startbidding"]').value;
        const end = document.querySelector('input[name="endbidding"]').value;

        if (start && end) {
            const startDate = new Date(start);
            const endDate = new Date(end);

            if (endDate <= startDate) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'ข้อผิดพลาด',
                    text: 'เวลาจบการประมูลต้องมากกว่าเวลาเริ่มการประมูล',
                    confirmButtonText: 'ตกลง'
                });
                return;
            }
        }

        document.getElementById('signin-spinner').classList.remove('hidden');
        document.getElementById('signin-text').classList.add('hidden');
    });
</script>