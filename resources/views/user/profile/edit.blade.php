@extends('layouts.landing', ['pageTitle' => 'Profil Saya'])
@section('content')
<div class="max-w-5xl mx-auto px-4 py-12 space-y-10">
  <div class="relative overflow-hidden rounded-4xl bg-gradient-to-br from-emerald-500 via-sky-500 to-indigo-600 text-white shadow-2xl">
    <img src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=60" alt="" class="absolute inset-0 w-full h-full object-cover opacity-30">
    <div class="absolute inset-0 bg-gradient-to-br from-emerald-600/90 via-emerald-500/70 to-sky-600/80"></div>
    <div class="relative p-8 lg:p-10 flex flex-col gap-6">
      <div class="flex flex-wrap items-center gap-4">
        <div class="h-14 w-14 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-2xl font-semibold shadow">{{ strtoupper(substr($u->username,0,1)) }}</div>
        <div>
          <p class="text-xs uppercase tracking-[0.4em] text-white/70">Profil pengguna</p>
          <h1 class="mt-1 text-3xl font-semibold">Halo, {{ $u->username }}</h1>
          <p class="text-sm text-white/80">Perbarui informasi kontak dan pastikan keamanan akun selalu terjaga.</p>
        </div>
      </div>
      <dl class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3 text-xs">
        <div class="rounded-2xl bg-white/10 px-4 py-3 backdrop-blur-sm">
          <dt class="text-white/70 uppercase tracking-widest">Status email</dt>
          <dd class="text-base font-semibold">{{ $u->hasVerifiedEmail() ? 'Terverifikasi' : 'Belum Verifikasi' }}</dd>
        </div>
        <div class="rounded-2xl bg-white/10 px-4 py-3 backdrop-blur-sm">
          <dt class="text-white/70 uppercase tracking-widest">Terakhir diperbarui</dt>
          <dd class="text-base font-semibold">{{ optional($u->updated_at)->timezone('Asia/Jakarta')->format('d M Y H:i') }}</dd>
        </div>
        <div class="rounded-2xl bg-white/10 px-4 py-3 backdrop-blur-sm">
          <dt class="text-white/70 uppercase tracking-widest">Bergabung sejak</dt>
          <dd class="text-base font-semibold">{{ optional($u->created_at)->timezone('Asia/Jakarta')->format('d M Y') }}</dd>
        </div>
      </dl>
    </div>
  </div>

  @if(session('status') || session('status_password') || $errors->any())
    <div class="space-y-3">
      @if(session('status'))
        <div class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 shadow-sm">
          <span class="mt-0.5 h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
          <div>{{ session('status') }}</div>
        </div>
      @endif
      @if(session('status_password'))
        <div class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 shadow-sm">
          <span class="mt-0.5 h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
          <div>{{ session('status_password') }}</div>
        </div>
      @endif
    </div>
  @endif

  <div class="grid gap-8 lg:grid-cols-[1.65fr,1fr]">
    <div class="space-y-8">
      <div class="rounded-3xl border border-slate-100 bg-white shadow-xl">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-6 py-4">
          <div>
            <p class="text-xs uppercase tracking-[0.4em] text-emerald-600">Informasi profil</p>
            <p class="text-sm text-slate-500">Perbarui data utama untuk kebutuhan booking & notifikasi.</p>
          </div>
          <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>Terhubung
          </span>
        </div>
        <form method="post" action="{{ route('user.profile.update') }}" class="px-6 py-6 grid md:grid-cols-2 gap-6">
          @csrf @method('PUT')
          <div class="space-y-2">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Username</label>
            <input type="text" name="username" value="{{ old('username', $u->username) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100" required>
            @error('username')<p class="text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
          </div>
          <div class="space-y-2">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
            <input type="email" name="email" value="{{ old('email', $u->email) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100" required>
            @error('email')<p class="text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
            <p class="text-xs text-gray-500 dark:text-gray-400">
              @if($u->hasVerifiedEmail())
                Email sudah terverifikasi.
              @else
                Email <span class="text-amber-600">belum terverifikasi</span>; perubahan email akan mengirim verifikasi baru.
              @endif
            </p>
          </div>
          <div class="md:col-span-2 space-y-2">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-200">No. Telepon</label>
            <input type="text" name="no_telephone" value="{{ old('no_telephone', $u->no_telephone) }}" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100" placeholder="Contoh: +62 812-3456-7890">
            @error('no_telephone')<p class="text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
          </div>
          <div class="md:col-span-2 flex flex-wrap items-center justify-between gap-3">
            <p class="text-xs text-slate-500">Pastikan data sesuai untuk mempermudah konfirmasi booking.</p>
            <button class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-5 py-2 text-sm font-semibold text-white shadow hover:bg-slate-800">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
              Simpan perubahan
            </button>
          </div>
        </form>
      </div>

      <div class="rounded-3xl border border-slate-100 bg-white shadow-xl">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-6 py-4">
          <div>
            <p class="text-xs uppercase tracking-[0.4em] text-slate-600">Keamanan akun</p>
            <p class="text-sm text-slate-500">Gunakan password kuat dan unik.</p>
          </div>
          <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
            <span class="h-2 w-2 rounded-full bg-slate-600"></span>Terlindungi
          </span>
        </div>
        <form method="post" action="{{ route('user.profile.password.update') }}" class="px-6 py-6 grid md:grid-cols-2 gap-6">
          @csrf @method('PUT')
          <div class="md:col-span-2 space-y-2">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Password Saat Ini</label>
            <input type="password" name="current_password" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-slate-500 focus:ring-2 focus:ring-slate-100" required>
            @error('current_password')<p class="text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
          </div>
          <div class="space-y-2">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Password Baru</label>
            <input type="password" name="password" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-slate-500 focus:ring-2 focus:ring-slate-100" required>
            @error('password')<p class="text-xs font-medium text-rose-600">{{ $message }}</p>@enderror
          </div>
          <div class="space-y-2">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-slate-500 focus:ring-2 focus:ring-slate-100" required>
          </div>
          <div class="md:col-span-2 flex flex-wrap items-center justify-between gap-3">
            <p class="text-xs text-slate-500">Gunakan kombinasi huruf, angka, dan simbol untuk keamanan maksimal.</p>
            <button class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-5 py-2 text-sm font-semibold text-white shadow hover:bg-emerald-500">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
              Perbarui password
            </button>
          </div>
        </form>
      </div>
    </div>

    <aside class="space-y-6">
      <div class="rounded-3xl border border-indigo-100 bg-gradient-to-br from-indigo-50 via-sky-50 to-white p-6 shadow-inner">
        <h2 class="text-sm font-semibold text-indigo-700">Tips menjaga keamanan</h2>
        <ul class="mt-3 space-y-2 text-xs text-indigo-700/90">
          <li class="flex gap-2"><span class="mt-1 h-2 w-2 rounded-full bg-indigo-300"></span> Aktifkan verifikasi email agar notifikasi penting sampai.</li>
          <li class="flex gap-2"><span class="mt-1 h-2 w-2 rounded-full bg-indigo-300"></span> Perbarui password secara berkala dan hindari menggunakan password lama.</li>
          <li class="flex gap-2"><span class="mt-1 h-2 w-2 rounded-full bg-indigo-300"></span> Jangan bagikan akun dengan orang lain untuk menjaga privasi data booking.</li>
        </ul>
      </div>

      <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-lg">
        <h2 class="text-sm font-semibold text-slate-700">Informasi akun</h2>
        <dl class="mt-4 space-y-3 text-xs text-slate-600">
          <div>
            <dt class="uppercase tracking-wide text-gray-400">Email saat ini</dt>
            <dd class="font-medium">{{ $u->email }}</dd>
          </div>
          <div>
            <dt class="uppercase tracking-wide text-gray-400">Nomor telepon</dt>
            <dd class="font-medium">{{ $u->no_telephone ?: 'Belum diisi' }}</dd>
          </div>
          <div>
            <dt class="uppercase tracking-wide text-gray-400">Terdaftar sejak</dt>
            <dd class="font-medium">{{ optional($u->created_at)->timezone('Asia/Jakarta')->format('d M Y') }}</dd>
          </div>
        </dl>
        <a href="{{ route('user.settings.index') }}" class="mt-5 inline-flex items-center gap-2 rounded-full border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-700 hover:border-emerald-200 hover:text-emerald-600">
          Pengaturan preferensi
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m9 5 7 7-7 7"/></svg>
        </a>
      </div>
    </aside>
  </div>
</div>
@endsection
