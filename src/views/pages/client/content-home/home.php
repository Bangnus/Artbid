<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <?php
    $status = 'Live'; // หรือรับค่าจากฐานข้อมูล / API ฯลฯ

    if (!empty($_SESSION['sign_in_success'])) {
        echo "<script>
        const notyf = new Notyf({ position: { x: 'right', y: 'top' } });
        notyf.success('Sign in successfully!');
    </script>";
        unset($_SESSION['sign_in_success']);
    }
    if (!empty($_SESSION['sign_up_success'])) {
        echo "<script>
        const notyf = new Notyf({ position: { x: 'right', y: 'top' } });
        notyf.success('Sign up successfully!');
    </script>";
        unset($_SESSION['sign_up_success']);
    }
    ?>


    <a href="logout">Logout</a>


    <div class="space-y-[100px]">
        <div class="flex flex-col items-center flex-wrap w-full bg-white rounded-2xl md:flex md:flex-row">
            <div class="w-4/5 md:w-1/2 flex flex-col md:pl-10 py-10">
                <p class="text-[22px] text-textorange font-primarySemibold leading-tight lg:text-[40px]">Explore global
                    masterpieces.
                    <br>Bid and collect the
                    art you fall in love with.
                </p>
                <span class="text-[16px] text-textsecondary font-primaryMedium lg:text-[20px]">Unlock your imagination with
                    our carefully
                    curated collection of original artworks.
                </span>
                <button class="bg-textorange text-white w-full font-primaryMedium py-[12px] md:max-w-[170px] rounded-xl mt-5">
                    Start Bidding
                </button>
            </div>
            <div class="w-4/5 md:w-1/2 ">
                <img src="/Artbid/public/images/home2-banner-img.webp" alt="" class="w-full">
            </div>
        </div>

        <!-- Currently Section  -->
        <div class="bg-white px-10 py-5 rounded-2xl  text-textprimary font-primarySemibold space-y-10">
            <div class="flex items-center justify-between">
                <p class="text-[18px] sm:text-[30px]">Currently Auctioning</p>
                <a href="" class="text-textorange text-[14px] sm:text-[16px] font-primarySemibold">View All</a>
            </div>

            <div class="swiper mySwiper">
                <div class="swiper-wrapper items-stretch">

                    <div class="swiper-slide">
                        <div
                            class="card overflow-visible  shadow-[0_2px_15px_rgba(0,0,0,0.10)]   pb-0 rounded-2xl   mb-5 flex flex-col min-h-[485px] ">
                            <div class="relative">
                                <div class="overflow-hidden rounded-t-2xl">
                                    <img src="/../Artbid/public/images/art1.png" alt="Art"
                                        class="w-full h-[300px] object-cover object-[0%_10%] transition duration-500 transform hover:scale-105">
                                </div>

                                <div class="absolute top-5 left-0 w-full px-3 flex items-center justify-between ">
                                    <div class="<?php echo ($status == 'Live') ? 'bg-red-600' : 'bg-green-600'; ?> text-white px-3 py-1 rounded-md text-sm">
                                        <p><?php echo $status; ?></p>
                                    </div>
                                    <i class="fa-regular fa-heart text-textprimary text-[20px] bg-white rounded-full p-1.5 cursor-pointer"></i>
                                </div>


                                <div
                                    class="absolute bg-white w-[90%] left-1/2 -translate-x-1/2 top-full -translate-y-1/2 shadow-md px-3 py-3 rounded-xl z-10">
                                    <div class="flex items-center justify-center text-center gap-x-2 overflow-hidden">
                                        <!-- Days -->
                                        <div class="leading-4">
                                            <p class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium">249</p>
                                            <span class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">Days</span>
                                        </div>

                                        <div class="text-[14px] lg:text-[16px] font-bold text-textprimary">:</div>

                                        <!-- Hours -->
                                        <div class="leading-4">
                                            <p class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium">07</p>
                                            <span class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">Hours</span>
                                        </div>

                                        <div class="text-[14px] lg:text-[16px] font-bold text-textprimary">:</div>

                                        <!-- Minutes -->
                                        <div class="leading-4">
                                            <p class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium">40</p>
                                            <span class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">Minutes</span>
                                        </div>

                                        <div class="text-[14px] lg:text-[16px] font-bold text-textprimary">:</div>

                                        <!-- Seconds -->
                                        <div class="leading-4">
                                            <p class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium">50</p>
                                            <span class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">Seconds</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- body Card -->
                            <div class="card-body flex-1 flex flex-col">
                                <div class=" px-6 pt-10  rounded-b-2xl break-words whitespace-normal flex-1 ">
                                    <div class="space-y-1 ">
                                        <p class="text-[18px] text-textprimary font-primaryMedium">
                                            ean Paul (XX) - Landschaft mitSchafe
                                        </p>
                                        <div class="text-[16px] font-primaryMedium">
                                            <p class="text-textsecondary">Artist : <span class="text-textprimary">Pablo
                                                    Picasso</span>
                                            </p>
                                            <p class="text-textsecondary">Current bid :<span class="text-textprimary">$620.00</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-slide">
                        <div
                            class="card overflow-visible  shadow-[0_2px_15px_rgba(0,0,0,0.10)]   pb-0 rounded-2xl   mb-5 flex flex-col min-h-[485px] ">
                            <div class="relative">
                                <div class="overflow-hidden rounded-t-2xl">
                                    <img src="/../Artbid/public/images/art1.png" alt="Art"
                                        class="w-full h-[300px] object-cover object-[0%_10%] transition duration-500 transform hover:scale-105">
                                </div>

                                <div class="absolute top-5 left-0 w-full px-3 flex items-center justify-between ">
                                    <div class="<?php echo ($status == 'Live') ? 'bg-red-600' : 'bg-green-600'; ?> text-white px-3 py-1 rounded-md text-sm">
                                        <p><?php echo $status; ?></p>
                                    </div>
                                    <i class="fa-regular fa-heart text-textprimary text-[20px] bg-white rounded-full p-1.5 cursor-pointer"></i>
                                </div>


                                <div
                                    class="absolute bg-white w-[90%] left-1/2 -translate-x-1/2 top-full -translate-y-1/2 shadow-md px-3 py-3 rounded-xl z-10">
                                    <div class="flex items-center justify-center text-center gap-x-2 overflow-hidden">
                                        <!-- Days -->
                                        <div class="leading-4">
                                            <p class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium">249</p>
                                            <span class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">Days</span>
                                        </div>

                                        <div class="text-[14px] lg:text-[16px] font-bold text-textprimary">:</div>

                                        <!-- Hours -->
                                        <div class="leading-4">
                                            <p class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium">07</p>
                                            <span class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">Hours</span>
                                        </div>

                                        <div class="text-[14px] lg:text-[16px] font-bold text-textprimary">:</div>

                                        <!-- Minutes -->
                                        <div class="leading-4">
                                            <p class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium">40</p>
                                            <span class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">Minutes</span>
                                        </div>

                                        <div class="text-[14px] lg:text-[16px] font-bold text-textprimary">:</div>

                                        <!-- Seconds -->
                                        <div class="leading-4">
                                            <p class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium">50</p>
                                            <span class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">Seconds</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- body Card -->
                            <div class="card-body flex-1 flex flex-col">
                                <div class=" px-6 pt-10  rounded-b-2xl break-words whitespace-normal flex-1 ">
                                    <div class="space-y-1 ">
                                        <p class="text-[18px] text-textprimary font-primaryMedium">
                                            ean Paul (XX) - Landschaft mitSchafe
                                        </p>
                                        <div class="text-[16px] font-primaryMedium">
                                            <p class="text-textsecondary">Artist : <span class="text-textprimary">Pablo
                                                    Picasso</span>
                                            </p>
                                            <p class="text-textsecondary">Current bid :<span class="text-textprimary">$620.00</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Upcoming Section  -->
        <div class="bg-white px-10 py-5 rounded-2xl  text-textprimary font-primarySemibold space-y-10">
            <div class="flex items-center justify-between">
                <p class="text-[18px] sm:text-[30px]">Upcoming Auctions</p>
                <a href="" class="text-textorange text-[12px] sm:text-[16px] font-primarySemibold">View All</a>
            </div>

            <div class="swiper mySwiper">
                <div class="swiper-wrapper items-stretch">

                    <div class="swiper-slide">
                        <div
                            class="card overflow-visible  shadow-[0_2px_15px_rgba(0,0,0,0.10)]   pb-0 rounded-2xl   mb-5 flex flex-col min-h-[485px] ">
                            <div class="relative">
                                <div class="overflow-hidden rounded-t-2xl">
                                    <img src="/../Artbid/public/images/art1.png" alt="Art"
                                        class="w-full h-[300px] object-cover object-[0%_10%] transition duration-500 transform hover:scale-105">
                                </div>

                                <div class="absolute top-5 left-0 w-full px-3 flex items-center justify-between ">
                                    <div class="<?php echo ($status == 'Live') ? 'bg-red-600' : 'bg-green-600'; ?> text-white px-3 py-1 rounded-md text-sm">
                                        <p><?php echo $status; ?></p>
                                    </div>
                                    <i class="fa-regular fa-heart text-textprimary text-[20px] bg-white rounded-full p-1.5 cursor-pointer"></i>
                                </div>


                                <div
                                    class="absolute bg-white w-[90%] left-1/2 -translate-x-1/2 top-full -translate-y-1/2 shadow-md px-3 py-3 rounded-xl z-10">
                                    <div class="flex items-center justify-center text-center gap-x-2 overflow-hidden">
                                        <!-- Days -->
                                        <div class="leading-4">
                                            <p class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium">249</p>
                                            <span class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">Days</span>
                                        </div>

                                        <div class="text-[14px] lg:text-[16px] font-bold text-textprimary">:</div>

                                        <!-- Hours -->
                                        <div class="leading-4">
                                            <p class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium">07</p>
                                            <span class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">Hours</span>
                                        </div>

                                        <div class="text-[14px] lg:text-[16px] font-bold text-textprimary">:</div>

                                        <!-- Minutes -->
                                        <div class="leading-4">
                                            <p class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium">40</p>
                                            <span class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">Minutes</span>
                                        </div>

                                        <div class="text-[14px] lg:text-[16px] font-bold text-textprimary">:</div>

                                        <!-- Seconds -->
                                        <div class="leading-4">
                                            <p class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium">50</p>
                                            <span class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">Seconds</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- body Card -->
                            <div class="card-body flex-1 flex flex-col">
                                <div class=" px-6 pt-10  rounded-b-2xl break-words whitespace-normal flex-1 ">
                                    <div class="space-y-1 ">
                                        <p class="text-[18px] text-textprimary font-primaryMedium">
                                            ean Paul (XX) - Landschaft mitSchafe
                                        </p>
                                        <div class="text-[16px] font-primaryMedium">
                                            <p class="text-textsecondary">Artist : <span class="text-textprimary">Pablo
                                                    Picasso</span>
                                            </p>
                                            <p class="text-textsecondary">Current bid :<span class="text-textprimary">$620.00</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-slide">
                        <div
                            class="card overflow-visible  shadow-[0_2px_15px_rgba(0,0,0,0.10)]   pb-0 rounded-2xl   mb-5 flex flex-col min-h-[485px] ">
                            <div class="relative">
                                <div class="overflow-hidden rounded-t-2xl">
                                    <img src="/../Artbid/public/images/art1.png" alt="Art"
                                        class="w-full h-[300px] object-cover object-[0%_10%] transition duration-500 transform hover:scale-105">
                                </div>

                                <div class="absolute top-5 left-0 w-full px-3 flex items-center justify-between ">
                                    <div class="<?php echo ($status == 'Live') ? 'bg-red-600' : 'bg-green-600'; ?> text-white px-3 py-1 rounded-md text-sm">
                                        <p><?php echo $status; ?></p>
                                    </div>
                                    <i class="fa-regular fa-heart text-textprimary text-[20px] bg-white rounded-full p-1.5 cursor-pointer"></i>
                                </div>


                                <div
                                    class="absolute bg-white w-[90%] left-1/2 -translate-x-1/2 top-full -translate-y-1/2 shadow-md px-3 py-3 rounded-xl z-10">
                                    <div class="flex items-center justify-center text-center gap-x-2 overflow-hidden">
                                        <!-- Days -->
                                        <div class="leading-4">
                                            <p class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium">249</p>
                                            <span class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">Days</span>
                                        </div>

                                        <div class="text-[14px] lg:text-[16px] font-bold text-textprimary">:</div>

                                        <!-- Hours -->
                                        <div class="leading-4">
                                            <p class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium">07</p>
                                            <span class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">Hours</span>
                                        </div>

                                        <div class="text-[14px] lg:text-[16px] font-bold text-textprimary">:</div>

                                        <!-- Minutes -->
                                        <div class="leading-4">
                                            <p class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium">40</p>
                                            <span class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">Minutes</span>
                                        </div>

                                        <div class="text-[14px] lg:text-[16px] font-bold text-textprimary">:</div>

                                        <!-- Seconds -->
                                        <div class="leading-4">
                                            <p class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium">50</p>
                                            <span class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">Seconds</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- body Card -->
                            <div class="card-body flex-1 flex flex-col">
                                <div class=" px-6 pt-10  rounded-b-2xl break-words whitespace-normal flex-1 ">
                                    <div class="space-y-1 ">
                                        <p class="text-[18px] text-textprimary font-primaryMedium">
                                            ean Paul (XX) - Landschaft mitSchafe
                                        </p>
                                        <div class="text-[16px] font-primaryMedium">
                                            <p class="text-textsecondary">Artist : <span class="text-textprimary">Pablo
                                                    Picasso</span>
                                            </p>
                                            <p class="text-textsecondary">Current bid :<span class="text-textprimary">$620.00</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Discover Our Essence -->
        <div class="flex flex-col md:flex-row items-start gap-x-10 lg:gap-x-0  py-[100px] ">
            <div class="w-1/2 relative flex flex-col items-center">
                <img src="/../Artbid/public/images/bg.png" alt="image" class="w-[500px] h-auto rounded-xl">
                <div
                    class="absolute bottom-0 translate-y-1/2 bg-white flex items-center justify-between w-[60%] px-5 py-2 rounded-xl shadow">
                    <div class="text-center">
                        <p class="text-[30px] text-textprimary font-primaryMedium">65K</p>
                        <span class="text-[16px] text-textsecondary font-primaryMedium">Customers</span>
                    </div>
                    <div class="text-center">
                        <p class="text-[30px] text-textprimary font-primaryMedium">500</p>
                        <span class="text-[16px] text-textsecondary font-primaryMedium">Auctions</span>
                    </div>
                    <div class="text-center">
                        <p class="text-[30px] text-textprimary font-primaryMedium">1K</p>
                        <span class="text-[16px] text-textsecondary font-primaryMedium">Bidders</span>
                    </div>
                </div>
            </div>
            <div class="w-1/2 flex flex-col">
                <p class="text-[40px] text-textorange font-primaryBold">
                    Discover Our Essence
                </p>
                <span class="text-[20px] text-textsecondary  font-primaryRegular">
                    At Artmart, we are a community of passionate art lovers committed to bridging the gap between artists
                    and collectors through vibrant, engaging auctions. Our platform showcases the creativity and cultural
                    richness of artists worldwide, offering a space where their original works can be discovered, admired,
                    and owned.
                </span>
                <div class="grid grid-cols-2 gap-y-2 mt-6 text-[20px] text-textprimary font-primarySemibold">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-check text-green-500"></i>
                        <p class="m-0">Integrity</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-check text-green-500"></i>
                        <p class="m-0">Diversity</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-check text-green-500"></i>
                        <p class="m-0">Accessibility</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-check text-green-500"></i>
                        <p class="m-0">Support</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Artwork Category  -->
        <div class="bg-white px-10 py-5 space-y-10 rounded-2xl">
            <p class="text-[18px] sm:text-[30px] text-textprimary font-primarySemibold ">
                Artwork Category
            </p>
            <div class="flex gap-4  h-[500px]  pb-5">
                <!-- ซ้าย -->
                <div class="flex flex-col gap-4 w-[40%]">
                    <div
                        class="flex-1 relative overflow-hidden rounded-xl transition-transform duration-500 ease-in-out hover:scale-95 hover:shadow-lg hover:shadow-black/10">
                        <img src="/../Artbid/public/images/Prints.png" alt="Prints" class="w-full h-full object-cover ">
                        <div class="absolute inset-x-0 bottom-3 flex items-center justify-center ">
                            <p class="text-white text-[30px] font-primaryMedium text-center">Prints & Multiples Art</p>
                        </div>
                    </div>
                    <div
                        class="flex-1 relative overflow-hidden rounded-xl transition-transform duration-500 ease-in-out hover:scale-95 hover:shadow-lg hover:shadow-black/10">
                        <img src="/../Artbid/public/images/Photography.png" alt="" class="w-full h-full object-cover ">
                        <div class="absolute inset-x-0 bottom-3 flex items-center justify-center">
                            <p class="text-white text-[30px] font-primaryMedium text-center">Photography Art</p>
                        </div>
                    </div>
                </div>

                <!-- กลาง -->
                <div
                    class="w-1/3 relative transition-transform duration-500 ease-in-out hover:scale-95 hover:shadow-lg hover:shadow-black/10">
                    <img src="/../Artbid/public/images/Classical.png" alt="" class="w-full h-full object-cover rounded-xl">
                    <div class="absolute inset-x-0 bottom-3 flex items-center justify-center">
                        <p class="text-white text-[30px] font-primaryMedium text-center">Classical Art</p>
                    </div>
                </div>

                <!-- ขวา -->
                <div
                    class="w-1/2 relative bg-cover bg-center rounded-xl transition-transform duration-500 ease-in-out hover:scale-95 hover:shadow-lg hover:shadow-black/10">
                    <img src="/../Artbid/public/images/Modern.png" alt="" class="w-full h-full object-cover rounded-xl">
                    <div class="absolute inset-x-0 bottom-3 flex items-center justify-center ">
                        <p class="text-white text-[30px] font-primaryMedium text-center">Modern & Contemporary Art</p>
                    </div>
                </div>
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
    </script>
</body>

</html>