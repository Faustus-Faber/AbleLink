<?php
// F12 - Farhan Zarif
namespace App\Http\Controllers\Recommendation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Ai\Recommendation\RecommendationEngine;
use Illuminate\Support\Facades\Auth;

class RecommendationController extends Controller
{
    protected $engine;

    public function __construct(RecommendationEngine $engine)
    {
        $this->engine = $engine;
    }

    public function getJobs()
    {
        $user = Auth::user();
        if (!$user) return response()->json(['needsLogin' => true, 'items' => []]);

        // Check if user needs to set up skills
        $userSkills = $user->profile?->skills ?? [];
        $needsSetup = empty($userSkills);

        // Ensure we load employer relationship for the view
        $recommendations = $this->engine->getJobRecommendations($user, 5)->map(function ($item) {
            $job = $item['job'];
            $job->load('employer.employerProfile');
            // Explicitly attach company name to avoid JS traversal issues
            $item['company_name'] = $job->employer?->employerProfile?->company_name ?? 'Company Confidential';
            return $item;
        });

        return response()->json([
            'needsSetup' => $needsSetup,
            'items' => $recommendations
        ]);
    }

    public function getCourses()
    {
        $user = Auth::user();
        if (!$user) return response()->json(['needsLogin' => true, 'items' => []]);

        // Check if user needs to set up interests
        $userInterests = $user->profile?->interests ?? [];
        $needsSetup = empty($userInterests);

        $recommendations = $this->engine->getCourseRecommendations($user, 5);
        
        return response()->json([
            'needsSetup' => $needsSetup,
            'items' => $recommendations
        ]);
    }

    public function updatePreferences(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->profile) {
            // Ensure profile exists
             if ($user) \App\Models\Auth\UserProfile::firstOrCreate(['user_id' => $user->id]);
        }

        $validated = $request->validate([
            'skills' => 'nullable|array',
            'interests' => 'nullable|array',
            'learning_style' => 'nullable|string|in:visual,auditory,text',
        ]);

        // Merge logic: Don't overwrite existing if not provided
        $updateData = [];
        if ($request->has('skills')) $updateData['skills'] = $request->skills;
        if ($request->has('interests')) $updateData['interests'] = $request->interests;
        if ($request->has('learning_style')) $updateData['learning_style'] = $request->learning_style;

        $user->profile->update($updateData);

        return response()->json(['success' => true, 'message' => 'Preferences updated']);
    }

    public function dismiss(Request $request)
    {
        // Placeholder for Feedback Loop
        // Would save to 'recommendation_feedback' table
        return response()->json(['success' => true]);
    }
}


