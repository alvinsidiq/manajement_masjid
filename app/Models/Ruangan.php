<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ruangan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ruangan';
    protected $primaryKey = 'ruangan_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama_ruangan','deskripsi','fasilitas','foto','status','harga'
    ];

    protected $casts = [
        'fasilitas' => 'array',
        'harga' => 'decimal:2',
    ];

    public function pemesanans() { return $this->hasMany(Pemesanan::class, 'ruangan_id'); }
    public function jadwals()    { return $this->hasMany(Jadwal::class, 'ruangan_id'); }

    public function getRouteKeyName(): string
    {
        return 'ruangan_id';
    }
}
