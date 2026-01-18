@extends('layouts.admin', ['pageTitle'=>'Kelola Ruangan'])
@section('content')
@if(session('status'))
  <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('status') }}</div>
@endif
<div class="bg-white p-4 rounded-xl shadow space-y-4">
  <form method="get" class="grid md:grid-cols-6 gap-3">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama/deskripsi" class="border rounded px-3 py-2 md:col-span-2">
    <select name="status" class="border rounded px-3 py-2">
      <option value="">- Semua Status -</option>
      @foreach(['aktif','nonaktif','maintenance'] as $s)
        <option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst($s) }}</option>
      @endforeach
    </select>
    <input type="text" name="f" value="{{ request('f') }}" placeholder="Fasilitas (pisah koma)" class="border rounded px-3 py-2 md:col-span-2">
    <div class="flex gap-2">
      <select name="sort" class="border rounded px-3 py-2">
        @foreach(['created_at'=>'Dibuat','nama_ruangan'=>'Nama'] as $k=>$v)
          <option value="{{ $k }}" @selected(request('sort','created_at')===$k)>{{ $v }}</option>
        @endforeach
      </select>
      <select name="dir" class="border rounded px-3 py-2">
        <option value="asc" @selected(request('dir','desc')==='asc')>Asc</option>
        <option value="desc" @selected(request('dir','desc')==='desc')>Desc</option>
      </select>
    </div>
  </form>

  <div class="flex justify-between items-center">
    <div class="text-sm text-gray-600">Total: {{ $ruangan->total() }}</div>
    <a href="{{ route('admin.ruangan.create') }}" class="px-3 py-2 rounded bg-blue-600 text-white">Tambah</a>
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead>
        <tr class="text-left border-b">
          <th class="py-2 px-3">Foto</th>
          <th class="py-2 px-3">Nama</th>
          <th class="py-2 px-3">Harga</th>
          <th class="py-2 px-3">Kapasitas</th>
          <th class="py-2 px-3">Status</th>
          <th class="py-2 px-3">Fasilitas</th>
          <th class="py-2 px-3">Aksi</th>
        </tr>
      </thead>
      <tbody>
      @forelse($ruangan as $r)
        <tr class="border-b">
          <td class="py-2 px-3">
            <a href="{{ route('admin.ruangan.edit',$r) }}" class="inline-block" title="Ganti foto">
              @if($r->foto)
                <img src="{{ asset('storage/'.$r->foto) }}" class="w-16 h-12 object-cover rounded" alt="{{ $r->nama_ruangan }}">
              @else
                <div class="w-16 h-12 bg-gray-200 rounded"></div>
              @endif
            </a>
          </td>
          <td class="py-2 px-3">
            <a class="font-semibold hover:underline" href="{{ route('admin.ruangan.show',$r) }}">{{ $r->nama_ruangan }}</a>
            <div class="text-xs text-gray-500 line-clamp-1">{{ $r->deskripsi }}</div>
          </td>
          <td class="py-2 px-3">Rp {{ number_format($r->harga ?? 0,0,',','.') }}</td>
          <td class="py-2 px-3">{{ number_format($r->kapasitas ?? 0,0,',','.') }}</td>
          <td class="py-2 px-3">
            <span class="px-2 py-1 rounded text-xs @class([
              'bg-green-100 text-green-800' => $r->status==='aktif',
              'bg-yellow-100 text-yellow-800' => $r->status==='maintenance',
              'bg-gray-200 text-gray-800' => $r->status==='nonaktif',
            ])">{{ ucfirst($r->status) }}</span>
          </td>
          <td class="py-2 px-3">
            @if($r->fasilitas)
              <div class="flex flex-wrap gap-1">
                @foreach($r->fasilitas as $f)
                  <span class="px-2 py-0.5 rounded bg-gray-100">{{ $f }}</span>
                @endforeach
              </div>
            @endif
          </td>
          <td class="py-2 px-3 flex gap-2">
            <a class="px-2 py-1 rounded bg-white border" href="{{ route('admin.ruangan.show',$r) }}">Lihat</a>
            <a class="px-2 py-1 rounded bg-yellow-500 text-white" href="{{ route('admin.ruangan.edit',$r) }}">Ubah</a>
            <form method="post" action="{{ route('admin.ruangan.destroy',$r) }}" onsubmit="return confirm('Hapus ruangan ini?')">
              @csrf @method('DELETE')
              <button class="px-2 py-1 rounded bg-red-600 text-white">Hapus</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="7" class="py-6 text-center text-gray-500">Belum ada data.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>

  {{ $ruangan->links() }}
</div>
@endsection
