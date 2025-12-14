<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\Service;
use App\Models\AnalystProfile;
use App\Models\Order;
use App\Models\Deliverable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AnalystServiceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public pages
Route::get('/', function () {
    return view('home');
})->name('home');

// Simple admin access for testing
Route::get('/admin', function () {
    $user = Auth::user();
    if (!$user || $user->role !== 'ADMIN') {
        return redirect()->route('login')->with('error', 'Admin access required. Login as admin (username: admin, password: password)');
    }
    return redirect()->route('admin.payments');
})->name('admin.dashboard');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Auth endpoints
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin routes (simple implementation)
Route::get('/admin/payments', function () {
    // Simple admin check - in a real app you'd have proper admin authentication
    $user = Auth::user();
    if (!$user || $user->role !== 'ADMIN') {
        return redirect()->route('login')->with('error', 'Admin access required');
    }
    
    $payments = \App\Models\Payment::with(['order.service', 'order.clientProfile.user'])
        ->orderByDesc('payment_date')
        ->paginate(20);
    
    return view('admin.payments', compact('payments'));
})->name('admin.payments');

// Admin approve payment
Route::post('/admin/payments/{payment}/approve', function (\App\Models\Payment $payment) {
    $user = Auth::user();
    if (!$user || $user->role !== 'ADMIN') {
        return redirect()->route('login')->with('error', 'Admin access required');
    }
    
    if ($payment->status !== 'PENDING') {
        return redirect()->back()->with('error', 'Payment is not pending verification');
    }
    
    // Update payment status to completed
    $payment->status = 'COMPLETED';
    $payment->save();
    
    // Update order status to completed
    $order = $payment->order;
    $order->status = 'COMPLETED';
    $order->save();
    
    return redirect()->back()->with('status', 'Payment approved and order completed successfully');
})->name('admin.payments.approve');

// Admin reject payment
Route::post('/admin/payments/{payment}/reject', function (\App\Models\Payment $payment) {
    $user = Auth::user();
    if (!$user || $user->role !== 'ADMIN') {
        return redirect()->route('login')->with('error', 'Admin access required');
    }
    
    if ($payment->status !== 'PENDING') {
        return redirect()->back()->with('error', 'Payment is not pending verification');
    }
    
    // Update payment status to failed
    $payment->status = 'FAILED';
    $payment->save();
    
    // Order remains in SUBMITTED status so client can retry payment
    
    return redirect()->back()->with('status', 'Payment rejected. Client can resubmit payment proof.');
})->name('admin.payments.reject');

// Browse analysts (list services) with search and sort
Route::get('/analysts', function (Request $request) {
    $query = trim((string) $request->get('q'));
    $sort = (string) $request->get('sort', 'newest');

    $servicesQuery = Service::with(['analystProfile.ongoingOrders']);

    if ($query !== '') {
        $servicesQuery->where(function ($q) use ($query) {
            $q->where('title', 'like', "%{$query}%")
              ->orWhere('category', 'like', "%{$query}%")
              ->orWhere('description', 'like', "%{$query}%")
              ->orWhereHas('analystProfile', function ($aq) use ($query) {
                    $aq->where('full_name', 'like', "%{$query}%");
              });
        });
    }

    switch ($sort) {
        case 'rating_desc':
            $servicesQuery->join('analyst_profile', 'services.analyst_id', '=', 'analyst_profile.analyst_id')
                ->orderByDesc('analyst_profile.average_rating')
                ->orderBy('services.created_at', 'desc')
                ->select('services.*');
            break;
        case 'rating_asc':
            $servicesQuery->join('analyst_profile', 'services.analyst_id', '=', 'analyst_profile.analyst_id')
                ->orderBy('analyst_profile.average_rating')
                ->orderBy('services.created_at', 'desc')
                ->select('services.*');
            break;
        case 'price_low':
            $servicesQuery->orderBy('price_min')->orderBy('created_at', 'desc');
            break;
        case 'price_high':
            $servicesQuery->orderByDesc('price_max')->orderBy('created_at', 'desc');
            break;
        default: // newest
            $servicesQuery->orderByDesc('created_at');
    }

    $services = $servicesQuery->get();
    return view('analysts.index', compact('services', 'query', 'sort'));
})->name('analysts.index');

// Analyst profile setup/edit page (own)
Route::get('/analyst/profile', function () {
    $user = Auth::user();
    if (!$user || $user->role !== 'ANALYST') {
        return redirect()->route('login');
    }
    $profile = AnalystProfile::where('user_id', $user->user_id)->first();
    return view('analysts.profile', compact('profile'));
})->name('analysts.profile');

