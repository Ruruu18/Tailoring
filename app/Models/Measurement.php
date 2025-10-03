<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Measurement extends Model
{
    protected $fillable = [
        'user_id',
        'size',
        'is_custom',
        'chest',
        'waist',
        'hip',
        'shoulder',
        'sleeve_length',
        'shirt_length',
        'short_waist',
        'inseam',
        'notes',
    ];

    protected $casts = [
        'is_custom' => 'boolean',
        'chest' => 'decimal:2',
        'waist' => 'decimal:2',
        'hip' => 'decimal:2',
        'shoulder' => 'decimal:2',
        'sleeve_length' => 'decimal:2',
        'shirt_length' => 'decimal:2',
        'short_waist' => 'decimal:2',
        'inseam' => 'decimal:2',
    ];

    /**
     * Get predefined size measurements
     */
    public static function getPredefinedSizes()
    {
        return [
            'XS' => [
                'chest' => 34.0,
                'waist' => 28.0,
                'hip' => 34.0,
                'shoulder' => 16.5,
                'sleeve_length' => 23.0,
                'shirt_length' => 26.0,
                'short_waist' => 28.0,
                'inseam' => 30.0,
            ],
            'S' => [
                'chest' => 36.0,
                'waist' => 30.0,
                'hip' => 36.0,
                'shoulder' => 17.0,
                'sleeve_length' => 24.0,
                'shirt_length' => 27.0,
                'short_waist' => 30.0,
                'inseam' => 31.0,
            ],
            'M' => [
                'chest' => 40.0,
                'waist' => 34.0,
                'hip' => 40.0,
                'shoulder' => 18.0,
                'sleeve_length' => 25.0,
                'shirt_length' => 28.0,
                'short_waist' => 34.0,
                'inseam' => 32.0,
            ],
            'L' => [
                'chest' => 44.0,
                'waist' => 38.0,
                'hip' => 44.0,
                'shoulder' => 19.0,
                'sleeve_length' => 26.0,
                'shirt_length' => 29.0,
                'short_waist' => 38.0,
                'inseam' => 33.0,
            ],
            'XL' => [
                'chest' => 48.0,
                'waist' => 42.0,
                'hip' => 48.0,
                'shoulder' => 20.0,
                'sleeve_length' => 27.0,
                'shirt_length' => 30.0,
                'short_waist' => 42.0,
                'inseam' => 33.5,
            ],
            '2XL' => [
                'chest' => 52.0,
                'waist' => 46.0,
                'hip' => 52.0,
                'shoulder' => 21.0,
                'sleeve_length' => 28.0,
                'shirt_length' => 31.0,
                'short_waist' => 46.0,
                'inseam' => 34.0,
            ],
            '3XL' => [
                'chest' => 56.0,
                'waist' => 50.0,
                'hip' => 56.0,
                'shoulder' => 22.0,
                'sleeve_length' => 29.0,
                'shirt_length' => 32.0,
                'short_waist' => 50.0,
                'inseam' => 34.5,
            ],
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
