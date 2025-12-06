<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Services\Auth\OtpManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function __construct(private readonly OtpManager $otpManager)
    {
    }

    public function create(): View
    {
        return view('auth.register', [
            'roles' => User::COMMUNITY_ROLES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'digits:11'],
            'role' => ['required', 'string', 'in:'.implode(',', User::COMMUNITY_ROLES)],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'role' => $data['role'],
            'password' => Str::random(40),
        ]);

        // F3 - Even Yuvraj Munshi
        $user->profile()->create([
            'phone_number' => $data['phone'],
        ]);

        $code = $this->otpManager->send($user, 'registration');

        return redirect()
            ->route('otp.show', ['email' => $user->email, 'context' => 'registration'])
            ->with('status', 'We have sent a verification code to '.$user->email.'.')
            ->with('joke_otp', $code);
    }
}

