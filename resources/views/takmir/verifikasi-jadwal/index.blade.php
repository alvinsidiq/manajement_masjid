@extends('layouts.admin', ['pageTitle'=>'Verifikasi Jadwal'])
@section('content')
<div class="space-y-5">
  <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-600 to-emerald-600 text-white shadow">
    <div class="absolute inset-0 opacity-15" style="background-image: radial-gradient(circle at 15% 20%, rgba(255,255,255,0.45), transparent 40%), radial-gradient(circle at 80% 10%, rgba(255,255,255,0.25), transparent 45%);"></div>
    <div class="relative grid md:grid-cols-5 gap-4 p-6 items-center">
      <div class="md:col-span-3 space-y-2">
        <div class="text-sm uppercase tracking-wide text-white/80">Panel Verifikasi Kegiatan/Jadwal</div>
        <div class="text-2xl font-bold">Pastikan jadwal kegiatan disetujui dengan cepat</div>
        <p class="text-white/80 max-w-xl">Gunakan filter untuk melihat permintaan yang menunggu persetujuan takmir atau cek arsip verifikasi sebelumnya.</p>
        <p class="text-white/75 text-sm">Tombol mode verifikasi ada di bawah tabel.</p>
      </div>
      <div class="md:col-span-2">
        <div class="bg-white/10 backdrop-blur rounded-xl border border-white/15 overflow-hidden shadow-lg">
          <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=900&q=80" alt="Ilustrasi jadwal" class="w-full h-36 object-cover">
          <div class="p-4 text-sm text-white/90">
            <div class="font-semibold">Highlight</div>
            <div class="text-white/80">Prioritaskan permintaan yang jadwalnya paling dekat agar agenda berjalan tertib.</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @php($isWaiting = ($mode ?? 'verified')==='waiting')
  @php($tableAccent = $isWaiting ? 'border-amber-100 bg-amber-50/60' : 'border-emerald-100 bg-emerald-50/60')
  @php($rowTint = $isWaiting ? 'hover:bg-amber-50/60' : 'hover:bg-emerald-50/60')
  @php($badgeWaiting = $isWaiting ? 'bg-amber-100 text-amber-800' : 'bg-yellow-100 text-yellow-800')
  @php($badgeApproved = $isWaiting ? 'bg-teal-50 text-teal-700' : 'bg-emerald-100 text-emerald-800')
  @php($badgeRejected = $isWaiting ? 'bg-red-100 text-red-800' : 'bg-rose-100 text-rose-800')
  @php($headerTint = $isWaiting ? 'bg-amber-50 text-amber-900' : 'bg-emerald-50 text-emerald-900')
  @php($cardBg = $isWaiting ? 'bg-amber-50' : 'bg-emerald-50')

  <div class="{{ $cardBg }} p-5 rounded-xl shadow border {{ $tableAccent }} space-y-4">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
      <div class="px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center gap-2 {{ $headerTint }}">
        <span class="w-2 h-2 rounded-full {{ $isWaiting ? 'bg-amber-500' : 'bg-emerald-500' }}"></span>
        {{ $isWaiting ? 'Mode: Perlu Verifikasi' : 'Mode: Sudah Diverifikasi' }}
      </div>
      <div class="flex flex-wrap gap-2">
        @php($baseParams = request()->except('mode','page'))
        <a href="{{ route('takmir.verifikasi-jadwal.index', array_merge($baseParams, ['mode'=>'waiting'])) }}"
           class="px-3 py-2 rounded-lg border border-amber-300 {{ $isWaiting ? 'bg-amber-200 text-amber-900' : 'bg-white/60 text-amber-900' }}">
          Perlu Verifikasi
        </a>
        <a href="{{ route('takmir.verifikasi-jadwal.index', array_merge($baseParams, ['mode'=>'verified'])) }}"
           class="px-3 py-2 rounded-lg border border-emerald-300 {{ !$isWaiting ? 'bg-emerald-200 text-emerald-900' : 'bg-white/60 text-emerald-900' }}">
          Sudah Diverifikasi
        </a>
      </div>
    </div>
    @if(session('status'))
      <div class="p-3 rounded bg-green-100 text-green-800">{{ session('status') }}</div>
    @endif
    <form method="get" class="grid md:grid-cols-12 gap-3">
      <input type="text" name="q" value="{{ $q }}" placeholder="Cari tujuan/nama/ruangan" class="border rounded px-3 py-2 md:col-span-4">
      <input type="number" name="ruangan_id" value="{{ $rid }}" placeholder="Ruangan ID" class="border rounded px-3 py-2 md:col-span-2">
      <input type="date" name="date_from" value="{{ $df }}" class="border rounded px-3 py-2">
      <input type="date" name="date_to" value="{{ $dt }}" class="border rounded px-3 py-2">
      <div class="flex gap-2 md:col-span-3">
        <select name="sort" class="border rounded px-3 py-2">
          @foreach(['created_at'=>'Dibuat','user_id'=>'Pemesan','ruangan_id'=>'Ruangan'] as $k=>$v)
            <option value="{{ $k }}" @selected(($sort ?? 'created_at')===$k)>{{ $v }}</option>
          @endforeach
        </select>
        <select name="dir" class="border rounded px-3 py-2">
          <option value="asc" @selected(($dir ?? 'desc')==='asc')>Asc</option>
          <option value="desc" @selected(($dir ?? 'desc')==='desc')>Desc</option>
        </select>
      </div>
      <div>
        <select name="mode" class="border rounded px-3 py-2">
          <option value="verified" @selected(($mode ?? 'verified')==='verified')>Sudah diverifikasi</option>
          <option value="waiting" @selected(($mode ?? 'verified')==='waiting')>Menunggu verifikasi</option>
        </select>
      </div>
    </form>

    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead>
          <tr class="text-left border-b">
            <th class="py-2 px-3">#</th>
            <th class="py-2 px-3">Dibuat</th>
            <th class="py-2 px-3">Pemesan</th>
            <th class="py-2 px-3">Ruangan</th>
            <th class="py-2 px-3">Tujuan</th>
            <th class="py-2 px-3">Status</th>
            <th class="py-2 px-3">Aksi</th>
          </tr>
        </thead>
        <tbody>
        @forelse($items as $p)
          <tr class="border-b {{ $rowTint }}">
            <td class="py-2 px-3 font-semibold text-gray-700">#{{ $p->pemesanan_id }}</td>
            <td class="py-2 px-3 text-gray-600">{{ $p->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}</td>
            <td class="py-2 px-3 text-gray-800">{{ $p->user->username }}</td>
            <td class="py-2 px-3 text-gray-800">{{ $p->ruangan->nama_ruangan }}</td>
            <td class="py-2 px-3 text-gray-700">{{ $p->tujuan_pemesanan }}</td>
            <td class="py-2 px-3">
              @php($st = $p->status->value)
              <span class="px-2 py-1 rounded text-xs @class([
                $badgeWaiting => $st==='menunggu_verifikasi',
                $badgeApproved => $st==='diterima',
                $badgeRejected => $st==='ditolak',
              ])">{{ str($st)->replace('_',' ')->title() }}</span>
            </td>
            <td class="py-2 px-3">
              <a href="{{ route('takmir.verifikasi-jadwal.show',$p) }}" class="px-2 py-1 rounded bg-white border hover:bg-slate-50">Detail</a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="py-6 text-center text-gray-500">
              {{ ($mode ?? 'verified')==='waiting' ? 'Tidak ada jadwal yang perlu diverifikasi.' : 'Belum ada jadwal yang sudah diverifikasi.' }}
            </td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>

    {{ $items->links() }}
  </div>
</div>
@endsection
