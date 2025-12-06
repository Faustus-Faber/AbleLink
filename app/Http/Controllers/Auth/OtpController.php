<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Services\Auth\OtpManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

//F1 - Akida Lisi
class OtpController extends Controller
{
    public function __construct(private readonly OtpManager $otpManager)
    {
    }

    public function show(Request $request): View
    {
        return view('auth.otp', [
            'email' => $request->query('email', ''),
            'context' => $request->query('context', 'login'),
        ]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'digits:6'],
            'context' => ['required', 'in:registration,login'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || $user->isAdmin()) {
            return back()
                ->withErrors(['email' => 'We cannot find that account.'])
                ->withInput();
        }

             $otp = $this->otpManager->verify($user, $data['code'], $data['context']);

        if (! $otp) {
            return back()
                ->withErrors(['code' => 'That code is invalid or has expired.'])
                ->withInput();
        }

        $user->forceFill([
            'otp_verified_at' => $user->otp_verified_at ?? now(),
            'email_verified_at' => $user->email_verified_at ?? now(),
            'last_login_at' => now(),
        ])->save();

        Auth::login($user);

        //F4 - Farhan Zarif
        if ($user->hasRole('caregiver')) {
            return redirect()->route('caregiver.dashboard');
        }


        //F4 - Farhan Zarif
        if ($user->hasRole('user') || $user->hasRole('disabled')) {
            $hasPendingRequests = $user->caregivers()->wherePivot('status', 'pending')->exists();
            if ($hasPendingRequests) {
                return redirect()->route('user.requests');
            }
        }

        return redirect()->intended(route('dashboard'));
    }

    public function resend(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'context' => ['nullable', 'in:registration,login'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || $user->isAdmin()) {
            return back()
                ->withErrors(['email' => 'We cannot find that account.'])
                ->withInput();
        }

        $context = $data['context'] ?? 'login';

        $this->otpManager->send($user, $context);

        return back()->with('status', 'A new code has been sent to '.$user->email.'.');
    }
}

