@extends('layouts.admin', ['pageTitle'=>'Arsip Kegiatan'])
@section('content')
@if(session('status'))
  <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('status') }}</div>
@endif
<div class="bg-white p-4 rounded-xl shadow space-y-4">
  <form method="get" class="grid md:grid-cols-12 gap-3">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama/penanggung jawab/deskripsi" class="border rounded px-3 py-2 md:col-span-4">
    <select name="jenis_kegiatan" class="border rounded px-3 py-2 md:col-span-2">
      <option value="">- Semua Jenis -</option>
      @foreach(['rutin','berkala','khusus'] as $s)
        <option value="{{ $s }}" @selected(request('jenis_kegiatan')===$s)>{{ ucfirst($s) }}</option>
      @endforeach
    </select>
    <input type="date" name="date_from" value="{{ request('date_from') }}" class="border rounded px-3 py-2 md:col-span-2">
    <input type="date" name="date_to" value="{{ request('date_to') }}" class="border rounded px-3 py-2 md:col-span-2">
    <div class="flex gap-2 md:col-span-2">
      <select name="sort" class="border rounded px-3 py-2">
        @foreach(['archived_at'=>'Waktu Arsip','nama_kegiatan'=>'Nama'] as $k=>$v)
          <option value="{{ $k }}" @selected(request('sort','archived_at')===$k)>{{ $v }}</option>
        @endforeach
      </select>
      <select name="dir" class="border rounded px-3 py-2">
        <option value="asc" @selected(request('dir','desc')==='asc')>Asc</option>
        <option value="desc" @selected(request('dir','desc')==='desc')>Desc</option>
      </select>
    </div>
  </form>

  <div class="flex justify-between items-center">
    <div class="text-sm text-gray-600">Total: {{ $items->total() }}</div>
    <div class="flex gap-2">
      <a href="{{ route('admin.kegiatan.create',['arsip'=>1]) }}" class="px-3 py-2 rounded bg-blue-600 text-white">Tambah Arsip</a>
      <a href="{{ route('admin.kegiatan.index') }}" class="px-3 py-2 rounded bg-gray-200">Kembali ke Kegiatan</a>
    </div>
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead>
        <tr class="text-left border-b">
          <th class="py-2 px-3">Nama</th>
          <th class="py-2 px-3">Jenis</th>
          <th class="py-2 px-3">PJ</th>
          <th class="py-2 px-3">Diarsipkan</th>
          <th class="py-2 px-3">Alasan</th>
          <th class="py-2 px-3">Aksi</th>
        </tr>
      </thead>
      <tbody>
      @forelse($items as $k)
        <tr class="border-b">
          <td class="py-2 px-3 font-semibold"><a href="{{ route('admin.kegiatan-arsip.show',$k) }}" class="hover:underline">{{ $k->nama_kegiatan }}</a></td>
          <td class="py-2 px-3">{{ ucfirst($k->jenis_kegiatan->value) }}</td>
          <td class="py-2 px-3">{{ $k->penanggung_jawab }}</td>
          <td class="py-2 px-3">{{ optional($k->archived_at)->timezone('Asia/Jakarta')->format('d M Y H:i') }}</td>
          <td class="py-2 px-3">{{ str($k->archive_reason)->limit(40) }}</td>
          <td class="py-2 px-3">
            <div class="flex flex-wrap gap-2">
              <a href="{{ route('admin.kegiatan.edit',$k) }}" class="px-2 py-1 rounded bg-yellow-500 text-white">Edit Arsip</a>
              <form method="post" action="{{ route('admin.kegiatan.destroy',$k) }}" onsubmit="return confirm('Hapus arsip kegiatan ini?');">
                @csrf @method('DELETE')
                <button class="px-2 py-1 rounded bg-red-600 text-white">Hapus</button>
              </form>
              <form method="post" action="{{ route('admin.kegiatan.unarchive',$k) }}">
                @csrf
                <button class="px-2 py-1 rounded bg-green-600 text-white">Buka Kembali</button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="6" class="py-6 text-center text-gray-500">Belum ada arsip.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>

  {{ $items->links() }}
</div>
@endsection
