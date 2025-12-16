<?php
// F10 - Rifat Jahan Roza
//F10 - Rifat Jahan Roza

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Employment\Interview;
use App\Models\Employment\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//F10 - Employer Job Posting & Dashboard - Interviews
class InterviewController extends Controller
{
    public function index()
    {
        $employer = Auth::user();
        
        if (!$employer->hasRole(\App\Models\Auth\User::ROLE_EMPLOYER)) {
            abort(403, 'Only employers can access this page.');
        }

        $interviews = Interview::where('employer_id', $employer->id)
            ->with(['jobApplication.job', 'applicant.profile'])
            ->orderBy('scheduled_at', 'asc')
            ->paginate(15);

        return view('employer.interviews.index', compact('interviews'));
    }

    public function create(JobApplication $application)
    {
        $employer = Auth::user();
        
        if (!$employer->hasRole(\App\Models\Auth\User::ROLE_EMPLOYER) || 
            $application->job->employer_id !== $employer->id) {
            abort(403, 'Unauthorized access.');
        }

        return view('employer.interviews.create', compact('application'));
    }

    public function store(Request $request, JobApplication $application)
    {
        $employer = Auth::user();
        
        if (!$employer->hasRole(\App\Models\Auth\User::ROLE_EMPLOYER) || 
            $application->job->employer_id !== $employer->id) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_at' => 'required|date|after:now',
            'type' => 'required|in:phone,video,in-person,online',
            'location' => 'nullable|string|max:255',
            'meeting_link' => 'nullable|url',
        ]);

        $validated['job_application_id'] = $application->id;
        $validated['employer_id'] = $employer->id;
        $validated['applicant_id'] = $application->applicant_id;
        $validated['status'] = 'scheduled';

        $interview = Interview::create($validated);

        // F9 - Evan Munshi
        // Notify the applicant about the scheduled interview
        $application->applicant->notify(new \App\Notifications\Employment\InterviewScheduled($interview));
        // F9 - Evan Munshi

        return redirect()->route('employer.interviews.index')
            ->with('success', 'Interview scheduled successfully!');
    }

    public function updateStatus(Request $request, Interview $interview)
    {
        $employer = Auth::user();
        
        if (!$employer->hasRole(\App\Models\Auth\User::ROLE_EMPLOYER) || 
            $interview->employer_id !== $employer->id) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'status' => 'required|in:scheduled,completed,cancelled,rescheduled',
            'feedback' => 'nullable|string',
        ]);

        $interview->update($validated);

        return redirect()->back()
            ->with('success', 'Interview status updated successfully!');
    }
}


