<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use App\Models\Order;

class ReviewController extends Controller
{
    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'CLIENT') {
            return response()->json(['message' => 'Only clients can leave reviews.'], 403);
        }

        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,order_id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $order = Order::findOrFail($validatedData['order_id']);

        // Check if the authenticated user is the client for this order
        if ($order->client_id !== $user->user_id) {
            return response()->json(['message' => 'You can only review your own orders.'], 403);
        }
        
        // Check if the order is completed
        if ($order->status !== 'COMPLETED') {
            return response()->json(['message' => 'You can only review completed orders.'], 403);
        }
        
        // Check if a review already exists for this order
        if (Review::where('order_id', $order->order_id)->exists()) {
            return response()->json(['message' => 'A review for this order already exists.'], 409);
        }

        $review = Review::create([
            'order_id' => $order->order_id,
            'reviewer_id' => $user->user_id,
            'analyst_id' => $order->service->analyst_id,
            'rating' => $validatedData['rating'],
            'comment' => $validatedData['comment'],
        ]);

        return response()->json($review, 201);
    }
}
