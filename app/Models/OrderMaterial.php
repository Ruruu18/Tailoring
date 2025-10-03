<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderMaterial extends Model
{
    protected $fillable = [
        'order_id',
        'inventory_id',
        'quantity_used',
        'unit',
        'unit_price_at_time',
        'total_cost',
        'deducted_at',
        'notes',
    ];

    protected $casts = [
        'quantity_used' => 'decimal:3',
        'unit_price_at_time' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'deducted_at' => 'datetime',
    ];

    /**
     * Relationship with order
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relationship with inventory item
     */
    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_id');
    }

    /**
     * Check if material has been deducted from inventory
     */
    public function isDeducted(): bool
    {
        return !is_null($this->deducted_at);
    }

    /**
     * Mark material as deducted
     */
    public function markAsDeducted(): bool
    {
        $this->deducted_at = now();
        return $this->save();
    }

    /**
     * Calculate total cost based on quantity and unit price
     */
    public function calculateTotalCost(): void
    {
        $this->total_cost = $this->quantity_used * $this->unit_price_at_time;
    }

    /**
     * Boot method to automatically calculate total cost
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($orderMaterial) {
            if ($orderMaterial->quantity_used && $orderMaterial->unit_price_at_time) {
                $orderMaterial->calculateTotalCost();
            }
        });
    }

    /**
     * Scope for deducted materials
     */
    public function scopeDeducted($query)
    {
        return $query->whereNotNull('deducted_at');
    }

    /**
     * Scope for pending materials (not yet deducted)
     */
    public function scopePending($query)
    {
        return $query->whereNull('deducted_at');
    }
}