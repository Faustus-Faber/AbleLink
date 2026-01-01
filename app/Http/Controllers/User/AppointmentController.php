<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Health\DoctorAppointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// F17 - User Doctor Appointments View
class AppointmentController extends Controller
{
    /**
     * Display the appointments calendar for the authenticated user
     */
    public function index()
    {
        $user = Auth::user();
        
        $appointments = DoctorAppointment::where('user_id', $user->id)
            ->with(['caregiver'])
            ->orderBy('appointment_date', 'asc')
            ->get();

        return view('user.appointments.index', compact('appointments'));
    }

    /**
     * Get appointments as JSON for calendar
     */
    public function getCalendarData()
    {
        $user = Auth::user();

        $appointments = DoctorAppointment::where('user_id', $user->id)
            ->with(['caregiver'])
            ->get()
            ->map(function ($appointment) {
                $start = $appointment->appointment_date;
                $end = $appointment->appointment_date->copy()->addHour();
                
                return [
                    'id' => $appointment->id,
                    'title' => $appointment->doctor_name . ($appointment->specialization ? ' - ' . $appointment->specialization : ''),
                    'start' => $start->format('Y-m-d\TH:i:s'),
                    'end' => $end->format('Y-m-d\TH:i:s'),
                    'color' => $appointment->status === 'completed' ? '#10b981' : 
                              ($appointment->status === 'cancelled' ? '#ef4444' : '#3b82f6'),
                    'extendedProps' => [
                        'doctor' => $appointment->doctor_name,
                        'specialization' => $appointment->specialization,
                        'clinic' => $appointment->clinic_name,
                        'caregiver' => $appointment->caregiver ? $appointment->caregiver->name : null,
                        'status' => $appointment->status,
                        'reason' => $appointment->reason,
                        'notes' => $appointment->notes,
                    ]
                ];
            });

        return response()->json($appointments);
    }
}

