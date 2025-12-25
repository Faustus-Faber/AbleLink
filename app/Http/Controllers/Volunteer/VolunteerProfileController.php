<?php

namespace App\Http\Controllers\Volunteer;

use App\Http\Controllers\Controller;
use App\Models\Community\VolunteerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//F14 - Volunteer Matching System
class VolunteerProfileController extends Controller
{
    public function show()
    {
        $volunteer = Auth::user();
        
        if (!$volunteer->hasRole(\App\Models\Auth\User::ROLE_VOLUNTEER)) {
            abort(403, 'Only volunteers can access this page.');
        }

        $profile = VolunteerProfile::firstOrCreate(['user_id' => $volunteer->id], [
            'skills' => [],
            'availability' => [],
        ]);

        return view('volunteer.profile.show', compact('profile'));
    }

    public function edit()
    {
        $volunteer = Auth::user();
        
        if (!$volunteer->hasRole(\App\Models\Auth\User::ROLE_VOLUNTEER)) {
            abort(403, 'Only volunteers can access this page.');
        }

        $profile = VolunteerProfile::firstOrCreate(['user_id' => $volunteer->id], [
            'skills' => [],
            'availability' => [],
        ]);

        return view('volunteer.profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $volunteer = Auth::user();
        
        if (!$volunteer->hasRole(\App\Models\Auth\User::ROLE_VOLUNTEER)) {
            abort(403, 'Only volunteers can access this page.');
        }

        $validated = $request->validate([
            'bio' => 'nullable|string',
            'skills' => 'nullable|array',
            'skills.*' => 'string',
            'availability' => 'nullable|array',
            'availability.*' => 'string',
            'location' => 'nullable|string|max:255',
            'max_distance_km' => 'nullable|integer|min:1|max:100',
            'available_for_emergency' => 'boolean',
            'specializations' => 'nullable|string',
        ]);

        $validated['available_for_emergency'] = $request->has('available_for_emergency');

        VolunteerProfile::updateOrCreate(
            ['user_id' => $volunteer->id],
            $validated
        );

        return redirect()->route('volunteer.profile.show')
            ->with('success', 'Volunteer profile updated successfully!');
    }
}



