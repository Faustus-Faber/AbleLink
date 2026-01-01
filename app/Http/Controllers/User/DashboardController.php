<?php
//F10 - Rifat Jahan Roza

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use App\Models\Auth\User;
use App\Models\Employment\Job;
use App\Models\Employment\JobApplication;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return match ($user->role) {
            User::ROLE_DISABLED => $this->userDashboard($user),
            User::ROLE_CAREGIVER => redirect()->route('caregiver.dashboard'),
            User::ROLE_VOLUNTEER => $this->volunteerDashboard($user),
            User::ROLE_EMPLOYER => $this->employerDashboard($user),
            User::ROLE_ADMIN => redirect()->route('admin.dashboard'),
            default => $this->userDashboard($user),
        };
    }

    // F14 - Roza Akter
    private function volunteerDashboard($user)
    {
        $pendingRequests = \App\Models\Community\AssistanceRequest::where('status', 'pending')->count();

        $activeMatches = \App\Models\Community\VolunteerMatch::where('volunteer_id', $user->id)
            ->where('status', 'accepted')
            ->count();

        $completedMatches = \App\Models\Community\VolunteerMatch::where('volunteer_id', $user->id)
           ->where('status', 'completed')
           ->count();

        $completedThisWeek = \App\Models\Community\VolunteerMatch::where('volunteer_id', $user->id)
           ->where('status', 'completed')
           ->where('completed_at', '>=', now()->startOfWeek())
           ->count();
        $weeklyGoal = 5;
        $weeklyGoalPercent = min(round(($completedThisWeek / $weeklyGoal) * 100), 100);

        $levelProgress = ($completedMatches % 10) * 10; 

        return view('dashboards.volunteer', compact('user', 'pendingRequests', 'activeMatches', 'completedMatches', 'weeklyGoalPercent', 'levelProgress'));
    }

    //F10 - Roza Akter
    private function employerDashboard($user)
    {
        $totalJobs = Job::where('employer_id', $user->id)->count();
        $activeJobs = Job::where('employer_id', $user->id)->where('status', 'active')->count();
        $totalApplications = JobApplication::whereHas('job', function ($query) use ($user) {
            $query->where('employer_id', $user->id);
        })->count();
        $shortlistedCount = JobApplication::whereHas('job', function ($query) use ($user) {
            $query->where('employer_id', $user->id);
        })->where('status', 'shortlisted')->count();

        $processedApplications = JobApplication::whereHas('job', function ($query) use ($user) {
            $query->where('employer_id', $user->id);
        })->where('status', '!=', 'pending')->count();

        $hiringProgress = $totalApplications > 0 ? round(($processedApplications / $totalApplications) * 100) : 0;

        return view('dashboards.employer', compact('user', 'totalJobs', 'activeJobs', 'totalApplications', 'shortlistedCount', 'hiringProgress'));
    }

    //F3 - Evan Yuvraj Munshi
    private function userDashboard($user)
    {
        $profile = $user->profile;
        $completion = 0;
        if ($profile) {
            $fields = [
                'bio', 'disability_type', 'phone_number', 'address', 
                'emergency_contact_name', 'emergency_contact_phone',
                'skills', 'interests'
            ];
            $filled = 0;
            foreach ($fields as $field) {
                if (!empty($profile->$field)) {
                    $filled++;
                }
            }
            $completion = round(($filled / count($fields)) * 100);
        }

        $jobApplicationsCount = $user->jobApplications()->count();

        $learningProgress = 0; 

        // F17 - Roza Akter
        $appointments = \App\Models\Health\DoctorAppointment::where('user_id', $user->id)
            ->with(['caregiver'])
            ->orderBy('appointment_date', 'asc')
            ->get();
        $upcomingAppointments = $appointments->where('status', 'scheduled')
            ->where('appointment_date', '>=', now())
            ->take(5);

        return view('dashboards.user', compact('user', 'completion', 'jobApplicationsCount', 'learningProgress', 'appointments', 'upcomingAppointments'));
    }


}



