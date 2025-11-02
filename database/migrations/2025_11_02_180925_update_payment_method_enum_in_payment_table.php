<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE payment
            MODIFY COLUMN payment_method
            ENUM('credit_card', 'manual', 'bank_transfer', 'midtrans')
            DEFAULT 'manual'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE payment
            MODIFY COLUMN payment_method
            ENUM('credit_card', 'manual', 'bank_transfer')
            DEFAULT 'manual'
        ");
    }
};
