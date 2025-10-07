@extends('layouts.app')

@section('content')
<a href="{{ route('analysts.index') }}" class="text-sm text-gray-500">← Back</a>

<div class="mx-auto mt-4 max-w-2xl rounded-xl border bg-white p-6">
    <h2 class="text-xl font-semibold">Book: {{ $service->title }}</h2>
    
    @php
        $analyst = $service->analystProfile;
        $limit = (int) optional($analyst)->max_ongoing_orders ?: 5;
        $isAvailable = $analyst && $analyst->status === 'available' && $analyst->ongoing_orders_count < $limit;
    @endphp
    
    <div class="mt-2 flex items-center justify-between rounded-lg border p-3 {{ $isAvailable ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
        <div>
            <div class="text-sm font-medium {{ $isAvailable ? 'text-green-800' : 'text-red-800' }}">
                Analyst: {{ $analyst->full_name ?? 'Unknown' }}
            </div>
            <div class="text-sm {{ $isAvailable ? 'text-green-600' : 'text-red-600' }}">
                Status: {{ ucfirst($analyst->status ?? 'unknown') }} • {{ $analyst->ongoing_orders_count ?? 0 }}/{{ $limit }} ongoing orders
            </div>
        </div>
        <span class="rounded-full px-2 py-1 text-xs {{ $isAvailable ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
            {{ $isAvailable ? 'Available' : 'Unavailable' }}
        </span>
    </div>
    
    @if(!$isAvailable)
    <div class="mt-2 text-sm text-red-700 bg-red-50 border border-red-200 rounded p-3">
        <strong>This analyst is currently unavailable.</strong> 
        @if($analyst && $analyst->ongoing_orders_count >= $limit)
            They have reached their maximum capacity of {{ $limit }} ongoing orders.
        @endif
        Please choose another analyst or try again later.
    </div>
    @else
    <div class="mt-2 text-sm text-yellow-700 bg-yellow-50 border border-yellow-200 rounded p-3">
        Reminder: Please contact the analyst first to negotiate price, scope, and conditions before booking.
    </div>
    @endif
    @if($isAvailable)
    <form class="mt-4 grid gap-4" method="POST" action="{{ route('orders.book.submit', $service->service_id) }}">
        @csrf
        <div>
            <label class="block text-sm font-medium">Final Amount (you decide)</label>
            <input name="final_amount" type="number" step="0.01" class="mt-1 w-full rounded-md border px-3 py-2" placeholder="{{ number_format($service->price_min, 2) }}" required />
        </div>
        <div>
            <label class="block text-sm font-medium">Due Date (optional)</label>
            <input name="due_date" type="date" class="mt-1 w-full rounded-md border px-3 py-2" />
        </div>
        <div class="flex justify-end">
            <button type="submit" class="rounded-md bg-green-600 px-4 py-2 text-white hover:bg-green-700">Send Order</button>
        </div>
    </form>
    @else
    <div class="mt-4 text-center">
        <a href="{{ route('analysts.index') }}" class="rounded-md bg-gray-600 px-4 py-2 text-white hover:bg-gray-700">
            Browse Other Analysts
        </a>
    </div>
    @endif
</div>
@endsection









