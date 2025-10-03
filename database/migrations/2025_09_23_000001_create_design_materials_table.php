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
        Schema::create('design_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('design_brochure_id')->constrained()->onDelete('cascade');
            $table->foreignId('inventory_id')->constrained('inventory_items')->onDelete('cascade');
            $table->decimal('quantity_needed', 10, 3); // Support fractional quantities
            $table->string('unit', 50); // pieces, meters, yards, etc.
            $table->boolean('is_required')->default(true); // Required or optional material
            $table->text('notes')->nullable(); // Special instructions or notes
            $table->timestamps();

            // Prevent duplicate materials for same design
            $table->unique(['design_brochure_id', 'inventory_id'], 'design_material_unique');

            // Index for faster queries
            $table->index(['design_brochure_id', 'is_required']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('design_materials');
    }
};