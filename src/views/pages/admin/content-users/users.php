<?php
$roleLabels = [
    1 => 'ผู้ใช้ทั่วไป',
    2 => 'ผู้ขาย',
    3 => 'ผู้ดูแลระบบ'
];
?>

<div class="p-6" x-data="userManagement()">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-primaryBold text-textprimary">จัดการผู้ใช้</h2>
        <div class="flex items-center gap-4">
            <input type="text" x-model="search" placeholder="ค้นหาผู้ใช้..."
                class="px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-bgsecondary"
                @keyup="filterTable()">
            <button @click="openAddUserModal()"
                class="flex items-center gap-2 px-4 py-2 bg-bgsecondary text-white rounded-lg hover:bg-secondary-hover transition shadow-sm font-primarySemibold">
                <i class="fa-solid fa-user-plus"></i> เพิ่มผู้ใช้
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left" id="usersTable">
            <thead class="bg-gray-50">
                <tr>
                    <th class="pl-4 pr-2 py-2 text-sm font-primarySemibold text-textsecondary text-left">ข้อมูลผู้ใช้
                    </th>
                    <th class="px-2 py-2 text-sm font-primarySemibold text-textsecondary w-32 text-center">Role</th>
                    <th class="px-2 py-2 text-sm font-primarySemibold text-textsecondary w-32 text-center">สถานะ</th>
                    <th class="px-2 py-2 text-sm font-primarySemibold text-textsecondary w-auto text-center">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($users as $user): ?>
                    <?php
                    $firstname = $user['firstname'] ?? '';
                    $lastname = $user['lastname'] ?? '';
                    $username = $user['username'] ?? '';
                    $email = $user['email'] ?? '';
                    $initials = mb_substr($firstname, 0, 1) . mb_substr($lastname, 0, 1);
                    $hasImg = !empty($user['picture']);
                    $roleId = $user['role_id'] ?? 1;
                    $roleLabel = $roleLabels[$roleId] ?? 'ไม่ระบุ';
                    $roleBadgeClass = match ($roleId) {
                        3 => 'bg-red-100 text-red-600',
                        2 => 'bg-blue-100 text-blue-600',
                        default => 'bg-gray-100 text-gray-600'
                    };

                    // Searchable text
                    $searchText = strtolower($firstname . ' ' . $lastname . ' ' . $username . ' ' . $email);
                    ?>
                    <tr class="hover:bg-gray-50 transition-colors" data-search="<?= htmlspecialchars($searchText) ?>">
                        <td class="pl-4 pr-2 py-2">
                            <div class="flex items-center gap-3">
                                <?php if ($hasImg): ?>
                                    <img src="<?= htmlspecialchars($user['picture']) ?>" alt="Profile"
                                        class="w-10 h-10 rounded-full object-cover shrink-0">
                                <?php else: ?>
                                    <div
                                        class="w-10 h-10 rounded-full bg-bgsecondary text-white flex items-center justify-center font-primarySemibold shrink-0">
                                        <?= htmlspecialchars(strtoupper($initials)) ?>
                                    </div>
                                <?php endif; ?>
                                <span class="font-primaryMedium text-textprimary whitespace-nowrap">
                                    <?= htmlspecialchars($firstname . ' ' . $lastname) ?>
                                </span>
                            </div>
                        </td>
                        <td class="px-2 py-2 text-center">
                            <span class="px-2 py-1 rounded-full text-xs font-primaryMedium <?= $roleBadgeClass ?>">
                                <?= $roleLabel ?>
                            </span>
                        </td>
                        <td class="px-2 py-2 text-center">
                            <?php if ($user['verifyemail']): ?>
                                <span class="text-green-500"><i class="fa-solid fa-check-circle"></i> ยืนยันแล้ว</span>
                            <?php else: ?>
                                <span class="text-gray-400"><i class="fa-solid fa-clock"></i> รอยืนยัน</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-2 py-2 text-center">
                            <div class="flex justify-center gap-2">
                                <button type="button" @click="viewUserDetails('<?= $user['id'] ?>')"
                                    class="w-auto px-4 py-2 text-white rounded-lg text-sm hover:opacity-90 transition whitespace-nowrap"
                                    style="background-color: #3B82F6;">
                                    <i class="fa-solid fa-eye"></i> ดูข้อมูลทั้งหมด
                                </button>
                                <button @click="openRoleModal('<?= $user['id'] ?>', <?= $roleId ?>)"
                                    class="w-auto px-4 py-2 bg-bgsecondary text-white rounded-lg text-sm hover:bg-secondary-hover transition whitespace-nowrap">
                                    <i class="fa-solid fa-user-gear"></i> Role
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if (empty($users)): ?>
        <div class="text-center py-10 text-textsecondary">
            ไม่พบข้อมูลผู้ใช้
        </div>
    <?php endif; ?>

    <!-- User Details Modal (Alpine.js) -->
    <div x-show="detailsModalOpen" x-transition.opacity
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        style="display: none; background-color: rgba(0, 0, 0, 0.5);">
        <div @click.away="detailsModalOpen = false"
            class="bg-white rounded-xl p-0 w-full mx-4 max-h-[90vh] overflow-y-auto"
            style="max-width: 42rem; width: 100%;">

            <!-- Modal Header -->
            <div class="relative mb-6">
                <button @click="detailsModalOpen = false"
                    class="absolute z-20 text-gray-500 hover:text-gray-700 bg-white/50 rounded-full p-2 hover:bg-white transition"
                    style="top: 1rem; right: 1rem;">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>

                <div class="rounded-t-xl"
                    style="height: 8rem; background: linear-gradient(to right, #f1f5f9, #e2e8f0);"></div>

                <div class="px-6 relative flex items-center justify-between" style="margin-top: -5.5rem;">
                    <div class="flex items-center gap-5">
                        <div style="position: relative; z-index: 10;">
                            <template x-if="selectedUser.picture">
                                <img :src="selectedUser.picture"
                                    class="rounded-full border-4 border-white object-cover shadow-sm bg-white"
                                    style="width: 8rem; height: 8rem;">
                            </template>
                            <template x-if="!selectedUser.picture">
                                <div class="rounded-full border-4 border-white bg-bgsecondary text-white flex items-center justify-center font-primaryBold shadow-sm"
                                    style="width: 8rem; height: 8rem; font-size: 3rem;">
                                    <span x-text="getInitials(selectedUser.firstname, selectedUser.lastname)"></span>
                                </div>
                            </template>
                        </div>

                        <div class="mb-3" style="padding-bottom: 0.5rem;">
                            <h4 class="text-2xl font-primaryBold text-textprimary"
                                x-text="(selectedUser.firstname || '') + ' ' + (selectedUser.lastname || '')"></h4>
                            <p class="text-textsecondary text-sm" x-text="selectedUser.email || '-'"></p>
                        </div>
                    </div>

                    <div class="text-right">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-primaryMedium" :style="selectedUser.role_id == 3 ? 'background-color: #fee2e2; color: #dc2626;' : 
                                      (selectedUser.role_id == 2 ? 'background-color: #dbeafe; color: #2563eb;' : 
                                      'background-color: #e0e7ff; color: #4f46e5;')"
                            x-text="getRoleLabel(selectedUser.role_id)">
                        </span>
                        <p class="text-xs text-textsecondary mt-2 mb-4">สมัครเมื่อ <span
                                x-text="formatDate(selectedUser.create_at)"></span></p>
                    </div>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 px-8 pb-8">
                <!-- Account Info -->
                <div class="rounded-xl p-5 h-full" style="background-color: #F9FAFB; border: 1px solid #E5E7EB;">
                    <h5 class="font-primaryBold text-textprimary mb-4 flex items-center gap-2 text-base">
                        <i class="fa-solid fa-user-shield text-textorange"></i> ข้อมูลบัญชี
                    </h5>
                    <div class="space-y-3">
                        <div class="grid grid-cols-5 gap-4 items-center border-b border-gray-200 pb-2">
                            <span class="col-span-2 text-sm text-textsecondary font-primaryMedium">ชื่อผู้ใช้</span>
                            <span
                                class="col-span-3 font-primarySemibold text-textprimary text-base text-right break-words"
                                x-text="selectedUser.username || '-'"></span>
                        </div>
                        <div class="grid grid-cols-5 gap-4 items-center border-b border-gray-200 pb-2">
                            <span class="col-span-2 text-sm text-textsecondary font-primaryMedium">สถานะอีเมล</span>
                            <div class="col-span-3 text-right">
                                <span class="inline-flex items-center gap-1 font-primarySemibold text-sm"
                                    :class="selectedUser.verifyemail ? 'text-green-600' : 'text-slate-600'">
                                    <span
                                        x-html="selectedUser.verifyemail ? '<i class=\'fa-solid fa-check-circle\'></i> ยืนยันแล้ว' : '<i class=\'fa-solid fa-clock\'></i> รอยืนยัน'"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Info -->
                <div class="rounded-xl p-5 h-full" style="background-color: #F9FAFB; border: 1px solid #E5E7EB;">
                    <h5 class="font-primaryBold text-textprimary mb-4 flex items-center gap-2 text-base">
                        <i class="fa-solid fa-location-dot text-bgsecondary"></i> ข้อมูลติดต่อ & ที่อยู่
                    </h5>
                    <div class="space-y-4">
                        <!-- Phone Number Moved Here -->
                        <div>
                            <p class="text-xs text-textsecondary mb-1">เบอร์โทรศัพท์</p>
                            <p class="font-primarySemibold text-textprimary text-base"
                                x-text="selectedUser.phone || '-'"></p>
                        </div>

                        <!-- Address -->
                        <div>
                            <p class="text-xs text-textsecondary mb-1">ที่อยู่จัดส่ง</p>
                            <p class="font-primaryMedium text-textprimary text-base leading-relaxed"
                                x-text="selectedUser.address || '-'"></p>
                            <p class="text-textsecondary text-sm mt-1">
                                <span x-text="selectedUser.amphoe || '-'"></span> <span
                                    x-text="selectedUser.city ? ', ' + selectedUser.city : '-'"></span>
                            </p>
                            <p class="text-textsecondary text-sm">
                                <span x-text="selectedUser.province || '-'"></span> <span
                                    x-text="selectedUser.postal_code || '-'"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Role Change Modal (Alpine.js) -->
    <div x-show="roleModalOpen" x-transition.opacity
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        style="display: none; background-color: rgba(0, 0, 0, 0.5);">
        <div @click.away="roleModalOpen = false" class="bg-white rounded-xl p-0 w-full mx-4 overflow-hidden"
            style="max-width: 32rem; width: 100%;">

            <!-- Modal Header -->
            <div class="relative">
                <button @click="roleModalOpen = false"
                    class="absolute z-20 text-gray-500 hover:text-gray-700 bg-white/50 rounded-full p-2 hover:bg-white transition"
                    style="top: 1.5rem; right: 1.5rem;">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>

                <div class="rounded-t-xl px-8 py-8" style="background: linear-gradient(to right, #f1f5f9, #e2e8f0);">
                    <div class="flex items-center gap-5">
                        <div
                            class="w-16 h-16 rounded-full bg-bgsecondary text-white flex items-center justify-center shadow-md border-4 border-white">
                            <i class="fa-solid fa-user-gear text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-primaryBold text-textprimary">เปลี่ยนสิทธิ์ผู้ใช้</h3>
                            <p class="text-textsecondary text-sm mt-1">กำหนดสิทธิ์การใช้งานระบบ</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="p-4">
                <!-- Role Selection Card -->
                <div class="rounded-xl p-6" style="background-color: #F9FAFB; border: 1px solid #E5E7EB;">
                    <h5 class="font-primaryBold text-textprimary mb-4 flex items-center gap-2 text-base">
                        <i class="fa-solid fa-users-gear text-bgsecondary"></i> เลือก Role
                    </h5>

                    <div class="space-y-3">
                        <!-- ผู้ใช้ทั่วไป -->
                        <label
                            class="group flex items-center gap-3 p-3 rounded-lg cursor-pointer transition-all duration-200"
                            :class="selectedRole == 1 ? 'bg-gray-200' : 'hover:bg-gray-100'">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center transition-colors"
                                :class="selectedRole == 1 ? 'bg-gray-600' : 'bg-gray-300'">
                                <i class="fa-solid fa-user"
                                    :class="selectedRole == 1 ? 'text-white' : 'text-gray-600'"></i>
                            </div>
                            <input type="radio" name="roleSelect" value="1" x-model="selectedRole" class="hidden">
                            <div class="flex-1">
                                <span class="block font-primarySemibold text-textprimary">ผู้ใช้ทั่วไป</span>
                                <span class="text-xs text-textsecondary">ประมูลและซื้อผลงานศิลปะ</span>
                            </div>
                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                                :class="selectedRole == 1 ? 'border-gray-600 bg-gray-600' : 'border-gray-300'">
                                <i class="fa-solid fa-check text-white text-xs" x-show="selectedRole == 1"></i>
                            </div>
                        </label>

                        <!-- ผู้ขาย -->
                        <label
                            class="group flex items-center gap-3 p-3 rounded-lg cursor-pointer transition-all duration-200"
                            :class="selectedRole == 2 ? 'bg-blue-100' : 'hover:bg-gray-100'">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center transition-colors"
                                :class="selectedRole == 2 ? 'bg-blue-500' : 'bg-blue-200'">
                                <i class="fa-solid fa-store"
                                    :class="selectedRole == 2 ? 'text-white' : 'text-blue-600'"></i>
                            </div>
                            <input type="radio" name="roleSelect" value="2" x-model="selectedRole" class="hidden">
                            <div class="flex-1">
                                <span class="block font-primarySemibold text-textprimary">ผู้ขาย</span>
                                <span class="text-xs text-textsecondary">ลงขายและจัดการผลงานศิลปะ</span>
                            </div>
                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                                :class="selectedRole == 2 ? 'border-blue-500 bg-blue-500' : 'border-gray-300'">
                                <i class="fa-solid fa-check text-white text-xs" x-show="selectedRole == 2"></i>
                            </div>
                        </label>

                        <!-- ผู้ดูแลระบบ -->
                        <label
                            class="group flex items-center gap-3 p-3 rounded-lg cursor-pointer transition-all duration-200"
                            :class="selectedRole == 3 ? 'bg-red-100' : 'hover:bg-gray-100'">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center transition-colors"
                                :class="selectedRole == 3 ? 'bg-red-500' : 'bg-red-200'">
                                <i class="fa-solid fa-shield-halved"
                                    :class="selectedRole == 3 ? 'text-white' : 'text-red-600'"></i>
                            </div>
                            <input type="radio" name="roleSelect" value="3" x-model="selectedRole" class="hidden">
                            <div class="flex-1">
                                <span class="block font-primarySemibold text-textprimary">ผู้ดูแลระบบ</span>
                                <span class="text-xs text-textsecondary">จัดการระบบและผู้ใช้ทั้งหมด</span>
                            </div>
                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                                :class="selectedRole == 3 ? 'border-red-500 bg-red-500' : 'border-gray-300'">
                                <i class="fa-solid fa-check text-white text-xs" x-show="selectedRole == 3"></i>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="flex gap-3 mt-6">
                    <button @click="roleModalOpen = false"
                        class="flex-1 px-4 py-3 border border-gray-200 rounded-xl font-primarySemibold text-textsecondary hover:bg-gray-50 hover:text-textprimary transition">
                        ยกเลิก
                    </button>
                    <button @click="saveRole()"
                        class="flex-1 px-4 py-3 bg-bgsecondary text-white rounded-xl font-primarySemibold hover:bg-secondary-hover transition">
                        <i class="fa-solid fa-check mr-2"></i>บันทึก
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Add User Modal (Alpine.js) -->
    <div x-show="addUserModalOpen" x-transition.opacity
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        style="display: none; background-color: rgba(0, 0, 0, 0.5);">
        <div @click.away="addUserModalOpen = false" class="bg-white rounded-xl p-0 w-full mx-4 overflow-hidden"
            style="max-width: 40rem; width: 100%;">

            <!-- Modal Header -->
            <div class="relative">
                <button @click="addUserModalOpen = false"
                    class="absolute z-20 text-gray-500 hover:text-gray-700 bg-white/50 rounded-full p-2 hover:bg-white transition"
                    style="top: 1.5rem; right: 1.5rem;">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>

                <div class="rounded-t-xl px-8 py-8" style="background: linear-gradient(to right, #f1f5f9, #e2e8f0);">
                    <div class="flex items-center gap-5">
                        <div
                            class="w-16 h-16 rounded-full bg-bgsecondary text-white flex items-center justify-center shadow-md border-4 border-white">
                            <i class="fa-solid fa-user-plus text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-primaryBold text-textprimary">เพิ่มผู้ใช้ใหม่</h3>
                            <p class="text-textsecondary text-sm mt-1">กรอกข้อมูลเพื่อสร้างบัญชีผู้ใช้</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Content (Form) -->
            <div class="p-8">
                <form @submit.prevent="saveNewUser">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Firstname -->
                        <div>
                            <label class="block text-sm font-primaryBold text-textprimary mb-2">ชื่อจริง <span
                                    class="text-red-500">*</span></label>
                            <input type="text" x-model="newUser.firstname" required
                                class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-bgsecondary">
                        </div>
                        <!-- Lastname -->
                        <div>
                            <label class="block text-sm font-primaryBold text-textprimary mb-2">นามสกุล <span
                                    class="text-red-500">*</span></label>
                            <input type="text" x-model="newUser.lastname" required
                                class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-bgsecondary">
                        </div>
                        <!-- Username -->
                        <div>
                            <label class="block text-sm font-primaryBold text-textprimary mb-2">ชื่อผู้ใช้ <span
                                    class="text-red-500">*</span></label>
                            <input type="text" x-model="newUser.username" required
                                class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-bgsecondary">
                        </div>
                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-primaryBold text-textprimary mb-2">อีเมล <span
                                    class="text-red-500">*</span></label>
                            <input type="email" x-model="newUser.email" required
                                class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-bgsecondary">
                        </div>
                        <!-- Password -->
                        <div>
                            <label class="block text-sm font-primaryBold text-textprimary mb-2">รหัสผ่าน <span
                                    class="text-red-500">*</span></label>
                            <input type="password" x-model="newUser.password" required
                                class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-bgsecondary">
                        </div>
                        <!-- Role -->
                        <div>
                            <label class="block text-sm font-primaryBold text-textprimary mb-2">สิทธิ์การใช้งาน</label>
                            <select x-model="newUser.role_id"
                                class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-bgsecondary">
                                <option value="1">ผู้ใช้ทั่วไป</option>
                                <option value="2">ผู้ขาย</option>
                                <option value="3">ผู้ดูแลระบบ</option>
                            </select>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="flex gap-3 mt-8 pt-6 border-t border-gray-100">
                        <button type="button" @click="addUserModalOpen = false"
                            class="flex-1 px-4 py-3 border border-gray-200 rounded-xl font-primarySemibold text-textsecondary hover:bg-gray-50 hover:text-textprimary transition">
                            ยกเลิก
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-3 bg-bgsecondary text-white rounded-xl font-primarySemibold hover:bg-secondary-hover transition shadow-lg shadow-blue-900/20">
                            <i class="fa-solid fa-save mr-2"></i>บันทึกข้อมูล
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function userManagement() {
        return {
            search: '',
            detailsModalOpen: false,
            roleModalOpen: false,
            addUserModalOpen: false,
            selectedUser: {},
            selectedRoleUser: null,
            selectedRole: 1,
            newUser: {
                firstname: '',
                lastname: '',
                username: '',
                email: '',
                password: '',
                role_id: 1
            },

            init() {
            },

            filterTable() {
                const input = this.search.toLowerCase();
                const rows = document.querySelectorAll('#usersTable tbody tr');
                rows.forEach(row => {
                    const searchText = row.getAttribute('data-search') || '';
                    row.style.display = searchText.includes(input) ? '' : 'none';
                });
            },

            viewUserDetails(userId) {
                fetch(`/Artbid/public/admin/user-details?id=${userId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.selectedUser = data.user;
                            this.detailsModalOpen = true;
                        } else {
                            Swal.fire('Error', data.message || 'Error fetching user', 'error');
                        }
                    })
                    .catch(err => Swal.fire('Error', 'Connection error', 'error'));
            },

            openRoleModal(userId, currentRole) {
                this.selectedRoleUser = userId;
                this.selectedRole = currentRole;
                this.roleModalOpen = true;
            },

            openAddUserModal() {
                this.newUser = {
                    firstname: '',
                    lastname: '',
                    username: '',
                    email: '',
                    password: '',
                    role_id: 1
                };
                this.addUserModalOpen = true;
            },

            saveNewUser() {
                // Basic Validation
                if (!this.newUser.firstname || !this.newUser.lastname || !this.newUser.username || !this.newUser.email || !this.newUser.password) {
                    Swal.fire('ข้อผิดพลาด', 'กรุณากรอกข้อมูลให้ครบทุกช่อง', 'warning');
                    return;
                }

                // Show Loading
                Swal.fire({
                    title: 'กำลังบันทึก...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                fetch('/Artbid/public/admin/create-user', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(this.newUser)
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.addUserModalOpen = false;
                            Swal.fire({
                                icon: 'success',
                                title: 'สำเร็จ!',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 1500,
                                timerProgressBar: true
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('เกิดข้อผิดพลาด', data.message, 'error');
                        }
                    })
                    .catch(err => {
                        console.error('Error:', err);
                        Swal.fire('Error', 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้', 'error');
                    });
            },

            saveRole() {
                if (!this.selectedRoleUser) return;

                fetch('/Artbid/public/admin/update-role', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ user_id: this.selectedRoleUser, role_id: parseInt(this.selectedRole) })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.roleModalOpen = false;
                            Swal.fire({
                                icon: 'success',
                                title: 'สำเร็จ!',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 1500,
                                timerProgressBar: true
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    })
                    .catch(err => Swal.fire('Error', 'Connection error', 'error'));
            },

            getInitials(first, last) {
                return ((first?.charAt(0) || '') + (last?.charAt(0) || '')).toUpperCase();
            },

            getRoleLabel(roleId) {
                return roleId == 3 ? 'ผู้ดูแลระบบ' : (roleId == 2 ? 'ผู้ขาย' : 'ผู้ใช้ทั่วไป');
            },

            formatDate(dateString) {
                if (!dateString) return '-';
                return new Date(dateString).toLocaleDateString('th-TH');
            }
        }
    }
</script>