<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanPendaftaran extends Model
{
    use HasFactory;

    protected $table = 'kegiatan_pendaftaran';
    protected $primaryKey = 'pendaftaran_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'kegiatan_id','user_id','nama','email','no_telephone','catatan',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
