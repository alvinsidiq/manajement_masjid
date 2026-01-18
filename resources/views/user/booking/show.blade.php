@extends('layouts.admin', ['pageTitle'=>'Detail Booking'])
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
  $statusValue = $b->status->value;
  $meta = $statusMap[$statusValue] ?? ['label' => Str::headline($statusValue), 'class' => 'bg-gray-100 text-gray-700 border-gray-200'];
  $holdEnds = $b->hold_expires_at?->timezone('Asia/Jakarta');
  $holdActive = $statusValue === 'hold' && $holdEnds && $holdEnds->isFuture();
  $pemesanan = $b->pemesanan;
  $pemesananStatus = $pemesanan?->status?->value;

  $approvalState = 'pending';
  $approvalDesc = 'Takmir melakukan review dan konfirmasi.';
  $approvalStamp = null;
  if ($pemesananStatus === 'diterima' || $pemesananStatus === 'selesai') {
    $approvalState = 'done';
    $approvalDesc = $pemesananStatus === 'selesai' ? 'Pemesanan selesai.' : 'Permintaan disetujui takmir.';
    $approvalStamp = $pemesanan?->updated_at ?? $pemesanan?->created_at;
  } elseif ($pemesananStatus === 'ditolak') {
    $approvalState = 'rejected';
    $approvalDesc = 'Permintaan ditolak oleh takmir.';
    $approvalStamp = $pemesanan?->updated_at ?? $pemesanan?->created_at;
  } elseif ($pemesananStatus === 'dibatalkan') {
    $approvalState = 'cancelled';
    $approvalDesc = 'Pemesanan dibatalkan.';
    $approvalStamp = $pemesanan?->cancelled_at ?? $pemesanan?->updated_at ?? $pemesanan?->created_at;
  }

  $steps = [
    ['title' => 'Booking dibuat', 'desc' => 'Slot berhasil ditahan sementara.', 'state' => 'done', 'timestamp' => $b->created_at],
    ['title' => 'Pemesanan', 'desc' => $pemesanan ? 'Formulir pemesanan telah dikirim.' : 'Lengkapi formulir pemesanan untuk konfirmasi.', 'state' => $pemesanan ? 'done' : 'pending', 'timestamp' => $pemesanan?->created_at],
    ['title' => 'Persetujuan', 'desc' => $approvalDesc, 'state' => $approvalState, 'timestamp' => $approvalStamp],
  ];

  $stepStates = [
    'done' => ['label' => 'Selesai', 'class' => 'bg-emerald-100 text-emerald-700 border-emerald-200'],
    'pending' => ['label' => 'Menunggu', 'class' => 'bg-gray-100 text-gray-600 border-gray-200'],
    'rejected' => ['label' => 'Ditolak', 'class' => 'bg-rose-100 text-rose-700 border-rose-200'],
    'cancelled' => ['label' => 'Dibatalkan', 'class' => 'bg-gray-200 text-gray-600 border-gray-300'],
  ];
@endphp

