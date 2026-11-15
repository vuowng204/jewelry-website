<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Luna Jewelry')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-white font-storefront text-[#342821] antialiased">
    @include('partials.header')

    <main class="w-full flex-1" id="main-content">
        @yield('content')
    </main>

    @include('partials.footer')
</body>
</html>
