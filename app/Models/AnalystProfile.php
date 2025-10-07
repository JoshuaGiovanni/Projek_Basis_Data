<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalystProfile extends Model
{
    use HasFactory;

    protected $table = 'analyst_profile';

    protected $primaryKey = 'analyst_id';
    
    public $incrementing = true; // This one auto-increments

    protected $fillable = [
        'user_id',
        'full_name',
        'years_of_experience',
        'description',
        'status',
        'max_ongoing_orders',
        'skills',
        'average_rating', 
    ];

    protected $casts = [
        'skills' => 'array',
        'average_rating' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'analyst_id', 'analyst_id');
    }

    public function ongoingOrders()
    {
        return $this->hasManyThrough(
            Order::class,
            Service::class,
            'analyst_id', // Foreign key on services table
            'service_id', // Foreign key on orders table
            'analyst_id', // Local key on analyst_profiles table
            'service_id'  // Local key on services table
        )->where('status', 'IN_PROGRESS');
    }

    public function getOngoingOrdersCountAttribute()
    {
        return $this->ongoingOrders()->count();
    }

    public function getPhoneAttribute()
    {
    // Use loaded relation if available to avoid extra query
    if ($this->relationLoaded('user')) {
        return $this->user->phone ?? null;
        }

    // otherwise fetch phone via relation (single value)
    return $this->user()->value('phone');
    }
    
    public function updateAvailabilityStatus()
    {
        $ongoingCount = $this->ongoing_orders_count;
        $limit = (int)($this->max_ongoing_orders ?? 5);

        if ($ongoingCount >= $limit) {
            $this->status = 'unavailable';
        } elseif ($ongoingCount < $limit && $this->status === 'unavailable') {
            // Only auto-change to available if they were auto-set to unavailable
            // Don't override manual unavailable status
            $this->status = 'available';
        }
        
        $this->save();
        return $this;
    }
}
