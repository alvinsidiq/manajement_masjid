<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\UserSetting;
use App\Models\Ruangan;
use App\Models\Kegiatan;
use App\Policies\UserSettingPolicy;
use App\Policies\UserPolicy;
use App\Policies\RuanganPolicy;
use App\Policies\KegiatanPolicy;
use App\Policies\PemesananPolicy;
use App\Models\Pemesanan;
use App\Models\Payment;
use App\Policies\PaymentPolicy;
use App\Models\Notifikasi;
use App\Policies\NotifikasiPolicy;
use App\Models\Informasi;
use App\Policies\InformasiPolicy;
use Illuminate\Support\Facades\Event;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Notifications\Events\NotificationFailed;
use App\Models\Booking;
use App\Policies\BookingPolicy;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Carbon::setLocale(config('app.locale'));

        Gate::define('admin-only', function (User $user) {
            return method_exists($user, 'hasRole') ? $user->hasRole('admin') : false;
        });

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;
            return [
                Limit::perMinute(5)->by($email.$request->ip())
                    ->response(function(){
                        return back()->withErrors(['email' => 'Terlalu banyak percobaan. Coba lagi sebentar.']);
                    }),
            ];
        });

        // Ensure Spatie middleware aliases are registered correctly
        app('router')->aliasMiddleware('role', \Spatie\Permission\Middleware\RoleMiddleware::class);
        app('router')->aliasMiddleware('permission', \Spatie\Permission\Middleware\PermissionMiddleware::class);
        app('router')->aliasMiddleware('role_or_permission', \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class);

        // Register custom middleware aliases
        app('router')->aliasMiddleware('active', \App\Http\Middleware\EnsureUserIsActive::class);

        // Register policy for UserSetting & others
        Gate::policy(UserSetting::class, UserSettingPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Ruangan::class, RuanganPolicy::class);
        Gate::policy(Kegiatan::class, KegiatanPolicy::class);
        Gate::policy(Pemesanan::class, PemesananPolicy::class);
        Gate::policy(Payment::class, PaymentPolicy::class);
        Gate::policy(Notifikasi::class, NotifikasiPolicy::class);
        Gate::policy(Informasi::class, InformasiPolicy::class);

        Event::listen(NotificationSent::class, function ($event) {
            try {
                $logId = property_exists($event->notification, 'logId') ? ($event->notification->logId ?? null) : null;
                if ($logId && ($n = Notifikasi::find($logId))) {
                    $n->update([
                        'terkirim' => true,
                        'waktu_kirim' => now(),
                        'status_pengiriman' => 'sent:'.$event->channel,
                    ]);
                }
            } catch (\Throwable $e) {}
        });

        Event::listen(NotificationFailed::class, function ($event) {
            try {
                $logId = property_exists($event->notification, 'logId') ? ($event->notification->logId ?? null) : null;
                if ($logId && ($n = Notifikasi::find($logId))) {
                    $n->update([
                        'terkirim' => false,
                        'waktu_kirim' => now(),
                        'status_pengiriman' => 'failed:'.$event->channel,
                    ]);
                }
            } catch (\Throwable $e) {}
        });
        Gate::policy(Booking::class, BookingPolicy::class);
    }
}
