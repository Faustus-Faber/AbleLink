<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;
use App\Models\Community\MatrimonyProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//F16 - Evan Yuvraj Munshi
class MatrimonyController extends Controller
{
    public function index(Request $request)
    {
        $query = MatrimonyProfile::with('user')
            ->where(function($q) {
                $q->where('privacy_level', 'public')
                  ->orWhere('user_id', Auth::id()); 
            });

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('bio', 'like', "%{$search}%")
                  ->orWhere('occupation', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQ) use ($search) {
                      $userQ->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('age_min')) {
            $query->where('age', '>=', $request->age_min);
        }

        if ($request->filled('age_max')) {
            $query->where('age', '<=', $request->age_max);
        }

        $profiles = $query->paginate(12);
        
        $myProfile = MatrimonyProfile::where('user_id', Auth::id())->first();

        return view('community.matrimony.index', compact('profiles', 'myProfile'));
    }

    public function create()
    {
        $profile = Auth::user()->matrimonyProfile;
        if ($profile) {
            return redirect()->route('community.matrimony.edit', $profile);
        }
        return view('community.matrimony.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->matrimonyProfile) {
            return redirect()->route('community.matrimony.edit', Auth::user()->matrimonyProfile)
                ->with('error', 'You already have a profile. You can edit it here.');
        }

        $validated = $request->validate([
            'bio' => 'nullable|string',
            'gender' => 'nullable|string|in:Male,Female,Other',
            'age' => 'nullable|integer|min:18|max:100',
            'occupation' => 'nullable|string',
            'education' => 'nullable|string',
            'marital_status' => 'nullable|string',
            'religion' => 'nullable|string',
            'partner_preferences' => 'nullable|string',
            'hobbies' => 'nullable|array',
            'photo' => 'nullable|image|max:2048', // 2MB Max
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('matrimony-photos', 'public');
            $validated['photo_path'] = $path;
        }

        $profile = new MatrimonyProfile($validated);
        $profile->user_id = Auth::id();
        $profile->privacy_level = 'public'; 
        $profile->save();

        return redirect()->route('community.matrimony.index')->with('success', 'Profile created successfully.');
    }

    public function edit()
    {
        $profile = MatrimonyProfile::where('user_id', Auth::id())->first();
        if (!$profile) {
            return redirect()->route('community.matrimony.create');
        }
        return view('community.matrimony.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $profile = MatrimonyProfile::where('user_id', Auth::id())->first();
        
        if (!$profile) {
            return redirect()->route('community.matrimony.create');
        }
        
        $validated = $request->validate([
            'bio' => 'nullable|string',
            'gender' => 'nullable|string|in:Male,Female,Other',
            'age' => 'nullable|integer|min:18|max:100',
            'occupation' => 'nullable|string',
            'education' => 'nullable|string',
            'marital_status' => 'nullable|string',
            'religion' => 'nullable|string',
            'partner_preferences' => 'nullable|string',
            'hobbies' => 'nullable|array',
        ]);

        $validated['privacy_level'] = 'public'; 

        $profile->update($validated);

        return redirect()->route('community.matrimony.index')->with('success', 'Profile updated successfully.');
    }

    public function show(MatrimonyProfile $matrimony)
    {
        $matrimony->load('user');
        return view('community.matrimony.show', ['profile' => $matrimony]);
    }
}

