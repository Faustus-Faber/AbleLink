<?php
// F5 - Rifat Jahan Roza

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApplyAccessibilityPreferences
{
    /**
     * Bootstrap the accessibility preferences for the current request.
     *
     * This middleware:
     * - Ensures the authenticated user's accessibility_preferences are stored in session
     * - Shares them with all Blade views via $accessibilityPreferences
     */
    public function handle(Request $request, Closure $next): Response
    {
        $preferences = [];

        if (Auth::check()) {
            $user = Auth::user();

            // ✅ Apply adaptive UI only for disabled users
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

