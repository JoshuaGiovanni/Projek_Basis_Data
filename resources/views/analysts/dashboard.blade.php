@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between">
    <h2 class="text-2xl font-semibold text-white">Analyst Dashboard</h2>
    <div class="flex items-center space-x-4">
        <div class="rounded-lg px-3 py-2 panel">
            <div class="text-sm text-blue-400">Ongoing Orders</div>
            @php $limit = (int) optional($profile)->max_ongoing_orders ?: 5; @endphp
            <div class="text-lg font-semibold text-white">{{ $profile->ongoing_orders_count ?? 0 }}/{{ $limit }}</div>
        </div>
        <div class="rounded-lg px-3 py-2 panel">
            <div class="text-sm text-blue-400">Status</div>
            <div class="text-lg font-semibold text-white">
                {{ ucfirst($profile->status ?? 'available') }}
            </div>
        </div>
    </div>
    </div>

@if(empty($profile->description) || empty($profile->skills))
<div class="mt-4 rounded-md border-l-4 border-yellow-500 bg-yellow-900/20 p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm text-yellow-200">
                Your profile is incomplete. <a href="{{ route('analysts.profile') }}" class="font-medium underline hover:text-yellow-100">Add a description and skills</a> to improve your visibility to clients.
            </p>
        </div>
    </div>
</div>
@endif

@if($profile && $profile->ongoing_orders_count >= max(($limit ?? 5) - 1, 0))
<div class="mt-4 rounded-md panel p-4">
    <div class="flex">
        <div class="{{ $profile->ongoing_orders_count >= ($limit ?? 5) ? 'text-red-400' : 'text-yellow-400' }}">
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-white">
                @if($profile->ongoing_orders_count >= ($limit ?? 5))
                    Maximum Orders Reached
                @else
                    Approaching Order Limit
                @endif
            </h3>
            <div class="mt-2 text-sm muted">
                @if($profile->ongoing_orders_count >= ($limit ?? 5))
                    You have reached the maximum of {{ $limit ?? 5 }} ongoing orders. Your status has been automatically set to unavailable. Complete some orders to accept new ones.
                @else
                    You have {{ $profile->ongoing_orders_count }} ongoing orders. You can accept {{ ($limit ?? 5) - $profile->ongoing_orders_count }} more before reaching the limit.
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<div class="mt-4 grid gap-6 md:grid-cols-3">
    <section class="rounded-xl panel p-4 md:col-span-2">
        <div class="flex items-center justify-between">
            <h3 class="font-semibold text-white">Order Offers</h3>
        </div>
        <div class="mt-3 divide-y">
            @forelse ($offers as $order)
            <div class="py-3">
                <div class="text-sm muted">Service #{{ $order->service_id }} · {{ $order->status }}</div>
                <div class="font-medium text-white">Final amount: Rp {{ number_format($order->final_amount, 0, ',', '.') }}</div>
                @php $brief = \App\Models\OrderBrief::where('order_id', $order->order_id)->first(); @endphp
                @if ($brief)
                <a href="{{ route('analyst.order.brief', $order) }}" class="mt-2 inline-flex rounded-md bg-gray-900 px-3 py-1 text-white">View Order Brief</a>
                @endif
                @if ($order->status === 'PENDING')
                <div class="mt-2 flex gap-2">
                    <form method="POST" action="{{ route('orders.accept', $order) }}">@csrf<button class="rounded-md bg-green-600 px-3 py-1 text-white">Accept</button></form>
                    <form method="POST" action="{{ route('orders.reject', $order) }}">@csrf<button class="rounded-md bg-red-600 px-3 py-1 text-white">Reject</button></form>
                </div>
                @elseif ($order->status === 'IN_PROGRESS')
                <div class="mt-2">
                    @php $deliverable = \App\Models\Deliverable::where('order_id', $order->order_id)->first(); @endphp
                    @if ($deliverable)
                    <div class="text-sm text-green-600">✓ Work submitted on {{ $deliverable->submitted_at ? $deliverable->submitted_at->format('M d, Y') : 'Unknown' }}</div>
                    <a href="{{ route('analyst.order.submit', $order) }}" class="mt-1 inline-flex rounded-md bg-blue-600 px-3 py-1 text-white">Update Submission</a>
                    @else
                    <a href="{{ route('analyst.order.submit', $order) }}" class="inline-flex rounded-md bg-blue-600 px-3 py-1 text-white">Submit Work</a>
                    @endif
                </div>
                @elseif ($order->status === 'SUBMITTED')
                <div class="text-sm text-blue-600">Awaiting client payment</div>
                @elseif ($order->status === 'COMPLETED')
                <div class="text-sm text-green-600">✓ Completed</div>
                @endif
            </div>
            @empty
            <div class="text-sm text-gray-600">No offers yet.</div>
            @endforelse
        </div>
    </section>

    <section class="rounded-xl panel p-4">
        <h3 class="font-semibold text-white">Quick Actions</h3>
        <div class="mt-3 grid gap-2">
            <a href="{{ route('analysts.profile') }}" class="rounded-md bg-white/10 hover:bg-white/20 px-3 py-2 text-white text-center">Edit Profile</a>
            <a href="{{ route('analyst.service.new') }}" class="rounded-md px-3 py-2 text-white text-center" style="background:#001D39">Post a Service</a>
        </div>
        <form class="mt-4 grid gap-2" method="POST" action="{{ route('analysts.profile.limit') }}">
            @csrf
            <label class="text-sm font-medium">Max Ongoing Projects</label>
            <div class="flex items-center gap-2">
                <input name="max_ongoing_orders" type="number" min="1" max="50" class="w-24 rounded-md border px-3 py-2" value="{{ $limit }}" />
                <button type="submit" class="rounded-md bg-blue-600 px-3 py-2 text-white">Update</button>
            </div>
            <div class="text-xs text-gray-500">Controls your capacity and availability.</div>
        </form>
    </section>

    <section class="rounded-xl panel p-4 md:col-span-3">
        <h3 class="font-semibold text-white">Your Services</h3>
        <div class="mt-3 grid gap-4 md:grid-cols-3">
            @forelse ($services as $service)
            <div class="rounded-lg panel p-3">
                <div class="font-medium text-white">{{ $service->title }}</div>
                <div class="text-sm muted">{{ $service->category }}</div>
                <div class="text-sm text-gray-300">Rp {{ number_format($service->price_min, 0, ',', '.') }} - Rp {{ number_format($service->price_max, 0, ',', '.') }}</div>
                <form action="{{ route('analyst.service.destroy', $service->service_id) }}" method="POST" onsubmit="return confirm('Delete this service?');" class="inline-block mt-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm rounded px-2 py-1 bg-red-50 text-red-700 hover:bg-red-100">Delete</button>
                </form>
            </div>
            @empty
            <div class="text-sm text-gray-600">No services yet.</div>
            @endforelse
        </div>
    </section>
</div>
@endsection