<div class="space-y-4">
  @if(session('status'))
    <div class="rounded border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-700">{{ session('status') }}</div>
  @endif

  <div class="bg-white p-4 rounded-xl shadow">
    <div class="flex flex-wrap items-center justify-between gap-3">
      <div>
        <div class="text-sm text-gray-500">Detail booking</div>
        <div class="text-xl font-semibold">#{{ $b->booking_id }}</div>
      </div>
      <span class="inline-flex items-center px-3 py-1 rounded-full border text-xs font-semibold {{ $meta['class'] }}">
        {{ $meta['label'] }}
      </span>
    </div>
  </div>

  <div class="grid lg:grid-cols-3 gap-4">
    <div class="lg:col-span-2 space-y-4">
      <div class="bg-white p-4 rounded-xl shadow">
        <h3 class="text-sm font-semibold text-gray-700">Ringkasan booking</h3>
        <dl class="mt-3 grid md:grid-cols-2 gap-3 text-sm text-gray-700">
          <div class="flex items-center justify-between gap-2">
            <dt class="text-gray-500">Ruangan</dt>
            <dd class="font-semibold text-gray-900">{{ $b->ruangan?->nama_ruangan ?? 'Belum ditentukan' }}</dd>
          </div>
          <div class="flex items-center justify-between gap-2">
            <dt class="text-gray-500">Tanggal</dt>
            <dd class="font-semibold text-gray-900">{{ $b->hari_tanggal->timezone('Asia/Jakarta')->translatedFormat('l, d M Y') }}</dd>
          </div>
          <div class="flex items-center justify-between gap-2">
            <dt class="text-gray-500">Jam</dt>
            <dd class="font-semibold text-gray-900">{{ Str::of($b->jam)->substr(0,5) }} WIB</dd>
          </div>
          <div class="flex items-center justify-between gap-2">
            <dt class="text-gray-500">Harga</dt>
            <dd class="font-semibold text-gray-900">Rp {{ number_format($b->ruangan->harga ?? 0, 0, ',', '.') }}</dd>
          </div>
          <div class="flex items-center justify-between gap-2">
            <dt class="text-gray-500">Tujuan</dt>
            <dd class="font-semibold text-gray-900">{{ $tujuan ?? 'Akan diisi saat pemesanan' }}</dd>
          </div>
          <div class="flex items-center justify-between gap-2">
            <dt class="text-gray-500">Status pemesanan</dt>
            <dd class="font-semibold text-gray-900">{{ $pemesananStatus ? Str::headline($pemesananStatus) : 'Belum dibuat' }}</dd>
          </div>
        </dl>

        <div class="mt-4 rounded-lg border {{ $holdActive ? 'border-amber-200 bg-amber-50 text-amber-700' : 'border-gray-200 bg-gray-50 text-gray-600' }} px-3 py-2 text-xs">
          @if($holdActive)
            Booking ditahan sampai {{ $holdEnds->format('d M Y H:i') }}. Segera lanjutkan pemesanan agar ruangan tetap tersedia.
          @elseif($holdEnds)
            Masa hold berakhir pada {{ $holdEnds->format('d M Y H:i') }}. Jika masih diperlukan, lakukan booking ulang.
          @else
            Hold tidak memiliki batas waktu yang dapat dihitung otomatis.
          @endif
        </div>
      </div>

      <div class="bg-white p-4 rounded-xl shadow">
        <h3 class="text-sm font-semibold text-gray-700">Progress booking</h3>
        <div class="mt-3 space-y-3">
          @foreach($steps as $step)
            @php
              $state = $stepStates[$step['state'] ?? 'pending'] ?? $stepStates['pending'];
            @endphp
            <div class="flex flex-wrap items-start justify-between gap-3 rounded-lg border border-gray-100 px-3 py-3">
              <div>
                <div class="text-sm font-semibold text-gray-800">{{ $step['title'] }}</div>
                <div class="text-xs text-gray-500">{{ $step['desc'] }}</div>
              </div>
              <div class="text-right">
                <span class="inline-flex items-center px-2 py-1 rounded-full border text-xs {{ $state['class'] }}">{{ $state['label'] }}</span>
                @if(($step['timestamp'] ?? null) instanceof \Illuminate\Support\Carbon)
                  <div class="mt-1 text-[11px] text-gray-400">{{ $step['timestamp']->timezone('Asia/Jakarta')->format('d M Y H:i') }}</div>
                @endif
              </div>
            </div>
          @endforeach
        </div>
      </div>

      @php($showPayment = $pemesanan && (in_array($pemesanan->status?->value, ['diterima','selesai']) || $pemesanan->payment))
      @if($showPayment)
        @include('user.booking.payment-card', ['pemesanan' => $pemesanan])
      @endif
    </div>

    <aside class="space-y-4">
      <div class="bg-white p-4 rounded-xl shadow">
        <h3 class="text-sm font-semibold text-gray-700">Aksi</h3>
        <div class="mt-3 space-y-2 text-sm">
          <a href="{{ route('user.booking.dashboard') }}" class="flex w-full items-center justify-center rounded border px-3 py-2">Kembali ke daftar</a>
          @if($pemesanan)
            <a href="{{ route('user.pemesanan.show', $pemesanan) }}" class="flex w-full items-center justify-center rounded border border-indigo-200 px-3 py-2 text-indigo-700">Lihat pemesanan</a>
          @elseif($statusValue === 'hold')
            <a href="{{ route('user.pemesanan.create', ['booking_id' => $b->booking_id, 'tujuan' => $tujuan]) }}" class="flex w-full items-center justify-center rounded bg-indigo-600 px-3 py-2 text-white">Lanjutkan pemesanan</a>
          @endif
          @if(in_array($statusValue, ['hold','proses']))
            <a href="{{ route('user.booking.cancel.confirm', $b) }}" class="flex w-full items-center justify-center rounded border border-rose-200 px-3 py-2 text-rose-600">Batalkan booking</a>
          @endif
        </div>
      </div>

      <div class="bg-white p-4 rounded-xl shadow">
        <h3 class="text-sm font-semibold text-gray-700">Informasi</h3>
        <p class="mt-2 text-xs text-gray-600">Hubungi takmir jika perlu bantuan atau perpanjangan waktu hold.</p>
      </div>
    </aside>
  </div>
</div>
@endsection
