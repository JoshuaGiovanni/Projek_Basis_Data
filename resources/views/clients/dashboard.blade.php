@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-semibold text-gray-800 mb-4">Your Orders</h2>

<div class="mt-4 grid gap-4">
    @forelse ($orders as $order)
    @php
        $deliverable = $order->deliverable; // eager load in controller
        $payment = $order->payment;         // eager load in controller
    @endphp
    <div class="rounded-lg border bg-white/5 p-4 shadow-sm backdrop-blur-sm">
        <div class="flex items-center justify-between mb-1">
            <div class="font-medium text-white">Order #{{ $order->order_id }}</div>
            <div class="text-sm text-gray-400">Status: <span class="font-semibold">{{ $order->status }}</span></div>
        </div>
        <div class="text-sm text-gray-300 mb-3">
            Service: {{ $order->service->title ?? 'N/A' }} · Final: Rp {{ number_format($order->final_amount, 0, ',', '.') }}
        </div>

        {{-- IN_PROGRESS --}}
        @if ($order->status === 'IN_PROGRESS')
            <a href="{{ route('orders.brief', $order) }}" class="inline-flex mt-2 rounded-md bg-blue-600 px-3 py-2 text-white hover:bg-blue-700 transition">
                Send Order Brief
            </a>
        @endif

        {{-- SUBMITTED --}}
        @if ($order->status === 'SUBMITTED' && $deliverable)
            <div class="mt-2 rounded-lg border border-green-300 bg-green-50 p-3">
                <div class="flex items-start justify-between">
                    <div>
                        <h4 class="font-medium text-green-800">Work Submitted</h4>
                        <div class="mt-1 text-sm text-green-700">
                            Link: <a href="{{ $deliverable->submission_link }}" target="_blank" class="text-blue-600 underline">{{ $deliverable->submission_link }}</a><br>
                            @if($deliverable->submission_note)
                                Note: {{ $deliverable->submission_note }}<br>
                            @endif
                            Submitted: {{ $deliverable->submitted_at?->format('M d, Y \a\t g:i A') ?? 'Unknown' }}
                        </div>
                    </div>
                    <a href="{{ $deliverable->submission_link }}" target="_blank"
                       class="rounded-md bg-blue-600 px-3 py-1 text-sm text-white hover:bg-blue-700 transition">
                        View Work
                    </a>
                </div>
            </div>

            @if(!$payment || $payment->status === 'PENDING')
                <div class="mt-2 flex gap-2">
                    <a href="{{ route('orders.payment', $order) }}" class="inline-flex rounded-md bg-blue-600 px-3 py-2 text-white hover:bg-blue-700 transition">
                        Make Payment
                    </a>
                </div>
            @endif
        @endif

        {{-- PAYMENT STATUS --}}
        @if ($payment)
            @if($payment->status === 'PENDING')
                <div class="mt-3 rounded-lg border border-yellow-300 bg-yellow-50 p-3">
                    <div class="flex items-start justify-between">
                        <div>
                            <h4 class="font-medium text-yellow-800">Payment Verification Pending</h4>
                            <div class="mt-1 text-sm text-yellow-700">
                                @if($payment->proof_image)
                                    Proof submitted: 
                                    <a href="{{ \Illuminate\Support\Str::startsWith($payment->proof_image, ['http://','https://']) ? $payment->proof_image : asset($payment->proof_image) }}" target="_blank" class="text-blue-600 underline">View proof</a><br>
                                @endif
                                Submitted: {{ $payment->payment_date?->format('M d, Y \a\t g:i A') ?? 'Unknown' }}
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded text-yellow-800 bg-yellow-100">Pending Verification</span>
                    </div>
                </div>
            @elseif($payment->status === 'FAILED')
                <div class="mt-3 rounded-lg border border-red-300 bg-red-50 p-3">
                    <div class="flex items-start justify-between">
                        <div>
                            <h4 class="font-medium text-red-800">Payment Rejected</h4>
                            <div class="mt-1 text-sm text-red-700">Your payment proof was rejected. Please resubmit.</div>
                        </div>
                        <a href="{{ route('orders.payment', $order) }}" class="inline-flex rounded-md bg-red-600 px-3 py-2 text-white hover:bg-red-700 transition">Resubmit Payment</a>
                    </div>
                </div>
            @elseif($payment->status === 'COMPLETED')
                <div class="mt-2 text-sm text-green-600">
                    ✓ Payment completed
                    @if($payment->proof_image)
                        <br><span class="text-xs text-gray-400">Proof: 
                            <a href="{{ \Illuminate\Support\Str::startsWith($payment->proof_image, ['http://','https://']) ? $payment->proof_image : asset($payment->proof_image) }}" target="_blank" class="text-blue-600 underline">View proof</a>
                        </span>
                    @endif
                </div>
            @endif
        @endif

        {{-- COMPLETED WORK --}}
        @if ($order->status === 'COMPLETED' && $deliverable)
            <div class="mt-3 rounded-lg border border-black-300 bg-white-50 p-3" style="background:#001D39">
                <div class="flex items-start justify-between">
                    <div>
                        <h4 class="font-medium text-black-800">Completed Work</h4>
                        <div class="mt-1 text-sm text-gray-600">
                            Link: <a href="{{ $deliverable->submission_link }}" target="_blank" class="text-blue-600 underline">{{ $deliverable->submission_link }}</a><br>
                            @if($deliverable->submission_note)
                                Note: {{ $deliverable->submission_note }}<br>
                            @endif
                            Submitted: {{ $deliverable->submitted_at?->format('M d, Y \a\t g:i A') ?? 'Unknown' }}
                        </div>
                    </div>
                    <a href="{{ $deliverable->submission_link }}" target="_blank" class="rounded-md bg-gray-600 px-3 py-1 text-sm text-white hover:bg-gray-700 transition">
                        View Work
                    </a>
                </div>
            </div>
        @endif
    </div>
    @empty
        <div class="text-sm text-gray-400">No orders yet.</div>
    @endforelse
</div>
@endsection
