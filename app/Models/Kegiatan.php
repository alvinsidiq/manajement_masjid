<?php

namespace App\Models;

use App\Enums\JenisKegiatan;
use App\Enums\StatusKegiatan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Kegiatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kegiatan';
    protected $primaryKey = 'kegiatan_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama_kegiatan','deskripsi','jenis_kegiatan','approval_status','penanggung_jawab','no_telephone','foto','dokumen',
        'is_archived','archived_at','archived_by','archive_reason',
    ];

    protected $casts = [
        'jenis_kegiatan' => JenisKegiatan::class,
        'approval_status' => StatusKegiatan::class,
        'is_archived' => 'boolean',
        'archived_at' => 'datetime',
    ];

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'kegiatan_id');
    }

    public function pendaftarans()
    {
        return $this->hasMany(KegiatanPendaftaran::class, 'kegiatan_id');
    }

    public function getRouteKeyName(): string
    {
        return 'kegiatan_id';
    }

    public function archivedByUser() { return $this->belongsTo(User::class, 'archived_by'); }

    public function scopeArchived($q) { $q->where('is_archived', true); }
    public function scopeActive($q) { $q->where('is_archived', false); }
}
