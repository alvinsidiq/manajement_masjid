<?php

namespace App\Models;

use App\Enums\StatusBooking;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'booking';
    protected $primaryKey = 'booking_id';
    public $incrementing = true; protected $keyType = 'int';

    protected $fillable = [
        'user_id','hari_tanggal','jam','ruangan_id','status','hold_expires_at'
    ];

    protected $casts = [
        'hari_tanggal' => 'datetime',
        'hold_expires_at' => 'datetime',
        'status' => StatusBooking::class,
    ];

    // Relasi
    public function user(){ return $this->belongsTo(User::class, 'user_id'); }
    public function pemesanan(){ return $this->hasOne(Pemesanan::class, 'booking_id'); }
    public function ruangan(){ return $this->belongsTo(Ruangan::class, 'ruangan_id'); }
}