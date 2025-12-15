<?php

namespace App\Observers;

use App\Models\Review;
use App\Models\Order;
use Illuminate\Support\Facades\Artisan;

class ReviewObserver
{
    /**
     * Handle the Review "saved" event.
     */
    public function saved(Review $review): void
    {
        // Reviews affect Analyst Performance, which is keyed by Order Date in our current aggregation logic.
        $order = Order::find($review->order_id);
        
        if ($order) {
           Artisan::call('analytics:update', ['date' => $order->order_date]);
        }
    }

    /**
     * Handle the Review "deleted" event.
     */
    public function deleted(Review $review): void
    {
        $order = Order::find($review->order_id);
        
        if ($order) {
           Artisan::call('analytics:update', ['date' => $order->order_date]);
        }
    }
}
