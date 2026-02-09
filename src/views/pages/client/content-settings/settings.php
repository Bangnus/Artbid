<div class="min-h-screen py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">

        <!-- FORCE LAYOUT STRUCTURE AS REQUESTED -->
        <div class="flex gap-6 w-full">

            <!-- A: MENU (20%) -->
            <div style="width: 20%;" class="flex-shrink-0 h-fit sticky top-10 bg-white rounded-xl shadow-sm p-3 md:p-4">
                <nav class="space-y-2">
                    <a href="settings"
                        class="block px-4 py-2 rounded-md font-primaryBold text-base transition-all md:text-left
                        <?= !isset($_GET['tab']) || $_GET['tab'] === 'account' ? 'bg-bgprimary text-white shadow-sm' : 'text-textsecondary hover:bg-gray-100' ?>">
                        บัญชี
                    </a>
                    <a href="settings?tab=address"
                        class="block px-4 py-2 rounded-md font-primaryBold text-base transition-all md:text-left
                        <?= isset($_GET['tab']) && $_GET['tab'] === 'address' ? 'bg-bgprimary text-white shadow-sm' : 'text-textsecondary hover:bg-gray-100' ?>">
                        ที่อยู่
                    </a>
                    <?php if (isset($_SESSION['user']['role_id']) && $_SESSION['user']['role_id'] != 1): ?>
                        <a href="settings?tab=payment"
                            class="block px-4 py-2 rounded-md font-primaryBold text-base transition-all md:text-left
                            <?= isset($_GET['tab']) && $_GET['tab'] === 'payment' ? 'bg-bgprimary text-white shadow-sm' : 'text-textsecondary hover:bg-gray-100' ?>">
                            บัญชีรับเงิน
                        </a>
                    <?php endif; ?>
                </nav>
            </div>

            <!-- B: CONTENT (80%) -->
            <div style="width: 80%;" class="bg-white rounded-2xl shadow-sm p-6 md:p-10 min-w-0">
                <?php if (!isset($_GET['tab']) || $_GET['tab'] === 'account'): ?>
                    <h2 class="text-3xl font-primaryBold text-textprimary mb-6">บัญชี</h2>
                    <hr class="border-gray-400">

                    <div class="mt-6">
                        <form action="update-profile" method="POST" enctype="multipart/form-data">
                            <!-- Profile Picture -->
                            <div class="flex justify-between items-center">
                                <div class="flex gap-4 items-center">
                                    <?php
                                    $firstname = $_SESSION['user']['firstname'] ?? '';
                                    $lastname = $_SESSION['user']['lastname'] ?? '';
                                    $profileImg = $_SESSION['user']['picture'] ?? '';
                                    $hasImg = !empty($profileImg);
                                    $initials = mb_substr($firstname, 0, 1) . mb_substr($lastname, 0, 1);
                                    ?>
                                    <?php if ($hasImg): ?>
                                        <img src="<?= htmlspecialchars($profileImg) ?>" alt="Profile"
                                            class="w-16 h-16 rounded-full object-cover">
                                    <?php else: ?>
                                        <div
                                            class="flex items-center justify-center w-16 h-16 rounded-full bg-textorange text-white text-xl font-primarySemibold">
                                            <?= htmlspecialchars(strtoupper($initials)) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <p class="font-primaryBold text-textprimary text-lg">รูปโปรไฟล์</p>
                                        <p class="text-textsecondary text-xs font-primaryRegular">ขนาดไม่เกิน 2MB (jpg, png,
                                            gif, webp)</p>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <label
                                        class="bg-textorange text-white px-6 py-2.5 rounded-lg text-sm font-primaryMedium cursor-pointer hover:bg-orange-600 transition shadow-sm">
                                        อัปโหลดรูปใหม่
                                        <input type="file" name="profile_picture" accept="image/*" class="hidden"
                                            onchange="this.form.submit()">
                                    </label>
                                    <button type="button" onclick="deleteProfilePicture()"
                                        class="bg-gray-200 text-textprimary px-5 py-2.5 rounded-lg text-sm font-primaryMedium hover:bg-gray-200 transition">
                                        ลบรูปภาพ
                                    </button>
                                </div>
                            </div>
                            <?php if (isset($_SESSION['field_errors']['profile_picture'])): ?>
                                <p class="text-red-500 text-sm mt-2 font-primaryRegular">
                                    <?= $_SESSION['field_errors']['profile_picture'] ?>
                                </p>
                                <?php unset($_SESSION['field_errors']['profile_picture']); ?>
                            <?php endif; ?>

                            <!-- Name Fields -->
                            <div class="flex flex-col md:flex-row gap-6 mt-6 w-full">
                                <div class="w-full md:w-1/2">
                                    <label class="block text-sm font-primaryMedium text-textsecondary mb-2">ชื่อจริง</label>
                                    <input type="text" name="firstname"
                                        value="<?= htmlspecialchars($_SESSION['user']['firstname'] ?? '') ?>"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-lg font-primaryRegular focus:outline-none focus:border-textorange transition placeholder-gray-300">
                                </div>
                                <div class="w-full md:w-1/2">
                                    <label class="block text-sm font-primaryMedium text-textsecondary mb-2">นามสกุล</label>
                                    <input type="text" name="lastname"
                                        value="<?= htmlspecialchars($_SESSION['user']['lastname'] ?? '') ?>"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-lg font-primaryRegular focus:outline-none focus:border-textorange transition placeholder-gray-300">
                                </div>
                            </div>

                            <hr class="border-gray-200 mt-6">

                            <?php if ($hasPassword): ?>
                                <h3 class="text-xl font-primaryBold text-textprimary mt-6">
                                    ชื่อผู้ใช้และรหัสผ่าน
                                </h3>

                                <div class="mt-5 w-full">
                                    <label class="block text-sm font-primaryMedium text-textsecondary mb-2">ชื่อผู้ใช้</label>
                                    <input type="text" name="username"
                                        value="<?= htmlspecialchars($_SESSION['user']['username'] ?? '') ?>"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-lg font-primaryRegular focus:outline-none focus:border-textorange transition placeholder-gray-300">
                                </div>
                            <?php endif; ?>

                            <?php if ($hasPassword): ?>
                                <div class="flex flex-col md:flex-row gap-6 mt-5 w-full">
                                    <div class="w-full md:w-1/2">
                                        <label
                                            class="block text-sm font-primaryMedium text-textsecondary mb-2">รหัสผ่านปัจจุบัน</label>
                                        <div style="position: relative;">
                                            <input type="password" name="current_password" id="currentPasswordInput"
                                                class="w-full px-4 py-3 border <?= isset($_SESSION['field_errors']['current_password']) ? 'border-red-500' : 'border-gray-200' ?> rounded-lg font-primaryRegular focus:outline-none focus:border-textorange transition pr-10">
                                            <button type="button"
                                                onclick="togglePassword('currentPasswordInput', 'currentPasswordIcon')"
                                                style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #9CA3AF; background: none; border: none; cursor: pointer;">
                                                <i class="fa-regular fa-eye" id="currentPasswordIcon"></i>
                                            </button>
                                        </div>
                                        <?php if (isset($_SESSION['field_errors']['current_password'])): ?>
                                            <p class="text-red-500 text-sm mt-1 font-primaryRegular">
                                                <?= $_SESSION['field_errors']['current_password'] ?>
                                            </p>
                                            <?php unset($_SESSION['field_errors']['current_password']); ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="w-full md:w-1/2">
                                        <label
                                            class="block text-sm font-primaryMedium text-textsecondary mb-2">รหัสผ่านใหม่</label>
                                        <div style="position: relative;">
                                            <input type="password" name="new_password" id="newPasswordInput"
                                                class="w-full px-4 py-3 border <?= isset($_SESSION['field_errors']['new_password']) ? 'border-red-500' : 'border-gray-200' ?> rounded-lg font-primaryRegular focus:outline-none focus:border-textorange transition pr-10">
                                            <button type="button"
                                                onclick="togglePassword('newPasswordInput', 'newPasswordIcon')"
                                                style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #9CA3AF; background: none; border: none; cursor: pointer;">
                                                <i class="fa-regular fa-eye" id="newPasswordIcon"></i>
                                            </button>
                                        </div>
                                        <?php if (isset($_SESSION['field_errors']['new_password'])): ?>
                                            <p class="text-red-500 text-sm mt-1 font-primaryRegular">
                                                <?= $_SESSION['field_errors']['new_password'] ?>
                                            </p>
                                            <?php unset($_SESSION['field_errors']['new_password']); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="flex justify-end mt-5">
                                <button type="submit"
                                    class="bg-textorange text-white px-6 py-3 rounded-lg font-primaryMedium hover:bg-orange-600 transition shadow-sm">
                                    บันทึกข้อมูล
                                </button>
                            </div>
                        </form>
                    </div>

                <?php elseif ($_GET['tab'] === 'address'): ?>
                    <h2 class="text-3xl font-primaryBold text-textprimary mb-6">ที่อยู่</h2>
                    <hr class="border-gray-200">

                    <div class="mt-6">

                        <form action="update-address" method="POST">
                            <div class="space-y-8">
                                <div class="w-full">
                                    <label class="block text-sm font-primaryMedium text-textsecondary mb-2">ที่อยู่
                                        (บ้านเลขที่,
                                        ซอย, ถนน) <span class="text-red-500">*</span></label>
                                    <input type="text" name="address" id="address" required
                                        value="<?= htmlspecialchars($_SESSION['user']['address'] ?? '') ?>"
                                        placeholder="เช่น 123/45 ซอยสุขุมวิท 55 ถนนสุขุมวิท"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-lg font-primaryRegular focus:outline-none focus:border-textorange transition placeholder-gray-300">
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="w-full">
                                        <label class="block text-sm font-primaryMedium text-textsecondary mb-2">แขวง /
                                            ตำบล <span class="text-red-500">*</span></label>
                                        <input type="text" name="city" id="district" required
                                            value="<?= htmlspecialchars($_SESSION['user']['city'] ?? '') ?>"
                                            placeholder="เช่น คลองตันเหนือ"
                                            class="w-full px-4 py-3 border border-gray-200 rounded-lg font-primaryRegular focus:outline-none focus:border-textorange transition placeholder-gray-300">
                                    </div>
                                    <div class="w-full">
                                        <label class="block text-sm font-primaryMedium text-textsecondary mb-2">เขต /
                                            อำเภอ <span class="text-red-500">*</span></label>
                                        <input type="text" name="amphoe" id="amphoe" required
                                            value="<?= htmlspecialchars($_SESSION['user']['amphoe'] ?? '') ?>"
                                            placeholder="เช่น วัฒนา"
                                            class="w-full px-4 py-3 border border-gray-200 rounded-lg font-primaryRegular focus:outline-none focus:border-textorange transition placeholder-gray-300">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="w-full">
                                        <label class="block text-sm font-primaryMedium text-textsecondary mb-2">จังหวัด
                                            <span class="text-red-500">*</span></label>
                                        <input type="text" name="province" id="province" required
                                            value="<?= htmlspecialchars($_SESSION['user']['province'] ?? '') ?>"
                                            placeholder="เช่น กรุงเทพมหานคร"
                                            class="w-full px-4 py-3 border border-gray-200 rounded-lg font-primaryRegular focus:outline-none focus:border-textorange transition placeholder-gray-300">
                                    </div>
                                    <div class="w-full">
                                        <label class="block text-sm font-primaryMedium text-textsecondary mb-2">รหัสไปรษณีย์
                                            <span class="text-red-500">*</span></label>
                                        <input type="text" name="postal_code" id="zipcode" required
                                            value="<?= htmlspecialchars($_SESSION['user']['postal_code'] ?? '') ?>"
                                            placeholder="เช่น 10110"
                                            class="w-full px-4 py-3 border border-gray-200 rounded-lg font-primaryRegular focus:outline-none focus:border-textorange transition placeholder-gray-300">
                                    </div>
                                </div>

                                <div class="w-full">
                                    <label class="block text-sm font-primaryMedium text-textsecondary mb-2">เบอร์โทรศัพท์
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" name="phone" maxlength="10" required
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                        value="<?= htmlspecialchars($_SESSION['user']['phone'] ?? '') ?>"
                                        placeholder="เช่น 0812345678"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-lg font-primaryRegular focus:outline-none focus:border-textorange transition placeholder-gray-300">
                                </div>
                            </div>

                            <div class="flex justify-end mt-6">
                                <button type="submit"
                                    class="bg-textorange text-white px-10 py-3 rounded-lg font-primaryMedium hover:bg-orange-600 transition shadow-sm">
                                    บันทึกข้อมูล
                                </button>
                            </div>
                        </form>
                    </div>


                    <!-- Scripts for jquery.Thailand.js -->
                    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
                    <script type="text/javascript"
                        src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dependencies/JQL.min.js"></script>
                    <script type="text/javascript"
                        src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dependencies/typeahead.bundle.js"></script>
                    <link rel="stylesheet"
                        href="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.css">
                    <script type="text/javascript"
                        src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.js"></script>

                    <script>
                        // Check if jQuery is loaded
                        if (typeof jQuery !== 'undefined') {
                            $.Thailand({
                                $district: $('#district'), // input ของตำบล
                                $amphoe: $('#amphoe'), // input ของอำเภอ
                                $province: $('#province'), // input ของจังหวัด
                                $zipcode: $('#zipcode'), // input ของรหัสไปรษณีย์
                            });
                        } else {
                            console.error('jQuery not loaded');
                        }
                    </script>

                    <style>
                        .tt-menu {
                            max-height: 250px;
                            overflow-y: auto;
                            background-color: white;
                            border: 1px solid #e5e7eb;
                            border-radius: 0.5rem;
                            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                            width: 100%;
                            z-index: 1000 !important;
                            /* Ensure it stays on top */
                        }

                        .tt-suggestion {
                            padding: 0.75rem 1rem;
                            cursor: pointer;
                            font-family: 'Outfit Medium', sans-serif;
                            font-size: 0.875rem;
                            color: #374151;
                        }

                        .tt-suggestion:hover {
                            background-color: #f3f4f6;
                            color: #FF5D02;
                        }
                    </style>
                <?php elseif (isset($_GET['tab']) && $_GET['tab'] === 'payment' && isset($_SESSION['user']['role_id']) && $_SESSION['user']['role_id'] != 1): ?>
                    <div x-data="paymentSettings()" class="max-w-5xl mx-auto">

                        <!-- Header -->
                        <div class="mb-8">
                            <h2 class="text-2xl font-primaryBold text-textprimary mb-2">บัญชีรับเงิน</h2>
                            <p class="text-textsecondary">ตั้งค่าบัญชีพร้อมเพย์สำหรับรับเงินจากการขายผลงาน</p>
                        </div>

                        <!-- Content Flex Container (Matches Admin UI) -->
                        <div style="display: flex; flex-wrap: wrap; gap: 2rem;">

                            <!-- Info Card (Left Side) -->
                            <div style="flex: 1; min-width: 300px;">
                                <div class="p-6 rounded-2xl border border-orange-100 bg-white shadow-sm"
                                    style="position: sticky; top: 6rem;">

                                    <!-- Dynamic QR Code Preview -->
                                    <div class="relative w-full bg-gray-50 rounded-2xl border border-orange-100 flex items-center justify-center mb-6 overflow-hidden group"
                                        style="aspect-ratio: 1/1;">
                                        <template x-if="form.promptpay_number && form.promptpay_number.length >= 10">
                                            <img :src="'https://promptpay.io/' + form.promptpay_number" alt="PromptPay QR"
                                                class="w-full h-full object-contain p-4 transition-transform duration-300 group-hover:scale-105">
                                        </template>
                                        <template x-if="!form.promptpay_number || form.promptpay_number.length < 10">
                                            <div class="flex flex-col items-center text-gray-300">
                                                <i class="fa-solid fa-qrcode text-6xl mb-3 opacity-30"></i>
                                                <span class="text-sm font-primaryMedium text-gray-400">ตัวอย่าง QR
                                                    Code</span>
                                            </div>
                                        </template>

                                        <!-- Official Badge -->
                                        <div
                                            class="absolute bottom-3 right-3 bg-[#003D6B] text-white text-[10px] px-3 py-1 rounded-md shadow-md opacity-90 font-primaryBold uppercase tracking-wide">
                                            PromptPay
                                        </div>
                                    </div>

                                    <h3 class="text-xl font-primaryBold text-textprimary mb-3">บัญชีรับเงิน</h3>
                                    <p class="text-sm text-textsecondary leading-relaxed mb-6 font-primaryMedium">
                                        ข้อมูลนี้จะแสดงให้ผู้ชำระเงินเห็นเมื่อต้องโอนเงินค่าภาพวาดให้คุณ
                                    </p>

                                    <!-- PromptPay Badge -->
                                    <div class="flex items-center gap-4 p-4 bg-[#F2F9FF] rounded-xl border border-blue-100">
                                        <div
                                            style="width: 48px; height: 48px; background-color: #003D6B; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                                            <i class="fa-solid fa-qrcode text-white text-xl"></i>
                                        </div>
                                        <div>
                                            <span class="block font-primaryBold text-[#003D6B] text-base">PromptPay</span>
                                            <span class="text-xs text-[#003D6B] opacity-70 font-primaryMedium">Official
                                                Payment Standard</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Form (Right Side) -->
                            <div style="flex: 2; min-width: 300px;">
                                <div
                                    class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 relative overflow-hidden">
                                    <div
                                        class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-[#FF7B00] to-[#FF4500]">
                                    </div>

                                    <div class="space-y-8">
                                        <!-- Header Form -->
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-lg font-primaryBold text-textprimary">รายละเอียดบัญชี</h3>
                                            <span
                                                class="px-3 py-1 bg-orange-50 text-textorange text-xs rounded-full font-primaryBold border border-orange-100">สำหรับรับเงิน</span>
                                        </div>

                                        <!-- PromptPay Input -->
                                        <div class="group">
                                            <label class="block text-sm font-primaryBold text-textprimary mb-2">
                                                เบอร์พร้อมเพย์ / เลขบัตรประชาชน <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative mt-2">
                                                <span class="absolute left-4 text-gray-400 pointer-events-none"
                                                    style="top: 50%; transform: translateY(-50%); z-index: 10;">
                                                    <i class="fa-solid fa-mobile-screen text-xl"></i>
                                                </span>
                                                <input type="text" x-model="form.promptpay_number"
                                                    style="height: 52px; padding-left: 3.5rem;"
                                                    class="w-full pr-4 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-textorange focus:ring-2 focus:ring-textorange focus:ring-offset-1 transition-all font-primaryBold text-xl tracking-wide text-textprimary placeholder-gray-300"
                                                    placeholder="08X-XXX-XXXX">
                                            </div>
                                            <p
                                                class="text-xs text-textsecondary mt-2 pl-1 flex items-center gap-1 opacity-70">
                                                <i class="fa-solid fa-circle-info"></i>
                                                กรอกเบอร์โทรศัพท์ 10 หลัก หรือเลขบัตรประชาชน 13 หลัก
                                            </p>
                                        </div>
                                    </div>

                                    <hr class="my-8 border-gray-100" style="border-style: dashed;">

                                    <button @click="saveSettings()"
                                        style="background: linear-gradient(90deg, #FF7B00 0%, #EA580C 100%); box-shadow: 0 4px 15px rgba(234, 88, 12, 0.3);"
                                        class="w-full mt-4 px-4 py-3 text-white rounded-xl font-primaryBold hover:-translate-y-0.5 active:scale-95 transition-all duration-200 flex items-center justify-center gap-3 text-lg">
                                        <i class="fa-solid fa-floppy-disk"></i>
                                        <span>บันทึกข้อมูล</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (isset($_SESSION['swal_success'])): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'สำเร็จ!',
            text: '<?= $_SESSION['swal_success'] ?>',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        });
    </script>
    <?php unset($_SESSION['swal_success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['swal_error'])): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'ผิดพลาด!',
            text: '<?= $_SESSION['swal_error'] ?>',
            confirmButtonColor: '#FF5D02',
            confirmButtonText: 'ตกลง'
        });
    </script>
    <?php unset($_SESSION['swal_error']); ?>
