@extends('layouts.admin', ['pageTitle'=>'Kelola Arsip'])
@section('content')
@if(session('status'))
  <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('status') }}</div>
@endif
<div class="bg-white p-4 rounded-xl shadow space-y-4">
  <form method="get" class="grid md:grid-cols-12 gap-3">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul/deskripsi" class="border rounded px-3 py-2 md:col-span-6">
    <div class="flex gap-2 md:col-span-3">
      <select name="sort" class="border rounded px-3 py-2 w-full">
        @foreach(['created_at'=>'Tanggal', 'judul'=>'Judul'] as $k=>$v)
          <option value="{{ $k }}" @selected(request('sort','created_at')===$k)>{{ $v }}</option>
        @endforeach
      </select>
      <select name="dir" class="border rounded px-3 py-2 w-full">
        <option value="asc" @selected(request('dir','desc')==='asc')>Asc</option>
        <option value="desc" @selected(request('dir','desc')==='desc')>Desc</option>
      </select>
    </div>
  </form>

  <div class="flex justify-between items-center">
    <div class="text-sm text-gray-600">Total: {{ $items->total() }}</div>
    <div class="flex gap-2">
      <a href="{{ route('admin.arsip.create') }}" class="px-3 py-2 rounded bg-blue-600 text-white">Tambah Arsip</a>
    </div>
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead>
        <tr class="text-left border-b">
          <th class="py-2 px-3">Judul</th>
          <th class="py-2 px-3">Diunggah Oleh</th>
          <th class="py-2 px-3">Tanggal</th>
          <th class="py-2 px-3">Dokumen</th>
          <th class="py-2 px-3">Aksi</th>
        </tr>
      </thead>
      <tbody>
      @forelse($items as $a)
        <tr class="border-b">
          <td class="py-2 px-3 font-semibold"><a href="{{ route('admin.arsip.show',$a) }}" class="hover:underline">{{ $a->judul }}</a></td>
          <td class="py-2 px-3">{{ optional($a->uploader)->username ?? optional($a->uploader)->name ?? '-' }}</td>
          <td class="py-2 px-3">{{ optional($a->created_at)->timezone('Asia/Jakarta')->format('d M Y H:i') }}</td>
          <td class="py-2 px-3">
            <a href="{{ asset('storage/'.$a->dokumen) }}" target="_blank" class="underline text-blue-600 hover:text-blue-800">Unduh</a>
          </td>
          <td class="py-2 px-3">
            <div class="flex flex-wrap gap-2">
              <a href="{{ route('admin.arsip.edit',$a) }}" class="px-2 py-1 rounded bg-yellow-500 text-white">Edit</a>
              <form method="post" action="{{ route('admin.arsip.destroy',$a) }}" onsubmit="return confirm('Hapus arsip ini?');">
                @csrf @method('DELETE')
                <button class="px-2 py-1 rounded bg-red-600 text-white">Hapus</button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr><td colspan="5" class="py-6 text-center text-gray-500">Belum ada arsip.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>

  {{ $items->links() }}
</div>
@endsection
