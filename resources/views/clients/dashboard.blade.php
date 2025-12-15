@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-semibold text-gray-800 mb-4">Your Orders</h2>

@if (session('status'))
    <div class="mb-4 rounded-md bg-green-50 p-4 text-green-700">
        {{ session('status') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-4 rounded-md bg-red-50 p-4 text-red-700">
        {{ session('error') }}
    </div>
@endif

<div class="mt-4 grid gap-4">
    @forelse ($orders as $order)
    @php
        $deliverable = $order->deliverable;
        $payment = $order->payment;
        $review = $order->review;
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
                </div>
            @endif
        @endif

        {{-- COMPLETED WORK & RATING --}}
        @if ($order->status === 'COMPLETED')
            <div class="mt-3 rounded-lg border border-gray-700 bg-gray-800 p-3">
                <div class="flex items-start justify-between">
                    <div>
                        <h4 class="font-medium text-gray-200">Order Completed</h4>
                        @if($deliverable)
                            <div class="mt-1 text-sm text-gray-400">
                                Deliverable: <a href="{{ $deliverable->submission_link }}" target="_blank" class="text-blue-400 underline">View Final Work</a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Rating Section --}}
                <div class="mt-4 pt-4 border-t border-gray-700">
                    @if($review)
                        <div class="flex items-center gap-2">
                            <span class="text-yellow-400 text-lg">
                                @for($i=1; $i<=5; $i++)
                                    {{ $i <= $review->rating ? '★' : '☆' }}
                                @endfor
                            </span>
                            <span class="text-sm text-gray-400">You rated this order.</span>
                        </div>
                        @if($review->comment)
                            <div class="mt-1 text-sm text-gray-500 italic">"{{ $review->comment }}"</div>
                        @endif
                    @else
                        <button onclick="openRatingModal('{{ $order->order_id }}', '{{ $order->service->title }}')" 
                                class="inline-flex items-center rounded-md bg-yellow-600 px-3 py-2 text-sm font-medium text-white hover:bg-yellow-700 transition">
                            ★ Rate Analyst
                        </button>
                    @endif
                </div>
            </div>
        @endif
    </div>
    @empty
        <div class="text-sm text-gray-400">No orders yet.</div>
    @endforelse
</div>

{{-- Rating Modal --}}
<div id="ratingModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeRatingModal()"></div>
        <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
            <form id="ratingForm" method="POST" action="">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Rate Service: <span id="modalServiceName"></span></h3>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Rating</label>
                        <div class="mt-1 flex gap-4 text-2xl text-gray-400 cursor-pointer">
                            {{-- Simple Star Input Hack --}}
                            <div class="rating-stars flex flex-row-reverse justify-end">
                                <input type="radio" name="rating" value="5" id="star5" class="peer hidden" required>
                                <label for="star5" class="peer-checked:text-yellow-500 hover:text-yellow-500 transition cursor-pointer">★</label>
                                
                                <input type="radio" name="rating" value="4" id="star4" class="peer hidden">
                                <label for="star4" class="peer-checked:text-yellow-500 hover:text-yellow-500 transition cursor-pointer">★</label>
                                
                                <input type="radio" name="rating" value="3" id="star3" class="peer hidden">
                                <label for="star3" class="peer-checked:text-yellow-500 hover:text-yellow-500 transition cursor-pointer">★</label>
                                
                                <input type="radio" name="rating" value="2" id="star2" class="peer hidden">
                                <label for="star2" class="peer-checked:text-yellow-500 hover:text-yellow-500 transition cursor-pointer">★</label>
                                
                                <input type="radio" name="rating" value="1" id="star1" class="peer hidden">
                                <label for="star1" class="peer-checked:text-yellow-500 hover:text-yellow-500 transition cursor-pointer">★</label>
                            </div>
                        </div>
                        <style>
                            /* Hover effect for previous siblings (visual next in RTL flex) */
                            .rating-stars label:hover ~ label,
                            .rating-stars input:checked ~ label {
                                color: #eab308; /* yellow-500 */
                            }
                        </style>
                    </div>

                    <div class="mt-4">
                        <label for="comment" class="block text-sm font-medium text-gray-700">Comment (Optional)</label>
                        <textarea name="comment" id="comment" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm text-black" placeholder="Share your experience..."></textarea>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="submit" class="inline-flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Submit Review</button>
                    <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeRatingModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openRatingModal(orderId, serviceTitle) {
        const modal = document.getElementById('ratingModal');
        const form = document.getElementById('ratingForm');
        const titleSpan = document.getElementById('modalServiceName');
        
        // Use the named route pattern: /orders/{order}/review
        form.action = `/orders/${orderId}/review`;
        titleSpan.textContent = serviceTitle;
        
        modal.classList.remove('hidden');
    }

    function closeRatingModal() {
        const modal = document.getElementById('ratingModal');
        modal.classList.add('hidden');
    }
</script>
@endsection
