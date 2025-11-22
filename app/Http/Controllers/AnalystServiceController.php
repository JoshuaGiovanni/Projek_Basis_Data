<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\AnalystProfile;

class AnalystServiceController extends Controller
{
    public function destroy($id, Request $request)
    {
        $user = $request->user();
        if (! $user) {
            // not authenticated — redirect to login
            return redirect()->route('login');
        }

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