<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClientProfile;

class ClientProfileController extends Controller
{
    /**
     * Display the authenticated client's profile.
     */
    public function show(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'CLIENT') {
            return response()->json(['message' => 'User is not a client.'], 403);
        }

        // Use firstOrCreate to return existing profile or create a new empty one
        $profile = ClientProfile::firstOrCreate(
            ['user_id' => $user->user_id]
        );

        return response()->json($profile);
    }

    /**
     * Create or update the authenticated client's profile.
     */
    public function storeOrUpdate(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'CLIENT') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'company_name' => 'sometimes|string|max:191',
            'industry' => 'sometimes|string|max:100',
        ]);

        $profile = ClientProfile::updateOrCreate(
            ['user_id' => $user->user_id],
            $validatedData
        );

        return response()->json($profile);
    }
}
