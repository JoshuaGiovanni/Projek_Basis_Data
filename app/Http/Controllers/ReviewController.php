<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Order $order)
    {
        $user = Auth::user();

        // Security Validation
        if (!$user || $user->role !== 'CLIENT' || $order->client_id !== $user->user_id) {
            abort(403);
        }

        if ($order->status !== 'COMPLETED') {
            return back()->with('error', 'You can only rate completed orders.');
        }

        // Check if review already exists
        if (Review::where('order_id', $order->order_id)->exists()) {
            return back()->with('error', 'You have already rated this order.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Create Review
        // Note: Analyst ID is derived from the Service linked to the Order
        $analystId = $order->service->analyst_id;

        Review::create([
            'order_id' => $order->order_id,
            'reviewer_id' => $user->user_id, // maps to client_id in schema
            'analyst_id' => $analystId,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'created_at' => now(),
        ]);

        // ReviewObserver will automatically trigger analytics update

        return back()->with('status', 'Thank you for your feedback!');
    }
}
