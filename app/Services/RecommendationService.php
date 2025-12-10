<?php

namespace App\Services;

use App\Models\User;
use App\Models\JobPosting;
use App\Models\Course;
use App\Models\CourseEnrollment;
use Illuminate\Support\Collection;

/**
 * AI Recommendation Engine Service
 * 
 * Currently rule-based, designed to be easily expandable with ML models.
 */
class RecommendationService
{
    /**
     * Get job recommendations for a user based on their skills, disability, and preferences.
     */
    public function getJobRecommendations(User $user): Collection
    {
        $recommendations = collect();

        // Get user's skills and disability type
        $userSkills = $user->profile?->skills ?? [];
        $disabilityType = strtolower($user->disability_type ?? '');
        $assistiveNeeds = $user->profile?->assistive_needs ?? [];

        // Get active job postings
        $jobs = JobPosting::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('closes_at')
                    ->orWhere('closes_at', '>', now());
            })
            ->get();

        foreach ($jobs as $job) {
            $score = $this->calculateJobScore($user, $job, $userSkills, $disabilityType, $assistiveNeeds);
            
            if ($score > 0) {
                $recommendations->push([
                    'job' => $job,
                    'score' => $score,
                    'reasons' => $this->getJobRecommendationReasons($user, $job, $userSkills, $disabilityType),
                ]);
            }
        }

        // Sort by score descending and return top recommendations
        return $recommendations->sortByDesc('score')->take(10);
    }

    /**
     * Get course recommendations for a user.
     */
    public function getCourseRecommendations(User $user): Collection
    {
        $recommendations = collect();

        $userSkills = $user->profile?->skills ?? [];
        $disabilityType = strtolower($user->disability_type ?? '');
        
        // Get courses user hasn't enrolled in
        $enrolledCourseIds = CourseEnrollment::where('user_id', $user->id)
            ->pluck('course_id')
            ->toArray();

        $courses = Course::where('is_active', true)
            ->whereNotIn('id', $enrolledCourseIds)
            ->get();

        foreach ($courses as $course) {
            $score = $this->calculateCourseScore($user, $course, $userSkills, $disabilityType);
            
            if ($score > 0) {
                $recommendations->push([
                    'course' => $course,
                    'score' => $score,
                    'reasons' => $this->getCourseRecommendationReasons($user, $course, $userSkills, $disabilityType),
                ]);
            }
        }

        return $recommendations->sortByDesc('score')->take(10);
    }

    /**
     * Calculate job recommendation score (0-100).
     */
    protected function calculateJobScore(
        User $user,
        JobPosting $job,
        array $userSkills,
        string $disabilityType,
        array $assistiveNeeds
    ): float {
        $score = 0;

        // Skill matching (0-40 points)
        $jobSkills = $job->required_skills ?? [];
        $matchedSkills = array_intersect($userSkills, $jobSkills);
        if (!empty($jobSkills)) {
            $skillMatchPercentage = count($matchedSkills) / count($jobSkills);
            $score += $skillMatchPercentage * 40;
        }

        // Accessibility features matching (0-30 points)
        $jobAccessibility = array_map('strtolower', $job->accessibility_features ?? []);
        if (!empty($jobAccessibility)) {
            $accessibilityMatch = 0;
            foreach ($assistiveNeeds as $need) {
                if (in_array(strtolower($need), $jobAccessibility)) {
                    $accessibilityMatch++;
                }
            }
            if (!empty($assistiveNeeds)) {
                $score += ($accessibilityMatch / count($assistiveNeeds)) * 30;
            }
        }

        // Remote work preference (0-15 points)
        if ($job->is_remote) {
            $score += 15; // Remote jobs get bonus points
        }

        // Disability type matching with accessibility features (0-15 points)
        if ($disabilityType && !empty($jobAccessibility)) {
            $disabilityAccessibilityMap = [
                'blind' => ['screen reader', 'braille', 'audio description'],
                'deaf' => ['sign language', 'subtitles', 'closed captions'],
                'hard of hearing' => ['hearing loop', 'subtitles', 'assistive listening'],
                'mobility' => ['wheelchair accessible', 'ramp', 'elevator'],
            ];

            $relevantFeatures = $disabilityAccessibilityMap[$disabilityType] ?? [];
            $hasRelevantFeatures = !empty(array_intersect($relevantFeatures, $jobAccessibility));
            
            if ($hasRelevantFeatures) {
                $score += 15;
            }
        }

        return min(100, $score);
    }

    /**
     * Calculate course recommendation score (0-100).
     */
    protected function calculateCourseScore(
        User $user,
        Course $course,
        array $userSkills,
        string $disabilityType
    ): float {
        $score = 50; // Base score

        // Accessibility features matching (0-30 points)
        $courseAccessibility = array_map('strtolower', $course->accessibility_features ?? []);
        $disabilityAccessibilityMap = [
            'blind' => ['screen reader', 'audio description', 'transcript'],
            'deaf' => ['subtitles', 'closed captions', 'transcript'],
            'hard of hearing' => ['subtitles', 'transcript'],
            'mobility' => ['keyboard navigation'],
        ];

        $relevantFeatures = $disabilityAccessibilityMap[$disabilityType] ?? [];
        $hasRelevantFeatures = !empty(array_intersect($relevantFeatures, $courseAccessibility));
        
        if ($hasRelevantFeatures) {
            $score += 30;
        }

        // Skill development relevance (0-20 points)
        // Courses that build on existing skills or teach new relevant skills
        $score += 20; // Simplified - can be expanded with skill taxonomy

        return min(100, $score);
    }

    /**
     * Get reasons why a job was recommended.
     */
    protected function getJobRecommendationReasons(
        User $user,
        JobPosting $job,
        array $userSkills,
        string $disabilityType
    ): array {
        $reasons = [];

        $jobSkills = $job->required_skills ?? [];
        $matchedSkills = array_intersect($userSkills, $jobSkills);
        
        if (!empty($matchedSkills)) {
            $reasons[] = 'Matches your skills: ' . implode(', ', array_slice($matchedSkills, 0, 3));
        }

        if ($job->is_remote) {
            $reasons[] = 'Remote work available';
        }

        $jobAccessibility = $job->accessibility_features ?? [];
        if (!empty($jobAccessibility)) {
            $reasons[] = 'Includes accessibility features: ' . implode(', ', array_slice($jobAccessibility, 0, 2));
        }

        return $reasons;
    }

    /**
     * Get reasons why a course was recommended.
     */
    protected function getCourseRecommendationReasons(
        User $user,
        Course $course,
        array $userSkills,
        string $disabilityType
    ): array {
        $reasons = [];

        $courseAccessibility = $course->accessibility_features ?? [];
        if (!empty($courseAccessibility)) {
            $reasons[] = 'Accessible course with: ' . implode(', ', array_slice($courseAccessibility, 0, 3));
        }

        $reasons[] = 'Difficulty level: ' . ucfirst($course->difficulty_level);

        return $reasons;
    }
}





