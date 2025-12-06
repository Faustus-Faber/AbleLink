<?php

namespace App\Services\Auth;

use App\Mail\Auth\OtpCodeMail;
use App\Models\Auth\OtpCode;
use App\Models\Auth\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class OtpManager
{
    public function __construct(private readonly int $ttlMinutes = 10)
    {
    }

    public function send(User $user, string $context = 'login'): string
    {
        $code = (string) random_int(100000, 999999);

        $user->otpCodes()->create([
            'context' => $context,
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes($this->ttlMinutes),
        ]);

        Mail::to($user->email)->send(new OtpCodeMail($code, $context, $this->ttlMinutes));

        return $code;
    }

    public function verify(User $user, string $code, string $context = 'login'): ?OtpCode
    {
        /** @var OtpCode|null $otp */
        $otp = $user->otpCodes()
            ->where('context', $context)
            ->active()
            ->latest()
            ->first();

        if (! $otp || ! Hash::check($code, $otp->code_hash)) {
            return null;
        }

        $otp->markUsed();

        return $otp;
    }
}

