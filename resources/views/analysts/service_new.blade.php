@extends('layouts.app')

@section('content')
<a href="{{ route('analyst.dashboard') }}" class="text-sm text-gray-500">← Back to dashboard</a>

<div class="mx-auto mt-4 max-w-2xl rounded-xl border bg-white p-6">
    <h2 class="text-xl font-semibold">Post a New Service</h2>
    <form class="mt-4 grid gap-4" method="POST" action="{{ route('analyst.service.store') }}">
        @csrf
        <div>
            <label class="block text-sm font-medium">Title</label>
            <input name="title" class="mt-1 w-full rounded-md border px-3 py-2" required />
        </div>
        <div>
            <label class="block text-sm font-medium">Category</label>
            <input name="category" class="mt-1 w-full rounded-md border px-3 py-2" />
        </div>
        <div>
            <label class="block text-sm font-medium">Description</label>
            <textarea name="description" rows="4" class="mt-1 w-full rounded-md border px-3 py-2"></textarea>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium">Price Min</label>
                <input name="price_min" type="number" step="0.01" class="mt-1 w-full rounded-md border px-3 py-2" required />
            </div>
            <div>
                <label class="block text-sm font-medium">Price Max</label>
                <input name="price_max" type="number" step="0.01" class="mt-1 w-full rounded-md border px-3 py-2" required />
            </div>
        </div>
        <div class="flex justify-end">
            <button type="submit" class="rounded-md bg-green-600 px-4 py-2 text-white">Publish</button>
        </div>
    </form>
    </div>
@endsection





