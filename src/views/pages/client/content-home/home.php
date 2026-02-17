<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<style>
    .swiper-wrapper {
        height: auto !important;
    }
</style>

<?php
require_once __DIR__ . '/../../../../controllers/controllers-backend/content-client/ClientController.php';
require_once __DIR__ . '/../../../../controllers/controllers-backend/content-client/FavoriteController.php';
$artworks_live = ClientController::getAllArtwork('Live', 10);
$artworks_up = ClientController::getAllArtwork('Upcoming', 10);


?>

<body>
    <div class="space-y-[100px]">
        <div class="flex flex-col items-center flex-wrap w-full bg-white rounded-2xl md:flex md:flex-row">
            <div class="w-4/5 md:w-1/2 flex flex-col md:pl-10 py-10">
                <p class="text-[22px] text-textorange font-primarybold leading-tight lg:text-[45px]">
                    สำรวจผลงานชิ้นเอกระดับโลก
                    <br>ประมูลและสะสมงานศิลป์ที่คุณตกหลุมรัก
                </p>
                <span
                    class="text-[16px] text-textsecondary font-primaryMedium lg:text-[20px]">ปลดปล่อยจินตนาการของคุณด้วยคอลเลกชันผลงานต้นฉบับที่คัดสรรอย่างพิถีพิถัน
                </span>
                <a href="home"
                    class="text-center bg-textorange text-white w-full font-primaryMedium py-[12px] md:max-w-[170px] rounded-xl mt-5">
                    เริ่มประมูล
                </a>
            </div>
            <div class="w-4/5 md:w-1/2 ">
                <img src="<?= $_ENV['APP_URL'] ?>/images/home2-banner-img.webp" alt="" class="w-full">
            </div>
        </div>

        <!-- Currently Section  -->
        <div class="bg-white px-10 py-5 rounded-2xl  text-textprimary font-primarySemibold space-y-10 ">
            <div class="flex items-center justify-between">
                <p class="text-[18px] sm:text-[30px]">งานที่กำลังประมูล</p>
                <a href="live-artworks"
                    class="text-textorange text-[14px] sm:text-[16px] font-primarySemibold">ดูทั้งหมด</a>
            </div>

            <div class="swiper mySwiper ">
                <div class="swiper-wrapper items-stretch">

                    <?php
                    if (!empty($artworks_live) && is_array($artworks_live)):
                        ?>

                        <?php foreach ($artworks_live as $i => $art): ?>
                            <div class="swiper-slide">
                                <div
                                    class="card overflow-visible shadow-[0_2px_15px_rgba(0,0,0,0.10)] pb-0 rounded-2xl mb-5 flex flex-col min-h-[485px] ">
                                    <div class="relative">
                                        <div class="overflow-hidden rounded-t-2xl">
                                            <a href="artwork-detail?id=<?= htmlspecialchars($art['id']) ?>">
                                                <img src="<?= htmlspecialchars($art['picture']) ?>" alt="Art"
                                                    class="w-full h-[300px] object-cover object-[0%_10%] transition duration-500 transform hover:scale-105">
                                            </a>
                                        </div>

                                        <div class="absolute top-5 left-0 w-full px-3 flex items-center justify-between ">
                                            <div class="bg-red-600 text-white px-3 py-1 rounded-md text-sm">
                                                <?php
                                                if ($art['type'])
                                                    ;
                                                echo 'กำลังประมูล';
                                                ?>
                                            </div>
                                            <?php $isLiveFav = isset($_SESSION['user']) ? FavoriteController::isFavorited($art['id']) : false; ?>
                                            <i onclick="toggleFavoriteCard('<?= $art['id'] ?>', this)"
                                                class="<?= $isLiveFav ? 'fa-solid text-red-500' : 'fa-regular text-textprimary' ?> fa-heart text-[20px] bg-white rounded-full p-1.5 cursor-pointer hover:scale-110 transition-all"></i>
                                        </div>


                                        <div data-startbidding="<?= htmlspecialchars($art['startbidding']) ?>"
                                            data-endbidding="<?= htmlspecialchars($art['endbidding']) ?>"
                                            data-artwork-type="Live"
                                            class="countdown-timer absolute bg-white w-[90%] left-1/2 -translate-x-1/2 top-full -translate-y-1/2 shadow-md px-3 py-3 rounded-xl z-10">
                                            <div class="flex items-center justify-center text-center gap-x-2 overflow-hidden">
                                                <div class="leading-4">
                                                    <p
                                                        class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium countdown-days">
                                                        00</p>
                                                    <span
                                                        class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">วัน</span>
                                                </div>
                                                <div class="text-[14px] lg:text-[16px] font-bold text-textprimary">:</div>
                                                <div class="leading-4">
                                                    <p
                                                        class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium countdown-hours">
                                                    </p>
                                                    <span
                                                        class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">ชั่วโมง</span>
                                                </div>
                                                <div class="text-[14px] lg:text-[16px] font-bold text-textprimary">:</div>
                                                <div class="leading-4">
                                                    <p
                                                        class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium countdown-minutes">
                                                        00</p>
                                                    <span
                                                        class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">นาที</span>
                                                </div>
                                                <div class="text-[14px] lg:text-[16px] font-bold text-textprimary">:</div>
                                                <div class="leading-4">
                                                    <p
                                                        class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium countdown-seconds">
                                                        00</p>
                                                    <span
                                                        class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">วินาที</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body flex-1 flex flex-col">
                                        <div class=" px-6 pt-10 rounded-b-2xl break-words whitespace-normal flex-1 ">
                                            <div class="space-y-1 ">
                                                <p class="text-[18px] text-textprimary font-primaryMedium">
                                                    <?= htmlspecialchars($art['title']) ?>
                                                </p>
                                                <div class="text-[16px] font-primaryMedium">
                                                    <p class="text-textsecondary">ศิลปิน :
                                                        <span
                                                            class="text-textprimary"><?= htmlspecialchars($art['artist']) ?></span>
                                                    </p>
                                                    <p class="text-textsecondary">ราคาปัจจุบัน :<span class="text-textprimary">
                                                            ฿<?= htmlspecialchars($art['startingprice']) ?>
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div
                            class="w-full col-span-full h-[300px] flex flex-col items-center justify-center bg-gray-50 rounded-xl text-textsecondary">
                            <i class="fa-regular fa-image text-[40px] mb-4"></i>
                            <p class="text-[18px] font-primaryMedium">ไม่มีรายการประมูลในขณะนี้</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>

        <!-- Upcoming Section  -->
        <div class="bg-white px-10 py-5 rounded-2xl  text-textprimary font-primarySemibold space-y-10">
            <div class="flex items-center justify-between">
                <p class="text-[18px] sm:text-[30px]">การประมูลที่จะมาถึง</p>
                <a href="upcoming-artworks"
                    class="text-textorange text-[12px] sm:text-[16px] font-primarySemibold">ดูทั้งหมด</a>
            </div>

            <div class="swiper mySwiper">
                <div class="swiper-wrapper items-stretch">

                    <?php

                    if (!empty($artworks_up) && is_array($artworks_up)):
                        ?>
                        <?php foreach ($artworks_up as $i => $art_up): ?>
                            <div class="swiper-slide">
                                <div
                                    class="card overflow-visible  shadow-[0_2px_15px_rgba(0,0,0,0.10)]   pb-0 rounded-2xl   mb-5 flex flex-col  min-h-[485px] ">
                                    <div class="relative">
                                        <div class="overflow-hidden rounded-t-2xl">
                                            <a href="artwork-detail?id=<?= htmlspecialchars($art_up['id']) ?>">
                                                <img src="<?= htmlspecialchars($art_up['picture']) ?>" alt="Art"
                                                    class="w-full h-[300px] object-cover object-[0%_10%] transition duration-500 transform hover:scale-105">
                                            </a>
                                        </div>

                                        <div class="absolute top-5 left-0 w-full px-3 flex items-center justify-between ">
                                            <div class="bg-green-600 text-white px-3 py-1 rounded-md text-sm">
                                                <?php
                                                if ($art_up['type'])
                                                    ;
                                                echo 'กำลังจะมา';
                                                ?>
                                            </div>
                                            <?php $isUpFav = isset($_SESSION['user']) ? FavoriteController::isFavorited($art_up['id']) : false; ?>
                                            <i onclick="toggleFavoriteCard('<?= $art_up['id'] ?>', this)"
                                                class="<?= $isUpFav ? 'fa-solid text-red-500' : 'fa-regular text-textprimary' ?> fa-heart text-[20px] bg-white rounded-full p-1.5 cursor-pointer hover:scale-110 transition-all"></i>
                                        </div>


                                        <div data-startbidding="<?= htmlspecialchars($art_up['startbidding']) ?>"
                                            data-endbidding="<?= htmlspecialchars($art_up['endbidding']) ?>"
                                            data-artwork-type="Upcoming"
                                            class="countdown-timer absolute bg-white w-[90%] left-1/2 -translate-x-1/2 top-full -translate-y-1/2 shadow-md px-3 py-3 rounded-xl z-10">
                                            <div class="flex items-center justify-center text-center gap-x-2 overflow-hidden">
                                                <!-- Days -->
                                                <div class="leading-4">
                                                    <p
                                                        class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium countdown-days">
                                                        00</p>
                                                    <span
                                                        class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">วัน</span>
                                                </div>

                                                <div class="text-[14px] lg:text-[16px] font-bold text-textprimary">:</div>

                                                <!-- Hours -->
                                                <div class="leading-4">
                                                    <p
                                                        class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium countdown-hours">
                                                        00</p>
                                                    <span
                                                        class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">ชั่วโมง</span>
                                                </div>

                                                <div class="text-[14px] lg:text-[16px] font-bold text-textprimary">:</div>

                                                <!-- Minutes -->
                                                <div class="leading-4">
                                                    <p
                                                        class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium countdown-minutes">
                                                        00</p>
                                                    <span
                                                        class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">นาที</span>
                                                </div>

                                                <div class="text-[14px] lg:text-[16px] font-bold text-textprimary">:</div>

                                                <!-- Seconds -->
                                                <div class="leading-4">
                                                    <p
                                                        class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium countdown-seconds">
                                                        00</p>
                                                    <span
                                                        class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">วินาที</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- body Card -->
                                    <div class="card-body flex-1 flex flex-col">
                                        <div class=" px-6 pt-10  rounded-b-2xl break-words whitespace-normal flex-1 ">
                                            <div class="space-y-2 ">
                                                <p class="text-[18px] text-textprimary font-primaryMedium">
                                                    <?= htmlspecialchars($art_up['title']) ?>
                                                </p>
                                                <div class="text-[16px] font-primaryMedium">
                                                    <p class="text-textsecondary">ศิลปิน :
                                                        <span class="text-textprimary">
                                                            <?= htmlspecialchars($art_up['artist']) ?>
                                                        </span>
                                                    </p>
                                                    <p class="text-textsecondary">ราคาปัจจุบัน :<span class="text-textprimary">
                                                            ฿<?= htmlspecialchars($art_up['startingprice']) ?>
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div
                            class="w-full col-span-full h-[300px] flex flex-col items-center justify-center bg-gray-50 rounded-xl text-textsecondary">
                            <i class="fa-regular fa-image text-[40px] mb-4"></i>
                            <p class="text-[18px] font-primaryMedium">ไม่มีรายการประมูลที่กำลังจะมาถึง</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Discover Our Essence -->
        <div class="flex flex-col md:flex-row items-start gap-x-10 lg:gap-x-0  py-[100px] ">
            <div class="w-1/2 relative flex flex-col items-center">
                <img src="<?= $_ENV['APP_URL'] ?>/images/bg.png" alt="image" class="w-[500px] h-auto rounded-xl">
                <div
                    class="absolute bottom-0 translate-y-1/2 bg-white flex items-center justify-between w-[60%] px-5 py-2 rounded-xl shadow">
                    <div class="text-center">
                        <p class="text-[30px] text-textprimary font-primaryMedium">65K</p>
                        <span class="text-[16px] text-textsecondary font-primaryMedium">ลูกค้า</span>
                    </div>
                    <div class="text-center">
                        <p class="text-[30px] text-textprimary font-primaryMedium">500</p>
                        <span class="text-[16px] text-textsecondary font-primaryMedium">การประมูล</span>
                    </div>
                    <div class="text-center">
                        <p class="text-[30px] text-textprimary font-primaryMedium">1K</p>
                        <span class="text-[16px] text-textsecondary font-primaryMedium">ผู้ประมูล</span>
                    </div>
                </div>
            </div>
            <div class="w-1/2 flex flex-col">
                <p class="text-[40px] text-textorange font-primaryBold">
                    ค้นพบแก่นแท้ของเรา
                </p>
                <span class="text-[20px] text-textsecondary  font-primaryRegular">
                    ที่ Artmart เราเป็นชุมชนของผู้ที่หลงใหลในงานศิลปะ
                    มุ่งมั่นเชื่อมโยงศิลปินและผู้สะสมผ่านการประมูลที่มีชีวิตชีวา
                    แพลตฟอร์มของเราแสดงความคิดสร้างสรรค์และความมั่งคั่งทางวัฒนธรรมของศิลปินจากทั่วโลก
                    ให้พื้นที่ในการค้นพบ ชื่นชม
                    และครอบครองผลงานต้นฉบับ
                </span>
                <div class="grid grid-cols-2 gap-y-2 mt-6 text-[20px] text-textprimary font-primarySemibold">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-check text-green-500"></i>
                        <p class="m-0">ความซื่อสัตย์</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-check text-green-500"></i>
                        <p class="m-0">ความหลากหลาย</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-check text-green-500"></i>
                        <p class="m-0">การเข้าถึง</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-check text-green-500"></i>
                        <p class="m-0">การสนับสนุน</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Artwork Category  -->
        <div class="bg-white px-10 py-5 space-y-10 rounded-2xl">
            <p class="text-[18px] sm:text-[30px] text-textprimary font-primarySemibold ">
                หมวดหมู่งานศิลปะ
            </p>
            <div class="flex gap-4  h-[500px]  pb-5">
                <!-- ซ้าย -->
                <div class="flex flex-col gap-4 w-[40%]">
                    <a href="home?category=1"
                        class="flex-1 relative overflow-hidden rounded-xl transition-transform duration-500 ease-in-out hover:scale-95 hover:shadow-lg hover:shadow-black/10">
                        <img src="<?= $_ENV['APP_URL'] ?>/images/Prints.png" alt="Prints"
                            class="w-full h-full object-cover ">
                        <div class="absolute inset-x-0 bottom-3 flex items-center justify-center ">
                            <p class="text-white text-[30px] font-primaryMedium text-center">งานพิมพ์</p>
                        </div>
                    </a>
                    <a href="home?category=2"
                        class="flex-1 relative overflow-hidden rounded-xl transition-transform duration-500 ease-in-out hover:scale-95 hover:shadow-lg hover:shadow-black/10">
                        <img src="<?= $_ENV['APP_URL'] ?>/images/Photography.png" alt=""
                            class="w-full h-full object-cover ">
                        <div class="absolute inset-x-0 bottom-3 flex items-center justify-center">
                            <p class="text-white text-[30px] font-primaryMedium text-center">ภาพถ่าย</p>
                        </div>
                    </a>
                </div>

                <!-- กลาง -->
                <a href="home?category=3"
                    class="w-1/3 relative transition-transform duration-500 ease-in-out hover:scale-95 hover:shadow-lg hover:shadow-black/10">
                    <img src="<?= $_ENV['APP_URL'] ?>/images/Classical.png" alt=""
                        class="w-full h-full object-cover rounded-xl">
                    <div class="absolute inset-x-0 bottom-3 flex items-center justify-center">
                        <p class="text-white text-[30px] font-primaryMedium text-center">ศิลปะคลาสสิก</p>
                    </div>
                </a>

                <!-- ขวา -->
                <a href="home?category=4"
                    class="w-1/2 relative bg-cover bg-center rounded-xl transition-transform duration-500 ease-in-out hover:scale-95 hover:shadow-lg hover:shadow-black/10">
                    <img src="<?= $_ENV['APP_URL'] ?>/images/Modern.png" alt=""
                        class="w-full h-full object-cover rounded-xl">
                    <div class="absolute inset-x-0 bottom-3 flex items-center justify-center ">
                        <p class="text-white text-[30px] font-primaryMedium text-center">ศิลปะร่วมสมัย</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.mySwiper', {
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            speed: 1000,
            slidesPerView: 1,
            spaceBetween: 20,
            freeMode: true,
            breakpoints: {
                768: {
                    slidesPerView: 3,
                    centeredSlides: true,
                },
                1025: {
                    slidesPerView: 4,
                },
            }
        });

        function toggleFavoriteCard(artworkId, btn) {
            // เปลี่ยนสีทันที (optimistic UI)
            if (btn.classList.contains('fa-regular')) {
                btn.classList.remove('fa-regular', 'text-textprimary');
                btn.classList.add('fa-solid', 'text-red-500');
            } else {
                btn.classList.remove('fa-solid', 'text-red-500');
                btn.classList.add('fa-regular', 'text-textprimary');
            }

            // ส่ง API เบื้องหลัง
            fetch('<?= $_ENV['APP_URL'] ?>/toggle-favorite', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'artwork_id=' + artworkId
            }).catch(error => console.error('Error:', error));
        }
    </script>


</body>

</html>