// Save analyst profile
Route::post('/analyst/profile', function (Request $request) {
    $user = Auth::user();
    if (!$user || $user->role !== 'ANALYST') {
        return redirect()->route('login');
    }
    $data = $request->validate([
        'full_name' => 'required|string|max:100',
        'years_of_experience' => 'nullable|integer|min:0',
        'description' => 'nullable|string',
        'status' => 'nullable|in:available,unavailable',
        'skills' => 'nullable|string',
        'location' => 'nullable|string|max:150',
        'max_ongoing_orders' => 'nullable|integer|min:1|max:50',
    ]);

    // Persist core columns; store extra UI fields in description/skills JSON
    $profile = AnalystProfile::firstOrCreate(['user_id' => $user->user_id]);
    $profile->full_name = $data['full_name'];
    
    // Explicitly handle years of experience
    if ($request->has('years_of_experience')) {
        $profile->years_of_experience = $request->input('years_of_experience');
    }
    
    $profile->description = $data['description'] ?? null;
    $profile->status = $data['status'] ?? 'available';
    if (isset($data['max_ongoing_orders'])) {
        $profile->max_ongoing_orders = (int) $data['max_ongoing_orders'];
    }
    $profile->skills = isset($data['skills']) ? array_map('trim', array_filter(explode(',', $data['skills']))) : $profile->skills;
    $profile->save();

    return redirect()->route('analyst.dashboard');
})->name('analysts.profile.save');

// Update only the analyst max ongoing orders from dashboard
Route::post('/analyst/profile/limit', function (Request $request) {
    $user = Auth::user();
    if (!$user || $user->role !== 'ANALYST') {
        return redirect()->route('login');
    }
    $data = $request->validate([
        'max_ongoing_orders' => 'required|integer|min:1|max:50',
    ]);
    $profile = AnalystProfile::firstOrCreate(['user_id' => $user->user_id]);
    $profile->max_ongoing_orders = (int) $data['max_ongoing_orders'];
    $profile->save();
    return back()->with('status', 'Updated maximum ongoing projects.');
})->name('analysts.profile.limit');

// Analyst dashboard
Route::get('/analyst/dashboard', function () {
    $user = Auth::user();
    if (!$user || $user->role !== 'ANALYST') {
        return redirect()->route('login');
    }
    $profile = AnalystProfile::where('user_id', $user->user_id)->with('ongoingOrders')->first();
    $services = Service::where('analyst_id', optional($profile)->analyst_id)->get();
    $offers = Order::whereIn('service_id', $services->pluck('service_id'))->with(['service', 'deliverable'])->latest('order_date')->get();
    return view('analysts.dashboard', compact('profile','services','offers'));
})->name('analyst.dashboard');

// Public analyst profile page (view by clients)
Route::get('/analysts/{analyst}', function ($analystId) {
    $profile = AnalystProfile::with(['user'])->findOrFail($analystId);
    $services = Service::where('analyst_id', $analystId)->get();
    return view('analysts.show', compact('profile', 'services'));
})->name('analysts.show');

// Post a new service (simple form)
Route::get('/analyst/services/new', function () {
    $user = Auth::user();
    if (!$user || $user->role !== 'ANALYST') {
        return redirect()->route('login');
    }
    return view('analysts.service_new');
})->name('analyst.service.new');

Route::post('/analyst/services', function (Request $request) {
    $user = Auth::user();
    if (!$user || $user->role !== 'ANALYST') {
        return redirect()->route('login');
    }
    $data = $request->validate([
        'title' => 'required|string|max:150',
        'description' => 'nullable|string',
        'price_min' => 'required|numeric|min:0',
        'price_max' => 'required|numeric|gte:price_min',
        'category' => 'nullable|string|max:100',
    ]);
    $profile = AnalystProfile::where('user_id', $user->user_id)->firstOrFail();
    Service::create([
        'analyst_id' => $profile->analyst_id,
        'title' => $data['title'],
        'description' => $data['description'] ?? null,
        'price_min' => $data['price_min'],
        'price_max' => $data['price_max'],
        'category' => $data['category'] ?? null,
    ]);
    return redirect()->route('analyst.dashboard');
})->name('analyst.service.store');

// Delete service (only owner analyst can delete) handled in AnalystServiceController
Route::delete('analyst/services/{service}', [AnalystServiceController::class, 'destroy'])
    ->name('analyst.service.destroy')
    ->middleware('auth');

// Booking page: client decides final amount, reminder to contact analyst first
Route::get('/orders/book/{service}', function (Service $service) {
    $user = Auth::user();
    if (!$user || $user->role !== 'CLIENT') {
        return redirect()->route('login');
    }
    $service->load('analystProfile.ongoingOrders');
    return view('orders.book', compact('service'));
})->name('orders.book');

