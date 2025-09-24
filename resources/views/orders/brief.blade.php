@extends('layouts.app')

@section('content')
<a href="{{ route('client.dashboard') }}" class="text-sm text-gray-500">← Back to dashboard</a>

<div class="mx-auto mt-4 max-w-2xl rounded-xl border bg-white p-6">
    <h2 class="text-xl font-semibold">Order Brief for Order #{{ $order->order_id }}</h2>
    <form class="mt-4 grid gap-4" method="POST" action="{{ route('orders.brief.submit', $order) }}">
        @csrf
        <div>
            <label class="block text-sm font-medium">Project Description</label>
            <textarea name="project_description" rows="5" class="mt-1 w-full rounded-md border px-3 py-2">{{ old('project_description', optional($brief)->project_description) }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium">Attachments URL (e.g., Google Drive link)</label>
            <input name="attachments_url" class="mt-1 w-full rounded-md border px-3 py-2" value="{{ old('attachments_url', optional($brief)->attachments_url) }}" />
        </div>
        <div class="flex justify-end">
            <button type="submit" class="rounded-md bg-gray-900 px-4 py-2 text-white">Save Brief</button>
        </div>
    </form>
    </div>
@endsection


