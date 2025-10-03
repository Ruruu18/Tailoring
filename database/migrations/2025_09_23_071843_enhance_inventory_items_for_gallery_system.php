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
        Schema::table('inventory_items', function (Blueprint $table) {
            // Add image support
            $table->json('images')->nullable()->after('description');
            $table->string('primary_image')->nullable()->after('images');

            // Add design type support
            $table->enum('design_type', ['pre_made', 'custom', 'both'])->default('both')->after('category');

            // Add color and size variations
            $table->json('colors')->nullable()->after('design_type');
            $table->json('sizes')->nullable()->after('colors');

            // Add material details
            $table->string('material')->nullable()->after('sizes');
            $table->string('brand')->nullable()->after('material');
            $table->string('origin')->nullable()->after('brand');

            // Add gallery display options
            $table->boolean('featured')->default(false)->after('origin');
            $table->integer('sort_order')->default(0)->after('featured');

            // Add more detailed description
            $table->text('specifications')->nullable()->after('sort_order');
            $table->text('care_instructions')->nullable()->after('specifications');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->dropColumn([
                'images',
                'primary_image',
                'design_type',
                'colors',
                'sizes',
                'material',
                'brand',
                'origin',
                'featured',
                'sort_order',
                'specifications',
                'care_instructions'
            ]);
        });
    }
};
