<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Service;

class ServiceController extends Controller
{
    /**
     * Display a listing of the analyst's services.
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->role !== 'ANALYST') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $services = Service::where('analyst_id', $user->user_id)->get();
        return response()->json($services);
    }

    /**
     * Store a newly created service in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'ANALYST') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
        ]);

        $service = Service::create([
            'analyst_id' => $user->user_id,
        ] + $validatedData);

        return response()->json($service, 201);
    }

    /**
     * Display the specified service.
     */
    public function show(Service $service)
    {
        // Ensure the authenticated user is the owner of the service
        if ($service->analyst_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return response()->json($service);
    }

    /**
     * Update the specified service in storage.
     */
    public function update(Request $request, Service $service)
    {
        if ($service->analyst_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'title' => 'sometimes|string|max:150',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'category' => 'sometimes|string|max:100',
        ]);

        $service->update($validatedData);

        return response()->json($service);
    }

    /**
     * Remove the specified service from storage.
     */
    public function destroy($id, Request $request)
    {
        $user = $request->user();
        $analystProfile = AnalystProfile::where('user_id', $user->user_id)->first();

        if (! $analystProfile) {
            abort(403);
        }

        $service = Service::where('service_id', $id)->firstOrFail();

        if ($service->analyst_id !== $analystProfile->analyst_id) {
            abort(403);
        }

        $service->delete();

        return redirect()->back()->with('success', 'Service deleted successfully.');
    }
}
