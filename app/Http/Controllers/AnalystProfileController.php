<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnalystProfileController extends Controller
{
    // Show the authenticated analyst's profile
    public function show(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'analyst') {
            return response()->json(['message' => 'User is not an analyst.'], 403);
        }
        return response()->json($user->profile);
    }

    // Create or Update the authenticated analyst's profile
    public function storeOrUpdate(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'analyst') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'professional_title' => 'sometimes|string|max:255',
            'location' => 'sometimes|string|max:255',
            'years_of_experience' => 'sometimes|string|max:50',
            'description' => 'sometimes|string',
            'skills' => 'sometimes|array',
            'hourly_rate' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:available,busy',
        ]);
        
        // Use updateOrCreate to handle both creation and updates
        $profile = $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $validatedData
        );

        return response()->json($profile);
    }
}
