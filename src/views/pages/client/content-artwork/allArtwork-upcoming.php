<?php
require __DIR__ . '/../../../../controllers/controllers-backend/content-client/ClientController.php';
$artworks = ClientController::getAllArtwork('Upcoming');
?>

<body>
    <div class="bg-white px-10 py-5 rounded-2xl  text-textprimary font-primarySemibold space-y-10 ">
        <div class="flex items-center justify-between">
            <p class="text-[18px] sm:text-[30px]">Currently Auctioning</p>
        </div>

        <div class="grid grid-cols-4 gap-4 w-full">
            <?php foreach ($artworks as $i => $art): ?>
                <div
                    class="card overflow-visible  shadow-[0_2px_15px_rgba(0,0,0,0.10)]   pb-0 rounded-2xl   mb-5 flex flex-col min-h-[485px] ">
                    <div class="relative">
                        <div class="overflow-hidden rounded-t-2xl">
                            <img src="/image?id=<?= htmlspecialchars($art['id']) ?>" alt="Art"
                                class="w-full h-[300px] object-cover object-[0%_10%] transition duration-500 transform hover:scale-105">
                        </div>

                        <div class="absolute top-5 left-0 w-full px-3 flex items-center justify-between ">
                            <div class="bg-green-600 text-white px-3 py-1 rounded-md text-sm">
                                <p><?= htmlspecialchars($art['type']) ?></p>
                            </div>
                            <i
                                class="fa-regular fa-heart text-textprimary text-[20px] bg-white rounded-full p-1.5 cursor-pointer"></i>
                        </div>


                        <div data-startbidding="<?= htmlspecialchars($art['startbidding']) ?>"
                            data-endbidding="<?= htmlspecialchars($art['endbidding']) ?>"
                            class="countdown-timer absolute bg-white w-[90%] left-1/2 -translate-x-1/2 top-full -translate-y-1/2 shadow-md px-3 py-3 rounded-xl z-10">
                            <div class="flex items-center justify-center text-center gap-x-2 overflow-hidden">
                                <!-- Days -->
                                <div class="leading-4">
                                    <p
                                        class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium countdown-days">
                                        00</p>
                                    <span
                                        class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">Days</span>
                                </div>

                                <div class="text-[14px] lg:text-[16px] font-bold text-textprimary">:</div>

                                <!-- Hours -->
                                <div class="leading-4">
                                    <p
                                        class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium countdown-hours">
                                        00</p>
                                    <span
                                        class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">Hours</span>
                                </div>

                                <div class="text-[14px] lg:text-[16px] font-bold text-textprimary">:</div>

                                <!-- Minutes -->
                                <div class="leading-4">
                                    <p
                                        class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium countdown-minutes">
                                        00</p>
                                    <span
                                        class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">Minutes</span>
                                </div>

                                <div class="text-[14px] lg:text-[16px] font-bold text-textprimary">:</div>

                                <!-- Seconds -->
                                <div class="leading-4">
                                    <p
                                        class="text-[14px] lg:text-[16px] text-textprimary font-primaryMedium countdown-seconds">
                                        00</p>
                                    <span
                                        class="text-[10px] lg:text-[12px] text-textsecondary font-primaryMedium">Seconds</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- body Card -->
                    <div class="card-body flex-1 flex flex-col">
                        <div class=" px-6 pt-10  rounded-b-2xl break-words whitespace-normal flex-1 ">
                            <div class="space-y-1 ">
                                <p class="text-[18px] text-textprimary font-primaryMedium">
                                    <?= htmlspecialchars($art['title']) ?>
                                </p>
                                <div class="text-[16px] font-primaryMedium">
                                    <p class="text-textsecondary">Artist :
                                        <span class="text-textprimary"><?= htmlspecialchars($art['artist']) ?></span>
                                    </p>
                                    <p class="text-textsecondary">Current bid :<span class="text-textprimary">
                                            $<?= htmlspecialchars($art['startingprice']) ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>

</body>