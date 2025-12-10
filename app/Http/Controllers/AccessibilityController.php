<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccessibilityController extends Controller
{
    /**
     * Update the authenticated user's accessibility preferences.
     * Only available for disabled users (role = 'user').
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Only disabled users can update accessibility settings
        if (!$user->isDisabledUser()) {
            abort(403, 'Accessibility settings are only available for users with disabilities.');
        }

        $validated = $request->validate([
            'font_size' => ['required', 'in:small,medium,large'],
            'contrast' => ['required', 'in:normal,high'],
            'high_contrast' => ['nullable', 'boolean'],
            'spacing' => ['required', 'in:normal,wide'],
            'screen_reader' => ['nullable', 'boolean'],
            'reduced_motion' => ['nullable', 'boolean'],
            'keyboard_only' => ['nullable', 'boolean'],
            'large_fonts' => ['nullable', 'boolean'],
        ]);

        $settings = $user->accessibility_settings ?? [];

        $settings['font_size'] = $validated['font_size'];
        $settings['contrast'] = $validated['contrast'];
        $settings['high_contrast'] = (bool) ($validated['high_contrast'] ?? false);
        $settings['spacing'] = $validated['spacing'];
        $settings['screen_reader'] = (bool) ($validated['screen_reader'] ?? false);
        $settings['reduced_motion'] = (bool) ($validated['reduced_motion'] ?? false);
        $settings['keyboard_only'] = (bool) ($validated['keyboard_only'] ?? false);
        $settings['large_fonts'] = (bool) ($validated['large_fonts'] ?? false);

        $user->accessibility_settings = $settings;
        $user->save();

        return back()->with('status', 'Accessibility settings updated.');
    }
}


