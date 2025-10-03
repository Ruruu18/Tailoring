<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DesignBrochure extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category',
        'tags',
        'style_type',
        'gender',
        'fabric_suggestions',
        'images',
        'price',
        'is_active',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'images' => 'array',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function materials(): BelongsToMany
    {
        return $this->belongsToMany(InventoryItem::class, 'design_materials', 'design_brochure_id', 'inventory_id')
                    ->withPivot(['quantity_needed', 'unit', 'is_required', 'notes'])
                    ->withTimestamps();
    }

    public function requiredMaterials(): BelongsToMany
    {
        return $this->materials()->wherePivot('is_required', true);
    }

    public function optionalMaterials(): BelongsToMany
    {
        return $this->materials()->wherePivot('is_required', false);
    }

    public function getTagsArrayAttribute()
    {
        return $this->tags ? explode(',', $this->tags) : [];
    }

    public function getFeaturedImageUrlAttribute()
    {
        if (!empty($this->images) && is_array($this->images)) {
            return asset('storage/' . $this->images[0]);
        }

        return asset('images/placeholder-design.svg');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByGender($query, $gender)
    {
        return $query->where('gender', $gender);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }
}
