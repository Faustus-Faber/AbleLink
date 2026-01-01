<?php
// F12 - Farhan Zarif
namespace App\Http\Controllers\Recommendation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Ai\Recommendation\RecommendationEngine;
use Illuminate\Support\Facades\Auth;

class RecommendationController extends Controller
{
    protected RecommendationEngine $engine;

    public function __construct(RecommendationEngine $engine)
    {
        $this->engine = $engine;
    }

    public function getJobs(): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        if ($user === null) {
            return response()->json(['needsLogin' => true, 'items' => []]);
        }

        $userSkills = [];
        if ($user->profile !== null) {
            if ($user->profile->skills !== null) {
                $userSkills = $user->profile->skills;
            }
        }
        
        $needsSetup = false;
        if (empty($userSkills)) {
            $needsSetup = true;
        }

        $recommendations = $this->engine->getJobRecommendations($user, 5);
        $processedRecommendations = [];

        foreach ($recommendations as $item) {
            $job = $item['job'];
            $job->load('employer.employerProfile');
            
            $companyName = 'Company Confidential';
            
            if ($job->employer !== null) {
                if ($job->employer->employerProfile !== null) {
                    if ($job->employer->employerProfile->company_name !== null) {
                        $companyName = $job->employer->employerProfile->company_name;
                    }
                }
            }
            
            $item['company_name'] = $companyName;
            $processedRecommendations[] = $item;
        }

        return response()->json([
            'needsSetup' => $needsSetup,
            'items' => $processedRecommendations
        ]);
    }

    public function getCourses(): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        if ($user === null) {
            return response()->json(['needsLogin' => true, 'items' => []]);
        }

        $userInterests = [];
        if ($user->profile !== null) {
            if ($user->profile->interests !== null) {
                $userInterests = $user->profile->interests;
            }
        }

        $needsSetup = false;
        if (empty($userInterests)) {
            $needsSetup = true;
        }

        $recommendations = $this->engine->getCourseRecommendations($user, 5);
        
        return response()->json([
            'needsSetup' => $needsSetup,
            'items' => $recommendations
        ]);
    }

    public function updatePreferences(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        
        if ($user !== null) {
            if ($user->profile === null) {
                 \App\Models\Auth\UserProfile::firstOrCreate(['user_id' => $user->id]);
                 $user->refresh();
            }
        } 
        else {

             return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'skills' => 'nullable|array',
            'interests' => 'nullable|array',
            'learning_style' => 'nullable|string|in:visual,auditory,text',
        ]);

        $updateData = [];
        if ($request->has('skills')) {
            $updateData['skills'] = $request->skills;
        }
        
        if ($request->has('interests')) {
            $updateData['interests'] = $request->interests;
        }
        
        if ($request->has('learning_style')) {
            $updateData['learning_style'] = $request->learning_style;
        }

        if ($user->profile !== null) {
            $user->profile->update($updateData);
        }

        return response()->json(['success' => true, 'message' => 'Preferences updated']);
    }

    public function dismiss(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json(['success' => true]);
    }
}


