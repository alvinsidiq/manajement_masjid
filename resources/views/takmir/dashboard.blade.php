@extends('layouts.admin', ['pageTitle'=>'Dashboard Takmir'])
@section('content')
<div class="space-y-6">
  <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-600 via-emerald-600 to-teal-500 text-white shadow">
    <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(ellipse at 10% 20%, rgba(255,255,255,0.45), transparent 40%), radial-gradient(ellipse at 85% 0%, rgba(255,255,255,0.25), transparent 45%);"></div>
    <div class="relative p-6 md:p-8 space-y-4">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
          <div class="text-sm uppercase tracking-wide text-white/80">Dashboard Takmir</div>
          <div class="text-2xl md:text-3xl font-bold">Tinjau antrian verifikasi</div>
          <p class="text-white/80 max-w-2xl">Pantau booking dan jadwal yang menunggu keputusan takmir. Mulai dari sini untuk melanjutkan proses verifikasi.</p>
        </div>
        <div class="flex flex-col gap-2">
          <a href="{{ route('takmir.verifikasi-booking.index', ['mode'=>'waiting']) }}" class="px-4 py-2 rounded-lg bg-white text-emerald-800 font-semibold shadow hover:shadow-lg">Mulai verifikasi booking</a>
          <a href="{{ route('takmir.verifikasi-jadwal.index', ['mode'=>'waiting']) }}" class="px-4 py-2 rounded-lg bg-white/15 border border-white/30 text-white hover:bg-white/20">Lihat verifikasi jadwal</a>
        </div>
      </div>
    </div>
  </div>

  <div class="grid md:grid-cols-2 gap-4">
    <div class="rounded-xl border border-amber-100 bg-amber-50 p-4 shadow space-y-2">
      <div class="flex items-center justify-between">
        <div class="font-semibold text-amber-900">Booking</div>
        <a href="{{ route('takmir.verifikasi-booking.index', ['mode'=>'waiting']) }}" class="text-xs text-amber-800 underline">Lihat daftar</a>
      </div>
      <div class="grid grid-cols-3 gap-3 text-center">
        <div class="p-3 rounded-lg bg-white shadow-sm">
          <div class="text-xs text-gray-500">Perlu verifikasi</div>
          <div class="text-2xl font-bold text-amber-700">{{ $bookingWaiting }}</div>
        </div>
        <div class="p-3 rounded-lg bg-white shadow-sm">
          <div class="text-xs text-gray-500">Disetujui</div>
          <div class="text-2xl font-bold text-emerald-600">{{ $bookingApproved }}</div>
        </div>
        <div class="p-3 rounded-lg bg-white shadow-sm">
          <div class="text-xs text-gray-500">Ditolak</div>
          <div class="text-2xl font-bold text-rose-600">{{ $bookingRejected }}</div>
        </div>
      </div>
    </div>
    <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-4 shadow space-y-2">
      <div class="flex items-center justify-between">
        <div class="font-semibold text-emerald-900">Jadwal/Kegiatan</div>
        <a href="{{ route('takmir.verifikasi-jadwal.index', ['mode'=>'waiting']) }}" class="text-xs text-emerald-800 underline">Lihat daftar</a>
      </div>
      <div class="grid grid-cols-3 gap-3 text-center">
        <div class="p-3 rounded-lg bg-white shadow-sm">
          <div class="text-xs text-gray-500">Perlu verifikasi</div>
          <div class="text-2xl font-bold text-amber-700">{{ $jadwalWaiting }}</div>
        </div>
        <div class="p-3 rounded-lg bg-white shadow-sm">
          <div class="text-xs text-gray-500">Disetujui</div>
          <div class="text-2xl font-bold text-emerald-600">{{ $jadwalApproved }}</div>
        </div>
        <div class="p-3 rounded-lg bg-white shadow-sm">
          <div class="text-xs text-gray-500">Ditolak</div>
          <div class="text-2xl font-bold text-rose-600">{{ $jadwalRejected }}</div>
        </div>
      </div>
    </div>
  </div>

  <div class="grid lg:grid-cols-2 gap-4">
    <div class="rounded-xl border border-amber-100 bg-amber-50 p-4 shadow space-y-3">
      <div class="flex items-center justify-between">
        <div class="font-semibold text-amber-900">Perlu verifikasi booking</div>
        <span class="text-xs text-amber-800">5 terbaru</span>
      </div>
      <div class="space-y-2">
        @forelse($waitingBookings as $item)
          <div class="flex items-center justify-between bg-white p-3 rounded-lg shadow-sm">
            <div>
              <div class="font-semibold text-gray-800">#{{ $item->pemesanan_id }} • {{ $item->ruangan->nama_ruangan }}</div>
              <div class="text-xs text-gray-500">{{ $item->user->username }} • {{ $item->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}</div>
              <div class="text-xs text-gray-600 line-clamp-1">{{ $item->tujuan_pemesanan }}</div>
            </div>
            <a href="{{ route('takmir.verifikasi-booking.show',$item) }}" class="text-sm px-3 py-1 rounded bg-amber-100 text-amber-900 border border-amber-200 hover:bg-amber-200">Detail</a>
          </div>
        @empty
          <div class="text-sm text-amber-900">Tidak ada booking menunggu verifikasi.</div>
        @endforelse
      </div>
    </div>

    <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-4 shadow space-y-3">
      <div class="flex items-center justify-between">
        <div class="font-semibold text-emerald-900">Perlu verifikasi jadwal/kegiatan</div>
        <span class="text-xs text-emerald-800">5 terbaru</span>
      </div>
      <div class="space-y-2">
        @forelse($waitingJadwals as $item)
          <div class="flex items-center justify-between bg-white p-3 rounded-lg shadow-sm">
            <div>
              <div class="font-semibold text-gray-800">#{{ $item->pemesanan_id }} • {{ $item->ruangan->nama_ruangan }}</div>
              <div class="text-xs text-gray-500">{{ $item->user->username }} • {{ $item->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}</div>
              <div class="text-xs text-gray-600 line-clamp-1">{{ $item->tujuan_pemesanan }}</div>
            </div>
            <a href="{{ route('takmir.verifikasi-jadwal.show',$item) }}" class="text-sm px-3 py-1 rounded bg-emerald-100 text-emerald-900 border border-emerald-200 hover:bg-emerald-200">Detail</a>
          </div>
        @empty
          <div class="text-sm text-emerald-900">Tidak ada jadwal menunggu verifikasi.</div>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endsection
