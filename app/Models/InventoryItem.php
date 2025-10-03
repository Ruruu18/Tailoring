<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryItem extends Model
{
    protected $fillable = [
        'name',
        'description',
        'images',
        'primary_image',
        'category',
        'design_type',
        'colors',
        'sizes',
        'material',
        'brand',
        'origin',
        'sku',
        'quantity',
        'min_quantity',
        'min_stock_level',
        'cost_per_unit',
        'unit_price',
        'unit',
        'supplier',
        'featured',
        'sort_order',
        'specifications',
        'care_instructions',
        'is_active',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'cost_per_unit' => 'decimal:2',
        'is_active' => 'boolean',
        'featured' => 'boolean',
        'images' => 'array',
        'colors' => 'array',
        'sizes' => 'array',
    ];

    /**
     * Check if item is low in stock
     */
    public function isLowStock(): bool
    {
        return $this->quantity <= $this->min_quantity;
    }

    /**
     * Get stock status
     */
    public function getStockStatusAttribute(): string
    {
        if ($this->quantity <= 0) {
            return 'out_of_stock';
        } elseif ($this->isLowStock()) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }

    /**
     * Get primary image URL or placeholder
     */
    public function getPrimaryImageUrlAttribute(): string
    {
        // Check primary_image first
        if ($this->primary_image) {
            return asset('storage/' . $this->primary_image);
        }

        // Fall back to first image in images array
        if ($this->images) {
            if (is_array($this->images) && count($this->images) > 0) {
                return asset('storage/' . $this->images[0]);
            }
            if (is_string($this->images) && !empty($this->images)) {
                return asset('storage/' . $this->images);
            }
        }

        return asset('images/placeholder-inventory.jpg');
    }

    /**
     * Get all image URLs
     */
    public function getImageUrlsAttribute(): array
    {
        if (!$this->images) {
            return [$this->primary_image_url];
        }

        if (is_array($this->images)) {
            return array_map(function($image) {
                return asset('storage/' . $image);
            }, array_filter($this->images));
        }

        if (is_string($this->images) && !empty($this->images)) {
            return [asset('storage/' . $this->images)];
        }

        return [$this->primary_image_url];
    }

    /**
     * Get category display name
     */
    public function getCategoryDisplayAttribute(): string
    {
        return match($this->category) {
            'garters' => 'Garters',
            'threads' => 'Threads',
            'fabrics' => 'Fabrics/Tela',
            'fabrics_tela' => 'Fabrics/Tela',
            default => ucfirst($this->category)
        };
    }

    /**
     * Scope for specific categories
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for featured items
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    /**
     * Scope for specific design type
     */
    public function scopeDesignType($query, $type)
    {
        return $query->where(function($q) use ($type) {
            $q->where('design_type', $type)
              ->orWhere('design_type', 'both');
        });
    }

    /**
     * Relationship with design brochures through design_materials
     */
    public function designs(): BelongsToMany
    {
        return $this->belongsToMany(DesignBrochure::class, 'design_materials', 'inventory_id', 'design_brochure_id')
                    ->withPivot(['quantity_needed', 'unit', 'is_required', 'notes'])
                    ->withTimestamps();
    }

    /**
     * Relationship with orders through order_materials
     */
    public function orderMaterials(): HasMany
    {
        return $this->hasMany(OrderMaterial::class, 'inventory_id');
    }

    /**
     * Deduct quantity from inventory
     */
    public function deductQuantity(float $quantity): bool
    {
        if ($this->quantity >= $quantity) {
            $this->quantity -= $quantity;
            return $this->save();
        }
        return false;
    }

    /**
     * Add quantity back to inventory (for cancellations)
     */
    public function addQuantity(float $quantity): bool
    {
        $this->quantity += $quantity;
        return $this->save();
    }

    /**
     * Check if sufficient quantity is available
     */
    public function hasQuantity(float $quantity): bool
    {
        return $this->quantity >= $quantity;
    }
}
