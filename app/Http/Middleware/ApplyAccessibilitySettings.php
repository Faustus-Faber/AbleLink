<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ApplyAccessibilitySettings
{
    /**
     * Apply accessibility settings only to disabled users.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Only apply accessibility settings to disabled users (role = 'user')
        if ($user && $user->isDisabledUser()) {
            $settings = $user->accessibility_settings ?? [
                'font_size' => 'medium',
                'contrast' => 'normal',
                'high_contrast' => false,
                'spacing' => 'normal',
                'screen_reader' => false,
                'reduced_motion' => false,
                'keyboard_only' => false,
                'large_fonts' => false,
            ];

            View::share('accessibility_settings', $settings);
            View::share('is_accessible_ui', true);
        } else {
            // Other roles get normal UI
            View::share('accessibility_settings', null);
            View::share('is_accessible_ui', false);
        }

        return $next($request);
    }
}





