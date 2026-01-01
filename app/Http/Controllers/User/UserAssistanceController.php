<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Community\AssistanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//F14 - Roza Akter
class UserAssistanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requests = Auth::user()->assistanceRequests()->latest()->paginate(10);
        return view('user.assistance.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.assistance.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string|in:transportation,companionship,errands,technical_support,medical_assistance,other',
            'urgency' => 'required|string|in:low,medium,high,emergency',
            'location' => 'required|string|max:255',
            'preferred_date_time' => 'required|date|after:now',
            'special_requirements' => 'nullable|string',
        ]);

        $validated['status'] = 'pending';
        $validated['user_id'] = Auth::id();

        AssistanceRequest::create($validated);

        return redirect()->route('user.assistance.index')
            ->with('success', 'Assistance request created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AssistanceRequest $assistanceRequest)
    {
        $user = Auth::user();
        
        if ($assistanceRequest->user_id !== $user->id) {
            $isLinkedCaregiver = false;
            if ($user->hasRole('caregiver')) {
                 $isLinkedCaregiver = $user->patients()
                    ->where('users.id', $assistanceRequest->user_id)
                    ->wherePivot('status', 'active')
                    ->exists();
            }

            if (!$isLinkedCaregiver) {
                abort(403);
            }
        }

        return view('user.assistance.show', ['request' => $assistanceRequest]);
    }
}

