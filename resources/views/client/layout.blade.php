<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>@yield('title')</title>
</head>

<body>
    {{-- Navbar --}}
    <x-content-navbar.navbarPage />

    {{-- Content --}}
    <div class="">
        @yield('content')
    </div>

    {{-- Footer --}}
    <x-content-footer.footer/>
</body>

</html>
