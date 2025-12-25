<?php

namespace App\Http\Controllers\Volunteer;

use App\Http\Controllers\Controller;
use App\Models\Community\AssistanceRequest;
use App\Models\Community\VolunteerMatch;
use App\Models\Community\VolunteerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//F14 - Volunteer Matching System
class AssistanceRequestController extends Controller
{
    public function index(Request $request)
    {
        $volunteer = Auth::user();
        
        if (!$volunteer->hasRole(\App\Models\Auth\User::ROLE_VOLUNTEER)) {
            abort(403, 'Only volunteers can access this page.');
        }

        $profile = VolunteerProfile::where('user_id', $volunteer->id)->first();
        
        // Get available requests that match volunteer's skills and availability
        $query = AssistanceRequest::where('status', 'pending')
            ->whereDoesntHave('matches', function ($q) use ($volunteer) {
                $q->where('volunteer_id', $volunteer->id);
            });

        // Search Logic
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $requests = $query->with(['user.profile'])
            ->latest()
            ->paginate(6); // Pagination set to 6

        return view('volunteer.requests.index', compact('requests', 'profile'));
    }

    public function accept(AssistanceRequest $assistanceRequest)
    {
        $volunteer = Auth::user();
        
        if (!$volunteer->hasRole(\App\Models\Auth\User::ROLE_VOLUNTEER)) {
            abort(403, 'Only volunteers can access this page.');
        }

        // Check if already matched
        if ($assistanceRequest->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'This request is no longer available.');
        }

        // Create match
        $match = VolunteerMatch::create([
            'assistance_request_id' => $assistanceRequest->id,
            'volunteer_id' => $volunteer->id,
            'status' => 'accepted',
            'matched_at' => now(),
        ]);

        // Update request status
        $assistanceRequest->update(['status' => 'matched']);

        return redirect()->route('volunteer.assistance.active')
            ->with('success', 'You have accepted the assistance request!');
    }

    public function decline(AssistanceRequest $assistanceRequest)
    {
        $volunteer = Auth::user();
        
        if (!$volunteer->hasRole(\App\Models\Auth\User::ROLE_VOLUNTEER)) {
            abort(403, 'Only volunteers can access this page.');
        }

        // Create declined match record
        VolunteerMatch::create([
            'assistance_request_id' => $assistanceRequest->id,
            'volunteer_id' => $volunteer->id,
            'status' => 'declined',
        ]);

        return redirect()->back()
            ->with('success', 'Request declined.');
    }

    public function active()
    {
        $volunteer = Auth::user();
        
        if (!$volunteer->hasRole(\App\Models\Auth\User::ROLE_VOLUNTEER)) {
            abort(403, 'Only volunteers can access this page.');
        }

        $matches = VolunteerMatch::where('volunteer_id', $volunteer->id)
            ->whereIn('status', ['accepted'])
            ->with(['assistanceRequest.user.profile'])
            ->latest('matched_at')
            ->get();

        return view('volunteer.assistance.active', compact('matches'));
    }

    public function complete(Request $request, VolunteerMatch $match)
    {
        $volunteer = Auth::user();
        
        if (!$volunteer->hasRole(\App\Models\Auth\User::ROLE_VOLUNTEER) || 
            $match->volunteer_id !== $volunteer->id) {
            abort(403, 'Unauthorized access.');
        }

        try {
            $match->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
    
            $match->assistanceRequest->update(['status' => 'completed']);
            
            return redirect()->back()
                ->with('success', 'Assistance marked as completed!');
                
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function history()
    {
        $volunteer = Auth::user();
        
        if (!$volunteer->hasRole(\App\Models\Auth\User::ROLE_VOLUNTEER)) {
            abort(403, 'Only volunteers can access this page.');
        }

        $matches = VolunteerMatch::where('volunteer_id', $volunteer->id)
            ->whereIn('status', ['completed', 'cancelled'])
            ->with(['assistanceRequest.user.profile'])
            ->latest('completed_at')
            ->paginate(15);

        return view('volunteer.assistance.history', compact('matches'));
    }
}


