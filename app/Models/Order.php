<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $primaryKey = 'order_id';

    public $timestamps = false;

    protected $fillable = ['service_id', 'client_id', 'order_date', 'due_date', 'final_amount', 'status'];

    public function clientProfile()
    {
        return $this->belongsTo(ClientProfile::class, 'client_id', 'client_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'service_id');
    }

    public function brief()
    {
        return $this->hasOne(OrderBrief::class, 'order_id', 'order_id');
    }
    
    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'order_id');
    }

    public function review()
    {
        return $this->hasOne(Review::class, 'order_id', 'order_id');
    }

    public function deliverable()
    {
        return $this->hasOne(Deliverable::class, 'order_id', 'order_id');
    }

    public function updateAnalystAvailability()
    {
        $service = $this->service;
        if ($service && $service->analystProfile) {
            $service->analystProfile->updateAvailabilityStatus();
        }
    }

    protected static function booted()
    {
        static::updated(function ($order) {
            // Update analyst availability when order status changes
            if ($order->isDirty('status')) {
                $order->updateAnalystAvailability();
            }
        });

        static::created(function ($order) {
            // Update analyst availability when new order is created
            $order->updateAnalystAvailability();
        });
    }
}
