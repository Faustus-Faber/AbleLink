<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployerJobController extends Controller
{
    /**
     * Show employer's job listings.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'employer') {
            abort(403, 'Only employers can access this page.');
        }

        $jobs = JobPosting::where('employer_id', $user->id)
            ->withCount('applications')
            ->latest()
            ->paginate(15);

        return view('employer.jobs.index', compact('jobs'));
    }

    /**
     * Show create job form.
     */
    public function create()
    {
        if (Auth::user()->role !== 'employer') {
            abort(403);
        }

        return view('employer.jobs.create');
    }

    /**
     * Store a new job posting.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'employer') {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'location' => ['nullable', 'string', 'max:255'],
            'is_remote' => ['boolean'],
            'job_type' => ['required', 'in:full-time,part-time,contract,freelance'],
            'salary_min' => ['nullable', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'min:0', 'gte:salary_min'],
            'salary_currency' => ['nullable', 'string', 'size:3'],
            'required_skills' => ['nullable', 'array'],
            'required_skills.*' => ['string', 'max:100'],
            'accessibility_features' => ['nullable', 'array'],
            'accessibility_features.*' => ['string', 'max:100'],
            'closes_at' => ['nullable', 'date', 'after:today'],
        ]);

        JobPosting::create([
            'employer_id' => $user->id,
            ...$validated,
            'is_active' => true,
        ]);

        return redirect()->route('employer.jobs.index')
            ->with('status', 'Job posting created successfully.');
    }

    /**
     * Show job applications for a job.
     */
    public function applications(JobPosting $job)
    {
        if ($job->employer_id !== Auth::id() || Auth::user()->role !== 'employer') {
            abort(403);
        }

        $applications = JobApplication::where('job_posting_id', $job->id)
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('employer.jobs.applications', compact('job', 'applications'));
    }

    /**
     * Update application status.
     */
    public function updateApplication(Request $request, JobApplication $application)
    {
        if ($application->jobPosting->employer_id !== Auth::id() || Auth::user()->role !== 'employer') {
            abort(403);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:pending,reviewed,shortlisted,rejected,accepted'],
            'employer_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $application->update($validated);

        return back()->with('status', 'Application status updated.');
    }
}





