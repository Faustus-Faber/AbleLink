<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;   // ✅ THIS LINE WAS MISSING (IMPORTANT)

class AuthController extends Controller
{
    // ✅ Show Login Page
    public function showLogin()
    {
        return view('auth.login');
    }

    // ✅ SIMPLE DEMO LOGIN (NO OTP, NO PASSWORD)
    public function simpleLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // ✅ Find user by email
        $user = User::where('email', $request->email)->first();

        // ✅ If not found → auto-create demo user
        if (!$user) {
            $user = User::create([
                'name' => 'Demo User',
                'email' => $request->email,
                'password' => bcrypt('password'), // dummy password
                'role' => 'user',                  // change to 'admin' if needed
                'disability_type' => 'default'
            ]);
        }

        // ✅ Log the user in
        Auth::login($user);

        // ✅ Redirect to dashboard
        return redirect()->route('dashboard');
    }
}
