<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Update the status column to include new payment statuses
            $table->string('status')->default('pending')->change();

            // Add index for better performance on status queries
            $table->index('status');

            // Add index for payment queries
            $table->index(['total_amount', 'paid_amount']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['orders_status_index']);
            $table->dropIndex(['orders_total_amount_paid_amount_index']);
        });
    }
};