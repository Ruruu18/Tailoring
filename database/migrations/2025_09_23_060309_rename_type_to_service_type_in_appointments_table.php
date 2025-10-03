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
        Schema::table('appointments', function (Blueprint $table) {
            $table->renameColumn('type', 'service_type');
        });

        // Update the enum values to include 'pickup'
        DB::statement("ALTER TABLE appointments MODIFY service_type ENUM('consultation', 'fitting', 'measurement', 'pickup', 'delivery')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->renameColumn('service_type', 'type');
        });

        // Revert the enum values
        DB::statement("ALTER TABLE appointments MODIFY type ENUM('fitting', 'consultation', 'delivery', 'measurement')");
    }
};
