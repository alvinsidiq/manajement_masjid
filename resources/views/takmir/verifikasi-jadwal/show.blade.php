@extends('layouts.admin', ['pageTitle' => 'Detail Verifikasi Jadwal'])
@section('content')
@if(session('status'))
  <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('status') }}</div>
@endif

@php($status = $p->status->value)
@php($badgeClass = $status==='diterima' ? 'bg-green-100 text-green-800' : ($status==='ditolak' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'))

<div class="space-y-6">
  <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-600 to-emerald-600 text-white shadow">
    <div class="absolute inset-0 opacity-15" style="background-image: radial-gradient(ellipse at 20% 20%, rgba(255,255,255,0.45), transparent 40%), radial-gradient(ellipse at 80% 0%, rgba(255,255,255,0.25), transparent 45%);"></div>
    <div class="relative grid md:grid-cols-5 gap-4 p-6 items-center">
      <div class="md:col-span-3 space-y-2">
        <div class="flex items-center gap-2 text-sm uppercase tracking-wide text-white/80">
          <span class="px-2 py-1 rounded-full bg-white/15 border border-white/20">Verifikasi Kegiatan/Jadwal</span>
          <span class="px-2 py-1 rounded-full bg-white/15 border border-white/20">ID #{{ $p->pemesanan_id }}</span>
        </div>
        <div class="text-2xl font-bold">{{ $p->ruangan->nama_ruangan }}</div>
        <div class="text-white/80">Pemesan: {{ $p->user->username }} ({{ $p->user->email }})</div>
        <div class="flex flex-wrap gap-2">
          <span class="px-3 py-1 rounded-full {{ $badgeClass }} text-sm font-semibold">{{ str($status)->replace('_',' ')->title() }}</span>
          <span class="px-3 py-1 rounded-full bg-white/15 border border-white/20 text-sm">Dibuat {{ $p->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}</span>
        </div>
      </div>
      <div class="md:col-span-2">
        <div class="bg-white/10 backdrop-blur rounded-xl border border-white/15 overflow-hidden shadow-lg">
          <img src="https://images.unsplash.com/photo-1520607162513-77705c0f0d4a?auto=format&fit=crop&w=900&q=80" alt="Ilustrasi verifikasi kegiatan" class="w-full h-36 object-cover">
          <div class="p-4 space-y-1 text-sm text-white/90">
            <div class="flex items-center justify-between">
              <span>Ruangan</span><span class="font-semibold">{{ $p->ruangan->nama_ruangan }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span>Status</span><span class="font-semibold">{{ str($status)->replace('_',' ')->title() }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span>Tujuan</span><span class="font-semibold text-right">{{ str($p->tujuan_pemesanan)->limit(28) }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="grid lg:grid-cols-3 gap-5">
    <div class="lg:col-span-2 space-y-4">
      <div class="bg-white p-5 rounded-xl shadow border border-slate-100 space-y-4">
        <div class="flex items-center justify-between">
          <div class="font-semibold text-gray-800">Rincian Pemesanan</div>
          <div class="text-xs text-gray-500">Terakhir diperbarui {{ $p->updated_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}</div>
        </div>
        <div class="grid md:grid-cols-2 gap-4">
          <div class="p-3 rounded-lg bg-slate-50">
            <div class="text-xs text-gray-500">Tujuan</div>
            <div class="font-semibold text-gray-800">{{ $p->tujuan_pemesanan }}</div>
          </div>
          <div class="p-3 rounded-lg bg-slate-50">
            <div class="text-xs text-gray-500">Ruangan</div>
            <div class="font-semibold text-gray-800">{{ $p->ruangan->nama_ruangan }}</div>
          </div>
          <div class="p-3 rounded-lg bg-slate-50">
            <div class="text-xs text-gray-500">Status</div>
            <div><span class="px-2 py-1 rounded text-xs {{ $badgeClass }}">{{ str($status)->replace('_',' ')->title() }}</span></div>
          </div>
          @if($p->catatan)
          <div class="md:col-span-2 p-3 rounded-lg bg-indigo-50 border border-indigo-100">
            <div class="text-xs text-gray-600">Catatan Pemesan</div>
            <div class="font-semibold text-gray-800">{{ $p->catatan }}</div>
          </div>
          @endif
        </div>

        @if($status==='ditolak' && $p->alasan_penolakan)
        <div class="p-4 rounded-lg bg-red-50 border border-red-100">
          <div class="text-sm font-semibold text-red-800">Alasan Penolakan</div>
          <div class="text-sm text-red-700">{{ $p->alasan_penolakan }}</div>
        </div>
        @endif

        @if($p->alasan_pembatalan)
        <div class="p-4 rounded-lg bg-gray-50 border">
          <div class="text-sm font-semibold text-gray-800">Alasan Pembatalan</div>
          <div class="text-sm text-gray-700">{{ $p->alasan_pembatalan }}</div>
        </div>
        @endif
      </div>

      <div class="bg-white p-5 rounded-xl shadow border border-slate-100">
        <div class="font-semibold text-gray-800 mb-3">Timeline Verifikasi</div>
        <div class="space-y-3">
          <div class="flex items-start gap-3">
            <div class="mt-1 w-2 h-2 rounded-full bg-emerald-500"></div>
            <div>
              <div class="text-sm font-semibold text-gray-800">Pengajuan</div>
              <div class="text-xs text-gray-500">{{ $p->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}</div>
            </div>
          </div>
          <div class="flex items-start gap-3">
            <div class="mt-1 w-2 h-2 rounded-full bg-indigo-500"></div>
            <div>
              <div class="text-sm font-semibold text-gray-800">Proses Takmir</div>
              <div class="text-xs text-gray-500">Cek jadwal & keterisian ruangan, kemudian putuskan.</div>
            </div>
          </div>
          @if(in_array($status,['diterima','ditolak']))
          <div class="flex items-start gap-3">
            <div class="mt-1 w-2 h-2 rounded-full {{ $status==='diterima' ? 'bg-green-600' : 'bg-red-600' }}"></div>
            <div>
              <div class="text-sm font-semibold text-gray-800">{{ $status==='diterima' ? 'Disetujui' : 'Ditolak' }}</div>
              <div class="text-xs text-gray-500">Terakhir diperbarui {{ $p->updated_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}</div>
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>

    <div class="space-y-4">
      @if($status==='menunggu_verifikasi')
        @role('takmir')
        <div class="bg-white p-5 rounded-xl shadow border border-slate-100">
          <div class="font-semibold mb-2 text-gray-800">Persetujuan</div>
          <form method="post" action="{{ route('takmir.verifikasi-jadwal.approve',$p) }}" class="space-y-3">
            @csrf
            <label class="block text-sm text-gray-700">Catatan (opsional)</label>
            <textarea name="catatan" class="w-full border rounded px-3 py-2 focus:ring focus:ring-indigo-100" rows="3" placeholder="Tulis catatan tambahan..."></textarea>
            @error('catatan')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
            <button class="w-full mt-1 px-3 py-2 rounded bg-green-600 text-white hover:bg-green-700">Setujui</button>
          </form>
        </div>
        <div class="bg-white p-5 rounded-xl shadow border border-slate-100">
          <div class="font-semibold mb-2 text-gray-800">Penolakan</div>
          <form method="post" action="{{ route('takmir.verifikasi-jadwal.reject',$p) }}" class="space-y-3">
            @csrf
            <label class="block text-sm text-gray-700">Alasan Penolakan</label>
            <textarea name="alasan_penolakan" class="w-full border rounded px-3 py-2 focus:ring focus:ring-red-100" rows="3" required placeholder="Tuliskan alasan penolakan"></textarea>
            @error('alasan_penolakan')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
            <label class="block text-sm text-gray-700">Catatan (opsional)</label>
            <textarea name="catatan" class="w-full border rounded px-3 py-2 focus:ring focus:ring-red-100" rows="2" placeholder="Catatan tambahan..."></textarea>
            <button class="w-full mt-1 px-3 py-2 rounded bg-red-600 text-white hover:bg-red-700">Tolak</button>
          </form>
        </div>
        @else
          <div class="bg-white p-5 rounded-xl shadow border border-slate-100 text-sm text-gray-700">Menunggu persetujuan takmir.</div>
        @endrole
      @else
        <div class="bg-white p-5 rounded-xl shadow border border-slate-100">
          <div class="font-semibold mb-1 text-gray-800">Status Verifikasi</div>
          <div class="text-sm text-gray-700">Pemesanan telah diverifikasi: <span class="font-semibold">{{ str($status)->replace('_',' ')->title() }}</span>.</div>
        </div>
      @endif
    </div>
  </div>

  <div class="pt-1 flex gap-3">
    <a href="{{ route('takmir.verifikasi-jadwal.index') }}" class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300">Kembali ke daftar</a>
  </div>
</div>
@endsection
