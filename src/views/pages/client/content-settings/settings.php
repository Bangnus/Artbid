<div class="min-h-screen py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">

        <!-- FORCE LAYOUT STRUCTURE AS REQUESTED -->
        <div class="flex gap-6 w-full">

            <!-- A: MENU (20%) -->
            <div style="width: 20%;" class="flex-shrink-0 h-fit sticky top-10 bg-white rounded-xl shadow-sm p-3 md:p-4">
                <nav class="space-y-2">
                    <a href="settings"
                        class="block px-6 py-8 rounded-md font-primaryBold text-lg transition-all md:text-left
                        <?= !isset($_GET['tab']) || $_GET['tab'] === 'account' ? 'bg-bgprimary text-white shadow-sm' : 'text-textsecondary hover:bg-bgprimary hover:text-white' ?>">
                        บัญชี
                    </a>
                    <a href="settings?tab=address"
                        class="block px-6 py-8 rounded-md font-primaryBold text-lg transition-all md:text-left
                        <?= isset($_GET['tab']) && $_GET['tab'] === 'address' ? 'bg-bgprimary text-white shadow-sm' : 'text-textsecondary hover:bg-bgprimary hover:text-white' ?>">
                        ที่อยู่
                    </a>
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
                                    <?= $_SESSION['field_errors']['profile_picture'] ?></p>
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

                            <h3 class="text-xl font-primaryBold text-textprimary mt-6">
                                <?= $hasPassword ? 'ชื่อผู้ใช้และรหัสผ่าน' : 'ชื่อผู้ใช้' ?>
                            </h3>

                            <div class="mt-5 w-full">
                                <label class="block text-sm font-primaryMedium text-textsecondary mb-2">ชื่อผู้ใช้</label>
                                <input type="text" name="username"
                                    value="<?= htmlspecialchars($_SESSION['user']['username'] ?? '') ?>"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-lg font-primaryRegular focus:outline-none focus:border-textorange transition placeholder-gray-300">
                            </div>

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
                                                <?= $_SESSION['field_errors']['current_password'] ?></p>
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
                                                <?= $_SESSION['field_errors']['new_password'] ?></p>
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
                                        ซอย, ถนน)</label>
                                    <input type="text" name="address" id="address"
                                        value="<?= htmlspecialchars($_SESSION['user']['address'] ?? '') ?>"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-lg font-primaryRegular focus:outline-none focus:border-textorange transition placeholder-gray-300">
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="w-full">
                                        <label class="block text-sm font-primaryMedium text-textsecondary mb-2">แขวง /
                                            ตำบล</label>
                                        <input type="text" name="city" id="district"
                                            value="<?= htmlspecialchars($_SESSION['user']['city'] ?? '') ?>"
                                            class="w-full px-4 py-3 border border-gray-200 rounded-lg font-primaryRegular focus:outline-none focus:border-textorange transition placeholder-gray-300">
                                    </div>
                                    <div class="w-full">
                                        <label class="block text-sm font-primaryMedium text-textsecondary mb-2">เขต /
                                            อำเภอ</label>
                                        <input type="text" name="amphoe" id="amphoe"
                                            value="<?= htmlspecialchars($_SESSION['user']['amphoe'] ?? '') ?>"
                                            class="w-full px-4 py-3 border border-gray-200 rounded-lg font-primaryRegular focus:outline-none focus:border-textorange transition placeholder-gray-300">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="w-full">
                                        <label
                                            class="block text-sm font-primaryMedium text-textsecondary mb-2">จังหวัด</label>
                                        <input type="text" name="province" id="province"
                                            value="<?= htmlspecialchars($_SESSION['user']['province'] ?? '') ?>"
                                            class="w-full px-4 py-3 border border-gray-200 rounded-lg font-primaryRegular focus:outline-none focus:border-textorange transition placeholder-gray-300">
                                    </div>
                                    <div class="w-full">
                                        <label
                                            class="block text-sm font-primaryMedium text-textsecondary mb-2">รหัสไปรษณีย์</label>
                                        <input type="text" name="postal_code" id="zipcode"
                                            value="<?= htmlspecialchars($_SESSION['user']['postal_code'] ?? '') ?>"
                                            class="w-full px-4 py-3 border border-gray-200 rounded-lg font-primaryRegular focus:outline-none focus:border-textorange transition placeholder-gray-300">
                                    </div>
                                </div>

                                <div class="w-full">
                                    <label
                                        class="block text-sm font-primaryMedium text-textsecondary mb-2">เบอร์โทรศัพท์</label>
                                    <input type="text" name="phone" maxlength="10"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                        value="<?= htmlspecialchars($_SESSION['user']['phone'] ?? '') ?>"
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
        if (confirm('คุณแน่ใจหรือไม่ที่จะลบรูปโปรไฟล์ของคุณ?')) {
            fetch('delete-profile-picture', { method: 'POST' })
                .then(response => response.json())
                .then(data => { if (data.success) location.reload(); })
                .catch(error => console.error('Error:', error));
        }
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
</script>