@extends('client.layout')
@section('title')
    Home
@endsection
@section('content')
    <div class="space-y-[100px]">
        <div class="flex flex-col items-center flex-wrap w-full bg-white rounded-2xl md:flex md:flex-row">
            <div class="w-4/5 md:w-1/2 flex flex-col md:pl-10">
                <p class="text-[20px] text-textorange font-primarySemibold leading-tight lg:text-[40px]">Explore global
                    masterpieces.
                    <br>Bid and collect the
                    art you fall in love with.
                </p>
                <span class="text-[16px] text-textsecondary font-primaryMedium lg:text-[20px]">Unlock your imagination with
                    our carefully
                    curated collection of original artworks.</span>
                <x-content-button.fullbutton class="py-[12px] md:max-w-[170px] rounded-xl mt-5">
                    Start Bidding
                </x-content-button.fullbutton>
            </div>
            <div class="w-4/5 md:w-1/2 ">
                <img src="{{asset('images/home2-banner-img.webp')}}" alt="" class="w-full">
            </div>
        </div>

        {{-- Currently Section --}}
        <div class="bg-white px-10 py-5 rounded-2xl  text-textprimary font-primarySemibold space-y-10">
            <div class="flex items-center justify-between">
                <p class="text-[18px] sm:text-[30px]">Currently Auctioning</p>
                <a href="" class="text-textorange text-[12px] sm:text-[16px] font-primarySemibold">View All</a>
            </div>

            <div class="swiper mySwiper">
                <div class="swiper-wrapper items-stretch">

                    <div class="swiper-slide">
                        <x-content-card.card image="images/bg.png" :type="'live'" title="Live">
                            <div class="space-y-1 ">
                                <p class="text-[18px] text-textprimary font-primaryMedium">
                                    123456789101112131
                                </p>
                                <div class="text-[16px] font-primaryMedium">
                                    <p class="text-textsecondary">Artist : <span class="text-textprimary">Pablo
                                            Picasso</span>
                                    </p>
                                    <p class="text-textsecondary">Current bid :<span class="text-textprimary">$620.00</span>
                                    </p>
                                </div>
                            </div>
                        </x-content-card.card>
                    </div>

                    <div class="swiper-slide">
                        <x-content-card.card image="images/art2.png" type="live" title="Live">
                            <div class="space-y-1 ">
                                <p class="text-[18px] text-textprimary font-primaryMedium">Herman Conrad (1957) -
                                    Battaglia
                                    di
                                    Trafalgar</p>
                                <div class="text-[16px] font-primaryMedium">
                                    <p class="text-textsecondary">Artist : <span class="text-textprimary">Pablo
                                            Picasso</span>
                                    </p>
                                    <p class="text-textsecondary">Current bid :<span class="text-textprimary">$620.00</span>
                                    </p>
                                </div>
                            </div>
                        </x-content-card.card>
                    </div>

                    <div class="swiper-slide">
                        <x-content-card.card image="images/art3.png" type="live" title="Live">
                            <div class="space-y-1">
                                <p class="text-[18px] text-textprimary font-primaryMedium">Herman Conrad (1957) -
                                    Battaglia
                                    di
                                    Trafalgar</p>
                                <div class="text-[16px] font-primaryMedium">
                                    <p class="text-textsecondary">Artist : <span class="text-textprimary">Pablo
                                            Picasso</span>
                                    </p>
                                    <p class="text-textsecondary">Current bid :<span class="text-textprimary">$620.00</span>
                                    </p>
                                </div>
                            </div>
                        </x-content-card.card>
                    </div>

                    <div class="swiper-slide">
                        <x-content-card.card image="images/art1.png" type="live" title="Live">
                            <div class="space-y-1">
                                <p class="text-[18px] text-textprimary font-primaryMedium">Herman Conrad (1957) -
                                    Battaglia
                                    di
                                    Trafalgar</p>
                                <div class="text-[16px] font-primaryMedium">
                                    <p class="text-textsecondary">Artist : <span class="text-textprimary">Pablo
                                            Picasso</span>
                                    </p>
                                    <p class="text-textsecondary">Current bid :<span class="text-textprimary">$620.00</span>
                                    </p>
                                </div>
                            </div>
                        </x-content-card.card>
                    </div>

                    <div class="swiper-slide">
                        <x-content-card.card image="images/art4.png" type="live" title="Live">
                            <div class="space-y-1">
                                <p class="text-[18px] text-textprimary font-primaryMedium">Herman Conrad (1957) -
                                    Battaglia
                                    di
                                    Trafalgar</p>
                                <div class="text-[16px] font-primaryMedium">
                                    <p class="text-textsecondary">Artist : <span class="text-textprimary">Pablo
                                            Picasso</span>
                                    </p>
                                    <p class="text-textsecondary">Current bid :<span class="text-textprimary">$620.00</span>
                                    </p>
                                </div>
                            </div>
                        </x-content-card.card>
                    </div>
                </div>
            </div>
        </div>

        {{-- Upcoming Section --}}
        <div class="bg-white px-10 py-5 rounded-2xl  text-textprimary font-primarySemibold space-y-10">
            <div class="flex items-center justify-between">
                <p class="text-[18px] sm:text-[30px]">Upcoming Auctions</p>
                <a href="" class="text-textorange text-[12px] sm:text-[16px] font-primarySemibold">View All</a>
            </div>

            <div class="swiper mySwiper">
                <div class="swiper-wrapper items-stretch">

                    <div class="swiper-slide">
                        <x-content-card.card image="images/bg.png" :type="'upcoming'" title="Upcoming">
                            <div class="space-y-1 ">
                                <p class="text-[18px] text-textprimary font-primaryMedium">
                                    123456789101112131
                                </p>
                                <div class="text-[16px] font-primaryMedium">
                                    <p class="text-textsecondary">Artist : <span class="text-textprimary">Pablo
                                            Picasso</span>
                                    </p>
                                    <p class="text-textsecondary">Current bid :<span class="text-textprimary">$620.00</span>
                                    </p>
                                </div>
                            </div>
                        </x-content-card.card>
                    </div>

                    <div class="swiper-slide">
                        <x-content-card.card image="images/art2.png" type="upcoming" title="Upcoming">
                            <div class="space-y-1 ">
                                <p class="text-[18px] text-textprimary font-primaryMedium">Herman Conrad (1957) -
                                    Battaglia
                                    di
                                    Trafalgar</p>
                                <div class="text-[16px] font-primaryMedium">
                                    <p class="text-textsecondary">Artist : <span class="text-textprimary">Pablo
                                            Picasso</span>
                                    </p>
                                    <p class="text-textsecondary">Current bid :<span class="text-textprimary">$620.00</span>
                                    </p>
                                </div>
                            </div>
                        </x-content-card.card>
                    </div>

                    <div class="swiper-slide">
                        <x-content-card.card image="images/art3.png" type="upcoming" title="Upcoming">
                            <div class="space-y-1">
                                <p class="text-[18px] text-textprimary font-primaryMedium">Herman Conrad (1957) -
                                    Battaglia
                                    di
                                    Trafalgar</p>
                                <div class="text-[16px] font-primaryMedium">
                                    <p class="text-textsecondary">Artist : <span class="text-textprimary">Pablo
                                            Picasso</span>
                                    </p>
                                    <p class="text-textsecondary">Current bid :<span class="text-textprimary">$620.00</span>
                                    </p>
                                </div>
                            </div>
                        </x-content-card.card>
                    </div>

                    <div class="swiper-slide">
                        <x-content-card.card image="images/art1.png" type="upcoming" title="Upcoming">
                            <div class="space-y-1">
                                <p class="text-[18px] text-textprimary font-primaryMedium">Herman Conrad (1957) -
                                    Battaglia
                                    di
                                    Trafalgar</p>
                                <div class="text-[16px] font-primaryMedium">
                                    <p class="text-textsecondary">Artist : <span class="text-textprimary">Pablo
                                            Picasso</span>
                                    </p>
                                    <p class="text-textsecondary">Current bid :<span class="text-textprimary">$620.00</span>
                                    </p>
                                </div>
                            </div>
                        </x-content-card.card>
                    </div>

                    <div class="swiper-slide">
                        <x-content-card.card image="images/art4.png" type="upcoming" title="Upcoming">
                            <div class="space-y-1">
                                <p class="text-[18px] text-textprimary font-primaryMedium">Herman Conrad (1957) -
                                    Battaglia
                                    di
                                    Trafalgar</p>
                                <div class="text-[16px] font-primaryMedium">
                                    <p class="text-textsecondary">Artist : <span class="text-textprimary">Pablo
                                            Picasso</span>
                                    </p>
                                    <p class="text-textsecondary">Current bid :<span class="text-textprimary">$620.00</span>
                                    </p>
                                </div>
                            </div>
                        </x-content-card.card>
                    </div>
                </div>
            </div>
        </div>

        {{-- Discover Our Essence --}}
        <div class="flex items-start mx-[100px] py-[100px]">
            <div class="w-1/2 relative flex flex-col items-center">
                <img src="{{asset('images/bg.png')}}" alt="image" class="w-[500px] h-auto rounded-xl">
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

        {{-- Artwork Category --}}
        <div class="bg-white px-10 py-5 space-y-10 rounded-2xl">
            <p class="text-[18px] sm:text-[30px] text-textprimary font-primarySemibold ">
                Artwork Category
            </p>
            <div class="flex gap-4  h-[500px]  pb-5">
                <!-- ซ้าย -->
                <div class="flex flex-col gap-4 w-[40%]">
                    <div
                        class="flex-1 relative overflow-hidden rounded-xl transition-transform duration-500 ease-in-out hover:scale-95 hover:shadow-lg hover:shadow-black/10">
                        <img src="{{asset('images/Prints.png')}}" alt="Prints" class="w-full h-full object-cover ">
                        <div class="absolute inset-x-0 bottom-3 flex items-center justify-center ">
                            <p class="text-white text-[30px] font-primaryMedium text-center">Prints & Multiples Art</p>
                        </div>
                    </div>
                    <div
                        class="flex-1 relative overflow-hidden rounded-xl transition-transform duration-500 ease-in-out hover:scale-95 hover:shadow-lg hover:shadow-black/10">
                        <img src="{{asset('images/Photography.png')}}" alt="" class="w-full h-full object-cover ">
                        <div class="absolute inset-x-0 bottom-3 flex items-center justify-center">
                            <p class="text-white text-[30px] font-primaryMedium text-center">Photography Art</p>
                        </div>
                    </div>
                </div>

                <!-- กลาง -->
                <div
                    class="w-1/3 relative transition-transform duration-500 ease-in-out hover:scale-95 hover:shadow-lg hover:shadow-black/10">
                    <img src="{{asset('images/Classical.png')}}" alt="" class="w-full h-full object-cover rounded-xl">
                    <div class="absolute inset-x-0 bottom-3 flex items-center justify-center">
                        <p class="text-white text-[30px] font-primaryMedium text-center">Classical Art</p>
                    </div>
                </div>

                <!-- ขวา -->
                <div
                    class="w-1/2 relative bg-cover bg-center rounded-xl transition-transform duration-500 ease-in-out hover:scale-95 hover:shadow-lg hover:shadow-black/10">
                    <img src="{{asset('images/Modern.png')}}" alt="" class="w-full h-full object-cover rounded-xl">
                    <div class="absolute inset-x-0 bottom-3 flex items-center justify-center ">
                        <p class="text-white text-[30px] font-primaryMedium text-center">Modern & Contemporary Art</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
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
@endsection