<?php

namespace App\Http\Controllers\Caregiver;

use App\Http\Controllers\Controller;

use App\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Schema;
use App\Models\Emergency\EmergencySosEvent; 
use App\Models\Auth\UserProfile;
use App\Models\Health\MedicationSchedule;
use App\Models\Health\HealthGoal;


class CaregiverController extends Controller
{
    // F19 - Evan Yuvraj Munshi
    public function manageHealth(User $user)
    {
        $caregiver = Auth::user();

        $isLinked = $caregiver->patients()
                            ->where('user_id', $user->id)
                            ->wherePivot('status', 'active')
                            ->exists();

        if (!$isLinked) {
            abort(403, 'Unauthorized.');
        }

        if (!$user->profile) {
            $user->profile()->create([]);
        }

        $medications = MedicationSchedule::where('user_id', $user->id)->where('is_active', true)->get();
        $goals = HealthGoal::where('user_id', $user->id)->where('status', 'active')->get();

        return view('health.caregiver.manage', compact('user', 'medications', 'goals'));
    }

    public function updateDiagnosis(Request $request, User $user)
    {
        $caregiver = Auth::user();

        $isLinked = $caregiver->patients()
                            ->where('user_id', $user->id)
                            ->wherePivot('status', 'active')
                            ->exists();

        if (!$isLinked) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'diagnosis' => 'nullable|string|max:1000',
            'medical_history' => 'nullable|string|max:5000',
        ]);

        $user->profile()->update([
            'diagnosis' => $validated['diagnosis'],
            'medical_history' => $validated['medical_history'],
        ]);

        return redirect()->back()->with('success', 'Health profile updated successfully.');
    }

    //F4 - Farhan Zarif
    public function index()
    {
        $caregiver = Auth::user();
        
        if (!$caregiver->hasRole('caregiver')) {
            abort(403, 'Unauthorized action.');
        }

        $patients = $caregiver->patients;
        
        // F4 - Farhan Zarif
        $caregiverPatients = $caregiver->patients;
        $pendingRequestsCount = 0;
        foreach ($caregiverPatients as $currentPatient) {
            $currentPivot = $currentPatient->pivot;
            if ($currentPivot->status === 'pending') {
                $pendingRequestsCount = $pendingRequestsCount + 1;
            }
        }

        // F15 - Akida Lisi
        $activePatientIds = $caregiver->patients()
            ->wherePivot('status', 'active')
            ->pluck('users.id')
            ->all();

        $sosAlerts = empty($activePatientIds)
            ? collect()
            : (Schema::hasTable('emergency_sos_events') ? EmergencySosEvent::query()
                ->whereNull('resolved_at')
                ->whereIn('user_id', $activePatientIds)
                ->with(['user.profile'])
                ->latest()
                ->take(10)
                ->get() : collect());

        // F17 - Roza Akter
        $appointmentsCount = empty($activePatientIds)
            ? 0
            : (Schema::hasTable('doctor_appointments') ? \App\Models\Health\DoctorAppointment::whereIn('user_id', $activePatientIds)
                ->where('status', 'scheduled')
                ->count() : 0);

        return view('caregiver.dashboard', compact('patients', 'sosAlerts', 'pendingRequestsCount', 'appointmentsCount'));
    }

    //F4 - Farhan Zarif
    public function sendRequest(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $currentCaregiver = Auth::user();
        $targetUserEmail = $request->email;
        $userQuery = User::where('email', $targetUserEmail);
        $targetUser = $userQuery->first();

        $isNormalUser = $targetUser->hasRole('user');
        $isDisabledUser = $targetUser->hasRole('disabled');
        
        if ($isNormalUser === false) {
            if ($isDisabledUser === false) {
                 throw ValidationException::withMessages([
                    'email' => 'This user is not registered as a person with disability.',
                ]);
            }
        }

        $caregiverPatientsRelation = $currentCaregiver->patients();
        $existingRequestQuery = $caregiverPatientsRelation->where('user_id', $targetUser->id);
        $requestAlreadyExists = $existingRequestQuery->exists();
        
        if ($requestAlreadyExists) {
             throw ValidationException::withMessages([
                'email' => 'You have already sent a request to this user.',
            ]);
        }

        $targetUserCaregiversRelation = $targetUser->caregivers();
        $activeCaregiverQuery = $targetUserCaregiversRelation->wherePivot('status', 'active');
        $hasActiveCaregiver = $activeCaregiverQuery->exists();

        if ($hasActiveCaregiver) {
             throw ValidationException::withMessages([
                'email' => 'This user already has a linked caregiver.',
            ]);
        }

        $caregiverPatientsRelation = $currentCaregiver->patients();
        $caregiverPatientsRelation->attach($targetUser->id, ['status' => 'pending']);

        $redirectResponse = redirect()->back();
        $redirectResponseWithSuccess = $redirectResponse->with('success', 'Connection request sent to ' . $targetUser->name);
        
        return $redirectResponseWithSuccess;
    }

    //F4 - Farhan Zarif
    public function editPatient(User $user): \Illuminate\View\View
    {
        $currentCaregiver = Auth::user();
        
        $caregiverPatientsRelation = $currentCaregiver->patients();
        $patientLinkQuery = $caregiverPatientsRelation->where('user_id', $user->id);
        $activeLinkQuery = $patientLinkQuery->wherePivot('status', 'active');
        $isLinked = $activeLinkQuery->exists();

        if ($isLinked === false) {
            abort(403, 'You are not linked to this patient.');
        }

        $patientProfile = $user->profile;
        if ($patientProfile === null) {
            $userProfileRelation = $user->profile();
            $userProfileRelation->create([]);
        }

        return view('caregiver.patient-edit', compact('user'));
    }

    //F4 - Farhan Zarif
    public function updatePatient(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        $currentCaregiver = Auth::user();
        
        $caregiverPatientsRelation = $currentCaregiver->patients();
        $patientLinkQuery = $caregiverPatientsRelation->where('user_id', $user->id);
        $activeLinkQuery = $patientLinkQuery->wherePivot('status', 'active');
        $isLinked = $activeLinkQuery->exists();

        if ($isLinked === false) {
            abort(403, 'Unauthorized.');
        }

        $validatedData = $request->validate([
            'bio' => 'nullable|string|max:1000',
            'disability_type' => 'nullable|string|max:255',
            'phone_number' => 'nullable|regex:/^[0-9]+$/|max:20',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date|before:today',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|regex:/^[0-9]+$/|max:20',
            'accessibility_preferences' => 'nullable|array',
        ]);

        $updatePayload = [
            'bio' => $validatedData['bio'],
            'disability_type' => null,
            'phone_number' => null,
            'address' => null,
            'date_of_birth' => null,
            'emergency_contact_name' => null,
            'emergency_contact_phone' => null,
            'accessibility_preferences' => [],
        ];

        if (array_key_exists('disability_type', $validatedData)) {
            $updatePayload['disability_type'] = $validatedData['disability_type'];
        }
        if (array_key_exists('phone_number', $validatedData)) {
            $updatePayload['phone_number'] = $validatedData['phone_number'];
        }
        if (array_key_exists('address', $validatedData)) {
             $updatePayload['address'] = $validatedData['address'];
        }
        if (array_key_exists('date_of_birth', $validatedData)) {
            $updatePayload['date_of_birth'] = $validatedData['date_of_birth'];
        }
        if (array_key_exists('emergency_contact_name', $validatedData)) {
            $updatePayload['emergency_contact_name'] = $validatedData['emergency_contact_name'];
        }
        if (array_key_exists('emergency_contact_phone', $validatedData)) {
            $updatePayload['emergency_contact_phone'] = $validatedData['emergency_contact_phone'];
        }
        if (array_key_exists('accessibility_preferences', $validatedData)) {
            $updatePayload['accessibility_preferences'] = $validatedData['accessibility_preferences'];
        }

        $userProfileRelation = $user->profile();
        $searchConditions = ['user_id' => $user->id];
        
        $userProfileRelation->updateOrCreate($searchConditions, $updatePayload);

        $redirectRoute = redirect()->route('caregiver.dashboard');
        $redirectRouteWithSuccess = $redirectRoute->with('success', 'Patient profile updated successfully.');

        return $redirectRouteWithSuccess;
    }

    //F4 - Farhan Zarif
    public function unlink(User $user): \Illuminate\Http\RedirectResponse
    {
        $currentCaregiver = Auth::user();
        $caregiverPatients = $currentCaregiver->patients();
        $caregiverPatients->detach($user->id);

        $redirectResponse = redirect()->back();
        $successResponse = $redirectResponse->with('success', 'Patient removed.');
        
        return $successResponse;
    }
    
    public function resolveSos(EmergencySosEvent $event)
    {
        $caregiver = Auth::user();
        
        $isLinked = $caregiver->patients()
                            ->where('user_id', $event->user_id)
                            ->wherePivot('status', 'active')
                            ->exists();

        if (!$isLinked) {
            abort(403, 'Unauthorized. You are not linked to this patient.');
        }

        $event->update([
            'resolved_at' => now(),
            'resolution_notes' => 'Marked safe by caregiver ' . $caregiver->name
        ]);

        return redirect()->back()->with('success', 'SOS alert marked as safe.');
    }
}
