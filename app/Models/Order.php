<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'design_brochure_id',
        'design_type',
        'design_notes',
        'order_number',
        'items',
        'status',
        'total_amount',
        'paid_amount',
        'payment_status',
        'notes',
        'delivery_date',
        'design_images',
    ];

    protected $casts = [
        'items' => 'array',
        'design_images' => 'array',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'delivery_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function designBrochure(): BelongsTo
    {
        return $this->belongsTo(DesignBrochure::class);
    }

    public function materials(): HasMany
    {
        return $this->hasMany(OrderMaterial::class);
    }

    public function deductedMaterials(): HasMany
    {
        return $this->hasMany(OrderMaterial::class)->deducted();
    }

    public function pendingMaterials(): HasMany
    {
        return $this->hasMany(OrderMaterial::class)->pending();
    }

    /**
     * Get total material cost for this order
     */
    public function getTotalMaterialCostAttribute()
    {
        return $this->materials()->sum('total_cost');
    }

    /**
     * Check if order has materials assigned
     */
    public function hasMaterials(): bool
    {
        return $this->materials()->exists();
    }

    /**
     * Check if all materials have been deducted
     */
    public function allMaterialsDeducted(): bool
    {
        return $this->materials()->count() > 0 &&
               $this->materials()->count() === $this->deductedMaterials()->count();
    }

    public function getPendingAmountAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'quoted' => 'orange',
            'confirmed' => 'purple',
            'paid' => 'green',
            'partial_payment' => 'blue',
            'in_progress' => 'blue',
            'ready' => 'green',
            'completed' => 'gray',
            'cancelled' => 'red',
            default => 'gray'
        };
    }

    public function getIsFullyPaidAttribute()
    {
        return $this->paid_amount >= $this->total_amount;
    }

    public function getHasBalanceAttribute()
    {
        return $this->total_amount > $this->paid_amount;
    }
}
