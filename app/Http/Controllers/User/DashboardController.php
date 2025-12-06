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
            // Volunteer role enabled for Sprint 5
            User::ROLE_VOLUNTEER => $this->volunteerDashboard($user),
            User::ROLE_EMPLOYER => $this->employerDashboard($user),
            User::ROLE_ADMIN => redirect()->route('admin.dashboard'),
            default => $this->userDashboard($user),
        };
    }

    // F14 - Volunteer Dashboard Logic
    private function volunteerDashboard($user)
    {
        // Count pending requests (unassigned) - Global Pool
        $pendingRequests = \App\Models\Community\AssistanceRequest::where('status', 'pending')->count();

        // Count active tasks for this volunteer (Accepted matches)
        $activeMatches = \App\Models\Community\VolunteerMatch::where('volunteer_id', $user->id)
            ->where('status', 'accepted')
            ->count();

        // Count completed tasks for this volunteer
        $completedMatches = \App\Models\Community\VolunteerMatch::where('volunteer_id', $user->id)
           ->where('status', 'completed')
           ->count();

        // Weekly Goal Logic (Goal: 5 tasks per week)
        $completedThisWeek = \App\Models\Community\VolunteerMatch::where('volunteer_id', $user->id)
           ->where('status', 'completed')
           ->where('completed_at', '>=', now()->startOfWeek())
           ->count();
        $weeklyGoal = 5;
        $weeklyGoalPercent = min(round(($completedThisWeek / $weeklyGoal) * 100), 100);

        // Level Progress Logic (Every 10 tasks is a "level")
        $levelProgress = ($completedMatches % 10) * 10; 

        return view('dashboards.volunteer', compact('user', 'pendingRequests', 'activeMatches', 'completedMatches', 'weeklyGoalPercent', 'levelProgress'));
    }

    //F10 - Employer Job Posting & Dashboard
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

        // Calculate Hiring Progress (Percentage of applications processed/not pending)
        $processedApplications = JobApplication::whereHas('job', function ($query) use ($user) {
            $query->where('employer_id', $user->id);
        })->where('status', '!=', 'pending')->count();

        $hiringProgress = $totalApplications > 0 ? round(($processedApplications / $totalApplications) * 100) : 0;

        return view('dashboards.employer', compact('user', 'totalJobs', 'activeJobs', 'totalApplications', 'shortlistedCount', 'hiringProgress'));
    }

    //F3 - User Profile & Accessibility
    private function userDashboard($user)
    {
        // 1. Profile Completion Calculation
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

        // 2. Job Applications Count
        $jobApplicationsCount = $user->jobApplications()->count();

        // 3. Learning Progress (Placeholder as course enrollment not yet implemented)
        $learningProgress = 0; 

        // F17 - Doctor Appointments for disabled user - Rifat Jahan Roza
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



