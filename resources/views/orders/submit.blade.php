@extends('layouts.app')

@section('content')
<a href="{{ route('analyst.dashboard') }}" class="text-sm text-gray-500">← Back to dashboard</a>

<div class="mt-4">
    <h2 class="text-2xl font-semibold">Submit Work for Order #{{ $order->order_id }}</h2>
    <div class="mt-2 text-sm text-gray-600">
        Service: {{ $order->service->title ?? 'N/A' }} · Final Amount: Rp {{ number_format($order->final_amount, 0, ',', '.') }}
    </div>
</div>

@if ($deliverable)
<div class="mt-4 rounded-lg border border-green-200 bg-green-50 p-4">
    <div class="flex items-center">
        <div class="text-green-600">
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-green-800">Work Already Submitted</h3>
            <div class="mt-1 text-sm text-green-700">
                Link: <a href="{{ $deliverable->submission_link }}" target="_blank" class="text-blue-600 underline">{{ $deliverable->submission_link }}</a><br>
                @if($deliverable->submission_note)
                    Note: {{ $deliverable->submission_note }}<br>
                @endif
                Submitted: {{ $deliverable->submitted_at ? $deliverable->submitted_at->format('M d, Y \a\t g:i A') : 'Unknown' }}
            </div>
        </div>
    </div>
</div>
@endif

<div class="mt-6 rounded-xl border bg-white p-6">
    <h3 class="text-lg font-semibold">{{ $deliverable ? 'Update' : 'Submit' }} Your Work</h3>
    <p class="mt-2 text-sm text-gray-600">
        Provide a link to your completed work (Google Drive, Dropbox, etc.). This saves storage space and allows for easier sharing.
    </p>

    <form method="POST" action="{{ route('analyst.order.submit.store', $order) }}" class="mt-4">
        @csrf
        
        <div class="space-y-4">
            <div>
                <label for="submission_link" class="block text-sm font-medium text-gray-700">Work Link *</label>
                <div class="mt-1">
                    <input type="url" 
                           id="submission_link" 
                           name="submission_link" 
                           value="{{ old('submission_link', $deliverable->submission_link ?? '') }}"
                           placeholder="https://drive.google.com/file/d/... or https://www.dropbox.com/s/..."
                           required
                           class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500">
                </div>
                @error('submission_link')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Make sure the link is publicly accessible or has view permissions for the client.</p>
            </div>

            <div>
                <label for="submission_note" class="block text-sm font-medium text-gray-700">Additional Notes (Optional)</label>
                <div class="mt-1">
                    <textarea id="submission_note" 
                              name="submission_note" 
                              rows="3"
                              placeholder="Any additional information about your submission..."
                              class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500">{{ old('submission_note', $deliverable->submission_note ?? '') }}</textarea>
                </div>
                @error('submission_note')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="rounded-md bg-yellow-50 p-4">
                <div class="flex">
                    <div class="text-yellow-400">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Important Notes</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <ul class="list-disc space-y-1 pl-5">
                                <li>Provide a direct link to your completed work</li>
                                <li>Supported platforms: Google Drive, Dropbox, OneDrive, etc.</li>
                                <li>Ensure the link has proper viewing/download permissions</li>
                                <li>Include all necessary files and documentation in your submission</li>
                                <li>Once submitted, the client will be notified to review and make payment</li>
                                <li>After submission, you'll be available to take new orders</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('analyst.dashboard') }}" 
                   class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" 
                        class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                    {{ $deliverable ? 'Update Submission' : 'Submit Work' }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
