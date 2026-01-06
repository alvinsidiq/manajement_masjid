<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $timestamps = false; // hanya created_at
    protected $table = 'audit_logs';
    protected $fillable = [ 'user_id','action','reference_type','reference_id','meta','created_at' ];
    protected $casts = [ 'meta' => 'array', 'created_at' => 'datetime' ];

    public function user(){ return $this->belongsTo(User::class, 'user_id'); }
}