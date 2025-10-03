<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function measurements(): HasOne
    {
        return $this->hasOne(Measurement::class)->latest();
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function getActiveOrdersCountAttribute()
    {
        return $this->orders()->whereIn('status', ['pending', 'in_progress', 'ready'])->count();
    }

    public function getPendingPaymentsAttribute()
    {
        return $this->orders()->where('paid_amount', '<', 'total_amount')->sum('total_amount') -
               $this->orders()->where('paid_amount', '<', 'total_amount')->sum('paid_amount');
    }

    public function getCompletedOrdersCountAttribute()
    {
        return $this->orders()->where('status', 'completed')->count();
    }

    public function getNextAppointmentAttribute()
    {
        return $this->appointments()->where('appointment_date', '>', now())
            ->where('status', '!=', 'cancelled')
            ->orderBy('appointment_date')
            ->first();
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is customer
     */
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }
}
