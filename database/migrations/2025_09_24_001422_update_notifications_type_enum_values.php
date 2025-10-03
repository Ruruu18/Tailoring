<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the enum type to include new notification types
        DB::statement("ALTER TABLE notifications MODIFY COLUMN type ENUM('sms', 'system', 'order_update', 'payment_update', 'payment_reminder', 'appointment') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE notifications MODIFY COLUMN type ENUM('sms', 'system', 'order_update', 'payment_reminder') NOT NULL");
    }
};
