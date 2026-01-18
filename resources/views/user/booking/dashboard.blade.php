@extends('layouts.admin', ['pageTitle'=>'Daftar booking Anda'])
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
@endphp

<div class="bg-white p-4 rounded-xl shadow space-y-4">
  @if(session('status'))
    <div class="rounded border border-green-200 bg-green-50 px-3 py-2 text-sm text-green-700">{{ session('status') }}</div>
  @endif

  <div>
    <div class="text-sm text-gray-500">Booking</div>
    <div class="text-lg font-semibold">Daftar booking Anda</div>
  </div>

  @if($bookings->count() === 0)
    <div class="text-sm text-gray-500">Belum ada booking. Mulai dengan membuat booking pertama Anda.</div>
  @else
    <div class="overflow-x-auto">
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

    <div>
      {{ $bookings->links() }}
    </div>
  @endif
</div>
@endsection
