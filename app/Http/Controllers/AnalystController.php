<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AnalystController extends Controller
{
    // Get all users who are analysts and their profiles
    public function index()
    {
        $analysts = User::where('role', 'analyst')
            ->with('profile') // Eager load the profile relationship
            ->get();
            
        return response()->json($analysts);
    }

    // Get a single analyst's data
    public function show($id)
    {
        $analyst = User::where('role', 'analyst')
            ->with('profile')
            ->findOrFail($id);

        return response()->json($analyst);
    }
}
