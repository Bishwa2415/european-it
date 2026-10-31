<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'European IT Institute | Professional IT Training Institute in Bangladesh')</title>
    <meta name="description" content="@yield('description', 'European IT Institute provides professional IT training in Bangladesh.')">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">

    {{-- Tailwind CSS + JS (Vite) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-body antialiased">

    {{-- Header ekhane ashbe (Part 2) --}}

    <main>
        @yield('content')
    </main>

    {{-- Footer ekhane ashbe (Part 3) --}}

</body>
</html>