Route::post('/orders/book/{service}', function (Request $request, Service $service) {
    $user = Auth::user();
    if (!$user || $user->role !== 'CLIENT') {
        return redirect()->route('login');
    }
    
    // Check if analyst is available and not at capacity
    $analyst = $service->analystProfile;
    $limit = (int) optional($analyst)->max_ongoing_orders ?: 5;
    if (!$analyst || $analyst->status !== 'available' || $analyst->ongoing_orders_count >= $limit) {
        return redirect()->back()->with('error', 'This analyst is currently unavailable or at capacity. Please try another analyst.');
    }
    
    $data = $request->validate([
        'final_amount' => 'required|numeric|min:0',
        'due_date' => 'nullable|date',
    ]);
    Order::create([
        'service_id' => $service->service_id,
        'client_id' => $user->user_id,
        'order_date' => now(),
        'due_date' => $data['due_date'] ?? null,
        'final_amount' => $data['final_amount'],
        'status' => 'PENDING',
    ]);
    return redirect()->route('client.dashboard')->with('status', 'Order sent. Await analyst response.');
})->name('orders.book.submit');

// Analyst actions: accept or reject orders
Route::post('/orders/{order}/accept', function (Order $order) {
    $user = Auth::user();
    if (!$user || $user->role !== 'ANALYST') return redirect()->route('login');
    $profile = AnalystProfile::where('user_id', $user->user_id)->first();
    $owns = Service::where('analyst_id', optional($profile)->analyst_id)->where('service_id', $order->service_id)->exists();
    if (!$owns) abort(403);
    
    // Check if analyst is at capacity
    $limit = (int) optional($profile)->max_ongoing_orders ?: 5;
    if ($profile && $profile->ongoing_orders_count >= $limit) {
        return back()->with('error', 'Cannot accept order: You have reached your maximum number of ongoing orders.');
    }
    
    $order->status = 'IN_PROGRESS';
    $order->save();
    return back()->with('status', 'Order accepted.');
})->name('orders.accept');

Route::post('/orders/{order}/reject', function (Order $order) {
    $user = Auth::user();
    if (!$user || $user->role !== 'ANALYST') return redirect()->route('login');
    $profile = AnalystProfile::where('user_id', $user->user_id)->first();
    $owns = Service::where('analyst_id', optional($profile)->analyst_id)->where('service_id', $order->service_id)->exists();
    if (!$owns) abort(403);
    $order->status = 'CANCELLED';
    $order->save();
    return back()->with('status', 'Order rejected.');
})->name('orders.reject');

// Client dashboard
Route::get('/client/dashboard', function () {
    $user = Auth::user();
    if (!$user || $user->role !== 'CLIENT') return redirect()->route('login');
    $orders = Order::where('client_id', $user->user_id)->with(['service', 'deliverable'])->latest('order_date')->get();
    return view('clients.dashboard', compact('orders'));
})->name('client.dashboard');

// Client order brief (in-progress only)
Route::get('/orders/{order}/brief', function (Order $order) {
    $user = Auth::user();
    if (!$user || $user->role !== 'CLIENT' || $order->client_id !== $user->user_id) return redirect()->route('login');
    if ($order->status !== 'IN_PROGRESS') return redirect()->route('client.dashboard');
    $brief = \App\Models\OrderBrief::where('order_id', $order->order_id)->first();
    return view('orders.brief', compact('order', 'brief'));
})->name('orders.brief');

Route::post('/orders/{order}/brief', function (Request $request, Order $order) {
    $user = Auth::user();
    if (!$user || $user->role !== 'CLIENT' || $order->client_id !== $user->user_id) return redirect()->route('login');
    if ($order->status !== 'IN_PROGRESS') return redirect()->route('client.dashboard');
    $data = $request->validate([
        'project_description' => 'nullable|string',
        'attachments_url' => 'nullable|string|max:1000',
    ]);
    \App\Models\OrderBrief::updateOrCreate(
        ['order_id' => $order->order_id],
        [
            'project_description' => $data['project_description'] ?? null,
            'attachments_url' => $data['attachments_url'] ?? null,
            'submitted_at' => now(),
        ]
    );
    return redirect()->route('client.dashboard')->with('status', 'Brief saved and sent to analyst.');
})->name('orders.brief.submit');

// Analyst view of a brief (must own the service)
Route::get('/analyst/orders/{order}/brief', function (Order $order) {
    $user = Auth::user();
    if (!$user || $user->role !== 'ANALYST') return redirect()->route('login');
    $profile = AnalystProfile::where('user_id', $user->user_id)->first();
    $owns = Service::where('analyst_id', optional($profile)->analyst_id)->where('service_id', $order->service_id)->exists();
    if (!$owns) abort(403);
    $brief = \App\Models\OrderBrief::where('order_id', $order->order_id)->first();
    return view('orders.brief_readonly', compact('order', 'brief'));
})->name('analyst.order.brief');

