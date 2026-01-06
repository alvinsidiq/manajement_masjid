<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicSite\InfoController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Bendahara\BendaharaDashboardController;
use App\Http\Controllers\Takmir\TakmirDashboardController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\Payment\XenditController;

Route::get('/', [\App\Http\Controllers\Frontend\HomeController::class, 'index'])->name('home');
Route::prefix('informasi')->as('public.informasi.')->group(function(){
    Route::get('/', [\App\Http\Controllers\Frontend\InformasiController::class, 'index'])->name('index');
    Route::get('{slug}', [\App\Http\Controllers\Frontend\InformasiController::class, 'show'])->name('show');
});
Route::get('jadwal', [\App\Http\Controllers\Frontend\JadwalController::class, 'index'])->name('public.jadwal.index');

// Fallback dashboard target after verification/login
Route::get('/dashboard', function () {
    $u = auth()->user();
    if (!$u) return redirect()->route('home');
    if ($u->hasAnyRole(['admin','bendahara','takmir'])) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('user.dashboard');
})->middleware(['auth','verified','active'])->name('dashboard');

require __DIR__.'/auth.php';

Route::middleware(['auth','verified','active','role:admin'])
    ->prefix('admin')->as('admin.')
    ->group(function(){
        Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        Route::resource('ruangan', \App\Http\Controllers\Admin\RuanganController::class);
        Route::resource('kegiatan', \App\Http\Controllers\Admin\KegiatanController::class);
        Route::post('kegiatan/{kegiatan}/archive', [\App\Http\Controllers\Admin\KegiatanController::class,'archive'])
            ->middleware('throttle:6,1')->name('kegiatan.archive');
        Route::resource('kegiatan-arsip', \App\Http\Controllers\Admin\ArsipController::class)
            ->parameters(['kegiatan-arsip' => 'arsip'])
            ->names('arsip');
        Route::post('kegiatan/{kegiatan}/unarchive', [\App\Http\Controllers\Admin\ArsipKegiatanController::class,'unarchive'])
            ->middleware('throttle:6,1')->name('kegiatan.unarchive');
        Route::resource('konfirmasi-booking', \App\Http\Controllers\Admin\PemesananController::class)
            ->parameters(['konfirmasi-booking' => 'pemesanan'])
            ->names('pemesanan');
        Route::resource('jadwal', \App\Http\Controllers\Admin\JadwalController::class);
        Route::post('konfirmasi-booking/{pemesanan}/approve', [\App\Http\Controllers\Admin\PemesananController::class, 'approve'])
            ->middleware('throttle:6,1')->name('pemesanan.approve');
        Route::post('konfirmasi-booking/{pemesanan}/reject', [\App\Http\Controllers\Admin\PemesananController::class, 'reject'])
            ->middleware('throttle:6,1')->name('pemesanan.reject');
        Route::post('konfirmasi-booking/{pemesanan}/cancel', [\App\Http\Controllers\Admin\PemesananController::class, 'cancel'])
            ->middleware('throttle:6,1')->name('pemesanan.cancel');
        Route::post('konfirmasi-booking/{pemesanan}/complete', [\App\Http\Controllers\Admin\PemesananController::class, 'complete'])
            ->middleware('throttle:6,1')->name('pemesanan.complete');
        Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class,'index'])->name('reports.index');
    });

Route::middleware(['auth','verified','active','role:admin|takmir'])
    ->prefix('admin')->as('admin.')
    ->group(function(){
        Route::resource('booking', \App\Http\Controllers\Admin\BookingController::class);
    });

Route::middleware(['auth','verified','active','role:admin|takmir|bendahara'])
    ->prefix('admin')->as('admin.')
    ->group(function(){
        Route::resource('notifikasi', \App\Http\Controllers\Admin\NotifikasiController::class);
        Route::post('notifikasi/{notifikasi}/resend', [\App\Http\Controllers\Admin\NotifikasiController::class,'resend'])->middleware('role:admin|takmir')->name('notifikasi.resend');
    });

Route::middleware(['auth','verified','active','role:bendahara'])
    ->prefix('bendahara')->as('bendahara.')
    ->group(function(){
        Route::get('/dashboard', BendaharaDashboardController::class)->name('dashboard');
        Route::resource('payment', \App\Http\Controllers\Bendahara\PaymentController::class);
        Route::post('payment/{payment}/mark-paid', [\App\Http\Controllers\Bendahara\PaymentController::class,'markPaid'])->name('payment.markPaid');
        // Laporan transaksi bendahara
        Route::get('laporan-transaksi', [\App\Http\Controllers\Bendahara\LaporanController::class,'index'])->name('laporan.index');
        // Lihat booking
        Route::get('booking', [\App\Http\Controllers\Bendahara\BookingController::class,'index'])->name('booking.index');
        Route::get('booking/{booking}', [\App\Http\Controllers\Bendahara\BookingController::class,'show'])->name('booking.show');
    });

