<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'is_read',
        'sent_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'sent_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeColorAttribute()
    {
        return match ($this->type) {
            'sms' => 'blue',
            'system' => 'gray',
            'order_update' => 'green',
            'payment_update' => 'blue',
            'payment_reminder' => 'yellow',
            'appointment' => 'purple',
            default => 'gray'
        };
    }
}
