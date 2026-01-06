@extends('layouts.admin', ['pageTitle'=>'Kelola Kegiatan'])
@section('content')
@if(session('status'))
  <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('status') }}</div>
@endif
<div class="bg-white p-4 rounded-xl shadow space-y-4">
  <form method="get" class="grid md:grid-cols-6 gap-3">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama/penanggung jawab/deskripsi" class="border rounded px-3 py-2 md:col-span-3">
    <select name="jenis_kegiatan" class="border rounded px-3 py-2">
      <option value="">- Semua Jenis -</option>
      @foreach(['rutin','berkala','khusus'] as $s)
        <option value="{{ $s }}" @selected(request('jenis_kegiatan')===$s)>{{ ucfirst($s) }}</option>
      @endforeach
    </select>
    <div class="flex gap-2 md:col-span-2">
      <select name="sort" class="border rounded px-3 py-2">
        @foreach(['created_at'=>'Dibuat','nama_kegiatan'=>'Nama'] as $k=>$v)
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
    <div class="text-sm text-gray-600">Total: {{ $kegiatan->total() }}</div>
    <a href="{{ route('admin.kegiatan.create') }}" class="px-3 py-2 rounded bg-blue-600 text-white">Tambah</a>
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead>
        <tr class="text-left border-b">
          <th class="py-2 px-3">Foto</th>
          <th class="py-2 px-3">Nama</th>
          <th class="py-2 px-3">Jenis</th>
          <th class="py-2 px-3">Status</th>
          <th class="py-2 px-3">Penanggung Jawab</th>
          <th class="py-2 px-3">Telepon</th>
          <th class="py-2 px-3">Aksi</th>
        </tr>
      </thead>
      <tbody>
      @forelse($kegiatan as $k)
        <tr class="border-b">
          <td class="py-2 px-3">
            @if($k->foto)
              <img src="{{ asset('storage/'.$k->foto) }}" alt="{{ $k->nama_kegiatan }}" class="w-16 h-12 object-cover rounded border">
            @else
              <div class="w-16 h-12 rounded border border-dashed flex items-center justify-center text-xs text-gray-400">Tidak ada</div>
            @endif
          </td>
          <td class="py-2 px-3">
            <a class="font-semibold hover:underline" href="{{ route('admin.kegiatan.show',$k) }}">{{ $k->nama_kegiatan }}</a>
            @if($k->deskripsi)
              <div class="text-xs text-gray-500 line-clamp-1">{{ $k->deskripsi }}</div>
            @endif
          </td>
          <td class="py-2 px-3">{{ ucfirst($k->jenis_kegiatan->value) }}</td>
          <td class="py-2 px-3">
            @php($status = $k->approval_status?->value ?? 'pending')
            <span class="px-2 py-1 rounded text-xs font-medium @class([
              'bg-yellow-100 text-yellow-800' => $status === 'pending',
              'bg-green-100 text-green-800' => $status === 'approved',
              'bg-red-100 text-red-800' => $status === 'rejected',
            ])">
              {{ $k->approval_status?->label() ?? 'Menunggu persetujuan takmir' }}
            </span>
          </td>
          <td class="py-2 px-3">{{ $k->penanggung_jawab }}</td>
          <td class="py-2 px-3">{{ $k->no_telephone }}</td>
          <td class="py-2 px-3 flex gap-2">
            <a class="px-2 py-1 rounded bg-white border" href="{{ route('admin.kegiatan.show',$k) }}">Lihat</a>
            <a class="px-2 py-1 rounded bg-yellow-500 text-white" href="{{ route('admin.kegiatan.edit',$k) }}">Ubah</a>
            <form method="post" action="{{ route('admin.kegiatan.destroy',$k) }}" onsubmit="return confirm('Hapus kegiatan ini?')">
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

  {{ $kegiatan->links() }}
</div>
@endsection
