@extends('layouts.app')

@section('content')
<a href="{{ route('analyst.dashboard') }}" class="text-sm text-gray-500">← Back to dashboard</a>

<div class="mx-auto mt-4 max-w-3xl rounded-xl border bg-white p-6">
    <h2 class="text-xl font-semibold">Order Brief for Order #{{ $order->order_id }}</h2>
    @if ($brief)
    <div class="mt-4">
        <div class="text-sm text-gray-600">Submitted at: {{ $brief->submitted_at }}</div>
        <div class="mt-2 whitespace-pre-line">{{ $brief->project_description }}</div>
        @if ($brief->attachments_url)
        <div class="mt-3">
            {{-- This checks if the URL starts with http:// or https://. If not, it prepends https:// --}}
            <a class="text-blue-600 underline" href="{{ Illuminate\Support\Str::startsWith($brief->attachments_url, ['http://', 'https://']) ? $brief->attachments_url : 'https://' . $brief->attachments_url }}" target="_blank">View Attachments</a>
        </div>
        @endif
    </div>
    @else
    <div class="mt-4 text-sm text-gray-600">No brief submitted.</div>
    @endif
</div>
@endsection


















