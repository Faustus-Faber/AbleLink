<?php
// F9 - Evan Yuvraj Munshi

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use App\Models\Employment\Job;
use Illuminate\Http\Request;
use App\Models\Employment\JobApplication;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //F9 - Evan Munshi
        $query = Job::where('status', 'active');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('job_type')) {
            $query->where('job_type', $request->job_type);
        }

        // Accessibility Filters
        if ($request->has('wheelchair_accessible')) {
            $query->where('wheelchair_accessible', true);
        }
        if ($request->has('sign_language_support')) {
            $query->where('sign_language_support', true);
        }
        if ($request->has('screen_reader_compatible')) {
            $query->where('screen_reader_compatible', true);
        }
        if ($request->has('flexible_hours')) {
            $query->where('flexible_hours', true);
        }
        if ($request->has('remote_work_available')) {
            $query->where('remote_work_available', true);
        }
        //F9 - Evan Munshi

        $jobs = $query->with('employer')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('jobs.index', compact('jobs'));
    }

    public function show(Job $job)
    {
        if ($job->status !== 'active') {
            abort(404);
        }
        
        $hasApplied = false;
        if (Auth::check()) {
            $hasApplied = $job->applications()->where('applicant_id', Auth::id())->exists();
        }
        
        return view('jobs.show', compact('job', 'hasApplied'));
    }

    //F9 - Evan Munshi
    public function apply(Request $request, Job $job)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to apply.');
        }

        $user = Auth::user();

        // Check if already applied
        if ($job->applications()->where('applicant_id', $user->id)->exists()) {
            return back()->with('error', 'You have already applied for this job.');
        }

        $validated = $request->validate([
            'cv' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'cover_letter' => 'nullable|string|max:5000',
        ]);

        $resumePath = null;
        if ($request->hasFile('cv')) {
            $resumePath = $request->file('cv')->store('resumes', 'public');
        }

        JobApplication::create([
            'job_id' => $job->id,
            'applicant_id' => $user->id,
            'cover_letter' => $validated['cover_letter'] ?? null,
            'resume_path' => $resumePath,
            'status' => 'pending',
            'applied_at' => now(),
        ]);

        return back()->with('success', 'Application submitted successfully!');
    }
    //F9 - Evan Munshi
}

