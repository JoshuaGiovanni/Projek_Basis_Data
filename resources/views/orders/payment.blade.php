@extends('layouts.app')

@section('content')
<a href="{{ route('client.dashboard') }}" class="text-sm text-gray-500">← Back to dashboard</a>

<div class="mt-4">
    <h2 class="text-2xl font-semibold">Payment for Order #{{ $order->order_id }}</h2>
    <div class="mt-2 text-sm text-gray-600">
        Service: {{ $order->service->title ?? 'N/A' }} · Amount: Rp {{ number_format($order->final_amount, 0, ',', '.') }}
    </div>
</div>

<div class="mt-6 grid gap-6 md:grid-cols-2">
    <!-- Deliverable Information -->
    <div class="rounded-xl border bg-white p-6">
        <h3 class="text-lg font-semibold">Submitted Work</h3>
        <div class="mt-4 space-y-3">
            <div class="rounded-lg border border-green-200 bg-green-50 p-4">
                <div class="flex items-start justify-between">
                    <div>
                        <h4 class="font-medium text-green-800">Analyst's Work Submission</h4>
                        <div class="mt-1 text-sm text-green-700">
                            Link: <a href="{{ $deliverable->submission_link }}" target="_blank" class="text-blue-600 underline break-all">{{ $deliverable->submission_link }}</a><br>
                            @if($deliverable->submission_note)
                                Note: {{ $deliverable->submission_note }}<br>
                            @endif
                            Submitted: {{ $deliverable->submitted_at ? $deliverable->submitted_at->format('M d, Y \a\t g:i A') : 'Unknown' }}
                        </div>
                    </div>
                    <a href="{{ $deliverable->submission_link }}" 
                       target="_blank"
                       class="rounded-md bg-blue-600 px-3 py-1 text-sm text-white hover:bg-blue-700" style="background:#001D39">
                        View Work
                    </a>
                </div>
            </div>
            
            <div class="rounded-md bg-blue-50 p-4">
                <div class="flex">
                    <div class="text-blue-400">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Review the Work</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            Please visit the link and review the submitted work before making payment. Once payment is confirmed, the order will be marked as completed.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Section -->
    <div class="rounded-xl border bg-white p-6">
        <h3 class="text-lg font-semibold">Payment</h3>
        
        <div class="mt-4">
    <div class="rounded-lg border-2 border-dashed border-gray-300 p-8 text-center">
        <div id="qrCodeTrigger" class="mx-auto h-32 w-32 rounded-lg bg-gray-100 flex items-center justify-center overflow-hidden cursor-pointer">
            <img src = "{{ asset('images/QR_Static.png') }}" alt="DataMate Logo" class="w-full h-full object-cover" />
        </div>
        <div class="mt-4">
            <h4 class="text-lg font-medium text-gray-900">QR Code Payment</h4>
            <p class="mt-2 text-sm text-gray-600">
                Scan the QR code above to make payment of <strong>Rp 343</strong>
            </p>
            <p class="mt-1 text-xs text-gray-500">
                (QR code will be provided by admin)
            </p>
        </div>
    </div>
</div>

<div id="qrModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75">
    <div class="relative p-4">
        <button id="closeModal" class="absolute -top-2 -right-2 h-8 w-8 rounded-full bg-white text-black flex items-center justify-center">&times;</button>
        <img src="{{ asset('images/QR_Static.png') }}" alt="DataMate Logo" class="max-w-xs md:max-w-sm rounded-lg">
    </div>
</div>

<script>
    const qrCodeTrigger = document.getElementById('qrCodeTrigger');
    const qrModal = document.getElementById('qrModal');
    const closeModal = document.getElementById('closeModal');

    // Show the modal when the small QR code is clicked
    qrCodeTrigger.addEventListener('click', () => {
        qrModal.classList.remove('hidden');
    });

    // Hide the modal when the close button is clicked
    closeModal.addEventListener('click', () => {
        qrModal.classList.add('hidden');
    });

    // Optional: Hide the modal when clicking on the background overlay
    qrModal.addEventListener('click', (event) => {
        if (event.target === qrModal) {
            qrModal.classList.add('hidden');
        }
    });
</script>

        <div class="mt-6">
            <div class="rounded-md bg-yellow-50 p-4">
                <div class="flex">
                    <div class="text-yellow-400">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Payment Instructions</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <ol class="list-decimal space-y-1 pl-5">
                                <li>Review the submitted work by visiting the link above</li>
                                <li>Scan the QR code to make payment</li>
                                <li>After successful payment, confirm below</li>
                                <li>The order will be marked as completed</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('orders.payment.confirm', $order) }}" enctype="multipart/form-data" class="mt-6">
            @csrf
            
            <div class="space-y-4">
                <div>
                    <label for="proof_image" class="block text-sm font-medium text-gray-700">Payment Proof *</label>
                    <div class="mt-1">
                        <input type="file" 
                               id="proof_image" 
                               name="proof_image" 
                               accept="image/*"
                               required
                               class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-500">
                    </div>
                    @error('proof_image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Please upload a screenshot or photo of your payment confirmation (PNG, JPG, JPEG - max 5MB)</p>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" 
                           id="payment_confirmation" 
                           name="payment_confirmation" 
                           value="1"
                           required
                           class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="payment_confirmation" class="ml-2 text-sm text-gray-700">
                        I confirm that I have made the payment of Rp {{ number_format($order->final_amount, 0, ',', '.') }} via QR code and uploaded the proof above
                    </label>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('client.dashboard') }}" 
                       class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700" style="background:#001D39">
                        Confirm Payment
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@if (session('error'))
<div class="mt-4 rounded-md bg-red-50 p-4">
    <div class="flex">
        <div class="text-red-400">
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-red-800">Error</h3>
            <div class="mt-2 text-sm text-red-700">
                {{ session('error') }}
            </div>
        </div>
    </div>
</div>
@endif
@endsection
