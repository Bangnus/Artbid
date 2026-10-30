<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="paymentManagement()">
    <!-- Loading Overlay -->
    <div x-show="reloading" x-transition.opacity
        class="fixed inset-0 z-[100] backdrop-blur-sm flex items-center justify-center">
        <?php include __DIR__ . '/../../../../components/content-loading/page-loading.php'; ?>
    </div>

    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-3xl font-primaryBold text-textprimary mb-2">จัดการรายการโอนเงิน</h2>
        <p class="text-textsecondary">ตรวจสอบและยืนยันการชำระเงินจากผู้ซื้อ แล้วโอนให้ผู้ขาย</p>
    </div>

    <!-- Status Tabs -->
    <div class="flex flex-wrap gap-2 mb-6">
        <button @click="filterStatus = 'all'"
            :class="filterStatus === 'all' ? 'bg-bgsecondary text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
            class="px-4 py-2 rounded-lg font-primaryMedium transition-all">
            ทั้งหมด
        </button>
        <button @click="filterStatus = 'pending_admin_verify'"
            :class="filterStatus === 'pending_admin_verify' ? 'bg-yellow-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
            class="px-4 py-2 rounded-lg font-primaryMedium transition-all">
            รอตรวจสอบ
        </button>
        <button @click="filterStatus = 'verified'"
            :class="filterStatus === 'verified' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
            class="px-4 py-2 rounded-lg font-primaryMedium transition-all">
            รอโอนให้ผู้ขาย
        </button>
        <button @click="filterStatus = 'completed'"
            :class="filterStatus === 'completed' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
            class="px-4 py-2 rounded-lg font-primaryMedium transition-all">
            เสร็จสิ้น
        </button>
        <button @click="filterStatus = 'rejected'"
            :class="filterStatus === 'rejected' ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
            class="px-4 py-2 rounded-lg font-primaryMedium transition-all">
            ปฏิเสธ
        </button>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-primaryBold text-gray-800 uppercase">ผลงาน</th>
                        <th class="px-4 py-3 text-left text-xs font-primaryBold text-gray-800 uppercase">ผู้ซื้อ</th>
                        <th class="px-4 py-3 text-left text-xs font-primaryBold text-gray-800 uppercase">ผู้ขาย</th>
                        <th class="px-4 py-3 text-center text-xs font-primaryBold text-gray-800 uppercase">จำนวนเงิน
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-primaryBold text-gray-800 uppercase">สถานะ</th>
                        <th class="px-4 py-3 text-center text-xs font-primaryBold text-gray-800 uppercase">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <template x-for="payment in filteredPayments" :key="payment.id">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <!-- Artwork -->
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="relative cursor-pointer" @click="previewImage = payment.artwork_image">
                                        <img :src="payment.artwork_image" class="w-10 h-10 rounded-lg object-cover"
                                            alt="">
                                    </div>
                                    <span class="font-primaryMedium text-textprimary line-clamp-1 max-w-[200px]"
                                        x-text="payment.artwork_title"></span>
                                </div>
                            </td>
                            <!-- Buyer -->
                            <td class="px-4 py-3">
                                <span class="font-primaryMedium text-textprimary"
                                    x-text="payment.buyer_firstname + ' ' + payment.buyer_lastname"></span>
                            </td>
                            <!-- Seller -->
                            <td class="px-4 py-3">
                                <span class="font-primaryMedium text-textprimary"
                                    x-text="payment.seller_firstname + ' ' + payment.seller_lastname"></span>
                            </td>
                            <!-- Amount -->
                            <td class="px-4 py-3 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="font-primaryBold text-bgsecondary"
                                        x-text="'฿' + Number(payment.amount).toLocaleString()"></span>
                                    <template x-if="payment.status === 'completed'">
                                        <span class="text-xs text-green-600 font-primaryMedium"
                                            x-text="'(สุทธิ: ฿' + Math.floor(Number(payment.amount) * 0.95).toLocaleString() + ')'"></span>
                                    </template>
                                </div>
                            </td>
                            <!-- Status -->
                            <td class="px-4 py-3 text-center">
                                <span :class="getStatusClass(payment.status)"
                                    class="px-3 py-1 rounded-full text-xs font-primaryBold"
                                    x-text="getStatusText(payment.status)"></span>
                            </td>
                            <!-- Actions -->
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    <button
                                        @click="payment.buyer_slip_image ? previewImage = payment.buyer_slip_image : Swal.fire('ไม่มีสลิป', 'รายการนี้ยังไม่มีการแนบสลิป', 'info')"
                                        class="p-2 text-gray-500 hover:text-bgsecondary hover:bg-slate-100 rounded-lg transition-all"
                                        title="ดูสลิป">
                                        <i class="fa-solid fa-receipt"></i>
                                    </button>

                                    <template x-if="payment.status === 'pending_admin_verify'">
                                        <button @click="verifyPayment(payment.id)"
                                            class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-all"
                                            title="ยืนยันรับเงิน">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                    </template>
                                    <template x-if="payment.status === 'verified'">
                                        <button @click="viewDetail(payment)"
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                            title="โอนเงินให้ผู้ขาย">
                                            <i class="fa-solid fa-money-bill-transfer"></i>
                                        </button>
                                    </template>
                                    <template x-if="payment.status === 'pending_admin_verify'">
                                        <button @click="rejectPayment(payment.id)"
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                            title="ปฏิเสธ">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-if="filteredPayments.length === 0">
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <i class="fa-solid fa-inbox text-4xl mb-3"></i>
                                <p class="font-primaryMedium">ไม่มีรายการ</p>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Detail Modal -->
    <div x-show="showModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4"
        style="display: none;">
        <div class="fixed inset-0 bg-black/50" @click="showModal = false"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="sticky top-0 bg-gradient-to-r from-[#00365D] to-[#005a9e] text-white p-6 rounded-t-2xl">
                <h3 class="text-xl font-primaryBold">รายละเอียดการชำระเงิน</h3>
                <button @click="showModal = false" class="absolute top-4 right-4 text-white/80 hover:text-white">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-6" x-show="selectedPayment">
                <!-- Artwork Info -->
                <div class="flex gap-4 p-4 bg-gray-50 rounded-xl">
                    <img :src="selectedPayment?.artwork_image" class="w-24 h-24 rounded-lg object-cover" alt="">
                    <div>
                        <h4 class="font-primaryBold text-textprimary text-lg" x-text="selectedPayment?.artwork_title">
                        </h4>
                        <p class="text-2xl font-primaryBold text-bgsecondary mt-2"
                            x-text="'฿' + Number(selectedPayment?.amount || 0).toLocaleString()"></p>
                    </div>
                </div>

                <!-- Buyer & Seller Info -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-blue-50 rounded-xl">
                        <p class="text-xs text-blue-600 font-primaryBold mb-1">ผู้ซื้อ</p>
                        <p class="font-primaryMedium text-textprimary"
                            x-text="selectedPayment?.buyer_firstname + ' ' + selectedPayment?.buyer_lastname"></p>
                        <p class="text-sm text-gray-500" x-text="selectedPayment?.buyer_email"></p>
                    </div>
                    <div class="p-4 bg-green-50 rounded-xl">
                        <p class="text-xs text-green-600 font-primaryBold mb-1">ผู้ขาย</p>
                        <p class="font-primaryMedium text-textprimary"
                            x-text="selectedPayment?.seller_firstname + ' ' + selectedPayment?.seller_lastname"></p>
                        <p class="text-sm text-gray-500" x-text="selectedPayment?.seller_email"></p>
                    </div>
                </div>

                <!-- Payment Slip -->
                <template x-if="selectedPayment?.buyer_slip_image && selectedPayment?.status !== 'verified'">
                    <div>
                        <p class="text-sm font-primaryBold text-gray-700 mb-2">สลิปโอนเงินจากผู้ซื้อ</p>
                        <img :src="selectedPayment?.buyer_slip_image"
                            class="h-48 w-auto object-contain rounded-xl cursor-pointer" style="max-height: 200px;"
                            @click="previewImage = selectedPayment?.buyer_slip_image" alt="Payment Slip">
                    </div>
                </template>

                <!-- Seller PromptPay QR (for admin to transfer) -->
                <template x-if="selectedPayment?.status === 'verified'">
                    <div class="p-4 bg-[#F2F9FF] rounded-xl border border-blue-100">
                        <p class="text-sm font-primaryBold text-[#003D6B] mb-3">โอนเงินให้ผู้ขาย</p>

                        <template x-if="selectedPayment?.seller_promptpay">
                            <div class="space-y-4">
                                <!-- QR Code -->
                                <div class="flex justify-center">
                                    <img :src="'https://promptpay.io/' + selectedPayment?.seller_promptpay + '/' + Math.floor(Number(selectedPayment?.amount || 0) * 0.95)"
                                        class="w-40 h-40 rounded-lg border border-gray-200" alt="Seller PromptPay">
                                </div>

                                <!-- Fee Calculation -->
                                <div class="bg-white rounded-lg p-4 space-y-2 border border-gray-100">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">ยอดขาย:</span>
                                        <span class="font-primaryMedium text-textprimary"
                                            x-text="'฿' + Number(selectedPayment?.amount || 0).toLocaleString()"></span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">หักค่าบริการ 5%:</span>
                                        <span class="font-primaryMedium text-red-500"
                                            x-text="'-฿' + Math.floor(Number(selectedPayment?.amount || 0) * 0.05).toLocaleString()"></span>
                                    </div>
                                    <div class="border-t pt-2 flex justify-between">
                                        <span class="font-primaryBold text-[#003D6B]">ยอดโอนสุทธิ:</span>
                                        <span class="font-primaryBold text-bgsecondary text-lg"
                                            x-text="'฿' + Math.floor(Number(selectedPayment?.amount || 0) * 0.95).toLocaleString()"></span>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template x-if="!selectedPayment?.seller_promptpay">
                            <div class="text-center py-4 bg-white/50 rounded-lg border border-dashed border-gray-300">
                                <i class="fa-solid fa-circle-exclamation text-yellow-500 text-2xl mb-2"></i>
                                <p class="text-gray-500 font-primaryMedium">ผู้ขายยังไม่ได้ระบุเลขพร้อมเพย์</p>
                                <p class="text-xs text-gray-400 mt-1">กรุณาติดต่อผู้ขายเพื่อขอช่องทางโอนเงิน</p>
                            </div>
                        </template>
                    </div>
                </template>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4 border-t">
                    <template x-if="selectedPayment?.status === 'pending_admin_verify'">
                        <button @click="verifyPayment(selectedPayment?.id); showModal = false;"
                            class="flex-1 py-3 bg-green-500 text-white rounded-xl font-primaryBold hover:bg-green-600 transition-all">
                            <i class="fa-solid fa-check mr-2"></i> ยืนยันรับเงิน
                        </button>
                    </template>
                    <template x-if="selectedPayment?.status === 'verified'">
                        <button @click="markPaid(selectedPayment?.id); showModal = false;"
                            class="flex-1 py-3 bg-bgsecondary text-white rounded-xl font-primaryBold hover:bg-secondary-hover transition-all">
                            <i class="fa-solid fa-paper-plane mr-2"></i> ยืนยันโอนให้ผู้ขายแล้ว
                        </button>
                    </template>
                    <template x-if="selectedPayment?.status === 'pending_admin_verify'">
                        <button @click="rejectPayment(selectedPayment?.id); showModal = false;"
                            class="flex-1 py-3 bg-red-500 text-white rounded-xl font-primaryBold hover:bg-red-600 transition-all">
                            <i class="fa-solid fa-xmark mr-2"></i> ปฏิเสธ
                        </button>
                    </template>
                    <button @click="showModal = false"
                        class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-primaryBold hover:bg-gray-300 transition-all">
                        ปิด
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Image Preview Modal -->
    <div x-show="previewImage" class="fixed inset-0 z-[60] flex items-center justify-center p-4" style="display: none;">
        <div class="fixed inset-0 bg-black/80" @click="previewImage = null"></div>
        <div x-show="previewImage" class="relative bg-transparent max-w-4xl max-h-[90vh] overflow-auto">
            <button @click="previewImage = null" class="absolute -top-10 right-0 text-white hover:text-gray-300">
                <i class="fa-solid fa-times text-2xl"></i>
            </button>
            <img :src="previewImage" class="max-w-[350px] max-h-[60vh] w-auto h-auto object-contain rounded-lg"
                alt="Preview">
        </div>
    </div>
