@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between">
    <h2 class="text-2xl font-semibold">Find Data Analysts</h2>
    <input type="text" placeholder="Search by skills, location, or name" class="w-80 rounded-md border px-3 py-2" />
    
    <select class="rounded-md border px-3 py-2">
        <option>Sort by</option>
        <option>Rating</option>
        <option>Experience</option>
    </select>
    
    <div class="grow"></div>
    @if(auth()->check() && auth()->user()->role === 'CLIENT')
    <a href="{{ route('client.dashboard') }}" class="rounded-md bg-gray-900 px-4 py-2 text-white">My Dashboard</a>
    @endif
</div>

<div class="mt-6 grid gap-6 md:grid-cols-3">
    @forelse ($services as $service)
    <div class="rounded-xl border bg-white p-5 h-full flex flex-col">
        <div class="flex items-start justify-between">
            <div>
                <div class="font-semibold">{{ $service->title }}</div>
                <div class="text-sm text-gray-600">{{ $service->category ?? 'Uncategorized' }}</div>
                <div class="text-sm text-gray-500">
                    by {{ optional($service->analystProfile)->full_name ?? 'Unknown Analyst' }}
                </div>
            </div>
            <span class="rounded-full bg-gray-100 px-2 py-1 text-xs">
                {{ optional($service->analystProfile)->status ?? 'available' }}
            </span>
        </div>
        <div class="mt-3 text-sm text-gray-600">{{ \Illuminate\Support\Str::limit($service->description, 140) }}</div>
        <div class="mt-4 flex items-center justify-between">
            <div class="text-sm text-gray-600">⭐ {{ number_format(optional($service->analystProfile)->average_rating ?? 0, 1) }}</div>
            <div class="font-semibold">${{ number_format($service->price_min, 2) }} - ${{ number_format($service->price_max, 2) }}</div>
        </div>
        <div class="mt-auto grid grid-cols-2 gap-2 pt-4">
            <a href="{{ route('analysts.show', $service->analyst_id) }}" class="inline-flex w-full justify-center rounded-md bg-gray-900 px-3 py-2 text-white">Contact</a>
            <a href="{{ route('orders.book', $service->service_id) }}" class="inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-white">Book</a>
        </div>
    </div>
    @empty
    <div class="text-gray-600">No services found.</div>
    @endforelse
</div>
@endsection



