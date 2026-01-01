<?php
// F10 - Roza Akter


namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Employment\Job;
use App\Models\Employment\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Notifications\Employment\ApplicationStatusChanged;

class EmployerJobController extends Controller
{

    /**
     * Display a listing of jobs for the authenticated employer
     */
    public function index(Request $request)
    {
        $employer = Auth::user();
        
        if (!$employer->hasRole(\App\Models\Auth\User::ROLE_EMPLOYER)) {
            abort(403, 'Only employers can access this page.');
        }

        //F9 - Evan Munshi
        $query = Job::where('employer_id', $employer->id);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('job_type')) {
            $query->where('job_type', $request->job_type);
        }
        //F9 - Evan Munshi

        $jobs = $query->withCount(['applications', 'pendingApplications', 'shortlistedApplications'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('employer.jobs.index', compact('jobs'));
    }

    /**
     * Show the form for creating a new job
     */
    public function create()
    {
        $employer = Auth::user();
        
        if (!$employer->hasRole(\App\Models\Auth\User::ROLE_EMPLOYER)) {
            abort(403, 'Only employers can access this page.');
        }

        return view('employer.jobs.create');
    }

    /**
     * Store a newly created job
     */
    public function store(Request $request)
    {
        $employer = Auth::user();
        
        if (!$employer->hasRole(\App\Models\Auth\User::ROLE_EMPLOYER)) {
            abort(403, 'Only employers can access this page.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'nullable|string|max:255',
            'job_type' => 'required|in:full-time,part-time,contract,remote',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'salary_currency' => 'nullable|string|size:3',
            'application_deadline' => 'nullable|date|after:today',
            'wheelchair_accessible' => 'boolean',
            'sign_language_support' => 'boolean',
            'screen_reader_compatible' => 'boolean',
            'flexible_hours' => 'boolean',
            'remote_work_available' => 'boolean',
            'accessibility_accommodations' => 'nullable|string',
            'additional_requirements' => 'nullable|string',
            'status' => 'required|in:draft,active',
        ]);

        $validated['employer_id'] = $employer->id;
        $validated['wheelchair_accessible'] = $request->has('wheelchair_accessible');
        $validated['sign_language_support'] = $request->has('sign_language_support');
        $validated['screen_reader_compatible'] = $request->has('screen_reader_compatible');
        $validated['flexible_hours'] = $request->has('flexible_hours');
        $validated['remote_work_available'] = $request->has('remote_work_available');

        $job = Job::create($validated);

        return redirect()->route('employer.jobs.show', $job)
            ->with('success', 'Job posted successfully!');
    }

    /**
     * Display the specified job with applications
     */
    public function show(Job $job)
    {
        $employer = Auth::user();
        
        if (!$employer->hasRole(\App\Models\Auth\User::ROLE_EMPLOYER) || $job->employer_id !== $employer->id) {
            abort(403, 'Unauthorized access.');
        }

        $job->load(['applications.applicant.profile']);

        $applications = $job->applications()
            ->with('applicant.profile')
            ->latest('applied_at')
            ->paginate(10);

        return view('employer.jobs.show', compact('job', 'applications'));
    }

    /**
     * Show the form for editing the specified job
     */
    public function edit(Job $job)
    {
        $employer = Auth::user();
        
        if (!$employer->hasRole(\App\Models\Auth\User::ROLE_EMPLOYER) || $job->employer_id !== $employer->id) {
            abort(403, 'Unauthorized access.');
        }

        return view('employer.jobs.edit', compact('job'));
    }

    /**
     * Update the specified job
     */
    public function update(Request $request, Job $job)
    {
        $employer = Auth::user();
        
        if (!$employer->hasRole(\App\Models\Auth\User::ROLE_EMPLOYER) || $job->employer_id !== $employer->id) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'nullable|string|max:255',
            'job_type' => 'required|in:full-time,part-time,contract,remote',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'salary_currency' => 'nullable|string|size:3',
            'application_deadline' => 'nullable|date|after:today',
            'wheelchair_accessible' => 'boolean',
            'sign_language_support' => 'boolean',
            'screen_reader_compatible' => 'boolean',
            'flexible_hours' => 'boolean',
            'remote_work_available' => 'boolean',
            'accessibility_accommodations' => 'nullable|string',
            'additional_requirements' => 'nullable|string',
            'status' => 'required|in:draft,active,closed,filled',
        ]);

        $validated['wheelchair_accessible'] = $request->has('wheelchair_accessible');
        $validated['sign_language_support'] = $request->has('sign_language_support');
        $validated['screen_reader_compatible'] = $request->has('screen_reader_compatible');
        $validated['flexible_hours'] = $request->has('flexible_hours');
        $validated['remote_work_available'] = $request->has('remote_work_available');

        $job->update($validated);

        return redirect()->route('employer.jobs.show', $job)
            ->with('success', 'Job updated successfully!');
    }

    /**
     * Remove the specified job
     */
    public function destroy(Job $job)
    {
        $employer = Auth::user();
        
        if (!$employer->hasRole(\App\Models\Auth\User::ROLE_EMPLOYER) || $job->employer_id !== $employer->id) {
            abort(403, 'Unauthorized access.');
        }

        $job->delete();

        return redirect()->route('employer.jobs.index')
            ->with('success', 'Job deleted successfully!');
    }

    /**
     * Update application status
     */
    public function updateApplicationStatus(Request $request, JobApplication $application)
    {
        $employer = Auth::user();
        
        if (!$employer->hasRole(\App\Models\Auth\User::ROLE_EMPLOYER)) {
            abort(403, 'Unauthorized access.');
        }

        $job = $application->job;
        if ($job->employer_id !== $employer->id) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,reviewing,shortlisted,interviewed,accepted,rejected',
            'employer_notes' => 'nullable|string',
        ]);

        $application->update($validated);

        $application->applicant->notify(new ApplicationStatusChanged($application));
        //F9 - Evan Munshi

        return redirect()->back()
            ->with('success', 'Application status updated successfully!');
    }

    /**
     * Show all applications across all jobs
     */
    public function applications(Request $request)
    {
        $employer = Auth::user();
        
        if (!$employer->hasRole(\App\Models\Auth\User::ROLE_EMPLOYER)) {
            abort(403, 'Only employers can access this page.');
        }

        //F9 - Evan Munshi
        $query = JobApplication::whereHas('job', function ($query) use ($employer) {
            $query->where('employer_id', $employer->id);
        });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('applicant', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('job', function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        //F9 - Evan Munshi

        $applications = $query->with(['job', 'applicant.profile'])
        ->latest('applied_at')
        ->paginate(15)
        ->withQueryString();

        return view('employer.applications.index', compact('applications'));
    }
}