</div>

<script>
    function paymentManagement() {
        return {
            payments: <?= json_encode($payments) ?>,
            filterStatus: 'all',
            showModal: false,
            selectedPayment: null,
            previewImage: null,
            reloading: false,

            get filteredPayments() {
                if (this.filterStatus === 'all') return this.payments;
                return this.payments.filter(p => p.status === this.filterStatus);
            },

            getStatusText(status) {
                const map = {
                    'pending_buyer_payment': 'รอผู้ซื้อโอนเงิน',
                    'pending_admin_verify': 'รอตรวจสอบ',
                    'verified': 'รอโอนให้ผู้ขาย',
                    'completed': 'เสร็จสิ้น',
                    'rejected': 'ปฏิเสธ'
                };
                return map[status] || status;
            },

            getStatusClass(status) {
                const map = {
                    'pending_buyer_payment': 'bg-gray-100 text-gray-700',
                    'pending_admin_verify': 'bg-yellow-100 text-yellow-700',
                    'verified': 'bg-blue-100 text-blue-700',
                    'completed': 'bg-green-100 text-green-700',
                    'rejected': 'bg-red-100 text-red-700'
                };
                return map[status] || 'bg-gray-100 text-gray-700';
            },

            viewDetail(payment) {
                this.selectedPayment = payment;
                this.showModal = true;
            },

            verifyPayment(id) {
                Swal.fire({
                    title: 'ยืนยันรับเงิน?',
                    text: 'คุณได้รับเงินจากผู้ซื้อแล้วใช่หรือไม่?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#22c55e',
                    confirmButtonText: 'ยืนยัน',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (result.isConfirmed) {
                            this.reloading = true;
                            fetch('/admin/payment/verify', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ payment_id: id })
                            })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire({
                                            title: 'สำเร็จ!',
                                            text: data.message,
                                            icon: 'success',
                                            timer: 1500,
                                            showConfirmButton: false,
                                            willClose: () => {
                                                window.location.reload();
                                            }
                                        });
                                    } else {
                                        this.reloading = false;
                                        Swal.fire('ผิดพลาด', data.message, 'error');
                                    }
                                })
                                .catch(() => {
                                    this.reloading = false;
                                    Swal.fire('ผิดพลาด', 'เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
                                });
                        }
                    }
                });
            },

            markPaid(id) {
                Swal.fire({
                    title: 'ยืนยันโอนเงินให้ผู้ขาย?',
                    text: 'คุณได้โอนเงินให้ผู้ขายแล้วใช่หรือไม่?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#00365D',
                    confirmButtonText: 'ยืนยัน',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.reloading = true;
                        fetch('/admin/payment/mark-paid', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ payment_id: id })
                        })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        title: 'สำเร็จ!',
                                        text: data.message,
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false,
                                        willClose: () => {
                                            window.location.reload();
                                        }
                                    });
                                } else {
                                    this.reloading = false;
                                    Swal.fire('ผิดพลาด', data.message, 'error');
                                }
                            })
                            .catch(() => {
                                this.reloading = false;
                                Swal.fire('ผิดพลาด', 'เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
                            });
                    }
                });
            },

            rejectPayment(id) {
                Swal.fire({
                    title: 'ปฏิเสธรายการนี้?',
                    input: 'textarea',
                    inputPlaceholder: 'ระบุเหตุผล (ถ้ามี)',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'ปฏิเสธ',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.reloading = true;
                        fetch('/admin/payment/reject', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ payment_id: id, notes: result.value || '' })
                        })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        title: 'สำเร็จ!',
                                        text: data.message,
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false,
                                        willClose: () => {
                                            window.location.reload();
                                        }
                                    });
                                } else {
                                    this.reloading = false;
                                    Swal.fire('ผิดพลาด', data.message, 'error');
                                }
                            })
                            .catch(() => {
                                this.reloading = false;
                                Swal.fire('ผิดพลาด', 'เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
                            });
                    }
                });
            }
        }
    }
</script>