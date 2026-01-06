<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Pemesanan;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        $pem = Pemesanan::inRandomOrder()->first() ?? Pemesanan::factory()->create();
        return [
            'pemesanan_id' => $pem->pemesanan_id,
            'gateway' => fake()->randomElement(['manual','midtrans','xendit']),
            'method' => fake()->randomElement(['cash','transfer','qris']),
            'amount' => fake()->numberBetween(50000, 300000),
            'currency' => 'IDR',
            'status' => fake()->randomElement(['pending','paid']),
            'external_ref' => strtoupper(fake()->bothify('INV#######')),
            'xendit_transaction_id' => null,
            'invoice_url' => null,
            'status_pembayaran' => null,
            'snap_url_or_qris' => null,
            'expired_at' => now()->addHours(3),
            'paid_at' => null,
            'payload_raw' => null,
        ];
    }
}
