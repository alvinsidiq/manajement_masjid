@extends('layouts.admin', ['pageTitle'=>'Konfirmasi Pemesanan Ruangan'])
@section('content')
<div class="bg-white p-4 rounded-xl shadow space-y-4">
  <div class="text-lg font-semibold">Setup Diperlukan</div>
  <p class="text-sm text-gray-700">Tabel <code>pemesanan</code> belum tersedia. Jalankan migrasi (dan seeder opsional) berikut dari root proyek:</p>
  <pre class="bg-gray-900 text-gray-100 p-3 rounded text-sm overflow-auto">php artisan migrate
php artisan db:seed --class=InitSeeder
php artisan db:seed --class=RuanganSeeder
php artisan db:seed --class=PemesananSeeder</pre>
  <p class="text-sm text-gray-600">Setelah itu, muat ulang halaman ini.</p>
  <a href="{{ route('admin.dashboard') }}" class="inline-block px-3 py-2 rounded bg-gray-200">Kembali ke Dashboard</a>
</div>
@endsection

