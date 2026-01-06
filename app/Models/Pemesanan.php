<?php

namespace App\Models;

use App\Enums\StatusPemesanan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'pemesanan';
    protected $primaryKey = 'pemesanan_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'user_id','ruangan_id','jadwal_id','booking_id','tujuan_pemesanan','status','catatan','alasan_penolakan',
        'dibatalkan_oleh','alasan_pembatalan','cancelled_at',
        'xendit_transaction_id','invoice_url','status_pembayaran'
    ];

    protected $casts = [
        'status' => StatusPemesanan::class,
        'cancelled_at' => 'datetime',
    ];

    public function user()    { return $this->belongsTo(User::class, 'user_id'); }
    public function ruangan() { return $this->belongsTo(Ruangan::class, 'ruangan_id'); }
    public function booking() { return $this->belongsTo(Booking::class, 'booking_id'); }
    public function jadwal()  { return $this->belongsTo('App\\Models\\Jadwal', 'jadwal_id'); }
    public function payment() { return $this->hasOne('App\\Models\\Payment', 'pemesanan_id'); }

    public function getRouteKeyName(): string
    {
        return 'pemesanan_id';
    }

    public function isCancellable(): bool
    {
        return $this->status?->value === 'diterima';
    }

    public function scopeDateRange($q, ?string $from, ?string $to): void
    {
        if ($from) {
            $q->where('created_at', '>=', Carbon::parse($from, 'Asia/Jakarta')->startOfDay()->utc());
        }
        if ($to) {
            $q->where('created_at', '<=', Carbon::parse($to, 'Asia/Jakarta')->endOfDay()->utc());
        }
    }

    public function scopeWithJoins($q): void
    {
        $q->with(['user','ruangan']);
    }
}
