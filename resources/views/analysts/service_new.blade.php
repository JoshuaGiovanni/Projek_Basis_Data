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
            <select name="category" class="mt-1 w-full rounded-md border px-3 py-2">
        <option value="">-- Select Category --</option>
        <option value="BI & Visualization" {{ old('category') === 'BI & Visualization' ? 'selected' : '' }}>BI & Visualization</option>
        <option value="Statistical Analysis" {{ old('category') === 'Statistical Analysis' ? 'selected' : '' }}>Statistical Analysis</option>
        <option value="ML/AI" {{ old('category') === 'ML/AI' ? 'selected' : '' }}>ML/AI</option>
        <option value="Data Engineering" {{ old('category') === 'Data Engineering' ? 'selected' : '' }}>Data Engineering</option>
        <option value="Consultation" {{ old('category') === 'Consultation' ? 'selected' : '' }}>Consultation</option>
    </select>
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
            <button type="submit" class="rounded-md bg-green-600 px-4 py-2 text-white" style="background:#001D39">Publish</button>
        </div>
    </form>
    </div>
@endsection

















