<?php

namespace App\Http\Controllers\Caregiver;

use App\Http\Controllers\Controller;

use App\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//F4 - Farhan Zarif
class ConnectionController extends Controller
{
    //F4 - Farhan Zarif
    public function index(): \Illuminate\View\View
    {
        $currentUser = Auth::user();

        $caregiversRelation = $currentUser->caregivers();
        $pendingRequestsQuery = $caregiversRelation->wherePivot('status', 'pending');
        $pendingRequestsList = $pendingRequestsQuery->get();

        return view('user.requests', ['requests' => $pendingRequestsList]);
    }

    //F4 - Farhan Zarif
    public function approve(User $caregiver): \Illuminate\Http\RedirectResponse
    {
        $currentUser = Auth::user();

        $caregiversRelation = $currentUser->caregivers();
        $specificCaregiverQuery = $caregiversRelation->where('caregiver_id', $caregiver->id);
        $pendingRequestQuery = $specificCaregiverQuery->wherePivot('status', 'pending');
        $hasPendingRequest = $pendingRequestQuery->exists();

        if ($hasPendingRequest === false) {
            abort(404, 'Request not found.');
        }

        $allCaregiversRelation = $currentUser->caregivers();
        $activeCaregiversQuery = $allCaregiversRelation->wherePivot('status', 'active');
        $hasActiveCaregiver = $activeCaregiversQuery->exists();
        
        if ($hasActiveCaregiver) {
            $redirector = redirect();
            $backResponse = $redirector->back();
            $responseWithError = $backResponse->with('error', 'You already have an active caregiver. Please unlink them first.');
            
            return $responseWithError;
        }

        $updateRelation = $currentUser->caregivers();
        $updateRelation->updateExistingPivot($caregiver->id, ['status' => 'active']);

        $redirector = redirect();
        $dashboardRoute = $redirector->route('dashboard');
        $responseWithSuccess = $dashboardRoute->with('success', 'Caregiver approved.');
        
        return $responseWithSuccess;
    }

    //F4 - Farhan Zarif
    public function deny(User $caregiver): \Illuminate\Http\RedirectResponse
    {
        $currentUser = Auth::user();
        $caregiversRelation = $currentUser->caregivers();
        $caregiversRelation->detach($caregiver->id);

        $redirector = redirect();
        $backResponse = $redirector->back();
        $responseWithSuccess = $backResponse->with('success', 'Request declined.');
        
        return $responseWithSuccess;
    }
}

