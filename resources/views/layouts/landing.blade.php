<!doctype html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs"></script>
    @stack('head')
    <style>
        :root {
            --landing-body: #f5f7fb;
            --landing-card: #ffffff;
            --landing-muted: #6b7280;
            --nav-glow: linear-gradient(135deg, #0ea5e9 0%, #10b981 50%, #6366f1 100%);
        }
        body.landing-body {
            font-family: 'Plus Jakarta Sans', 'Figtree', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: radial-gradient(circle at 20% 20%, rgba(16,185,129,0.08), transparent 25%), radial-gradient(circle at 80% 10%, rgba(14,165,233,0.08), transparent 22%), var(--landing-body);
            color: #0f172a;
            letter-spacing: -0.01em;
        }
        .brand-title {
            font-family: 'Space Grotesk', 'Plus Jakarta Sans', system-ui, sans-serif;
            letter-spacing: -0.02em;
        }
        .nav-shell {
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            box-shadow: 0 10px 40px rgba(16,24,40,0.15);
        }
        p { line-height: 1.75; }
        [x-cloak]{ display:none !important; }
    </style>
    </head>
<body class="landing-body min-h-full">
<header class="fixed top-0 left-0 right-0 z-40 border-b border-transparent bg-gradient-to-r from-emerald-700/95 via-emerald-600/90 to-indigo-700/95 backdrop-blur">
    <div x-data="{ menuOpen: false }" class="max-w-6xl mx-auto px-4 py-4">
        <div class="flex items-center justify-between text-white">
            <a href="{{ route('home') }}" class="font-semibold text-lg brand-title tracking-tight flex items-center gap-2">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-white/15 border border-white/25 shadow-sm">M</span>
                Masjid
            </a>
            <button class="md:hidden inline-flex items-center justify-center rounded-full border border-white/30 p-2 text-white hover:bg-white/10"
                    @click="menuOpen = !menuOpen"
                    aria-label="Toggle menu">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path :class="{'hidden': menuOpen, 'block': !menuOpen}" stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16"/>
                    <path :class="{'block': menuOpen, 'hidden': !menuOpen}" stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M6 18L18 6"/>
                </svg>
            </button>
        </div>
        @php
            $landingNavBase = 'px-4 py-2 rounded-xl text-sm font-semibold tracking-wide transition duration-150 border';
            $landingNavActive = function($pattern) {
                return request()->routeIs($pattern)
                    ? 'bg-white text-emerald-700 shadow-md border-white/60'
                    : 'text-white/85 border-white/20 hover:bg-white/10 hover:text-white';
            };
        @endphp
        <nav class="mt-4 hidden md:flex items-center gap-3 flex-wrap nav-shell rounded-2xl px-3 py-3">
            <a class="{{ $landingNavBase }} {{ $landingNavActive('home') }}" href="{{ route('home') }}">Home</a>
            <a class="{{ $landingNavBase }} {{ $landingNavActive('public.jadwal.*') }}" href="{{ route('public.jadwal.index') }}">Jadwal</a>
            <a class="{{ $landingNavBase }} {{ $landingNavActive('public.informasi.*') }}" href="{{ route('public.informasi.index') }}">Informasi</a>
            <a class="{{ $landingNavBase }} {{ $landingNavActive('public.kegiatan.*') }}" href="{{ route('public.kegiatan.index') }}">Kegiatan</a>
            @auth
                <a class="{{ $landingNavBase }} {{ $landingNavActive('user.booking.*') }}" href="{{ route('user.booking.index') }}">Daftar Ruangan</a>
                <a class="{{ $landingNavBase }} {{ $landingNavActive('user.status.*') }}" href="{{ route('user.status.index') }}">Lihat Status</a>
                <a class="{{ $landingNavBase }} {{ $landingNavActive('user.profile.*') }}" href="{{ route('user.profile.edit') }}">Profil</a>
                <span class="text-sm text-white/80">{{ auth()->user()->username ?? auth()->user()->email }}</span>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button class="px-3 py-1 rounded bg-white/10 border border-white/25 text-white hover:bg-white/20">Keluar</button>
                </form>
            @else
                <a class="px-3 py-1 rounded bg-white text-emerald-800 font-semibold shadow" href="{{ route('login') }}">Masuk</a>
            @endauth
        </nav>
        <div class="md:hidden mt-3" x-show="menuOpen" x-transition @click.outside="menuOpen = false" x-cloak>
            <div class="flex flex-col gap-2 border border-white/20 bg-white/95 rounded-2xl p-4 shadow-lg">
                <a class="{{ $landingNavBase }} {{ $landingNavActive('home') }}" href="{{ route('home') }}">Home</a>
                <a class="{{ $landingNavBase }} {{ $landingNavActive('public.jadwal.*') }}" href="{{ route('public.jadwal.index') }}">Jadwal</a>
                <a class="{{ $landingNavBase }} {{ $landingNavActive('public.informasi.*') }}" href="{{ route('public.informasi.index') }}">Informasi</a>
                <a class="{{ $landingNavBase }} {{ $landingNavActive('public.kegiatan.*') }}" href="{{ route('public.kegiatan.index') }}">Kegiatan</a>
                @auth
                    <a class="{{ $landingNavBase }} {{ $landingNavActive('user.booking.*') }}" href="{{ route('user.booking.index') }}">Daftar Ruangan</a>
                    <a class="{{ $landingNavBase }} {{ $landingNavActive('user.status.*') }}" href="{{ route('user.status.index') }}">Lihat Status</a>
                    <a class="{{ $landingNavBase }} {{ $landingNavActive('user.profile.*') }}" href="{{ route('user.profile.edit') }}">Profil</a>
                    <div class="text-sm text-gray-700 px-2">{{ auth()->user()->username ?? auth()->user()->email }}</div>
                    <form method="POST" action="{{ route('logout') }}" class="px-2">
                      @csrf
                      <button class="w-full px-3 py-2 rounded bg-red-600 text-white hover:bg-red-700">Keluar</button>
                    </form>
                @else
                    <a class="px-3 py-2 rounded bg-gray-800 text-white text-center" href="{{ route('login') }}">Masuk</a>
                @endauth
            </div>
        </div>
    </div>
    </header>
<main class="max-w-6xl mx-auto px-4 pt-28 pb-8">
    @isset($header)
        <h1 class="text-2xl font-bold mb-4">{{ $header }}</h1>
    @endisset
    {{ $slot ?? '' }}
    @yield('content')
    </main>
<footer class="border-t bg-white">
    <div class="max-w-6xl mx-auto px-4 py-6 text-sm text-gray-500">
        &copy; {{ now()->year }} {{ config('app.name') }}. All rights reserved.
    </div>
    </footer>
</body>
</html>
