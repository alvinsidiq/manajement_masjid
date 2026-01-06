<?php

namespace App\Models;

use App\Enums\StatusJadwal;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';
    protected $primaryKey = 'jadwal_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'kegiatan_id','ruangan_id','tanggal_mulai','tanggal_selesai','status','catatan'
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'status' => StatusJadwal::class,
    ];

    public function kegiatan() { return $this->belongsTo(Kegiatan::class, 'kegiatan_id'); }
    public function ruangan()  { return $this->belongsTo(Ruangan::class, 'ruangan_id'); }
    public function pemesanans(){ return $this->hasMany(Pemesanan::class, 'jadwal_id'); }

    public function scopeDateRange($q, ?string $from, ?string $to): void
    {
        if ($from) $q->where('tanggal_mulai', '>=', \Carbon\Carbon::parse($from, 'Asia/Jakarta')->startOfDay()->utc());
        if ($to)   $q->where('tanggal_selesai', '<=', \Carbon\Carbon::parse($to, 'Asia/Jakarta')->endOfDay()->utc());
    }

    public function scopeWithJoins($q): void
    { $q->with(['kegiatan','ruangan']); }
}

