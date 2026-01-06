<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\JenisReferensi;

class Notifikasi extends Model
{
    use HasFactory;
    protected $table = 'notifikasi';
    protected $primaryKey = 'notifikasi_id';
    protected $fillable = [
        'user_id','referensi_id','jenis_referensi','pesan','terkirim','waktu_kirim','status_pengiriman'
    ];

    protected $casts = [
        'terkirim' => 'boolean',
        'waktu_kirim' => 'datetime',
        'jenis_referensi' => JenisReferensi::class,
    ];

    public function user() { return $this->belongsTo(User::class, 'user_id'); }
}
