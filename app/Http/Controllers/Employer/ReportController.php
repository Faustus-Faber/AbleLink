<?php
// F10 - Rifat Jahan Roza
//F10 - Rifat Jahan Roza

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Employment\Job;
use App\Models\Employment\JobApplication;
use App\Models\Employment\Interview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//F10 - Employer Job Posting & Dashboard - Reports
class ReportController extends Controller
{
    public function index()
    {
        $employer = Auth::user();
        
        if (!$employer->hasRole(\App\Models\Auth\User::ROLE_EMPLOYER)) {
            abort(403, 'Only employers can access this page.');
        }

        // Statistics
        $totalJobs = Job::where('employer_id', $employer->id)->count();
        $activeJobs = Job::where('employer_id', $employer->id)->where('status', 'active')->count();
        $totalApplications = JobApplication::whereHas('job', function ($query) use ($employer) {
            $query->where('employer_id', $employer->id);
        })->count();
        
        $applicationsByStatus = JobApplication::whereHas('job', function ($query) use ($employer) {
            $query->where('employer_id', $employer->id);
        })->selectRaw('status, count(*) as count')
          ->groupBy('status')
          ->pluck('count', 'status');

        $recentApplications = JobApplication::whereHas('job', function ($query) use ($employer) {
            $query->where('employer_id', $employer->id);
        })->with(['job', 'applicant.profile'])
          ->latest('applied_at')
          ->limit(10)
          ->get();

        $upcomingInterviews = Interview::where('employer_id', $employer->id)
            ->where('status', 'scheduled')
            ->where('scheduled_at', '>=', now())
            ->with(['jobApplication.job', 'applicant'])
            ->orderBy('scheduled_at', 'asc')
            ->limit(5)
            ->get();

        return view('employer.reports.index', compact(
            'totalJobs',
            'activeJobs',
            'totalApplications',
            'applicationsByStatus',
            'recentApplications',
            'upcomingInterviews'
        ));
    }
}


