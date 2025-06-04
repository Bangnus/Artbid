<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <title>@yield('title')</title>
</head>

<body>
    {{-- Navbar --}}
    <x-content-navbar.navbarPage />

    {{-- Content --}}
    <div class="mx-5 md:mx-20 my-5">
        @yield('content')
    </div>

    {{-- Footer --}}
    <x-content-footer.footer />


    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    @yield('scripts')
    
</body>


</html>