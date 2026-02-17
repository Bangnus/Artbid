<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="paymentAccount()">
    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-3xl font-primaryBold text-textprimary mb-2">จัดการบัญชีโอนเงิน</h2>
        <p class="text-textsecondary">ตั้งค่าบัญชีธนาคารกลางสำหรับรับเงินจากผู้ซื้อก่อนโอนให้ผู้ขาย</p>
    </div>

    <!-- Content Flex Container -->
    <div style="display: flex; flex-wrap: wrap; gap: 2rem;">

        <!-- Info Card (Left Side) -->
        <div style="flex: 1; min-width: 300px;">
            <div class="p-6 rounded-2xl border border-gray-200 sticky top-24 bg-white shadow-lg"
                style="position: sticky; top: 6rem;">

                <!-- Dynamic QR Code Preview -->
                <div class="relative w-full bg-gray-50 rounded-2xl border border-gray-200 flex items-center justify-center mb-6 overflow-hidden group"
                    style="aspect-ratio: 1/1;">
                    <template x-if="form.promptpay_number && form.promptpay_number.length >= 10">
                        <img :src="'https://promptpay.io/' + form.promptpay_number" alt="PromptPay QR"
                            class="w-full h-full object-contain p-4 transition-transform duration-300 group-hover:scale-105">
                    </template>
                    <template x-if="!form.promptpay_number || form.promptpay_number.length < 10">
                        <div class="flex flex-col items-center text-gray-300">
                            <i class="fa-solid fa-qrcode text-6xl mb-3"></i>
                            <span class="text-sm font-primaryMedium text-gray-400">ตัวอย่าง QR Code</span>
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
                    ข้อมูลนี้จะแสดงให้ผู้ชำระเงินเห็นเมื่อต้องโอนเงินให้คุณ
                </p>

                <!-- PromptPay Badge -->
                <div class="flex items-center gap-4 p-4 bg-[#F2F9FF] rounded-xl border border-blue-100">
                    <div
                        style="width: 48px; height: 48px; background-color: #003D6B; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="#FFFFFF">
                            <rect x="3" y="3" width="7" height="7" rx="1" />
                            <rect x="14" y="3" width="7" height="7" rx="1" />
                            <rect x="3" y="14" width="7" height="7" rx="1" />
                            <rect x="14" y="14" width="3" height="3" />
                            <rect x="18" y="14" width="3" height="3" />
                            <rect x="14" y="18" width="3" height="3" />
                            <rect x="18" y="18" width="3" height="3" />
                        </svg>
                    </div>
                    <div>
                        <span class="block font-primaryBold text-[#003D6B] text-base">PromptPay</span>
                        <span class="text-xs text-[#003D6B] opacity-70 font-primaryMedium">Official Payment
                            Standard</span>
                    </div>
                </div>


            </div>
        </div>

        <!-- Form (Right Side) -->
        <div style="flex: 2; min-width: 300px;">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-[#00365D] to-[#005a9e]"></div>

                <form @submit.prevent="saveSettings">
                    <div class="space-y-8">
                        <!-- Header Form -->
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-primaryBold text-textprimary">รายละเอียดบัญชี</h3>
                            <span
                                class="px-3 py-1 bg-slate-100 text-bgsecondary text-xs rounded-full font-primaryBold border border-gray-200">สำหรับรับเงิน</span>
                        </div>

                        <!-- PromptPay/ID Card -->
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
                                    class="w-full pr-4 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-bgsecondary focus:ring-2 focus:ring-bgsecondary focus:ring-offset-1 transition-all font-primaryBold text-xl tracking-wide text-textprimary placeholder-gray-300"
                                    placeholder="08X-XXX-XXXX" required>
                            </div>
                            <p class="text-xs text-textsecondary mt-2 pl-1 flex items-center gap-1 opacity-70">
                                <i class="fa-solid fa-circle-info"></i>
                                กรอกเบอร์โทรศัพท์ 10 หลัก หรือเลขบัตรประชาชน 13 หลัก
                            </p>
                        </div>
                    </div>

                    <hr class="my-8 border-gray-100" style="border-style: dashed;">

                    <button type="submit"
                        style="background: linear-gradient(90deg, #00365D 0%, #004e8a 100%); box-shadow: 0 4px 15px rgba(0, 54, 93, 0.3);"
                        class="w-full mt-4 px-4 py-3 text-white rounded-xl font-primaryBold hover:-translate-y-0.5 active:scale-95 transition-all duration-200 flex items-center justify-center gap-3 text-lg">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>บันทึกข้อมูล</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function paymentAccount() {
        return {
            form: {
                promptpay_number: '<?= htmlspecialchars($settings['promptpay_number'] ?? '') ?>'
            },

            saveSettings() {
                Swal.fire({
                    title: 'ยืนยันการบันทึก?',
                    text: "ข้อมูลบัญชีกลางจะถูกอัปเดตทันที",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#00365D',
                    cancelButtonColor: '#9ca3af',
                    confirmButtonText: 'บันทึก',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('<?= $_ENV['APP_URL'] ?>/admin/payment/account/update', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(this.form)
                        })
                            .then(res => {
                                if (!res.ok) {
                                    throw new Error('Server returned ' + res.status);
                                }
                                return res.text();
                            })
                            .then(text => {
                                try {
                                    return JSON.parse(text);
                                } catch (e) {
                                    console.error('Server response:', text);
                                    throw new Error('Invalid JSON response');
                                }
                            })
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'บันทึกสำเร็จ!',
                                        text: 'ข้อมูลบัญชีถูกอัปเดตเรียบร้อยแล้ว',
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
                                Swal.fire('Error', 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้: ' + err.message, 'error');
                            });
                    }
                })
            }
        }
    }
</script>