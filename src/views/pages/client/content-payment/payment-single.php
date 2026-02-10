<?php
// Data is passed from BuyerPaymentController->singlePayment()
$payment = $payment ?? null;
$adminPromptpay = $adminPromptpay ?? '';
?>

<main class="min-h-screen p-6 ">
    <div class="max-w-8xl mx-auto p-6 bg-white rounded-xl" x-data="singlePayment()">
        <!-- Loading Overlay -->
        <div x-show="reloading" x-transition.opacity
            class="fixed inset-0 z-50  backdrop-blur-sm flex items-center justify-center">
            <?php include __DIR__ . '/../../../../components/content-loading/page-loading.php'; ?>
        </div>
        <?php if (!$payment): ?>
            <div class="text-center py-20">
                <i class="fa-solid fa-receipt text-gray-300 text-6xl mb-4"></i>
                <p class="font-primaryBold text-gray-400 text-lg">ไม่พบรายการชำระเงิน</p>
                <a href="/Artbid/public/my-auctions"
                    class="inline-block mt-4 px-6 py-3 bg-textorange text-white rounded-xl font-primaryBold hover:bg-orange-600 transition-all">
                    กลับไปหน้าการประมูล
                </a>
            </div>
        <?php else: ?>
            <!-- Header -->
            <div class="mb-6">
                <a href="/Artbid/public/my-auctions" class="text-textorange font-primaryMedium hover:underline">
                    <i class="fa-solid fa-arrow-left mr-2"></i>กลับไปหน้าการประมูล
                </a>
            </div>

            <h1 class="text-2xl font-primaryBold text-textprimary mb-6">
                <i class="fa-solid fa-credit-card text-textorange mr-2"></i>ชำระเงิน
            </h1>

            <div class="flex gap-4 items-start">
                <!-- Artwork Info -->
                <div class="w-1/2 space-y-4">
                    <div
                        class="bg-white rounded-xl shadow-md border border-gray-100 p-4 hover:shadow-lg transition-all flex items-center gap-4 h-full">
                        <img src="<?= htmlspecialchars($payment['artwork_image']) ?>"
                            class="w-16 h-16 rounded-md object-cover flex-shrink-0"
                            alt="<?= htmlspecialchars($payment['artwork_title']) ?>">
                        <div class="flex-1 min-w-0">
                            <h3 class="font-primaryBold text-textprimary text-sm mb-0.5 truncate">
                                <?= htmlspecialchars($payment['artwork_title']) ?>
                            </h3>
                            <p class="text-xs text-textsecondary font-primaryMedium truncate">
                                ผู้ขาย:
                                <?= htmlspecialchars($payment['seller_firstname'] . ' ' . $payment['seller_lastname']) ?>
                            </p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-xs text-textsecondary mb-0.5">ยอดชำระสุทธิ</p>
                            <p class="text-lg font-primaryBold text-textorange leading-none">
                                ฿<?= number_format($payment['amount']) ?>
                            </p>
                        </div>
                    </div>
                    <div class="">
                        <img src="<?= htmlspecialchars($payment['artwork_image']) ?>"
                            class="w-full h-full rounded-md object-cover flex-shrink-0"
                            alt="<?= htmlspecialchars($payment['artwork_title']) ?>">
                    </div>
                </div>

                <div class="w-1/2">
                    <?php if ($payment['status'] === 'pending_buyer_payment'): ?>
                        <!-- Countdown Timer -->
                        <div class="text-center mb-6 py-3 px-4 rounded-xl" x-show="timeLeft > 0" x-cloak>
                            <p class="text-red-500 font-primaryBold text-lg animate-pulse">
                                <i class="fa-solid fa-clock mr-2"></i>เหลือเวลาชำระเงิน: <span x-text="timeDisplay"
                                    class="font-mono text-xl"></span>
                            </p>
                        </div>

                        <div class="text-center mb-6 py-3 px-4 rounded-xl" x-show="timeLeft <= 0" x-cloak>
                            <p class="text-gray-500 font-primaryBold text-lg">
                                <i class="fa-solid fa-hourglass-end mr-2"></i>หมดเวลาชำระเงิน
                            </p>
                        </div>

                        <!-- QR Code Section -->
                        <div class="p-6 mb-6" x-show="timeLeft > 0">
                            <h2 class="font-primaryBold text-[#003D6B] mb-4 text-center">
                                <i class="fa-solid fa-qrcode mr-2"></i>สแกน QR เพื่อโอนเงิน
                            </h2>

                            <?php if ($adminPromptpay): ?>
                                <div class="flex flex-col items-center justify-center mb-4">
                                    <img src="https://promptpay.io/<?= $adminPromptpay ?>/<?= $payment['amount'] ?>.png"
                                        class="w-48 h-48 rounded-lg shadow-md mb-3" alt="PromptPay QR">
                                    <p class="text-textsecondary font-primaryMedium text-lg">
                                        พร้อมเพย์: <span
                                            class="text-textprimary font-primaryBold"><?= htmlspecialchars($adminPromptpay) ?></span>
                                    </p>
                                </div>
                            <?php else: ?>
                                <p class="text-center text-red-500 font-primaryMedium">ไม่พบข้อมูล PromptPay</p>
                            <?php endif; ?>

                            <p class="text-center text-2xl font-primaryBold text-textorange mt-4">
                                ฿<?= number_format($payment['amount']) ?>
                            </p>
                        </div>

                        <!-- Upload Slip -->
                        <div class="p-6" x-show="timeLeft > 0">
                            <h2 class="font-primaryBold text-textorange mb-4">
                                <i class="fa-solid fa-camera mr-2"></i>แนบสลิปโอนเงิน
                            </h2>

                            <!-- Preview -->
                            <template x-if="slipPreview">
                                <div class="mb-4 flex justify-center">
                                    <img :src="slipPreview" class="max-w-xs rounded-lg border border-gray-200"
                                        alt="Slip Preview">
                                </div>
                            </template>

                            <div class="space-y-3">
                                <label class="block">
                                    <input type="file" accept="image/*" @change="handleSlipUpload($event)" class="hidden">
                                    <div
                                        class="w-full py-8 px-4 bg-white border-2 border-dashed border-orange-300 rounded-xl text-center cursor-pointer hover:bg-orange-50 transition-all flex flex-col items-center justify-center gap-2">
                                        <i class="fa-solid fa-cloud-arrow-up text-4xl text-textorange mb-2"></i>
                                        <span class="font-primaryMedium text-textorange text-lg"
                                            x-text="slipPreview ? 'เปลี่ยนรูปสลิป' : 'คลิกเพื่อเลือกรูปสลิป'"></span>
                                        <span class="text-sm text-gray-400 font-primaryRegular" x-show="!slipPreview">รองรับไฟล์
                                            JPG,
                                            PNG</span>
                                    </div>
                                </label>

                                <div x-show="slipPreview" x-transition class="mt-4">
                                    <button @click="submitSlip()" :disabled="uploading"
                                        class="w-full py-3 text-lg bg-textorange text-white rounded-xl font-primaryBold hover:bg-orange-600 hover:scale-[1.02] transition-all disabled:opacity-50 shadow-md">
                                        <span x-show="!uploading"><i class="fa-solid fa-check mr-2"></i>ยืนยันส่งสลิป</span>
                                        <div x-show="uploading" class="flex justify-center items-center">
                                            <?php include __DIR__ . '/../../../../components/content-loading/dot-loading.php'; ?>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>

                    <?php elseif ($payment['status'] === 'pending_admin_verify'): ?>
                        <div class=" rounded-2xl p-8 text-center">
                            <i class="fa-solid fa-clock text-yellow-500 text-5xl mb-4"></i>
                            <h2 class="font-primaryBold text-yellow-700 text-xl mb-2">อยู่ระหว่างการตรวจสอบ</h2>
                            <p class="text-yellow-600 font-primaryMedium">ระบบได้รับข้อมูลเรียบร้อยแล้ว
                                กำลังตรวจสอบรายการโอนเงิน
                                (อาจใช้เวลา 1-2 วันทำการ)</p>
                        </div>

                    <?php elseif ($payment['status'] === 'verified'): ?>
                        <div class="rounded-2xl p-8 text-center">
                            <i class="fa-solid fa-check-circle text-blue-500 text-5xl mb-4"></i>
                            <h2 class="font-primaryBold text-blue-700 text-xl mb-2">ยืนยันรับเงินแล้ว</h2>
                            <p class="text-blue-600 font-primaryMedium">Admin กำลังดำเนินการโอนเงินให้ผู้ขาย</p>
                        </div>

                    <?php elseif ($payment['status'] === 'completed'): ?>
                        <div class="rounded-2xl p-8 text-center">
                            <i class="fa-solid fa-circle-check text-green-500 text-5xl mb-4"></i>
                            <h2 class="font-primaryBold text-green-700 text-xl mb-2">เสร็จสิ้น!</h2>
                            <p class="text-green-600 font-primaryMedium">การชำระเงินเสร็จสมบูรณ์แล้ว</p>
                        </div>

                    <?php elseif ($payment['status'] === 'rejected'): ?>
                        <div class="rounded-2xl p-8 text-center">
                            <i class="fa-solid fa-times-circle text-red-500 text-5xl mb-4"></i>
                            <h2 class="font-primaryBold text-red-700 text-xl mb-2">ถูกปฏิเสธ</h2>
                            <?php if (!empty($payment['admin_notes'])): ?>
                                <p class="text-red-600 font-primaryMedium">เหตุผล: <?= htmlspecialchars($payment['admin_notes']) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="rounded-2xl p-8 text-center">
                            <i class="fa-solid fa-receipt text-gray-500 text-5xl mb-4"></i>
                            <h2 class="font-primaryBold text-gray-700 text-xl mb-2">หมดอายุ / ยกเลิก</h2>
                            <p class="text-gray-600 font-primaryMedium">รายการชำระเงินนี้หมดอายุหรือถูกยกเลิกแล้ว</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
    function singlePayment() {
        return {
            paymentId: '<?= $payment['id'] ?? '' ?>',
            createdAt: '<?= $payment['created_at'] ?? '' ?>',
            slipPreview: null,
            slipFile: null,
            uploading: false,
            reloading: false,
            timeLeft: 0,
            timeDisplay: '',
            timerInterval: null,

            init() {
                if (this.createdAt && '<?= $payment['status'] ?? '' ?>' === 'pending_buyer_payment') {
                    this.updateTimer();
                    this.timerInterval = setInterval(() => this.updateTimer(), 1000);
                }
            },

            updateTimer() {
                // Assuming createdAt is typically UTC or server time, check format.
                // PHP defaults usually to server time. JS new Date() works with ISO 8601.
                // If CreatedAt is 'Y-m-d H:i:s', we might need to be careful with timezone.
                // Treat server timestamp as UTC
                const created = new Date(this.createdAt.replace(' ', 'T') + 'Z').getTime();
                const expiry = created + (24 * 60 * 60 * 1000); // 24 hours in ms
                const now = new Date().getTime();
                const diff = expiry - now;

                if (diff <= 0) {
                    this.timeLeft = 0;
                    this.timeDisplay = '00:00:00';
                    clearInterval(this.timerInterval);
                    return;
                }

                this.timeLeft = diff;
                const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                this.timeDisplay = `${hours}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            },

            handleSlipUpload(event) {
                const file = event.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = (e) => {
                    this.slipPreview = e.target.result;
                    this.slipFile = e.target.result;
                };
                reader.readAsDataURL(file);
            },

            submitSlip() {
                if (!this.slipFile) return;

                this.uploading = true;
                console.log("Submitting slip...");

                fetch('/Artbid/public/payment/upload-slip', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        payment_id: this.paymentId,
                        slip_image: this.slipFile
                    })
                })
                    .then(res => {
                        console.log("Response status:", res.status);
                        return res.text().then(text => {
                            try {
                                return JSON.parse(text);
                            } catch (e) {
                                console.error("Invalid JSON:", text);
                                throw new Error("Server returned invalid JSON");
                            }
                        });
                    })
                    .then(data => {
                        console.log("Response data:", data);
                        this.uploading = false;
                        if (data.success) {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    title: 'ส่งสลิปเรียบร้อย!',
                                    text: 'กรุณารอเจ้าหน้าที่ตรวจสอบความถูกต้อง',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    this.reloading = true;
                                    window.location.reload();
                                });
                            } else {
                                alert('ส่งสลิปเรียบร้อย!');
                                this.reloading = true;
                                window.location.reload();
                            }

                            // Force reload fallback
                            setTimeout(() => {
                                this.reloading = true;
                                window.location.reload();
                            }, 2000);
                        } else {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire('ผิดพลาด', data.message || 'เกิดข้อผิดพลาดในการส่งข้อมูล', 'error');
                            } else {
                                alert(data.message || 'เกิดข้อผิดพลาด');
                            }
                        }
                    })
                    .catch(err => {
                        console.error("Upload error:", err);
                        if (typeof Swal !== 'undefined') {
                            Swal.fire('ผิดพลาด', 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้ หรือเกิดข้อผิดพลาดภายใน', 'error');
                        } else {
                            alert('เชื่อมต่อเซิร์ฟเวอร์ล้มเหลว');
                        }
                    })
                    .finally(() => {
                        this.uploading = false;
                    });
            }
        }
    }
</script>