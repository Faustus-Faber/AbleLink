<?php

namespace App\Http\Controllers\Caregiver;

use App\Http\Controllers\Controller;
use App\Models\Health\DoctorAppointment;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\Health\AppointmentScheduled;

// F17 - Doctor Appointments Management
class DoctorAppointmentController extends Controller
{
    /**
     * Display the calendar dashboard with all appointments
     */
    public function index()
    {
        $caregiver = Auth::user();
        
        if (!$caregiver->hasRole('caregiver')) {
            abort(403, 'Unauthorized action.');
        }

        $activePatientIds = $caregiver->patients()
            ->wherePivot('status', 'active')
            ->pluck('users.id')
            ->all();

        $appointments = DoctorAppointment::whereIn('user_id', $activePatientIds)
            ->with(['user', 'caregiver'])
            ->orderBy('appointment_date', 'asc')
            ->get();

        $patients = $caregiver->patients()
            ->wherePivot('status', 'active')
            ->get();

        return view('caregiver.appointments.index', compact('appointments', 'patients'));
    }

    /**
     * Show the form for creating a new appointment
     */
    public function create(User $user)
    {
        $caregiver = Auth::user();
        
        $isLinked = $caregiver->patients()
            ->where('user_id', $user->id)
            ->wherePivot('status', 'active')
            ->exists();

        if (!$isLinked) {
            abort(403, 'Unauthorized. You are not linked to this patient.');
        }

        return view('caregiver.appointments.create', compact('user'));
    }

    /**
     * Store a newly created appointment
     */
    public function store(Request $request, User $user)
    {
        $caregiver = Auth::user();
        
        $isLinked = $caregiver->patients()
            ->where('user_id', $user->id)
            ->wherePivot('status', 'active')
            ->exists();

        if (!$isLinked) {
            abort(403, 'Unauthorized. You are not linked to this patient.');
        }

        $validated = $request->validate([
            'doctor_name' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'clinic_name' => 'nullable|string|max:255',
            'clinic_address' => 'nullable|string|max:500',
            'contact_phone' => 'nullable|string|max:20',
            'appointment_date' => 'required|date|after:now',
            'appointment_time' => 'required|date_format:H:i',
            'reason' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:2000',
        ]);

        $appointmentDateTime = $validated['appointment_date'] . ' ' . $validated['appointment_time'];

        $appointment = DoctorAppointment::create([
            'user_id' => $user->id,
            'caregiver_id' => $caregiver->id,
            'doctor_name' => $validated['doctor_name'],
            'specialization' => $validated['specialization'] ?? null,
            'clinic_name' => $validated['clinic_name'] ?? null,
            'clinic_address' => $validated['clinic_address'] ?? null,
            'contact_phone' => $validated['contact_phone'] ?? null,
            'appointment_date' => $appointmentDateTime,
            'reason' => $validated['reason'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => 'scheduled',
        ]);

        // F17 - Roza Akter
        $appointment->load(['user', 'caregiver']);

        try {
            $appointment->user->notify(new AppointmentScheduled($appointment));
        } catch (\Exception $e) {
        }

        return redirect()->route('caregiver.appointments.index')
            ->with('success', 'Appointment scheduled successfully.');
    }

    /**
     * Show the form for editing an appointment
     */
    public function edit(DoctorAppointment $appointment)
    {
        $caregiver = Auth::user();
        
        $isLinked = $caregiver->patients()
            ->where('user_id', $appointment->user_id)
            ->wherePivot('status', 'active')
            ->exists();

        if (!$isLinked || $appointment->caregiver_id !== $caregiver->id) {
            abort(403, 'Unauthorized.');
        }

        return view('caregiver.appointments.edit', compact('appointment'));
    }

    /**
     * Update an appointment
     */
    public function update(Request $request, DoctorAppointment $appointment)
    {
        $caregiver = Auth::user();
        
        $isLinked = $caregiver->patients()
            ->where('user_id', $appointment->user_id)
            ->wherePivot('status', 'active')
            ->exists();

        if (!$isLinked || $appointment->caregiver_id !== $caregiver->id) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'doctor_name' => 'required|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'clinic_name' => 'nullable|string|max:255',
            'clinic_address' => 'nullable|string|max:500',
            'contact_phone' => 'nullable|string|max:20',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'reason' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:2000',
            'status' => 'required|in:scheduled,completed,cancelled',
        ]);

        $appointmentDateTime = $validated['appointment_date'] . ' ' . $validated['appointment_time'];

        $appointment->update([
            'doctor_name' => $validated['doctor_name'],
            'specialization' => $validated['specialization'] ?? null,
            'clinic_name' => $validated['clinic_name'] ?? null,
            'clinic_address' => $validated['clinic_address'] ?? null,
            'contact_phone' => $validated['contact_phone'] ?? null,
            'appointment_date' => $appointmentDateTime,
            'reason' => $validated['reason'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()->route('caregiver.appointments.index')
            ->with('success', 'Appointment updated successfully.');
    }

    /**
     * Delete an appointment
     */
    public function destroy(DoctorAppointment $appointment)
    {
        $caregiver = Auth::user();
        
        $isLinked = $caregiver->patients()
            ->where('user_id', $appointment->user_id)
            ->wherePivot('status', 'active')
            ->exists();

        if (!$isLinked || $appointment->caregiver_id !== $caregiver->id) {
            abort(403, 'Unauthorized.');
        }

        $appointment->delete();

        return redirect()->route('caregiver.appointments.index')
            ->with('success', 'Appointment deleted successfully.');
    }

    /**
     * Get appointments as JSON for calendar
     */
    public function getCalendarData()
    {
        $caregiver = Auth::user();
        
        if (!$caregiver->hasRole('caregiver')) {
            abort(403, 'Unauthorized action.');
        }

        $activePatientIds = $caregiver->patients()
            ->wherePivot('status', 'active')
            ->pluck('users.id')
            ->all();

        $appointments = DoctorAppointment::whereIn('user_id', $activePatientIds)
            ->with(['user'])
            ->get()
            ->map(function ($appointment) {
                $start = $appointment->appointment_date;
                $end = $appointment->appointment_date->copy()->addHour();
                
                return [
                    'id' => $appointment->id,
                    'title' => $appointment->doctor_name . ' - ' . $appointment->user->name,
                    'start' => $start->format('Y-m-d\TH:i:s'),
                    'end' => $end->format('Y-m-d\TH:i:s'),
                    'color' => $appointment->status === 'completed' ? '#10b981' : 
                              ($appointment->status === 'cancelled' ? '#ef4444' : '#3b82f6'),
                    'extendedProps' => [
                        'patient' => $appointment->user->name,
                        'clinic' => $appointment->clinic_name,
                        'status' => $appointment->status,
                    ]
                ];
            });

        return response()->json($appointments);
    }
}

