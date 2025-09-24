<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\Service;
use App\Models\AnalystProfile;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

// Browse analysts (list services)
Route::get('/analysts', function () {
    $services = Service::with('analystProfile')
        ->orderByDesc('created_at')
        ->get();
    return view('analysts.index', compact('services'));
})->name('analysts.index');

// Analyst profile setup/edit page (own)
Route::get('/analyst/profile', function () {
    return view('analysts.profile');
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
        'professional_title' => 'nullable|string|max:150',
    ]);

    // Persist core columns; store extra UI fields in description/skills JSON
    $profile = AnalystProfile::firstOrCreate(['user_id' => $user->user_id]);
    $profile->full_name = $data['full_name'];
    $profile->years_of_experience = $data['years_of_experience'] ?? 0;
    $profile->description = $data['description'] ?? null;
    $profile->status = $data['status'] ?? 'available';
    $profile->skills = isset($data['skills']) ? array_map('trim', array_filter(explode(',', $data['skills']))) : $profile->skills;
    $profile->save();

    return redirect()->route('analyst.dashboard');
})->name('analysts.profile.save');

// Analyst dashboard
Route::get('/analyst/dashboard', function () {
    $user = Auth::user();
    if (!$user || $user->role !== 'ANALYST') {
        return redirect()->route('login');
    }
    $profile = AnalystProfile::where('user_id', $user->user_id)->first();
    $services = Service::where('analyst_id', optional($profile)->analyst_id)->get();
    $offers = Order::whereIn('service_id', $services->pluck('service_id'))->latest('order_date')->get();
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

// Booking page: client decides final amount, reminder to contact analyst first
Route::get('/orders/book/{service}', function (Service $service) {
    $user = Auth::user();
    if (!$user || $user->role !== 'CLIENT') {
        return redirect()->route('login');
    }
    return view('orders.book', compact('service'));
})->name('orders.book');

Route::post('/orders/book/{service}', function (Request $request, Service $service) {
    $user = Auth::user();
    if (!$user || $user->role !== 'CLIENT') {
        return redirect()->route('login');
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
    $orders = Order::where('client_id', $user->user_id)->latest('order_date')->get();
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

