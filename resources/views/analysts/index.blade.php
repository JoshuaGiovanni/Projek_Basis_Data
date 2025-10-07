@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between">
    <h2 class="text-2xl font-semibold text-white">Find Data Analysts</h2>
    <form method="GET" action="{{ route('analysts.index') }}" class="flex items-center gap-3 ml-6">
        <input name="q" value="{{ $query ?? request('q') }}" type="text" placeholder="Search by skills, location, or name" class="w-80 rounded-md border px-3 py-2" />
        <select name="sort" class="rounded-md border px-3 py-2">
            <option value="newest" {{ ($sort ?? request('sort')) === 'newest' ? 'selected' : '' }}>Newest</option>
            <option value="rating_desc" {{ ($sort ?? request('sort')) === 'rating_desc' ? 'selected' : '' }}>Rating: High → Low</option>
            <option value="rating_asc" {{ ($sort ?? request('sort')) === 'rating_asc' ? 'selected' : '' }}>Rating: Low → High</option>
            <option value="price_low" {{ ($sort ?? request('sort')) === 'price_low' ? 'selected' : '' }}>Price: Low → High</option>
            <option value="price_high" {{ ($sort ?? request('sort')) === 'price_high' ? 'selected' : '' }}>Price: High → Low</option>
        </select>
        <button class="rounded-md bg-white/10 px-4 py-2 text-white hover:bg-white/20">Apply</button>
    </form>
    <div class="grow"></div>
    @if(auth()->check() && auth()->user()->role === 'CLIENT')
    <a href="{{ route('client.dashboard') }}" class="rounded-md bg-white/10 px-4 py-2 text-white hover:bg-white/20">My Dashboard</a>
    @endif
</div>

<div class="mt-6 grid gap-6 md:grid-cols-3">
    @forelse ($services as $service)
        {{-- The h-full and flex flex-col are correct and essential --}}
        <div class="rounded-xl glass-container p-5 h-full flex flex-col">
            
            {{-- TOP CONTENT (Header and Description) --}}
            <div>
                <div class="flex items-start justify-between">
                    <div>
                        <div class="font-bold text-white">{{ $service->title }}</div>
                        <div class="text-sm text-gray-700">{{ $service->category ?? 'Uncategorized' }}</div>
                        <div class="text-sm text-gray-600">
                            by {{ optional($service->analystProfile)->full_name ?? 'Unknown Analyst' }}
                        </div>
                    </div>
                    <div class="flex flex-col items-end space-y-1">
                        <span class="rounded-full px-2 py-1 text-xs {{ optional($service->analystProfile)->status === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ optional($service->analystProfile)->status ?? 'available' }}
                        </span>
                        <div class="text-xs text-gray-500">
                            {{ optional($service->analystProfile)->ongoing_orders_count ?? 0 }}/{{ optional($service->analystProfile)->max_ongoing_orders ?? 5 }} orders
                        </div>
                    </div>
                </div>
                <div class="mt-3 text-sm text-gray-800">{{ \Illuminate\Support\Str::limit($service->description, 140) }}</div>
            </div>

            {{-- FOOTER WRAPPER --}}
            {{-- This new div will group all footer content and push it to the bottom --}}
            <div class="mt-auto pt-4">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">⭐ {{ number_format(optional($service->analystProfile)->average_rating ?? 0, 1) }}</div>
                    <div class="font-bold text-white">Rp {{ number_format($service->price_min, 0, ',', '.') }} - Rp {{ number_format($service->price_max, 0, ',', '.') }}</div>
                </div>

                {{-- The mt-auto was removed from here and moved to the parent div above --}}
                <div class="mt-4 grid grid-cols-2 gap-2">
                    @php
                        $analyst = $service->analystProfile;
                        $limit = (int) optional($analyst)->max_ongoing_orders ?: 5;
                        $isAvailable = $analyst && $analyst->status === 'available' && $analyst->ongoing_orders_count < $limit;
                    @endphp
                    
                    <a href="{{ route('analysts.show', $service->analyst_id) }}" class="inline-flex w-full justify-center rounded-md bg-black px-3 py-2 text-white hover:bg-black/90">Contact</a>
                    
                    @if($isAvailable)
                        <a href="{{ route('orders.book', $service->service_id) }}" class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-white-900 hover:bg-blue-700" >Book</a>
                    @else
                        <button disabled class="inline-flex w-full justify-center rounded-md bg-gray-400 px-3 py-2 text-white cursor-not-allowed">
                            @if($analyst && $analyst->ongoing_orders_count >= 5)
                                At Capacity
                            @else
                                Unavailable
                            @endif
                        </button>
                    @endif
                </div>
            </div>
            
        </div>
    @empty
    <div class="text-gray-600">No services found.</div>
    @endforelse
</div>
    
@endsection