<?php endif; ?>

<script>
    function deleteProfilePicture() {
        Swal.fire({
            title: 'ยืนยันการลบ',
            text: 'คุณแน่ใจหรือไม่ที่จะลบรูปโปรไฟล์ของคุณ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#FF5D02',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'ลบ',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('delete-profile-picture', { method: 'POST' })
                    .then(response => response.text())
                    .then(text => {
                        try {
                            const data = JSON.parse(text);
                            if (data.success) {
                                window.location.reload();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'เกิดข้อผิดพลาด',
                                    text: data.message || 'ไม่สามารถลบรูปโปรไฟล์ได้',
                                    confirmButtonColor: '#FF5D02'
                                });
                            }
                        } catch (e) {
                            console.error('JSON parse error:', e);
                        }
                    })
                    .catch(error => console.error('Fetch error:', error));
            }
        });
    }

    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function paymentSettings() {
        return {
            form: {
                promptpay_number: '<?= htmlspecialchars($userPromptpay ?? '') ?>'
            },

            saveSettings() {
                Swal.fire({
                    title: 'ยืนยันการบันทึก?',
                    text: "ข้อมูลบัญชีรับเงินจะถูกอัปเดต",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ea580c',
                    cancelButtonColor: '#9ca3af',
                    confirmButtonText: 'บันทึก',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('/Artbid/public/update-payment', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(this.form)
                        })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'บันทึกสำเร็จ!',
                                        text: 'ข้อมูลบัญชีรับเงินถูกอัปเดตเรียบร้อยแล้ว',
                                        showConfirmButton: false,
                                        timer: 1500,
                                        timerProgressBar: true
                                    });
                                } else {
                                    Swal.fire('เกิดข้อผิดพลาด', data.message, 'error');
                                }
                            })
                            .catch(err => {
                                console.error('Fetch error:', err);
                                Swal.fire('Error', 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้', 'error');
                            });
                    }
                });
            }
        }
    }
</script>