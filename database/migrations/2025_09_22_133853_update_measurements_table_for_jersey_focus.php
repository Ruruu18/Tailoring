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
        Schema::table('measurements', function (Blueprint $table) {
            // Add short_waist column and copy data from trouser_waist if it exists
            $table->decimal('short_waist', 5, 2)->nullable()->after('shirt_length');

            // Remove trouser_length column
            if (Schema::hasColumn('measurements', 'trouser_length')) {
                $table->dropColumn('trouser_length');
            }
        });

        // Copy data from trouser_waist to short_waist if trouser_waist column exists
        if (Schema::hasColumn('measurements', 'trouser_waist')) {
            DB::statement('UPDATE measurements SET short_waist = trouser_waist WHERE trouser_waist IS NOT NULL');

            // Then drop the trouser_waist column
            Schema::table('measurements', function (Blueprint $table) {
                $table->dropColumn('trouser_waist');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('measurements', function (Blueprint $table) {
            // Re-add trouser_waist and trouser_length columns
            $table->decimal('trouser_waist', 5, 2)->nullable()->after('shirt_length');
            $table->decimal('trouser_length', 5, 2)->nullable()->after('trouser_waist');

            // Remove short_waist column
            if (Schema::hasColumn('measurements', 'short_waist')) {
                // Copy data back from short_waist to trouser_waist
                DB::statement('UPDATE measurements SET trouser_waist = short_waist WHERE short_waist IS NOT NULL');
                $table->dropColumn('short_waist');
            }
        });
    }
};
