@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-semibold">Analyst Dashboard</h2>

<div class="mt-4 grid gap-6 md:grid-cols-3">
    <section class="rounded-xl border bg-white p-4 md:col-span-2">
        <div class="flex items-center justify-between">
            <h3 class="font-semibold">Order Offers</h3>
        </div>
        <div class="mt-3 divide-y">
            @forelse ($offers as $order)
            <div class="py-3">
                <div class="text-sm text-gray-600">Service #{{ $order->service_id }} · {{ $order->status }}</div>
                <div class="font-medium">Final amount: ${{ number_format($order->final_amount, 2) }}</div>
                @php $brief = \App\Models\OrderBrief::where('order_id', $order->order_id)->first(); @endphp
                @if ($brief)
                <a href="{{ route('analyst.order.brief', $order) }}" class="mt-2 inline-flex rounded-md bg-gray-900 px-3 py-1 text-white">View Order Brief</a>
                @endif
                @if ($order->status === 'PENDING')
                <div class="mt-2 flex gap-2">
                    <form method="POST" action="{{ route('orders.accept', $order) }}">@csrf<button class="rounded-md bg-green-600 px-3 py-1 text-white">Accept</button></form>
                    <form method="POST" action="{{ route('orders.reject', $order) }}">@csrf<button class="rounded-md bg-red-600 px-3 py-1 text-white">Reject</button></form>
                </div>
                @endif
            </div>
            @empty
            <div class="text-sm text-gray-600">No offers yet.</div>
            @endforelse
        </div>
    </section>

    <section class="rounded-xl border bg-white p-4">
        <h3 class="font-semibold">Quick Actions</h3>
        <div class="mt-3 grid gap-2">
            <a href="{{ route('analysts.profile') }}" class="rounded-md bg-gray-900 px-3 py-2 text-white text-center">Edit Profile</a>
            <a href="{{ route('analyst.service.new') }}" class="rounded-md bg-green-600 px-3 py-2 text-white text-center">Post a Service</a>
        </div>
    </section>

    <section class="rounded-xl border bg-white p-4 md:col-span-3">
        <h3 class="font-semibold">Your Services</h3>
        <div class="mt-3 grid gap-4 md:grid-cols-3">
            @forelse ($services as $service)
            <div class="rounded-lg border p-3">
                <div class="font-medium">{{ $service->title }}</div>
                <div class="text-sm text-gray-600">{{ $service->category }}</div>
                <div class="text-sm">${{ number_format($service->price_min, 2) }} - ${{ number_format($service->price_max, 2) }}</div>
            </div>
            @empty
            <div class="text-sm text-gray-600">No services yet.</div>
            @endforelse
        </div>
    </section>
</div>
@endsection


