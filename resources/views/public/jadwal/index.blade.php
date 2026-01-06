@extends('layouts.landing', ['title' => 'Jadwal Kegiatan'])

@section('content')
@php
    $monthNames = $monthNames ?? [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];
    $activeMonthLabel = $monthNames[$month] ?? now()->translatedFormat('F');
@endphp

<section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-600 via-sky-600 to-blue-600 text-white shadow-2xl mb-10">
    <img src="https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1500&q=60"
         alt="Masjid"
         class="absolute inset-0 h-full w-full object-cover opacity-40">
    <div class="absolute inset-0 bg-gradient-to-r from-emerald-700/90 via-emerald-600/70 to-sky-600/80"></div>
    <div class="relative p-10 flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
        <div class="space-y-4">
            <p class="inline-flex items-center rounded-full bg-white/10 px-4 py-1 text-xs font-semibold uppercase tracking-[0.4em] text-white/80">
                Jadwal Masjid
            </p>
            <h1 class="text-3xl md:text-4xl font-semibold leading-tight">
                Agenda &amp; Booking Ruangan Bulan {{ $activeMonthLabel }} {{ $year }}.
            </h1>
            <p class="text-sm md:text-base text-white/80 max-w-2xl">
                Semua jadwal yang dibuat admin ditampilkan dalam bentuk kartu grid yang lebih mudah dipindai.
                Temukan kajian, kegiatan komunitas, maupun booking ruangan yang sudah disetujui.
            </p>
        </div>
        <div class="grid gap-3 sm:grid-cols-2 text-sm">
            <div class="rounded-2xl bg-white/10 px-4 py-3 shadow-sm backdrop-blur">
                <p class="text-white/70 text-xs uppercase tracking-widest">Total Kegiatan</p>
                <p class="text-3xl font-semibold">{{ number_format($kegiatanCount) }}</p>
                <p class="text-white/70 text-xs mt-1">Termasuk jadwal harian dan mingguan.</p>
            </div>
            <div class="rounded-2xl bg-white/10 px-4 py-3 shadow-sm backdrop-blur">
                <p class="text-white/70 text-xs uppercase tracking-widest">Booking Diterima</p>
                <p class="text-3xl font-semibold">{{ number_format($pemesananCount) }}</p>
                <p class="text-white/70 text-xs mt-1">Ruangan yang sudah dikunci untuk jamaah.</p>
            </div>
        </div>
    </div>
</section>

<section class="rounded-[32px] bg-white shadow-xl border border-white/60 mb-10 p-6 md:p-8">
    <form method="get" class="grid gap-6">
        <input type="hidden" name="view" value="list">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <label class="flex flex-col gap-2 text-xs font-semibold text-slate-600">
                Bulan
                <select name="month" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100">
                    @foreach($monthNames as $num => $label)
                        <option value="{{ $num }}" @selected($month == $num)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>
            <label class="flex flex-col gap-2 text-xs font-semibold text-slate-600">
                Tahun
                <select name="year" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100">
                    @foreach($yearOptions as $option)
                        <option value="{{ $option }}" @selected($year == $option)>{{ $option }}</option>
                    @endforeach
                </select>
            </label>
            <label class="flex flex-col gap-2 text-xs font-semibold text-slate-600 md:col-span-2 xl:col-span-2">
                Pencarian
                <input type="text"
                       name="q"
                       value="{{ $q }}"
                       placeholder="Cari kegiatan / ruangan / catatan"
                       class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100">
            </label>
        </div>
        <div class="flex flex-wrap items-center justify-between gap-4">
            <p class="text-sm text-slate-500">
                Menampilkan jadwal pada rentang {{ \Carbon\Carbon::parse($from)->translatedFormat('d F Y') }} – {{ \Carbon\Carbon::parse($to)->translatedFormat('d F Y') }}.
            </p>
            <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow hover:bg-emerald-700 transition">
                Terapkan Filter
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m9 5 7 7-7 7"/></svg>
            </button>
        </div>
    </form>
</section>

<section class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.3em] text-emerald-600">Jadwal Admin</p>
            <h2 class="text-2xl font-semibold text-slate-900">Ditata dalam grid cards yang ramah pengguna.</h2>
        </div>
        <span class="text-sm text-slate-500">Total {{ $listItems->count() }} agenda bulan ini</span>
    </div>

    @if($listItems->isEmpty())
        <div class="rounded-3xl border border-dashed border-slate-200 bg-white px-6 py-16 text-center text-slate-500">
            Belum ada kegiatan atau booking pada periode ini.
        </div>
    @else
        <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
            @foreach($listItems as $item)
                @php
                    $date = $item['date']->timezone($tz);
                    $isJadwal = $item['type'] === 'jadwal';
                    $badgeColor = $isJadwal ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700';
                    $badgeDot = $isJadwal ? 'bg-emerald-500' : 'bg-amber-500';
                @endphp
                <article x-data="{ open: false }" class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm flex flex-col gap-4">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs text-slate-500 uppercase tracking-widest">{{ $date->translatedFormat('l, d F Y') }}</p>
                            <h3 class="text-lg font-semibold text-slate-900 mt-1">{{ $item['title'] }}</h3>
                            @if($item['time'])
                                <p class="text-sm text-slate-500 mt-1">Pukul {{ $item['time'] }}</p>
                            @endif
                        </div>
                        <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold {{ $badgeColor }}">
                            <span class="h-2 w-2 rounded-full {{ $badgeDot }}"></span>
                            {{ $isJadwal ? 'Kegiatan' : 'Booking' }}
                        </span>
                    </div>
                    <div x-show="open" x-transition class="space-y-2 text-sm text-slate-600 border-t border-slate-100 pt-3">
                        @if($item['ruangan'])
                            <p><span class="font-semibold text-slate-900">Ruangan:</span> {{ $item['ruangan'] }}</p>
                        @endif
                        @if($item['note'])
                            <p><span class="font-semibold text-slate-900">Catatan:</span> {{ $item['note'] }}</p>
                        @endif
                        <p class="text-xs text-slate-400">{{ $item['label'] }}</p>
                    </div>
                    <div class="mt-auto flex items-center justify-between text-xs text-slate-500">
                        <span>Status: <span class="font-semibold text-slate-700">{{ ucfirst($item['status'] ?? '-') }}</span></span>
                        <button type="button"
                                @click="open = !open"
                                class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-4 py-2 text-[11px] font-semibold text-slate-700 hover:border-emerald-200 hover:text-emerald-600 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/>
                                <path x-show="open" stroke-linecap="round" stroke-linejoin="round" d="M6 12h12"/>
                            </svg>
                            <span x-text="open ? 'Tutup Detail' : 'Lihat Detail'"></span>
                        </button>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</section>
@endsection
