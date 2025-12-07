<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//F3 - Evan Yuvraj Munshi
class AccessibilityController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        
        if (!$user->profile) {
            $user->profile()->create([]);
        }

        return view('accessibility.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'font_size' => 'nullable|in:small,normal,large,extra_large',
            'contrast_mode' => 'nullable|in:normal,high,inverted',
            'spacing' => 'nullable|in:compact,normal,relaxed',
            'screen_reader_enabled' => 'nullable|boolean',
            'text_to_speech_enabled' => 'nullable|boolean',
            'voice_navigation_enabled' => 'nullable|boolean',
            'color_blind_mode' => 'nullable|in:none,protanopia,deuteranopia,tritanopia',
            'animation_reduced' => 'nullable|boolean',
        ]);

        $preferences = [
            'font_size' => $validated['font_size'] ?? 'normal',
            'contrast_mode' => $validated['contrast_mode'] ?? 'normal',
            'spacing' => $validated['spacing'] ?? 'normal',
            'screen_reader_enabled' => $validated['screen_reader_enabled'] ?? false,
            'text_to_speech_enabled' => $validated['text_to_speech_enabled'] ?? false,
            'voice_navigation_enabled' => $validated['voice_navigation_enabled'] ?? false,
            'color_blind_mode' => $validated['color_blind_mode'] ?? 'none',
            'animation_reduced' => $validated['animation_reduced'] ?? false,
        ];

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            ['accessibility_preferences' => $preferences]
        );

        session(['accessibility_preferences' => $preferences]);

        return redirect()->route('accessibility.edit')->with('success', 'Accessibility preferences saved successfully!');
    }

    public function apply()
    {
        $user = Auth::user();
        
        if ($user && $user->profile && $user->profile->accessibility_preferences) {
            session(['accessibility_preferences' => $user->profile->accessibility_preferences]);
        }
    }
}
