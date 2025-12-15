<?php

namespace App\Observers;

use App\Models\Order;
use Illuminate\Support\Facades\Artisan;

class OrderObserver
{
    /**
     * Handle the Order "saved" event.
     * This covers both created and updated events.
     */
    public function saved(Order $order): void
    {
        // Check if the status is one that affects analytics, or if the amount changed
        // We trigger update if status is COMPLETED/SUBMITTED or if it was recently one of those.
        // For simplicity and robustness, we can just trigger it if the order is in a relevant state
        // or effectively, any change to an order *might* impact analytics (e.g. changing date, amount).
        
        // Optimizing: Only trigger if status is finalized or changed to finalized
        if (in_array($order->status, ['COMPLETED', 'SUBMITTED']) || 
            ($order->wasChanged('status') && in_array($order->getOriginal('status'), ['COMPLETED', 'SUBMITTED']))) {
            
            // Trigger analytics update for the date of this order
            // We use 'call' which runs synchronously.
            Artisan::call('analytics:update', ['date' => $order->order_date]);
        }
    }
}
