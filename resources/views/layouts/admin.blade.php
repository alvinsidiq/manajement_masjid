<!doctype html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin - '.config('app.name') }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs"></script>
    <style>[x-cloak]{ display:none !important; }</style>
</head>
<body class="h-full bg-gray-100">
@php $isUserArea = request()->routeIs('user.*'); @endphp
<div class="min-h-full flex">

    @unless($isUserArea)
        <aside class="w-64 bg-white border-r hidden md:block">
            <x-app-sidebar />
        </aside>
    @endunless

    <div class="flex-1">
        <header class="bg-white border-b">
            <div class="px-4 py-3 flex items-center justify-between">
                <div class="font-semibold">{{ $pageTitle ?? 'Dashboard' }}</div>
                <div class="flex items-center gap-3">
                    @if($isUserArea)
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center gap-2 px-3 py-2 border border-gray-200 rounded text-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                                    <span>{{ auth()->user()->username ?? '' }}</span>
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('user.profile.edit')">
                                    Profil
                                </x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                        Keluar
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    @else
                        <span class="text-sm">{{ auth()->user()->username ?? '' }}</span>
                        <form method="POST" action="{{ route('logout') }}">@csrf
                            <button class="px-3 py-1 rounded bg-gray-800 text-white">Keluar</button>
                        </form>
                    @endif
                </div>
            </div>
        </header>
        <main class="p-4">
            @yield('content')
        </main>
    </div>

</div>
</body>
</html>
