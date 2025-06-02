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

        <div class="bg-white px-10 py-5 rounded-2xl text-[30px] text-textprimary font-primarySemibold space-y-10">
            <div class="flex items-center justify-between">
                <p>Currently Auctioning</p>
                <a href="" class="text-textorange text-[16px] font-primarySemibold">View All</a>
            </div>
            <div class="flex items-center justify-between">
                <x-content-card.card image="images/art1.png">
                    <div class="space-y-1">
                        <p class="text-[18px] text-textprimary font-primaryMedium">Herman Conrad (1957) - Battaglia di
                            Trafalgar</p>
                        <div class="text-[16px] font-primaryMedium">
                            <p class="text-textsecondary">Artist : <span class="text-textprimary">Pablo Picasso</span>
                            </p>
                            <p class="text-textsecondary">Current bid :<span class="text-textprimary">$620.00</span></p>
                        </div>
                    </div>
                </x-content-card.card>
                <x-content-card.card image="images/art2.png">
                    <div class="space-y-1">
                        <p class="text-[18px] text-textprimary font-primaryMedium">Herman Conrad (1957) - Battaglia di
                            Trafalgar</p>
                        <div class="text-[16px] font-primaryMedium">
                            <p class="text-textsecondary">Artist : <span class="text-textprimary">Pablo Picasso</span>
                            </p>
                            <p class="text-textsecondary">Current bid :<span class="text-textprimary">$620.00</span></p>
                        </div>
                    </div>
                </x-content-card.card>
                <x-content-card.card image="images/art3.png">
                    <div class="space-y-1">
                        <p class="text-[18px] text-textprimary font-primaryMedium">Herman Conrad (1957) - Battaglia di
                            Trafalgar</p>
                        <div class="text-[16px] font-primaryMedium">
                            <p class="text-textsecondary">Artist : <span class="text-textprimary">Pablo Picasso</span>
                            </p>
                            <p class="text-textsecondary">Current bid :<span class="text-textprimary">$620.00</span></p>
                        </div>
                    </div>
                </x-content-card.card>
                <x-content-card.card image="images/art1.png">
                    <div class="space-y-1">
                        <p class="text-[18px] text-textprimary font-primaryMedium">Herman Conrad (1957) - Battaglia di
                            Trafalgar</p>
                        <div class="text-[16px] font-primaryMedium">
                            <p class="text-textsecondary">Artist : <span class="text-textprimary">Pablo Picasso</span>
                            </p>
                            <p class="text-textsecondary">Current bid :<span class="text-textprimary">$620.00</span></p>
                        </div>
                    </div>
                </x-content-card.card>
            </div>

        </div>
    </div>
@endsection