<?php

namespace App\Models;

use App\Enums\{PaymentGateway, PaymentStatus};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payment';
    protected $primaryKey = 'payment_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'pemesanan_id','gateway','method','amount','currency','status',
        'external_ref','xendit_transaction_id','invoice_url','status_pembayaran',
        'snap_url_or_qris','expired_at','paid_at','payload_raw'
    ];

    protected $casts = [
        'gateway' => PaymentGateway::class,
        'status' => PaymentStatus::class,
        'expired_at' => 'datetime',
        'paid_at' => 'datetime',
        'payload_raw' => 'array',
    ];

    public function pemesanan(){ return $this->belongsTo(Pemesanan::class, 'pemesanan_id'); }
}
