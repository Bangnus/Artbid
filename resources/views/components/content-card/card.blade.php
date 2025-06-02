<div class="bg-white max-w-[300px] rounded-2xl shadow ">
    <div class="relative">
        <div class="overflow-hidden rounded-t-2xl">
            <img src="{{asset($image)}}" alt="Art" class="w-[300px] h-[300px] object-cover object-[0%_10%]  transition duration-500 transform hover:scale-105">
        </div>

        <div
            class=" absolute bg-white w-[90%] left-1/2 -translate-x-1/2 top-full -translate-y-1/2 shadow-lg px-6 py-3 rounded-xl z-10">
            <div class="flex items-center justify-center text-center space-x-2">
                <!-- Days -->
                <div class="leading-4">
                    <p class="text-[18px] text-textprimary font-primaryMedium">249</p>
                    <span class="text-[12px] text-textsecondary font-primaryMedium">Days</span>
                </div>

                <!-- Separator -->
                <div class="text-[18px] font-bold text-textprimary">:</div>

                <!-- Hours -->
                <div class="leading-4">
                    <p class="text-[18px] text-textprimary font-primaryMedium">07</p>
                    <span class="text-[12px] text-textsecondary font-primaryMedium">Hours</span>
                </div>

                <!-- Separator -->
                <div class="text-[18px] font-bold text-textprimary">:</div>

                <!-- Minutes -->
                <div class="leading-4">
                    <p class="text-[18px] text-textprimary font-primaryMedium">40</p>
                    <span class="text-[12px] text-textsecondary font-primaryMedium">Minutes</span>
                </div>

                <!-- Separator -->
                <div class="text-[18px] font-bold text-textprimary">:</div>

                <!-- Seconds -->
                <div class="leading-4">
                    <p class="text-[18px] text-textprimary font-primaryMedium">50</p>
                    <span class="text-[12px] text-textsecondary font-primaryMedium">Seconds</span>
                </div>
            </div>
        </div>
    </div>

    <div class="p-5 mt-5">
        {{$slot}}
    </div>

</div>