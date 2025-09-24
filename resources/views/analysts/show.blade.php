@extends('layouts.app')

@section('content')
<a href="{{ route('analysts.index') }}" class="text-sm text-gray-500">← Back to list</a>

<div class="mt-4 rounded-xl border bg-white p-6">
    <div class="flex items-start justify-between">
        <div>
            <div class="text-2xl font-semibold">{{ $profile->full_name }}</div>
            <div class="text-sm text-gray-600">{{ $profile->status }}</div>
        </div>
        <span class="rounded-full bg-gray-100 px-2 py-1 text-xs">Rating: {{ number_format($profile->average_rating, 1) }}</span>
    </div>
    <div class="mt-3 text-gray-700">{{ $profile->description }}</div>
</div>

<h3 class="mt-6 text-xl font-semibold">Services</h3>
<div class="mt-2 grid gap-4 md:grid-cols-2">
    @foreach ($services as $service)
    <div class="rounded-lg border bg-white p-4 h-full flex flex-col">
        <div class="font-medium">{{ $service->title }}</div>
        <div class="text-sm text-gray-600">{{ $service->category ?? 'Uncategorized' }}</div>
        <div class="mt-2 text-sm">{{ $service->description }}</div>
        <div class="mt-2 font-semibold">${{ number_format($service->price_min, 2) }} - ${{ number_format($service->price_max, 2) }}</div>
        <form class="mt-auto pt-3" method="POST" action="{{ route('orders.book', $service->service_id) }}">
            @csrf
            <button type="submit" class="rounded-md bg-green-600 px-3 py-2 text-white">Book</button>
        </form>
    </div>
    @endforeach
</div>
@endsection


