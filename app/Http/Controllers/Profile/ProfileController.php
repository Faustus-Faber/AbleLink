<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;

use App\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

//F3 - Evan Yuvraj Munshi
class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        
        if (!$user->profile) {
            $user->profile()->create([]);
        }

        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        
        if (!$user->profile) {
            $user->profile()->create([]);
        }

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        \Illuminate\Support\Facades\Log::info('Profile Update Request Info:', [
            'has_avatar' => $request->hasFile('avatar'),
        ]);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|max:20',
            'bio' => 'nullable|string|max:1000',
            'disability_type' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date|before:today',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|max:20',
        ];

        if ($request->hasFile('avatar')) {
            $rules['avatar'] = 'image|mimes:jpeg,png,jpg,gif|max:51200'; 
        }

        $validated = $request->validate($rules);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone_number'] ?? $user->phone, 
        ]);

        $profile = $user->profile ?? new \App\Models\Auth\UserProfile(['user_id' => $user->id]);
        
        $profile->phone_number = $validated['phone_number'] ?? null;
        $profile->bio = $validated['bio'] ?? null;
        $profile->disability_type = $validated['disability_type'] ?? null;
        $profile->address = $validated['address'] ?? null;
        $profile->date_of_birth = $validated['date_of_birth'] ?? null;
        $profile->emergency_contact_name = $validated['emergency_contact_name'] ?? null;
        $profile->emergency_contact_phone = $validated['emergency_contact_phone'] ?? null;
        
        if ($request->hasFile('avatar')) {
             \Illuminate\Support\Facades\Log::info('Avatar upload detected.');
             if ($request->file('avatar')->isValid()) {
                 \Illuminate\Support\Facades\Log::info('Avatar is valid.', [
                     'original_name' => $request->file('avatar')->getClientOriginalName(),
                     'mime' => $request->file('avatar')->getMimeType(),
                     'size' => $request->file('avatar')->getSize()
                 ]);
                 
                 if ($profile->avatar) {
                    Storage::disk('public')->delete($profile->avatar);
                }
                $path = $request->file('avatar')->store('avatars', 'public');
                \Illuminate\Support\Facades\Log::info('Avatar stored at: ' . $path);
                $profile->avatar = $path;
             } else {
                 \Illuminate\Support\Facades\Log::error('Avatar file is invalid.');
             }
        } else {
            \Illuminate\Support\Facades\Log::info('No avatar file in request.');
        }

        $profile->save();

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            if ($user->profile && $user->profile->avatar) {
                Storage::disk('public')->delete($user->profile->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');

            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                ['avatar' => $path]
            );
        }

        return redirect()->route('profile.show')->with('success', 'Avatar updated successfully!');
    }
}


