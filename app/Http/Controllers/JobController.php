<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use App\Models\JobApplication;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    protected $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    /**
     * Show job search page.
     */
    public function index(Request $request)
    {
        $query = JobPosting::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('closes_at')
                  ->orWhere('closes_at', '>', now());
            });

        // Apply filters
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('remote')) {
            $query->where('is_remote', $request->remote === '1');
        }

        if ($request->filled('accessibility')) {
            $query->whereJsonContains('accessibility_features', $request->accessibility);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $jobs = $query->with('employer')->latest()->paginate(12);

        // Get recommendations if user is logged in
        $recommendations = null;
        if (Auth::check() && Auth::user()->isDisabledUser()) {
            $recommendations = $this->recommendationService->getJobRecommendations(Auth::user());
        }

        return view('jobs.index', compact('jobs', 'recommendations'));
    }

    /**
     * Show job details.
     */
    public function show(JobPosting $job)
    {
        $hasApplied = false;
        if (Auth::check()) {
            $hasApplied = JobApplication::where('job_posting_id', $job->id)
                ->where('user_id', Auth::id())
                ->exists();
        }

        return view('jobs.show', compact('job', 'hasApplied'));
    }

    /**
     * Apply for a job.
     */
    public function apply(Request $request, JobPosting $job)
    {
        $user = Auth::user();

        if (!$user->isDisabledUser()) {
            abort(403, 'Only users can apply for jobs.');
        }

        // Check if already applied
        $existing = JobApplication::where('job_posting_id', $job->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            return back()->withErrors(['error' => 'You have already applied for this job.']);
        }

        $validated = $request->validate([
            'cover_letter' => ['nullable', 'string', 'max:2000'],
            'accommodation_needs' => ['nullable', 'string', 'max:1000'],
        ]);

        JobApplication::create([
            'job_posting_id' => $job->id,
            'user_id' => $user->id,
            'cover_letter' => $validated['cover_letter'] ?? null,
            'accommodation_needs' => $validated['accommodation_needs'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('jobs.show', $job)
            ->with('status', 'Your application has been submitted successfully.');
    }
}





