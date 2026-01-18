@extends('layouts.admin', ['pageTitle'=>'Dashboard Pengguna'])
@section('content')
@php
  $statusMap = [
    'hold' => ['label' => 'Menunggu Pemesanan', 'class' => 'bg-amber-100 text-amber-700 border-amber-200'],
    'proses' => ['label' => 'Diproses', 'class' => 'bg-sky-100 text-sky-700 border-sky-200'],
    'setuju' => ['label' => 'Disetujui', 'class' => 'bg-emerald-100 text-emerald-700 border-emerald-200'],
    'tolak' => ['label' => 'Ditolak', 'class' => 'bg-rose-100 text-rose-700 border-rose-200'],
    'cancelled' => ['label' => 'Dibatalkan', 'class' => 'bg-rose-100 text-rose-700 border-rose-200'],
    'expired' => ['label' => 'Kedaluwarsa', 'class' => 'bg-gray-200 text-gray-700 border-gray-300'],
  ];

  $cards = [
    ['label' => 'Total Booking', 'value' => $stats['total'] ?? 0, 'note' => 'Semua booking yang dibuat'],
    ['label' => 'Menunggu Pemesanan', 'value' => $stats['hold'] ?? 0, 'note' => 'Butuh konfirmasi pemesanan'],
    ['label' => 'Sedang Diproses', 'value' => $stats['proses'] ?? 0, 'note' => 'Menunggu review takmir'],
    ['label' => 'Disetujui', 'value' => $stats['setuju'] ?? 0, 'note' => 'Booking telah disetujui'],
    ['label' => 'Ditolak/Dibatalkan', 'value' => $stats['rejected'] ?? 0, 'note' => 'Booking tidak berlanjut'],
  ];
@endphp

<div class="space-y-6">
  <div class="bg-white p-4 rounded-xl shadow">
    <div class="text-xl font-semibold">Selamat datang, {{ auth()->user()->username }}.</div>
    <div class="mt-1 text-sm text-gray-600">Ringkasan booking dan informasi terbaru yang perlu Anda tindak lanjuti.</div>
  </div>

  <div class="grid md:grid-cols-3 gap-4">
    @foreach($cards as $card)
      <div class="bg-white p-4 rounded-xl shadow">
        <div class="text-sm text-gray-500">{{ $card['label'] }}</div>
        <div class="mt-2 text-3xl font-semibold">{{ number_format($card['value']) }}</div>
        <div class="mt-2 text-xs text-gray-500">{{ $card['note'] }}</div>
      </div>
    @endforeach
  </div>

  <div class="grid lg:grid-cols-2 gap-4">
    <div class="bg-white p-4 rounded-xl shadow">
      <div class="flex items-center justify-between gap-3">
        <div>
          <div class="text-sm text-gray-500">Booking</div>
          <div class="text-lg font-semibold">Daftar booking Anda</div>
        </div>
        <a href="{{ route('user.booking.dashboard') }}" class="text-sm text-blue-600 hover:underline">Lihat semua</a>
      </div>

      @if($bookings->count() === 0)
        <div class="mt-4 text-sm text-gray-500">Belum ada booking. Mulai dengan membuat booking pertama Anda.</div>
      @else
        <div class="mt-4 overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead><tr class="text-left border-b">
              <th class="py-2 px-3">#</th>
              <th class="py-2 px-3">Waktu</th>
              <th class="py-2 px-3">Ruangan</th>
              <th class="py-2 px-3">Status</th>
              <th class="py-2 px-3">Expire</th>
              <th class="py-2 px-3">Aksi</th>
            </tr></thead>
            <tbody>
              @foreach($bookings as $b)
                @php
                  $statusValue = $b->status->value;
                  $meta = $statusMap[$statusValue] ?? ['label' => Str::headline($statusValue), 'class' => 'bg-gray-100 text-gray-700 border-gray-200'];
                @endphp
                <tr class="border-b">
                  <td class="py-2 px-3">{{ $b->booking_id }}</td>
                  <td class="py-2 px-3">
                    {{ $b->hari_tanggal->timezone('Asia/Jakarta')->format('d M Y') }}
                    {{ Str::of($b->jam)->substr(0,5) }} WIB
                  </td>
                  <td class="py-2 px-3">{{ $b->ruangan?->nama_ruangan ?? 'Belum ditentukan' }}</td>
                  <td class="py-2 px-3">
                    <span class="inline-flex items-center px-2 py-1 rounded-full border text-xs font-semibold {{ $meta['class'] }}">
                      {{ $meta['label'] }}
                    </span>
                  </td>
                  <td class="py-2 px-3">
                    {{ $b->hold_expires_at ? $b->hold_expires_at->timezone('Asia/Jakarta')->format('d M Y H:i') : '-' }}
                  </td>
                  <td class="py-2 px-3">
                    <div class="flex flex-wrap gap-2">
                      <a href="{{ route('user.booking.show',$b) }}" class="px-2 py-1 rounded border">Detail</a>
                      @if($b->pemesanan)
                        <a href="{{ route('user.pemesanan.show', $b->pemesanan) }}" class="px-2 py-1 rounded border text-indigo-700 border-indigo-200">Pemesanan</a>
                      @elseif($statusValue === 'hold')
                        <a href="{{ route('user.pemesanan.create', ['booking_id' => $b->booking_id]) }}" class="px-2 py-1 rounded bg-indigo-600 text-white">Lanjutkan</a>
                      @endif
                      @if(in_array($statusValue, ['hold','proses']))
                        <a href="{{ route('user.booking.cancel.confirm', $b) }}" class="px-2 py-1 rounded border bg-rose-50 text-rose-600 border-rose-200">Batalkan</a>
                      @endif
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="pt-3">
          {{ $bookings->links() }}
        </div>
      @endif
    </div>

    <div class="bg-white p-4 rounded-xl shadow space-y-4">
      <div>
        <div class="text-sm text-gray-500">Informasi booking</div>
        <div class="text-lg font-semibold">Ringkasan status</div>
      </div>
      <div class="space-y-2 text-sm">
        <div class="flex items-center justify-between">
          <span>Menunggu pemesanan</span>
          <span class="font-semibold">{{ number_format($stats['hold'] ?? 0) }}</span>
        </div>
        <div class="flex items-center justify-between">
          <span>Sedang diproses</span>
          <span class="font-semibold">{{ number_format($stats['proses'] ?? 0) }}</span>
        </div>
        <div class="flex items-center justify-between">
          <span>Disetujui</span>
          <span class="font-semibold">{{ number_format($stats['setuju'] ?? 0) }}</span>
        </div>
        <div class="flex items-center justify-between">
          <span>Ditolak/Dibatalkan</span>
          <span class="font-semibold">{{ number_format($stats['rejected'] ?? 0) }}</span>
        </div>
      </div>

      <div class="rounded-lg border border-amber-100 bg-amber-50 px-3 py-2 text-xs text-amber-700">
        @if($nextHold)
          Booking menunggu pemesanan terdekat berakhir pada <span class="font-semibold">{{ $nextHold->hold_expires_at?->timezone('Asia/Jakarta')->format('d M Y · H:i') }}</span>.
          Segera lanjutkan ke pemesanan agar tidak kedaluwarsa.
        @else
          Tidak ada booking yang sedang menunggu pemesanan saat ini.
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
