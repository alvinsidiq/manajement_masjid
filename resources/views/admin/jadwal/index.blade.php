@extends('layouts.admin', ['pageTitle'=>'Jadwal Kegiatan'])
@section('content')
@if(session('status'))<div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('status') }}</div>@endif
<div class="bg-white p-4 rounded-xl shadow space-y-4">
  <div class="flex justify-between items-center">
    <form method="get" class="grid md:grid-cols-12 gap-3 flex-1">
      <input type="text" name="q" value="{{ $q }}" placeholder="Cari kegiatan/ruangan/catatan" class="border rounded px-3 py-2 md:col-span-3">
      <select name="kegiatan_id" class="border rounded px-3 py-2 md:col-span-3">
        <option value="">- Semua Kegiatan -</option>
        @foreach($kegiatans as $k)
          <option value="{{ $k->kegiatan_id }}" @selected($kid==$k->kegiatan_id)>{{ $k->nama_kegiatan }}</option>
        @endforeach
      </select>
      <select name="ruangan_id" class="border rounded px-3 py-2 md:col-span-3">
        <option value="">- Semua Ruangan -</option>
        @foreach($ruangans as $r)
          <option value="{{ $r->ruangan_id }}" @selected($rid==$r->ruangan_id)>{{ $r->nama_ruangan }}</option>
        @endforeach
      </select>
      <select name="status" class="border rounded px-3 py-2">
        <option value="">- Status -</option>
        @foreach(['rutin','berkala','khusus'] as $s)
          <option value="{{ $s }}" @selected($status==$s)>{{ ucfirst($s) }}</option>
        @endforeach
      </select>
      <input type="date" name="date_from" value="{{ $df }}" class="border rounded px-3 py-2">
      <input type="date" name="date_to" value="{{ $dt }}" class="border rounded px-3 py-2">
      <div class="flex gap-2">
        <select name="sort" class="border rounded px-3 py-2">
          @foreach(['tanggal_mulai'=>'Mulai','tanggal_selesai'=>'Selesai','kegiatan_id'=>'Kegiatan'] as $k=>$v)
            <option value="{{ $k }}" @selected($sort==$k)>{{ $v }}</option>
          @endforeach
        </select>
        <select name="dir" class="border rounded px-3 py-2">
          <option value="asc" @selected($dir==='asc')>Asc</option>
          <option value="desc" @selected($dir==='desc')>Desc</option>
        </select>
      </div>
    </form>
    <a href="{{ route('admin.jadwal.create') }}" class="px-3 py-2 rounded bg-blue-600 text-white">Tambah</a>
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead><tr class="text-left border-b">
        <th class="py-2 px-3">Mulai</th>
        <th class="py-2 px-3">Selesai</th>
        <th class="py-2 px-3">Kegiatan</th>
        <th class="py-2 px-3">Ruangan</th>
        <th class="py-2 px-3">Status</th>
        <th class="py-2 px-3">Catatan</th>
        <th class="py-2 px-3">Aksi</th>
      </tr></thead>
      <tbody>
        @forelse($items as $j)
        <tr class="border-b">
          <td class="py-2 px-3">{{ $j->tanggal_mulai->timezone('Asia/Jakarta')->format('d M Y H:i') }}</td>
          <td class="py-2 px-3">{{ $j->tanggal_selesai->timezone('Asia/Jakarta')->format('d M Y H:i') }}</td>
          <td class="py-2 px-3">{{ $j->kegiatan->nama_kegiatan }}</td>
          <td class="py-2 px-3">{{ $j->ruangan?->nama_ruangan ?? '-' }}</td>
          <td class="py-2 px-3">{{ ucfirst($j->status->value) }}</td>
          <td class="py-2 px-3">{{ str($j->catatan)->limit(32) }}</td>
          <td class="py-2 px-3 flex gap-2">
            <a href="{{ route('admin.jadwal.show',$j) }}" class="px-2 py-1 rounded border">Detail</a>
            <a href="{{ route('admin.jadwal.edit',$j) }}" class="px-2 py-1 rounded border">Ubah</a>
            <form method="post" action="{{ route('admin.jadwal.destroy',$j) }}" onsubmit="return confirm('Hapus jadwal?');">
              @csrf @method('DELETE')
              <button class="px-2 py-1 rounded border bg-red-50">Hapus</button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="7" class="py-6 text-center text-gray-500">Belum ada jadwal.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{ $items->links() }}
</div>
@endsection

