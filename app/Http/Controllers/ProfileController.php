<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Show the user profile page.
     */
    public function show()
    {
        $user = Auth::user();
        
        // Only disabled users can access profile with accessibility settings
        if ($user->isDisabledUser()) {
            return view('profile.user', compact('user'));
        }

        // Other roles see a simpler profile
        return view('profile.general', compact('user'));
    }

    /**
     * Update user profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'skills' => ['nullable', 'string'],
            'disability_type' => ['nullable', 'string', 'max:255'],
        ]);

        // Update user fields
        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'disability_type' => $validated['disability_type'] ?? $user->disability_type,
        ]);

        // Process skills from comma-separated string
        $skillsArray = null;
        if (!empty($validated['skills'])) {
            $skillsArray = array_map('trim', explode(',', $validated['skills']));
            $skillsArray = array_filter($skillsArray); // Remove empty values
            $skillsArray = array_values($skillsArray); // Reindex
        }

        // Update or create profile
        $profile = $user->profile ?? new UserProfile(['user_id' => $user->id]);
        $profile->fill([
            'bio' => $validated['bio'] ?? null,
            'skills' => $skillsArray,
        ]);
        $profile->save();

        return back()->with('status', 'Profile updated successfully.');
    }
}

