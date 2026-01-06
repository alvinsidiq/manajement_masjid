<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Informasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'informasi';
    protected $primaryKey = 'informasi_id';
    public $incrementing = true; protected $keyType = 'int';

    protected $fillable = [
        'user_id','judul','slug','ringkasan','isi','foto','is_published','published_at'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function author(){ return $this->belongsTo(User::class, 'user_id'); }

    protected static function booted()
    {
        static::creating(function($m){ if(empty($m->slug)) $m->slug = Str::slug(Str::limit($m->judul, 50, '')); });
    }
}