// Analyst deliverable submission
Route::get('/analyst/orders/{order}/submit', function (Order $order) {
    $user = Auth::user();
    if (!$user || $user->role !== 'ANALYST') return redirect()->route('login');
    $profile = AnalystProfile::where('user_id', $user->user_id)->first();
    $owns = Service::where('analyst_id', optional($profile)->analyst_id)->where('service_id', $order->service_id)->exists();
    if (!$owns) abort(403);
    if ($order->status !== 'IN_PROGRESS') return redirect()->route('analyst.dashboard');
    
    // Check if order brief has been submitted by client
    $brief = \App\Models\OrderBrief::where('order_id', $order->order_id)->first();
    if (!$brief) {
        return redirect()->route('analyst.dashboard')->with('error', 'Cannot submit work until client has provided the order brief.');
    }
    
    $deliverable = Deliverable::where('order_id', $order->order_id)->first();
    return view('orders.submit', compact('order', 'deliverable', 'brief'));
})->name('analyst.order.submit');

Route::post('/analyst/orders/{order}/submit', function (Request $request, Order $order) {
    $user = Auth::user();
    if (!$user || $user->role !== 'ANALYST') return redirect()->route('login');
    $profile = AnalystProfile::where('user_id', $user->user_id)->first();
    $owns = Service::where('analyst_id', optional($profile)->analyst_id)->where('service_id', $order->service_id)->exists();
    if (!$owns) abort(403);
    if ($order->status !== 'IN_PROGRESS') return redirect()->route('analyst.dashboard');
    
    // Check if order brief has been submitted by client
    $brief = \App\Models\OrderBrief::where('order_id', $order->order_id)->first();
    if (!$brief) {
        return redirect()->route('analyst.dashboard')->with('error', 'Cannot submit work until client has provided the order brief.');
    }
    
    $request->validate([
        'submission_link' => 'required|url|max:500',
        'submission_note' => 'nullable|string|max:1000',
    ]);
    
    Deliverable::updateOrCreate(
        ['order_id' => $order->order_id],
        [
            'submission_link' => $request->submission_link,
            'submission_note' => $request->submission_note,
            'submitted_at' => now(),
            'approved_by_admin' => false,
        ]
    );
    
    // Update order status to indicate work is submitted
    $order->status = 'SUBMITTED';
    $order->save();
    
    return redirect()->route('analyst.dashboard')->with('status', 'Work submitted successfully via link.');
})->name('analyst.order.submit.store');

// Client payment page
Route::get('/orders/{order}/payment', function (Order $order) {
    $user = Auth::user();
    if (!$user || $user->role !== 'CLIENT' || $order->client_id !== $user->user_id) return redirect()->route('login');
    if ($order->status !== 'SUBMITTED') return redirect()->route('client.dashboard');
    $deliverable = Deliverable::where('order_id', $order->order_id)->first();
    if (!$deliverable) return redirect()->route('client.dashboard');
    return view('orders.payment', compact('order', 'deliverable'));
})->name('orders.payment');

Route::post('/orders/{order}/payment', function (Request $request, Order $order) {
    $user = Auth::user();
    if (!$user || $user->role !== 'CLIENT' || $order->client_id !== $user->user_id) return redirect()->route('login');
    if ($order->status !== 'SUBMITTED') return redirect()->route('client.dashboard');
    
    $request->validate([
        'payment_confirmation' => 'required|boolean',
        'proof_image' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
    ]);
    
    if ($request->payment_confirmation && $request->hasFile('proof_image')) {
        // Store the proof image
        $proofImage = $request->file('proof_image');
        $fileName = 'payment_proof_order_' . $order->order_id . '_' . time() . '.' . $proofImage->getClientOriginalExtension();
        $filePath = $proofImage->storeAs('payment_proofs', $fileName, 'public');
        
        // Check if there's an existing payment (for resubmission)
        $existingPayment = \App\Models\Payment::where('order_id', $order->order_id)->first();
        
        if ($existingPayment) {
            // Update existing payment
            $existingPayment->update([
                'amount' => $order->final_amount,
                'payment_date' => now(),
                'proof_image' => Storage::url($filePath),
                'status' => 'PENDING',
            ]);
        } else {
            // Create new payment record
            \App\Models\Payment::create([
                'order_id' => $order->order_id,
                'amount' => $order->final_amount,
                'payment_date' => now(),
                'payment_method' => 'QR_CODE',
                'proof_image' => Storage::url($filePath),
                'status' => 'PENDING',
            ]);
        }
        
        // Order remains in SUBMITTED status until admin verifies payment
        
        return redirect()->route('client.dashboard')->with('status', 'Payment proof submitted successfully. Awaiting admin verification.');
    }
    
    return redirect()->back()->with('error', 'Payment confirmation and proof image are required.');
})->name('orders.payment.confirm');

