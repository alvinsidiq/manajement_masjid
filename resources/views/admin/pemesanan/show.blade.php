@extends('layouts.admin', ['pageTitle'=>'Detail Pemesanan'])
@section('content')
@if(session('status'))
  <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('status') }}</div>
@endif
@php($status = $p->status->value)
@php($statusLabel = str($status)->replace('_',' ')->title())
@php($badgeClass = match($status){
  'menunggu_verifikasi' => 'bg-yellow-100 text-yellow-800',
  'diterima' => 'bg-green-100 text-green-800',
  'ditolak' => 'bg-red-100 text-red-800',
  default => 'bg-gray-200 text-gray-800'
})
<div class="space-y-5">
  <div class="bg-white p-5 rounded-xl shadow border border-slate-100">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
      <div>
        <div class="text-xs uppercase tracking-wide text-gray-500">Pemesanan #{{ $p->pemesanan_id }}</div>
        <div class="text-2xl font-semibold text-gray-900">{{ $p->ruangan->nama_ruangan }}</div>
        <div class="text-sm text-gray-600">Pemesan: {{ $p->user->username }} ({{ $p->user->email }})</div>
      </div>
      <div class="flex flex-col items-start gap-2 md:items-end">
        <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $badgeClass }}">{{ $statusLabel }}</span>
        <div class="text-xs text-gray-500">Dibuat {{ $p->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}</div>
        <div class="text-xs text-gray-500">Diperbarui {{ $p->updated_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}</div>
      </div>
    </div>
  </div>

  <div class="grid lg:grid-cols-3 gap-5">
    <div class="lg:col-span-2 space-y-4">
      <div class="bg-white p-5 rounded-xl shadow border border-slate-100 space-y-4">
        <div class="font-semibold text-gray-800">Rincian Pemesanan</div>
        <div class="grid md:grid-cols-2 gap-4">
          <div class="p-3 rounded-lg bg-slate-50">
            <div class="text-xs text-gray-500">Tujuan</div>
            <div class="font-semibold text-gray-800">{{ $p->tujuan_pemesanan }}</div>
          </div>
          <div class="p-3 rounded-lg bg-slate-50">
            <div class="text-xs text-gray-500">Status</div>
            <div><span class="px-2 py-1 rounded text-xs {{ $badgeClass }}">{{ $statusLabel }}</span></div>
          </div>
          <div class="p-3 rounded-lg bg-slate-50">
            <div class="text-xs text-gray-500">Catatan Pemesan</div>
            <div class="text-sm text-gray-800">{{ $p->catatan ?: '-' }}</div>
          </div>
          <div class="p-3 rounded-lg bg-slate-50">
            <div class="text-xs text-gray-500">Pembatalan</div>
            <div class="text-sm text-gray-800">
              @if($p->alasan_pembatalan)
                {{ $p->alasan_pembatalan }} @if($p->cancelled_at) ({{ $p->cancelled_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}) @endif
              @else
                -
              @endif
            </div>
          </div>
          @if($p->alasan_penolakan)
            <div class="md:col-span-2 p-3 rounded-lg bg-red-50 border border-red-100">
              <div class="text-xs text-red-700">Alasan Penolakan</div>
              <div class="text-sm text-red-800">{{ $p->alasan_penolakan }}</div>
            </div>
          @endif
        </div>
      </div>

      <div class="grid md:grid-cols-2 gap-4">
        <div class="bg-white p-5 rounded-xl shadow border border-slate-100">
          <div class="font-semibold text-gray-800 mb-2">Detail Booking</div>
          @if($p->booking)
            <div class="space-y-1 text-sm text-gray-700">
              <div class="flex justify-between"><span>ID</span><span class="font-semibold">#{{ $p->booking->booking_id }}</span></div>
              <div class="flex justify-between"><span>Tanggal</span><span class="font-semibold">{{ $p->booking->hari_tanggal->timezone('Asia/Jakarta')->format('d M Y') }}</span></div>
              <div class="flex justify-between"><span>Jam</span><span class="font-semibold">{{ $p->booking->jam }}</span></div>
              <div class="flex justify-between"><span>Status</span><span class="font-semibold">{{ ucfirst($p->booking->status->value) }}</span></div>
              <div class="flex justify-between"><span>Hold Expire</span><span class="font-semibold">{{ optional($p->booking->hold_expires_at)->timezone('Asia/Jakarta')->format('d M Y H:i') ?? '-' }}</span></div>
            </div>
          @else
            <div class="text-sm text-gray-600">Pemesanan ini tidak berasal dari booking hold.</div>
          @endif
        </div>

        <div class="bg-white p-5 rounded-xl shadow border border-slate-100">
          <div class="font-semibold text-gray-800 mb-2">Detail Jadwal</div>
          @if($p->jadwal)
            <div class="space-y-1 text-sm text-gray-700">
              <div class="flex justify-between"><span>ID Jadwal</span><span class="font-semibold">#{{ $p->jadwal->jadwal_id }}</span></div>
              <div class="flex justify-between"><span>Mulai</span><span class="font-semibold">{{ $p->jadwal->tanggal_mulai->timezone('Asia/Jakarta')->format('d M Y H:i') }}</span></div>
              <div class="flex justify-between"><span>Selesai</span><span class="font-semibold">{{ $p->jadwal->tanggal_selesai->timezone('Asia/Jakarta')->format('d M Y H:i') }}</span></div>
            </div>
          @else
            <div class="text-sm text-gray-600">Belum dikaitkan ke jadwal tertentu.</div>
          @endif
        </div>
      </div>

      <div class="bg-white p-5 rounded-xl shadow border border-slate-100">
        <div class="font-semibold text-gray-800 mb-2">Info Pembayaran</div>
        @if($p->payment)
          <div class="grid sm:grid-cols-2 gap-3 text-sm text-gray-700">
            <div class="flex justify-between"><span>Status</span><span class="font-semibold">{{ ucfirst($p->payment->status->value ?? '-') }}</span></div>
            <div class="flex justify-between"><span>Metode</span><span class="font-semibold">{{ $p->payment->method ?? '-' }}</span></div>
            <div class="flex justify-between"><span>Gateway</span><span class="font-semibold">{{ $p->payment->gateway?->value ?? '-' }}</span></div>
            <div class="flex justify-between"><span>Jumlah</span><span class="font-semibold">{{ $p->payment->amount ? 'Rp '.number_format($p->payment->amount,0,',','.') : '-' }}</span></div>
            <div class="flex justify-between"><span>Jatuh Tempo</span><span class="font-semibold">{{ optional($p->payment->expired_at)->timezone('Asia/Jakarta')->format('d M Y H:i') ?? '-' }}</span></div>
            <div class="flex justify-between"><span>Dibayar</span><span class="font-semibold">{{ optional($p->payment->paid_at)->timezone('Asia/Jakarta')->format('d M Y H:i') ?? '-' }}</span></div>
            <div class="sm:col-span-2 flex justify-between">
              <span>Invoice</span>
              @if($p->payment->invoice_url)
                <a href="{{ $p->payment->invoice_url }}" target="_blank" class="text-indigo-600 hover:underline">Buka invoice</a>
              @else
                <span class="font-semibold">-</span>
              @endif
            </div>
          </div>
        @else
          <div class="text-sm text-gray-600">Belum ada data pembayaran untuk pemesanan ini.</div>
        @endif
      </div>
    </div>

    <div class="space-y-4">
      <div class="p-4 rounded-xl border bg-gray-50 text-sm text-gray-700 shadow-sm">
        Halaman ini hanya menampilkan informasi. Persetujuan atau penolakan dilakukan oleh takmir.
      </div>
    </div>
  </div>

  <div class="pt-2">
    <a href="{{ route('admin.pemesanan.index',['status'=>'menunggu_verifikasi']) }}" class="inline-flex items-center gap-2 px-3 py-2 rounded bg-gray-200 hover:bg-gray-300 text-sm text-gray-800">
      Kembali ke daftar
    </a>
  </div>
</div>
@endsection
