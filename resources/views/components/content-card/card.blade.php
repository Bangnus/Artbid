@php
    $color = $badgeColor();
@endphp
<div
    class="card overflow-visible  shadow-[0_2px_15px_rgba(0,0,0,0.10)]   pb-0 rounded-2xl   mb-5 flex flex-col min-h-[485px] ">
    <div class="relative">
        <div class="overflow-hidden rounded-t-2xl">
            <img src="{{ asset($image) }}" alt="Art"
                class="w-full h-[300px] object-cover object-[0%_10%] transition duration-500 transform hover:scale-105">
        </div>

        <div class="absolute top-5 left-0 w-full px-3 flex items-center justify-between ">
            <div class=" {{ $color }} text-white px-3 py-1 rounded-md text-sm">
                <p>{{$title}}</p>
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

    <!-- ขยายความสูงเพื่อให้ content เต็มเท่าๆ กัน -->
    <div class="card-body flex-1 flex flex-col">
        <div class=" px-6 pt-10  rounded-b-2xl break-words whitespace-normal flex-1 ">
            {{$slot}}

        </div>
    </div>
</div>