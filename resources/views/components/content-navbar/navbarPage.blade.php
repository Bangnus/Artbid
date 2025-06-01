<div class="bg-white px-20 py-1 flex items-center justify-between">
    <a href="{{url('/')}}" class="flex items-center">
        <img src="{{ asset('icons/AerBid Logo.png') }}" alt="Logo" width="80" height="80">
        <h1 class="text-textorange text-[30px] font-primaryBold">Artbid</h1>
    </a>
    <div class="bg-[#F0F0F0] px-[30px] py-[15px] rounded-2xl w-[872px] flex items-center gap-3">
        <i class="fa-solid fa-magnifying-glass text-textsecondary"></i>
        <input class="bg-[#F0F0F0] focus:outline-none focus:right-0 w-full" type="text"
            placeholder="Search your favorite art to bid now">
    </div>
    <div class="flex items-center gap-x-[30px]">
        <i class="fa-regular fa-heart text-textprimary text-[20px]"></i>
        <x-content-button.fullbutton class="rounded-[50px] text-[16px] px-[30px] py-2">
            Sign in
        </x-content-button.fullbutton>
    </div>
</div>