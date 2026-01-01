<?php
// F9 - Evan Yuvraj Munshi

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Employment\JobApplication;

class CandidateApplicationController extends Controller
{
    /**
     * Display a listing of the candidate's applications.
     */
    public function index()
    {
        $user = Auth::user();
        
        $applications = JobApplication::where('applicant_id', $user->id)
            ->with(['job.employer'])
            ->latest('applied_at')
            ->paginate(10);

        return view('candidate.applications.index', compact('applications'));
    }
}

