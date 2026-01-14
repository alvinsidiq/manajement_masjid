@extends('layouts.landing', ['pageTitle'=>'Buat Booking'])
@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
  <div class="grid gap-8 lg:grid-cols-[2fr,1fr]">
    <div class="rounded-3xl border border-gray-100 bg-white p-8 shadow-lg">
      <div class="mb-8 flex items-start justify-between gap-4">
        <div>
          <p class="text-xs uppercase tracking-[0.3em] text-indigo-500">Langkah 1 dari 2</p>
          <h2 class="mt-2 text-2xl font-semibold text-gray-900">Tahan slot ruangan</h2>
          <p class="mt-2 text-sm text-gray-500">Isi detail acara Anda. Slot yang tersedia akan di-hold selama 45 menit sebelum perlu dikonfirmasi melalui pemesanan.</p>
        </div>
        <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-indigo-50 text-indigo-600">1</span>
      </div>

      <form method="post" action="{{ route('user.booking.store') }}" class="space-y-6">
        @csrf
        <div class="grid gap-6 md:grid-cols-2">
          <div class="space-y-2">
            <label class="text-sm font-medium text-gray-700">Ruangan</label>
            <div class="relative">
              @if($selectedRuangan)
                <div class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm">
                  <div class="text-xs text-gray-500">Ruangan dipilih</div>
                  <div class="font-semibold text-gray-900">{{ $selectedRuangan->nama_ruangan }}</div>
                  <div class="mt-1 text-xs text-gray-500">Harga</div>
                  <div class="font-semibold text-gray-900">Rp {{ number_format($selectedRuangan->harga ?? 0,0,',','.') }}</div>
                </div>
                <input type="hidden" name="ruangan_id" value="{{ $selectedRuangan->ruangan_id }}">
              @else
                <select name="ruangan_id" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100" required>
                  <option value="">Pilih Ruangan</option>
                  @foreach($ruangan as $r)
                    <option value="{{ $r->ruangan_id }}" @selected(old('ruangan_id', request('ruangan_id'))==$r->ruangan_id)>{{ $r->nama_ruangan }} — Rp {{ number_format($r->harga ?? 0,0,',','.') }}</option>
                  @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-gray-400">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </div>
              @endif
            </div>
            @error('ruangan_id')<div class="text-xs font-medium text-rose-600">{{ $message }}</div>@enderror
          </div>
          <div class="space-y-2">
            <label class="text-sm font-medium text-gray-700">Hari &amp; Tanggal</label>
            <input type="date" name="hari_tanggal" value="{{ old('hari_tanggal', now('Asia/Jakarta')->format('Y-m-d')) }}" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100" required>
            @error('hari_tanggal')<div class="text-xs font-medium text-rose-600">{{ $message }}</div>@enderror
          </div>
          <div class="space-y-2">
            <label class="text-sm font-medium text-gray-700">Jam Mulai</label>
            <input type="time" name="jam" value="{{ old('jam','09:00') }}" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100" required>
            @error('jam')<div class="text-xs font-medium text-rose-600">{{ $message }}</div>@enderror
          </div>
          <div class="space-y-2 md:col-span-2">
            <label class="text-sm font-medium text-gray-700">Tujuan Acara</label>
            <textarea name="tujuan" rows="3" class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100" placeholder="Contoh: Rapat Remaja Masjid" required>{{ old('tujuan') }}</textarea>
            @error('tujuan')<div class="text-xs font-medium text-rose-600">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3">
          <p class="text-xs text-gray-500">Dengan menekan tombol ini, slot akan ditahan sementara dan perlu dikonfirmasi di tahap berikutnya.</p>
          <button class="inline-flex items-center gap-2 rounded-full bg-indigo-600 px-6 py-2 text-sm font-semibold text-white transition hover:bg-indigo-500">
            Tahan Slot Sekarang
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
          </button>
        </div>
      </form>
    </div>

    <aside class="space-y-6">
      <div class="rounded-3xl border border-indigo-100 bg-indigo-50/60 p-6 shadow-inner">
        <h3 class="text-sm font-semibold text-indigo-700">Tips booking efektif</h3>
        <ul class="mt-3 space-y-2 text-xs text-indigo-700/90">
          <li class="flex gap-2"><span class="mt-1 h-2 w-2 rounded-full bg-indigo-400"></span>Pilih tanggal minimal H+1 agar tak berbenturan dengan jadwal berjalan.</li>
          <li class="flex gap-2"><span class="mt-1 h-2 w-2 rounded-full bg-indigo-400"></span>Hold berlaku 45 menit, jadi siapkan data pemesanan sebelum waktu tersebut.</li>
          <li class="flex gap-2"><span class="mt-1 h-2 w-2 rounded-full bg-indigo-400"></span>Gunakan kolom tujuan untuk menjelaskan kebutuhan Anda secara singkat.</li>
        </ul>
      </div>

      <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
        <h3 class="text-sm font-semibold text-gray-700">Kenapa perlu booking terlebih dahulu?</h3>
        <p class="mt-2 text-xs text-gray-500">Booking memastikan slot ruangan ditahan sementara agar Anda dapat melengkapi dokumen pemesanan tanpa takut diserobot pengguna lain.</p>
        <div class="mt-4 space-y-2 text-xs text-gray-600">
          <div class="flex items-center gap-2">
            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">1</span>
            <span>Hold slot (halaman ini)</span>
          </div>
          <div class="flex items-center gap-2">
            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-indigo-100 text-indigo-600">2</span>
            <span>Konfirmasi &amp; lengkapi pemesanan</span>
          </div>
          <div class="flex items-center gap-2">
            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-sky-100 text-sky-600">3</span>
            <span>Tunggu persetujuan takmir</span>
          </div>
        </div>
      </div>
    </aside>
  </div>
</div>
@endsection
