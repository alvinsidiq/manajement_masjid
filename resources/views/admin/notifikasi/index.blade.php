@extends('layouts.admin', ['pageTitle'=>'Log Notifikasi'])
@section('content')
@if(session('status'))<div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('status') }}</div>@endif
<div class="bg-white p-4 rounded-xl shadow space-y-4">
  <form method="get" class="grid md:grid-cols-12 gap-3">
    <input type="text" name="q" value="{{ $q }}" placeholder="Cari pesan" class="border rounded px-3 py-2 md:col-span-3">
    <select name="jenis" class="border rounded px-3 py-2 md:col-span-2">
      <option value="">- Semua Jenis -</option>
      @foreach(['umum','pemesanan','payment','kegiatan'] as $x)
        <option value="{{ $x }}" @selected(($jr ?? '')==$x)>{{ ucfirst($x) }}</option>
      @endforeach
    </select>
    <select name="status" class="border rounded px-3 py-2 md:col-span-2">
      <option value="">- Semua Status -</option>
      <option value="sent" @selected(($st ?? '')==='sent')>Terkirim</option>
      <option value="queued" @selected(($st ?? '')==='queued')>Antri</option>
      <option value="failed" @selected(($st ?? '')==='failed')>Gagal</option>
    </select>
    <select name="user_id" class="border rounded px-3 py-2 md:col-span-3">
      <option value="">- Semua User -</option>
      @foreach($users as $u)
        <option value="{{ $u->user_id }}" @selected(($uid ?? '')==$u->user_id)>{{ $u->username }}</option>
      @endforeach
    </select>
    <input type="date" name="date_from" value="{{ $df }}" class="border rounded px-3 py-2">
    <input type="date" name="date_to" value="{{ $dt }}" class="border rounded px-3 py-2">
    <div class="md:col-span-12">
      <a href="{{ route('admin.notifikasi.create') }}" class="px-3 py-2 rounded bg-blue-600 text-white">Broadcast Manual</a>
    </div>
  </form>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead><tr class="text-left border-b">
        <th class="py-2 px-3">#</th>
        <th class="py-2 px-3">User</th>
        <th class="py-2 px-3">Jenis</th>
        <th class="py-2 px-3">Pesan</th>
        <th class="py-2 px-3">Status</th>
        <th class="py-2 px-3">Waktu Kirim</th>
        <th class="py-2 px-3">Aksi</th>
      </tr></thead>
      <tbody>
        @forelse($items as $n)
        <tr class="border-b">
          <td class="py-2 px-3">{{ $n->notifikasi_id }}</td>
          <td class="py-2 px-3">{{ $n->user->username }}</td>
          <td class="py-2 px-3">{{ ucfirst($n->jenis_referensi->value ?? $n->jenis_referensi) }}</td>
          <td class="py-2 px-3">{{ str($n->pesan)->limit(60) }}</td>
          <td class="py-2 px-3">{{ $n->terkirim ? 'Terkirim' : ($n->status_pengiriman ?? '-') }}</td>
          <td class="py-2 px-3">{{ $n->waktu_kirim?->timezone('Asia/Jakarta')->format('d M Y H:i') ?? '-' }}</td>
          <td class="py-2 px-3 flex gap-2">
            <a href="{{ route('admin.notifikasi.show',$n) }}" class="px-2 py-1 rounded border">Detail</a>
            <form method="post" action="{{ route('admin.notifikasi.resend',$n) }}" class="inline">@csrf
              <button class="px-2 py-1 rounded border bg-indigo-50">Kirim Ulang</button>
            </form>
          </td>
        </tr>
        @empty
          <tr><td colspan="7" class="py-6 text-center text-gray-500">Belum ada log.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{ $items->links() }}
</div>
@endsection
