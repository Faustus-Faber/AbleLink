<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Services\Auth\OtpManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function __construct(private readonly OtpManager $otpManager)
    {
    }

    public function create(): View
    {
        return view('auth.login');
    }

    public function sendOtp(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $data['email'])
            ->whereIn('role', User::COMMUNITY_ROLES)
            ->first();

        if (! $user) {
            return back()
                ->withErrors(['email' => 'We could not find a community account with that email.'])
                ->withInput();
        }

        $code = $this->otpManager->send($user, 'login');

        return redirect()
            ->route('otp.show', ['email' => $user->email, 'context' => 'login'])
            ->with('status', 'Please check '.$user->email.' for your login code.')
            ->with('joke_otp', $code);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}

