<?php
// F5 - Roza Akter

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApplyAccessibilityPreferences
{

    public function handle(Request $request, Closure $next): Response
    {
        $preferences = [];

        if (Auth::check()) {
            $user = Auth::user();

            if ($user->hasRole(\App\Models\Auth\User::ROLE_DISABLED)) {
                $preferences = session('accessibility_preferences', []);

                if (empty($preferences) && $user->profile && $user->profile->accessibility_preferences) {
                    $preferences = $user->profile->accessibility_preferences;
                    session(['accessibility_preferences' => $preferences]);
                }

                if (! is_array($preferences)) {
                    $preferences = [];
                }
            }
        }

        view()->share('accessibilityPreferences', $preferences);

        return $next($request);
    }
}

