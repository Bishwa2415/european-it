<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | European IT Admin</title>
    <meta name="robots" content="noindex, nofollow">   {{-- keep the dashboard out of Google --}}

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/fsv.webp') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/admin.js'])
</head>
<body class="bg-[#F4F6F9] text-heading antialiased">

@php
    // Sidebar menu. 'route' = null means the module is not built yet
    $menu = [
        ['label' => 'Dashboard',   'icon' => 'bi-speedometer2',     'route' => 'admin.dashboard',     'active' => 'admin.dashboard'],
        ['label' => 'Home Page',   'icon' => 'bi-house',            'route' => 'admin.home-page.edit', 'active' => 'admin.home-page.*'],
        ['label' => 'Why choose us', 'icon' => 'bi-stars',           'route' => 'admin.home-content.index', 'active' => 'admin.home-content.*', 'collection' => 'features'],
        ['label' => 'Specialty blocks', 'icon' => 'bi-card-text',    'route' => 'admin.home-content.index', 'active' => 'admin.home-content.*', 'collection' => 'content-blocks'],
        ['label' => 'Success videos', 'icon' => 'bi-play-btn',       'route' => 'admin.home-content.index', 'active' => 'admin.home-content.*', 'collection' => 'success-videos'],
        ['label' => 'Testimonials', 'icon' => 'bi-chat-quote',      'route' => 'admin.home-content.index', 'active' => 'admin.home-content.*', 'collection' => 'testimonials'],
        ['label' => 'Support team', 'icon' => 'bi-people',           'route' => 'admin.home-content.index', 'active' => 'admin.home-content.*', 'collection' => 'team-members'],
        ['label' => 'Partners',     'icon' => 'bi-building',         'route' => 'admin.home-content.index', 'active' => 'admin.home-content.*', 'collection' => 'partners'],
        ['label' => 'Courses',     'icon' => 'bi-journal-bookmark', 'route' => 'admin.courses.index', 'active' => 'admin.courses.*'],
        ['label' => 'Pages',       'icon' => 'bi-file-earmark',     'route' => 'admin.content.index', 'active' => 'admin.content.*', 'type' => 'page'],
        ['label' => 'Posts',       'icon' => 'bi-pencil-square',    'route' => 'admin.content.index', 'active' => 'admin.content.*', 'type' => 'post'],
        ['label' => 'Page sections','icon' => 'bi-columns-gap',     'route' => 'admin.content.index', 'active' => 'admin.content.*', 'type' => 'section'],
        ['label' => 'Popups / modals','icon' => 'bi-window',        'route' => 'admin.content.index', 'active' => 'admin.content.*', 'type' => 'popup'],
        ['label' => 'Menus',       'icon' => 'bi-list',             'route' => 'admin.content.index', 'active' => 'admin.content.*', 'type' => 'menu'],
        ['label' => 'Site settings','icon' => 'bi-sliders',          'route' => 'admin.content.index', 'active' => 'admin.content.*', 'type' => 'setting'],
        ['label' => 'Categories',  'icon' => 'bi-grid',             'route' => null],
        ['label' => 'Mentors',     'icon' => 'bi-person-badge',     'route' => null],
        ['label' => 'Orders',      'icon' => 'bi-bag-check',        'route' => null],
        ['label' => 'Students',    'icon' => 'bi-people',           'route' => null],
        ['label' => 'Gallery',     'icon' => 'bi-images',           'route' => null],
        ['label' => 'Reviews',     'icon' => 'bi-chat-quote',       'route' => null],
        ['label' => 'Settings',    'icon' => 'bi-gear',             'route' => null],
    ];
@endphp

<div class="flex min-h-screen">

    {{-- ===== Sidebar ===== --}}
    <aside class="hidden lg:flex w-64 shrink-0 flex-col bg-[#202A38] text-white">
        <a href="{{ route('admin.dashboard') }}" class="flex h-16 items-center px-6 border-b border-white/10">
            <img src="{{ asset('images/footer/footer-logo.svg') }}" alt="European IT" class="h-9">
        </a>

        <nav class="flex-1 space-y-1 p-3 text-sm">
            @foreach ($menu as $item)
                @if ($item['route'])
                    <a href="{{ route($item['route'], isset($item['type']) ? ['type' => $item['type']] : (isset($item['collection']) ? ['collection' => $item['collection']] : [])) }}"
                       @class([
                           'flex items-center gap-3 rounded-lg px-3 py-2.5 transition',
                           'bg-primary text-white' => request()->routeIs($item['active']) && (! isset($item['type']) || request()->route('type') === $item['type']) && (! isset($item['collection']) || request()->route('collection') === $item['collection']),
                           'text-white/70 hover:bg-white/10 hover:text-white' => ! (request()->routeIs($item['active']) && (! isset($item['type']) || request()->route('type') === $item['type']) && (! isset($item['collection']) || request()->route('collection') === $item['collection'])),
                       ])>
                        <i class="bi {{ $item['icon'] }} text-base"></i> {{ $item['label'] }}
                    </a>
                @else
                    <span class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-white/30 cursor-not-allowed">
                        <i class="bi {{ $item['icon'] }} text-base"></i> {{ $item['label'] }}
                        <span class="ml-auto rounded bg-white/10 px-1.5 text-[10px] uppercase">soon</span>
                    </span>
                @endif
            @endforeach
        </nav>

        <a href="{{ url('/') }}" target="_blank" class="m-3 flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm text-white/70 hover:bg-white/10">
            <i class="bi bi-box-arrow-up-right"></i> View website
        </a>
    </aside>

    {{-- ===== Main area ===== --}}
    <div class="flex min-w-0 flex-1 flex-col">

        {{-- Top bar --}}
        <header class="flex h-16 items-center justify-between border-b border-gray-200 bg-white px-6">
            <h1 class="font-heading text-lg font-bold">@yield('title', 'Dashboard')</h1>

            <div class="flex items-center gap-4 text-sm">
                <span class="hidden sm:inline text-gray-500">
                    <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                </span>
                {{-- Logout must be POST + @csrf (a plain link could be abused by other sites) --}}
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button class="rounded-lg border border-gray-300 px-3 py-1.5 hover:bg-gray-50">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </header>

        <main class="flex-1 p-6">
            {{-- Green message after save/delete (set with ->with('success', '...') in a controller) --}}
            @if (session('success'))
                <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

</body>
</html>
