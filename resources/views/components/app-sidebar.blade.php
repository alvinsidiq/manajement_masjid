@php
    use Illuminate\Support\Facades\Route as RouteFacade;
    $u = auth()->user();
    $is = fn($name) => RouteFacade::currentRouteNamed($name) ? true : false;
    $safeLink = function($route, $fallback = '#') { return RouteFacade::has($route) ? route($route) : $fallback; };
    $isUserArea = request()->routeIs('user.*');
@endphp

<div class="p-4 font-bold">Panel</div>
<nav class="px-2 space-y-1">
    @if($isUserArea)
      <div class="mt-2 text-xs uppercase text-gray-500 px-3">Pengguna</div>
      <x-nav.sidebar-link :href="$safeLink('home')" :active="$is('home')">Halaman Utama</x-nav.sidebar-link>
      <x-nav.sidebar-link :href="$safeLink('user.dashboard')" :active="$is('user.dashboard')">Dashboard</x-nav.sidebar-link>
      <x-nav.sidebar-link :href="$safeLink('user.booking.dashboard')" :active="$is('user.booking.*')">Booking</x-nav.sidebar-link>
      <x-nav.sidebar-link :href="$safeLink('user.profile.edit')" :active="$is('user.profile.*')">Profil</x-nav.sidebar-link>
    @else
      <x-nav.sidebar-link :href="$safeLink('admin.dashboard')" :active="$is('admin.dashboard')" >Dashboard</x-nav.sidebar-link>
    @endif

    @role('admin')
      <div class="mt-2 text-xs uppercase text-gray-500 px-3">Admin</div>
      <x-nav.sidebar-link :href="$safeLink('admin.users.index')" :active="$is('admin.users.*')">Kelola Pengguna</x-nav.sidebar-link>
      <x-nav.sidebar-link :href="$safeLink('admin.ruangan.index')" :active="$is('admin.ruangan.*')">Kelola Ruangan</x-nav.sidebar-link>
      <x-nav.sidebar-link :href="$safeLink('admin.kegiatan.index')" :active="$is('admin.kegiatan.*')">Kelola Kegiatan</x-nav.sidebar-link>
      <x-nav.sidebar-link href="{{ route('admin.pemesanan.index',['status'=>'menunggu_verifikasi']) }}" :active="request()->routeIs('admin.pemesanan.*') && request('status')==='menunggu_verifikasi'">Informasi Booking</x-nav.sidebar-link>
      <x-nav.sidebar-link href="{{ route('admin.reports.index', ['report'=>'pemesanan']) }}" :active="$is('admin.reports.*')">Laporan</x-nav.sidebar-link>
    @endrole

    @role('bendahara')
      <div class="mt-2 text-xs uppercase text-gray-500 px-3">Bendahara</div>
      <x-nav.sidebar-link :href="$safeLink('bendahara.dashboard')" :active="$is('bendahara.dashboard')">Ringkasan</x-nav.sidebar-link>
      <x-nav.sidebar-link :href="$safeLink('bendahara.payment.create')" :active="$is('bendahara.payment.create')">Input Pembayaran</x-nav.sidebar-link>
      <x-nav.sidebar-link :href="$safeLink('bendahara.payment.index')" :active="$is('bendahara.payment.*')">Laporan Transaksi</x-nav.sidebar-link>
      <x-nav.sidebar-link :href="$safeLink('bendahara.booking.index')" :active="$is('bendahara.booking.*')">Lihat Booking</x-nav.sidebar-link>
    @endrole

    @role('takmir')
      <div class="mt-2 text-xs uppercase text-gray-500 px-3">Takmir</div>
      <x-nav.sidebar-link :href="$safeLink('takmir.dashboard')" :active="$is('takmir.dashboard')">Dashboard</x-nav.sidebar-link>
      <x-nav.sidebar-link :href="$safeLink('takmir.verifikasi-jadwal.index')" :active="$is('takmir.verifikasi-jadwal.*')">Verifikasi Jadwal</x-nav.sidebar-link>
      <x-nav.sidebar-link :href="$safeLink('takmir.verifikasi-booking.index')" :active="$is('takmir.verifikasi-booking.*')">Verifikasi Booking</x-nav.sidebar-link>
    @endrole

    
</nav>
