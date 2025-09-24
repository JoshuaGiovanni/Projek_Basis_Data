@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-semibold">Your Orders</h2>

<div class="mt-4 grid gap-4">
    @forelse ($orders as $order)
    <div class="rounded-lg border bg-white p-4">
        <div class="flex items-center justify-between">
            <div class="font-medium">Order #{{ $order->order_id }}</div>
            <div class="text-sm">Status: {{ $order->status }}</div>
        </div>
        <div class="text-sm text-gray-600">Service ID: {{ $order->service_id }} · Final: ${{ number_format($order->final_amount, 2) }}</div>
        @if ($order->status === 'IN_PROGRESS')
        <a href="{{ route('orders.brief', $order) }}" class="mt-2 inline-flex rounded-md bg-gray-900 px-3 py-2 text-white">Send Order Brief</a>
        @endif
    </div>
    @empty
    <div class="text-sm text-gray-600">No orders yet.</div>
    @endforelse
</div>
@endsection





