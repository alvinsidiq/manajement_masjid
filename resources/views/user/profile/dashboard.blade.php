@extends('layouts.admin', ['pageTitle' => 'Profil Pengguna'])
@section('content')
@php
  $emailStatus = $u->hasVerifiedEmail() ? 'Terverifikasi' : 'Belum Verifikasi';
@endphp

<div class="space-y-4">
  <div class="bg-white p-4 rounded-xl shadow">
    <div class="flex flex-wrap items-center justify-between gap-3">
      <div>
        <div class="text-sm text-gray-500">Profil</div>
        <div class="text-xl font-semibold">{{ $u->username }}</div>
      </div>
      <span class="inline-flex items-center px-3 py-1 rounded-full border text-xs font-semibold {{ $u->hasVerifiedEmail() ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-amber-100 text-amber-700 border-amber-200' }}">
        {{ $emailStatus }}
      </span>
    </div>
    <div class="mt-2 text-sm text-gray-600">Perbarui informasi akun untuk keperluan booking dan notifikasi.</div>
  </div>

  @if(session('status'))
    <div class="rounded border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-700">{{ session('status') }}</div>
  @endif
  @if(session('status_password'))
    <div class="rounded border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-700">{{ session('status_password') }}</div>
  @endif
  @if($errors->any())
    <div class="rounded border border-rose-200 bg-rose-50 px-3 py-2 text-sm text-rose-700">
      <ul class="list-disc list-inside space-y-1">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="grid lg:grid-cols-3 gap-4">
    <div class="lg:col-span-2 space-y-4">
      <div class="bg-white p-4 rounded-xl shadow">
        <div class="flex items-center justify-between gap-3">
          <div>
            <div class="text-sm text-gray-500">Informasi profil</div>
            <div class="text-lg font-semibold">Data utama pengguna</div>
          </div>
        </div>

        <form method="post" action="{{ route('user.profile.update') }}" class="mt-4 grid md:grid-cols-2 gap-4">
          @csrf @method('PUT')
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
            <input type="text" name="username" value="{{ old('username', $u->username) }}" class="w-full rounded border px-3 py-2" required>
            @error('username')<div class="text-xs text-rose-600 mt-1">{{ $message }}</div>@enderror
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $u->email) }}" class="w-full rounded border px-3 py-2" required>
            @error('email')<div class="text-xs text-rose-600 mt-1">{{ $message }}</div>@enderror
            <div class="text-xs text-gray-500 mt-1">
              @if($u->hasVerifiedEmail())
                Email sudah terverifikasi.
              @else
                Email belum terverifikasi; perubahan email akan mengirim verifikasi baru.
              @endif
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama lengkap</label>
            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $u->nama_lengkap) }}" class="w-full rounded border px-3 py-2" placeholder="Contoh: Ahmad Fauzi">
            @error('nama_lengkap')<div class="text-xs text-rose-600 mt-1">{{ $message }}</div>@enderror
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
            <input type="text" name="nik" value="{{ old('nik', $u->nik) }}" class="w-full rounded border px-3 py-2" placeholder="16 digit angka">
            @error('nik')<div class="text-xs text-rose-600 mt-1">{{ $message }}</div>@enderror
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
            <input type="text" name="no_telephone" value="{{ old('no_telephone', $u->no_telephone) }}" class="w-full rounded border px-3 py-2" placeholder="Contoh: +62 812-3456-7890">
            @error('no_telephone')<div class="text-xs text-rose-600 mt-1">{{ $message }}</div>@enderror
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat lengkap</label>
            <textarea name="alamat_lengkap" rows="3" class="w-full rounded border px-3 py-2" placeholder="Tuliskan alamat lengkap sesuai domisili">{{ old('alamat_lengkap', $u->alamat_lengkap) }}</textarea>
            @error('alamat_lengkap')<div class="text-xs text-rose-600 mt-1">{{ $message }}</div>@enderror
          </div>
          <div class="md:col-span-2 flex items-center justify-between gap-2">
            <p class="text-xs text-gray-500">Pastikan data sesuai untuk mempermudah konfirmasi booking.</p>
            <button class="rounded bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Simpan perubahan</button>
          </div>
        </form>
      </div>

      <div class="bg-white p-4 rounded-xl shadow">
        <div>
          <div class="text-sm text-gray-500">Keamanan akun</div>
          <div class="text-lg font-semibold">Perbarui password</div>
        </div>

        <form method="post" action="{{ route('user.profile.password.update') }}" class="mt-4 grid md:grid-cols-2 gap-4">
          @csrf @method('PUT')
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Password saat ini</label>
            <input type="password" name="current_password" class="w-full rounded border px-3 py-2" required>
            @error('current_password')<div class="text-xs text-rose-600 mt-1">{{ $message }}</div>@enderror
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password baru</label>
            <input type="password" name="password" class="w-full rounded border px-3 py-2" required>
            @error('password')<div class="text-xs text-rose-600 mt-1">{{ $message }}</div>@enderror
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi password baru</label>
            <input type="password" name="password_confirmation" class="w-full rounded border px-3 py-2" required>
          </div>
          <div class="md:col-span-2 flex items-center justify-between gap-2">
            <p class="text-xs text-gray-500">Gunakan kombinasi huruf, angka, dan simbol.</p>
            <button class="rounded bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">Perbarui password</button>
          </div>
        </form>
      </div>
    </div>

    <aside class="space-y-4">
      <div class="bg-white p-4 rounded-xl shadow">
        <h3 class="text-sm font-semibold text-gray-700">Informasi akun</h3>
        <dl class="mt-3 space-y-2 text-sm text-gray-700">
          <div class="flex items-center justify-between gap-2">
            <dt class="text-gray-500">Nama lengkap</dt>
            <dd class="font-semibold text-gray-900">{{ $u->nama_lengkap ?: 'Belum diisi' }}</dd>
          </div>
          <div class="flex items-center justify-between gap-2">
            <dt class="text-gray-500">NIK</dt>
            <dd class="font-semibold text-gray-900">{{ $u->nik ?: 'Belum diisi' }}</dd>
          </div>
          <div class="flex items-center justify-between gap-2">
            <dt class="text-gray-500">Email</dt>
            <dd class="font-semibold text-gray-900">{{ $u->email }}</dd>
          </div>
          <div class="flex items-center justify-between gap-2">
            <dt class="text-gray-500">No. HP</dt>
            <dd class="font-semibold text-gray-900">{{ $u->no_telephone ?: 'Belum diisi' }}</dd>
          </div>
          <div class="flex items-center justify-between gap-2">
            <dt class="text-gray-500">Alamat</dt>
            <dd class="font-semibold text-gray-900">{{ $u->alamat_lengkap ?: 'Belum diisi' }}</dd>
          </div>
          <div class="flex items-center justify-between gap-2">
            <dt class="text-gray-500">Terdaftar sejak</dt>
            <dd class="font-semibold text-gray-900">{{ optional($u->created_at)->timezone('Asia/Jakarta')->format('d M Y') }}</dd>
          </div>
        </dl>
      </div>

      <div class="bg-white p-4 rounded-xl shadow">
        <h3 class="text-sm font-semibold text-gray-700">Tips keamanan</h3>
        <ul class="mt-2 space-y-2 text-xs text-gray-600">
          <li>Gunakan password unik dan perbarui secara berkala.</li>
          <li>Pastikan email aktif agar notifikasi booking terkirim.</li>
          <li>Jangan bagikan akun kepada orang lain.</li>
        </ul>
      </div>
    </aside>
  </div>
</div>
@endsection
