<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Service;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'CLIENT') {
            $orders = Order::where('client_id', $user->user_id)->with('service.analyst')->get();
        } elseif ($user->role === 'ANALYST') {
            // Get orders for services offered by the analyst
            $analystServiceIds = Service::where('analyst_id', $user->user_id)->pluck('service_id');
            $orders = Order::whereIn('service_id', $analystServiceIds)->with('client')->get();
        } else {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($orders);
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'CLIENT') {
            return response()->json(['message' => 'Only clients can create orders.'], 403);
        }

        $validatedData = $request->validate([
            'service_id' => 'required|exists:services,service_id',
            'due_date' => 'required|date',
            // Add other order-related fields here, like from the order_briefs table
        ]);
        
        $service = Service::findOrFail($validatedData['service_id']);

        $order = Order::create([
            'client_id' => $user->user_id,
            'service_id' => $validatedData['service_id'],
            'order_date' => now(),
            'due_date' => $validatedData['due_date'],
            'final_amount' => $service->price, // Or calculate based on other factors
            'status' => 'PENDING',
        ]);

        return response()->json($order, 201);
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $user = Auth::user();
        $isClient = ($user->role === 'CLIENT' && $order->client_id === $user->user_id);
        
        $service = $order->service;
        $isAnalyst = ($user->role === 'ANALYST' && $service->analyst_id === $user->user_id);

        if (!$isClient && !$isAnalyst) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        return response()->json($order->load('service', 'client', 'brief', 'payment', 'review'));
    }
}
