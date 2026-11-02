<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'European IT Institute')</title>
    <meta name="description" content="@yield('description', '')">

    {{-- Landing pages use Inter (countdown) and Noto Sans Bengali (offline design) on top of the site fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Noto+Sans+Bengali:wght@400;600;700&family=Inter:wght@500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Poppins:wght@400;500;700&family=Roboto:wght@600&display=swap" rel="stylesheet">

    <link rel="icon" href="{{ asset('images/fsv.webp') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
{{-- A sales page: its own header (logo, offer countdown, phones), no main menu, and a small copyright bar --}}
<body class="bg-white font-bangla text-heading antialiased">
    @yield('content')

    <footer class="bg-[#222] p-5 text-center font-sans text-[15px] text-white">
        Copyright ©{{ date('Y') }} {{ $siteData['name'] }}. All Rights Reserved.
    </footer>

    {{-- Only the WhatsApp button (the live landing pages have no Demo Class / Discount tabs) --}}
    <a href="https://api.whatsapp.com/send?phone={{ $siteData['whatsapp'] }}" target="_blank" rel="noopener" aria-label="Chat on WhatsApp"
       class="fixed right-5 bottom-5 z-40 flex h-[54px] w-[54px] items-center justify-center rounded-full bg-[#25D366] text-3xl text-white shadow-lg transition hover:scale-110">
        <i class="bi bi-whatsapp"></i>
    </a>
</body>
</html>
