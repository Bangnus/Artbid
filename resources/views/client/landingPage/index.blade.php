@extends('client.layout')
@section('title')
    Home
@endsection
@section('content')
    <div class="mx-20 my-5">
        <div class="flex items-center w-full bg-white rounded-2xl">
            <div class="w-1/2 flex flex-col pl-10">
                <p class="text-[40px] text-textorange font-primarySemibold leading-tight">Explore global masterpieces. <br>Bid and collect the
                    art you fall in love with.</p>
                <span class="text-[20px] text-textsecondary font-primaryMedium">Unlock your imagination with our carefully curated collection of original artworks.</span>
                <x-content-button.fullbutton class=" py-[12px] max-w-[170px] rounded-xl mt-5">
                    Start Bidding
                </x-content-button.fullbutton>
            </div>
            <div class="w-1/2">
                <img src="{{asset('images/home2-banner-img.webp')}}" alt="" class="w-full">
            </div>
        </div>

    </div>
@endsection