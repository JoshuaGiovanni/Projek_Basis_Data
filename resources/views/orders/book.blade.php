@extends('layouts.app')

@section('content')
<a href="{{ route('analysts.index') }}" class="text-sm text-gray-500">← Back</a>

<div class="mx-auto mt-4 max-w-2xl rounded-xl border bg-white p-6">
    <h2 class="text-xl font-semibold">Book: {{ $service->title }}</h2>
    <div class="mt-2 text-sm text-yellow-700 bg-yellow-50 border border-yellow-200 rounded p-3">
        Reminder: Please contact the analyst first to negotiate price, scope, and conditions before booking.
    </div>
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
            <button type="submit" class="rounded-md bg-green-600 px-4 py-2 text-white">Send Order</button>
        </div>
    </form>
</div>
@endsection





