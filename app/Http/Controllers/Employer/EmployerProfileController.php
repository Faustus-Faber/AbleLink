<?php
// F10 - Rifat Jahan Roza
//F10 - Rifat Jahan Roza

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Employment\EmployerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//F10 - Employer Job Posting & Dashboard - Company Profile
class EmployerProfileController extends Controller
{
    public function show()
    {
        $employer = Auth::user();
        
        if (!$employer->hasRole(\App\Models\Auth\User::ROLE_EMPLOYER)) {
            abort(403, 'Only employers can access this page.');
        }

        $profile = EmployerProfile::firstOrCreate(['user_id' => $employer->id], [
            'company_name' => $employer->name . "'s Company",
        ]);

        return view('employer.profile.show', compact('profile'));
    }

    public function edit()
    {
        $employer = Auth::user();
        
        if (!$employer->hasRole(\App\Models\Auth\User::ROLE_EMPLOYER)) {
            abort(403, 'Only employers can access this page.');
        }

        $profile = EmployerProfile::firstOrCreate(['user_id' => $employer->id], [
            'company_name' => $employer->name . "'s Company",
        ]);

        return view('employer.profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $employer = Auth::user();
        
        if (!$employer->hasRole(\App\Models\Auth\User::ROLE_EMPLOYER)) {
            abort(403, 'Only employers can access this page.');
        }

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_description' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'industry' => 'nullable|string|max:255',
            'company_size' => 'nullable|integer|min:1',
            'wheelchair_accessible_office' => 'boolean',
            'sign_language_available' => 'boolean',
            'assistive_technology_support' => 'boolean',
            'accessibility_accommodations' => 'nullable|string',
            'inclusive_hiring_practices' => 'nullable|string',
        ]);

        $validated['wheelchair_accessible_office'] = $request->has('wheelchair_accessible_office');
        $validated['sign_language_available'] = $request->has('sign_language_available');
        $validated['assistive_technology_support'] = $request->has('assistive_technology_support');

        EmployerProfile::updateOrCreate(
            ['user_id' => $employer->id],
            $validated
        );

        return redirect()->route('employer.profile.show')
            ->with('success', 'Company profile updated successfully!');
    }
}


