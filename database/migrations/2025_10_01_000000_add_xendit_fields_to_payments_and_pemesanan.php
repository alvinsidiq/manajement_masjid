<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payment', function (Blueprint $table) {
            $table->string('xendit_transaction_id')->nullable()->unique()->after('external_ref');
            $table->text('invoice_url')->nullable()->after('xendit_transaction_id');
            $table->string('status_pembayaran')->nullable()->after('invoice_url');
        });

        Schema::table('pemesanan', function (Blueprint $table) {
            $table->string('xendit_transaction_id')->nullable()->unique()->after('booking_id');
            $table->text('invoice_url')->nullable()->after('xendit_transaction_id');
            $table->string('status_pembayaran')->nullable()->after('invoice_url');
        });
    }

    public function down(): void
    {
        Schema::table('payment', function (Blueprint $table) {
            $table->dropUnique('payment_xendit_transaction_id_unique');
            $table->dropColumn(['xendit_transaction_id','invoice_url','status_pembayaran']);
        });

        Schema::table('pemesanan', function (Blueprint $table) {
            $table->dropUnique('pemesanan_xendit_transaction_id_unique');
            $table->dropColumn(['xendit_transaction_id','invoice_url','status_pembayaran']);
        });
    }
};
