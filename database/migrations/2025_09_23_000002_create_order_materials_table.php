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
        Schema::create('order_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('inventory_id')->constrained('inventory_items')->onDelete('cascade');
            $table->decimal('quantity_used', 10, 3); // Actual quantity used
            $table->string('unit', 50); // Unit at time of use
            $table->decimal('unit_price_at_time', 10, 2); // Price when material was used
            $table->decimal('total_cost', 10, 2); // quantity_used * unit_price_at_time
            $table->timestamp('deducted_at')->nullable(); // When inventory was actually deducted
            $table->text('notes')->nullable(); // Any special notes about material usage
            $table->timestamps();

            // Index for faster queries
            $table->index(['order_id']);
            $table->index(['inventory_id']);
            $table->index(['deducted_at']);

            // Index for cost analysis
            $table->index(['order_id', 'total_cost']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_materials');
    }
};