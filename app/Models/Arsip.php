<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arsip extends Model
{
    use HasFactory;

    protected $table = 'arsip';
    protected $primaryKey = 'arsip_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'judul','deskripsi','dokumen','uploaded_by',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by', 'user_id');
    }

    public function getRouteKeyName(): string
    {
        return 'arsip_id';
    }
}
