@extends('layouts.admin', ['pageTitle' => 'Kelola Pengguna'])
@section('content')
@if(session('status'))
  <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('status') }}</div>
@endif
<div class="bg-white p-4 rounded-xl shadow space-y-4">
  <form method="get" class="grid md:grid-cols-7 gap-3">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari username/email" class="border rounded px-3 py-2 md:col-span-2">
    <select name="role" class="border rounded px-3 py-2">
      <option value="">- Semua Peran -</option>
      @foreach(['admin','user','bendahara','takmir'] as $r)
        <option value="{{ $r }}" @selected(request('role')===$r)>{{ ucfirst($r) }}</option>
      @endforeach
    </select>
    <select name="active" class="border rounded px-3 py-2">
      <option value="">- Status -</option>
      <option value="1" @selected(request('active')==='1')>Aktif</option>
      <option value="0" @selected(request('active')==='0')>Nonaktif</option>
    </select>
    <select name="verified" class="border rounded px-3 py-2">
      <option value="">- Verifikasi -</option>
      <option value="1" @selected(request('verified')==='1')>Terverifikasi</option>
      <option value="0" @selected(request('verified')==='0')>Belum Verifikasi</option>
    </select>
    <div class="flex gap-2 md:col-span-2">
      <select name="sort" class="border rounded px-3 py-2">
        @foreach(['created_at'=>'Dibuat','username'=>'Username','email'=>'Email'] as $k=>$v)
          <option value="{{ $k }}" @selected(request('sort',$sort)===$k)>{{ $v }}</option>
        @endforeach
      </select>
      <select name="dir" class="border rounded px-3 py-2">
        <option value="asc" @selected(request('dir',$dir)==='asc')>Asc</option>
        <option value="desc" @selected(request('dir',$dir)==='desc')>Desc</option>
      </select>
    </div>
  </form>

  <div class="flex justify-between items-center">
    <div class="text-sm text-gray-600">Total: {{ $users->total() }}</div>
    <a href="{{ route('admin.users.create') }}" class="px-3 py-2 rounded bg-blue-600 text-white">Tambah</a>
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead>
        <tr class="text-left border-b">
          <th class="py-2 px-3">Username</th>
          <th class="py-2 px-3">Email</th>
          <th class="py-2 px-3">Peran</th>
          <th class="py-2 px-3">Status</th>
          <th class="py-2 px-3">Verifikasi</th>
          <th class="py-2 px-3">Aksi</th>
        </tr>
      </thead>
      <tbody>
      @foreach($users as $u)
        <tr class="border-b">
          <td class="py-2 px-3">{{ $u->username }}</td>
          <td class="py-2 px-3">{{ $u->email }}</td>
          <td class="py-2 px-3">{{ ucfirst($u->role) }}</td>
          <td class="py-2 px-3">
            <span class="px-2 py-1 rounded text-xs {{ $u->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-800' }}">{{ $u->is_active ? 'Aktif' : 'Nonaktif' }}</span>
          </td>
          <td class="py-2 px-3">
            <span class="px-2 py-1 rounded text-xs {{ $u->hasVerifiedEmail() ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
              {{ $u->hasVerifiedEmail() ? 'Terverifikasi' : 'Belum Verifikasi' }}
            </span>
          </td>
          <td class="py-2 px-3 flex gap-2">
            <a class="px-2 py-1 rounded bg-white border" href="{{ route('admin.users.show',$u) }}">Lihat</a>
            <a class="px-2 py-1 rounded bg-yellow-500 text-white" href="{{ route('admin.users.edit',$u) }}">Ubah</a>
            @if(auth()->id() !== $u->user_id)
            <form method="post" action="{{ route('admin.users.destroy',$u) }}" onsubmit="return confirm('Hapus pengguna ini?')">
              @csrf @method('DELETE')
              <button class="px-2 py-1 rounded bg-red-600 text-white">Hapus</button>
            </form>
            @endif
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>

  {{ $users->links() }}
</div>
@endsection
