<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AnalystProfile;
use App\Models\ClientProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:ANALYST,CLIENT',
            'phone' => 'nullable|string|max:20',
            'years_of_experience' => 'required_if:role,ANALYST|nullable|integer|min:0',
            'client_type' => 'required_if:role,CLIENT|in:INDIVIDUAL,COMPANY',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $user = User::create([
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'password_hash' => Hash::make($request->input('password')),
                'phone' => $request->input('phone'),
                'role' => $request->input('role'),
            ]);

            if ($user->role === 'ANALYST') {
                AnalystProfile::create([
                    'user_id' => $user->user_id,
                    'full_name' => $user->username,
                    'status' => 'available',
                    'years_of_experience' => $request->input('years_of_experience', 0),
                    'description' => null,
                    'skills' => [],
                    'average_rating' => 0.00,
                ]);
            } else {
                ClientProfile::create([
                    'client_id' => $user->user_id,
                    'type' => $request->input('client_type', 'INDIVIDUAL'),
                    'company_name' => null,
                    'industry' => null,
                ]);
            }

            Auth::login($user);
            return $this->redirectByRole($user->role);
        } catch (\Throwable $e) {
            return back()->withInput()->withErrors(['general' => $e->getMessage()]);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'password' => 'required|string',
        ]);

        $identifier = $request->input('identifier');
        $password = $request->input('password');

        // Try email first if it looks like an email
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            if (Auth::attempt(['email' => $identifier, 'password' => $password])) {
                $request->session()->regenerate();
                return $this->redirectByRole(Auth::user()->role);
            }
        } else if (ctype_digit($identifier)) {
            // Login via user_id
            $user = User::where('user_id', (int) $identifier)->first();
            if ($user && Hash::check($password, $user->password_hash)) {
                Auth::login($user);
                $request->session()->regenerate();
                return $this->redirectByRole($user->role);
            }
        }

        return back()->withErrors([
            'identifier' => 'Invalid credentials.',
        ])->onlyInput('identifier');
    }

    private function redirectByRole(string $role)
    {
        if ($role === 'ANALYST') {
            $user = Auth::user();
            $hasProfile = \App\Models\AnalystProfile::where('user_id', optional($user)->user_id)->exists();
            return $hasProfile
                ? redirect()->intended(route('analyst.dashboard'))
                : redirect()->intended(route('analysts.profile'));
        }
        // Default to client
        return redirect()->intended(route('analysts.index'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
