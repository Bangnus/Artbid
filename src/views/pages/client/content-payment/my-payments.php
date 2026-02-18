<?php
// Data is passed from BuyerPaymentController
$payments = $payments ?? [];
?>

<div class="min-h-screen bg-gray-50 py-12 p-6" x-data="paymentHistory()">
    <div class=" mx-auto px-4">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-primaryBold text-textprimary mb-2">
                    <i class="fa-solid fa-receipt mr-2 text-textorange"></i>ประวัติการชำระเงิน
                </h1>
                <p class="text-textsecondary font-primaryMedium">รายการชำระเงินทั้งหมดและสถานะ</p>
            </div>

            <!-- Filter/Tabs could go here if needed -->
        </div>

        <!-- Payments List -->
        <div class="space-y-4">
            <template x-for="payment in payments" :key="payment.id">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 transition-all hover:shadow-md">
                    <div class="flex items-center gap-6">
                        <!-- Artwork Image -->
                        <div class="relative shrink-0">
                            <img :src="payment.artwork_image"
                                class="w-24 h-24 rounded-lg object-cover shadow-sm bg-gray-100" alt="">
                        </div>

                        <!-- Info -->
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="font-primaryBold text-textprimary text-lg truncate pr-4"
                                        x-text="payment.artwork_title"></h3>
                                    <p class="text-sm text-textsecondary font-primaryMedium">
                                        ผู้ขาย: <span
                                            x-text="payment.seller_firstname + ' ' + payment.seller_lastname"></span>
                                    </p>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-xl font-primaryBold text-textorange"
                                        x-text="'฿' + Number(payment.amount).toLocaleString()"></p>
                                    <p class="text-xs text-textsecondary mt-1" x-text="formatDate(payment.created_at)">
                                    </p>
                                </div>
                            </div>

                            <!-- Status & Action -->
                            <div class="flex justify-between items-center mt-3 pt-3 border-t border-gray-50">
                                <!-- Status Badge -->
                                <div class="flex items-center gap-2">
                                    <span :class="getStatusClass(payment.status)"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-primaryBold">
                                        <i :class="getStatusIcon(payment.status)"></i>
                                        <span x-text="getStatusText(payment.status)"></span>
                                    </span>
                                </div>

                                <!-- Action Button -->
                                <div>
                                    <a :href="'/pay?artwork_id=' + payment.artwork_id"
                                        class="inline-flex items-center gap-2 px-5 py-2 rounded-lg font-primaryBold text-sm transition-all"
                                        :class="getButtonClass(payment.status)">
                                        <i :class="getButtonIcon(payment.status)"></i>
                                        <span x-text="getButtonText(payment.status)"></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Empty State -->
            <template x-if="payments.length === 0">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-16 text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid fa-receipt text-gray-300 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-primaryBold text-gray-900 mb-2">ไม่มีประวัติการชำระเงิน</h3>
                    <p class="text-gray-500 font-primaryMedium mb-8">
                        คุณยังไม่มีรายการที่ต้องชำระหรือรายการชำระเงินย้อนหลัง</p>
                    <a href="/home"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-textorange text-white rounded-xl font-primaryBold hover:bg-orange-600 transition-all shadow-sm hover:shadow-md">
                        <i class="fa-solid fa-gavel"></i>
                        ไปดูผลงานประมูล
                    </a>
                </div>
            </template>
        </div>
    </div>
    </main>

    <script>
        function paymentHistory() {
            return {
                payments: <?= json_encode($payments) ?>,

                formatDate(dateStr) {
                    if (!dateStr) return '';
                    const date = new Date(dateStr.replace(' ', 'T') + 'Z');
                    return date.toLocaleDateString('th-TH', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                },

                getStatusText(status) {
                    const map = {
                        'pending_buyer_payment': 'รอชำระเงิน',
                        'pending_admin_verify': 'รอตรวจสอบ',
                        'verified': 'ชำระเงินสำเร็จ',
                        'completed': 'เสร็จสิ้น',
                        'rejected': 'ถูกปฏิเสธ'
                    };
                    return map[status] || status;
                },

                getStatusClass(status) {
                    const map = {
                        'pending_buyer_payment': 'bg-orange-50 text-orange-600',
                        'pending_admin_verify': 'bg-yellow-50 text-yellow-600',
                        'verified': 'bg-blue-50 text-blue-600',
                        'completed': 'bg-green-50 text-green-600',
                        'rejected': 'bg-red-50 text-red-600'
                    };
                    return map[status] || 'bg-gray-100 text-gray-600';
                },

                getStatusIcon(status) {
                    const map = {
                        'pending_buyer_payment': 'fa-solid fa-clock',
                        'pending_admin_verify': 'fa-solid fa-hourglass-half',
                        'verified': 'fa-solid fa-check-circle',
                        'completed': 'fa-solid fa-circle-check',
                        'rejected': 'fa-solid fa-circle-xmark'
                    };
                    return map[status] || 'fa-solid fa-circle';
                },

                getButtonClass(status) {
                    if (status === 'pending_buyer_payment' || status === 'rejected') {
                        return 'bg-textorange text-white hover:bg-orange-600 shadow-sm hover:shadow-md';
                    }
                    return 'bg-gray-100 text-textprimary hover:bg-gray-200';
                },

                getButtonText(status) {
                    if (status === 'pending_buyer_payment' || status === 'rejected') {
                        return 'ชำระเงิน';
                    }
                    return 'ดูรายละเอียด';
                },

                getButtonIcon(status) {
                    if (status === 'pending_buyer_payment' || status === 'rejected') {
                        return 'fa-solid fa-credit-card';
                    }
                    return 'fa-solid fa-eye';
                }
            }
        }
    </script>