Route::middleware(['auth','verified','active','role:takmir|admin|bendahara'])
    ->prefix('takmir')->as('takmir.')
    ->group(function(){
        Route::get('/dashboard', TakmirDashboardController::class)->name('dashboard');
        // Verifikasi Jadwal
        Route::get('verifikasi-jadwal', [\App\Http\Controllers\Takmir\VerifikasiJadwalController::class,'index'])->name('verifikasi-jadwal.index');
        Route::get('verifikasi-jadwal/{pemesanan}', [\App\Http\Controllers\Takmir\VerifikasiJadwalController::class,'show'])->name('verifikasi-jadwal.show');
        Route::get('verifikasi-jadwal/{pemesanan}/approve', [\App\Http\Controllers\Takmir\VerifikasiJadwalController::class,'approveConfirm'])->name('verifikasi-jadwal.approve.confirm');
        Route::post('verifikasi-jadwal/{pemesanan}/approve', [\App\Http\Controllers\Admin\PemesananController::class,'approve'])->name('verifikasi-jadwal.approve');
        Route::post('verifikasi-jadwal/{pemesanan}/reject', [\App\Http\Controllers\Admin\PemesananController::class,'reject'])->name('verifikasi-jadwal.reject');

        // Verifikasi Booking (khusus pemesanan yang berasal dari booking)
        Route::get('verifikasi-booking', [\App\Http\Controllers\Takmir\VerifikasiBookingController::class,'index'])->name('verifikasi-booking.index');
        Route::get('verifikasi-booking/{pemesanan}', [\App\Http\Controllers\Takmir\VerifikasiBookingController::class,'show'])->name('verifikasi-booking.show');
        Route::get('verifikasi-booking/{pemesanan}/approve', [\App\Http\Controllers\Takmir\VerifikasiBookingController::class,'approveConfirm'])->name('verifikasi-booking.approve.confirm');
        Route::post('verifikasi-booking/{pemesanan}/approve', [\App\Http\Controllers\Admin\PemesananController::class,'approve'])->name('verifikasi-booking.approve');
        Route::post('verifikasi-booking/{pemesanan}/reject', [\App\Http\Controllers\Admin\PemesananController::class,'reject'])->name('verifikasi-booking.reject');
    });

Route::middleware(['auth','verified','active','role:user|admin|bendahara|takmir'])
    ->prefix('user')->as('user.')
    ->group(function(){
        Route::get('/dashboard', UserDashboardController::class)->name('dashboard');
        Route::resource('settings', \App\Http\Controllers\User\UserSettingController::class)
            ->only(['index','edit','update','destroy','show']);
        Route::resource('ruangan', \App\Http\Controllers\User\BookingController::class)
            ->parameters(['ruangan' => 'booking'])
            ->names('booking');
        Route::resource('pemesanan', \App\Http\Controllers\User\PemesananController::class);
        Route::post('pemesanan/{pemesanan}/pay', [\App\Http\Controllers\User\PemesananController::class,'pay'])->name('pemesanan.pay');

        // Booking cancel (path baru 'ruangan')
        Route::get('ruangan/{booking}/cancel', [\App\Http\Controllers\User\CancelController::class,'bookingConfirm'])->name('booking.cancel.confirm');
        Route::post('ruangan/{booking}/cancel', [\App\Http\Controllers\User\CancelController::class,'bookingProcess'])->name('booking.cancel');

        // Pemesanan cancel
        Route::get('pemesanan/{pemesanan}/cancel', [\App\Http\Controllers\User\CancelController::class,'pemesananConfirm'])->name('pemesanan.cancel.confirm');
        Route::post('pemesanan/{pemesanan}/cancel', [\App\Http\Controllers\User\CancelController::class,'pemesananProcess'])->name('pemesanan.cancel');

        Route::resource('status', \App\Http\Controllers\User\StatusController::class)->only(['index','show']);

        Route::get('profile', [\App\Http\Controllers\User\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [\App\Http\Controllers\User\ProfileController::class, 'update'])->name('profile.update');
        Route::put('profile/password', [\App\Http\Controllers\User\ProfileController::class, 'updatePassword'])->name('profile.password.update');
    });

// Xendit payment flow
Route::middleware(['auth','verified','active'])->group(function(){
    Route::get('/pay/xendit/{externalRef}', [XenditController::class, 'pay'])->name('payment.xendit.pay');
});
Route::get('/payment/success/{externalRef}', [XenditController::class, 'success'])->name('payment.success');
Route::get('/payment/failed/{externalRef}', [XenditController::class, 'failed'])->name('payment.failed');

// Webhook (CSRF disabled)
Route::post('/webhook/xendit', [XenditController::class, 'webhook'])
    ->name('payment.xendit.webhook')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// Callback simulasi (publik)
Route::post('callback/{gateway}/{externalRef}', [\App\Http\Controllers\Bendahara\PaymentController::class,'callback'])->name('callback.payment');

// Publik: Ruangan
Route::prefix('ruangan')->as('public.ruangan.')->group(function(){
    Route::get('/', [\App\Http\Controllers\Frontend\RuanganController::class,'index'])->name('index');
    Route::get('{ruangan}', [\App\Http\Controllers\Frontend\RuanganController::class,'show'])->name('show');
});

// Publik: Kegiatan
Route::prefix('kegiatan')->as('public.kegiatan.')->group(function(){
    Route::get('/', [\App\Http\Controllers\Frontend\KegiatanController::class,'index'])->name('index');
    Route::get('{kegiatan}', [\App\Http\Controllers\Frontend\KegiatanController::class,'show'])->name('show');
    Route::post('{kegiatan}/daftar', [\App\Http\Controllers\Frontend\KegiatanController::class,'daftar'])->name('daftar');
});
