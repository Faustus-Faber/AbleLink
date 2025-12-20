<?php
// F12 - Farhan Zarif
namespace App\Services\Ai\Recommendation;

use App\Models\Auth\User;
use App\Models\Employment\Job;
use App\Models\Education\Course;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class RecommendationEngine
{
    private ?string $geminiApiKey;
    private ?string $geminiApiUrl;

    public function __construct()
    {
        $this->geminiApiKey = env('AI_API_KEY');
        $this->geminiApiUrl = env('AI_API_URL');
    }
    /**
     * Get Job Recommendations for a User - Full AI-Powered.
     * Sends all job data to AI for semantic matching.
     */
    public function getJobRecommendations(User $user, $limit = 5): Collection
    {
        // Employers don't get job recommendations
        if ($user->hasRole('employer')) {
            return collect([]);
        }

        $jobs = Job::where('status', 'active')->with('employer.employerProfile')->get();
        
        if ($jobs->isEmpty()) {
            return collect([]);
        }

        $userProfile = $user->profile;
        $userSkills = $userProfile?->skills ?? [];
        $userInterests = $userProfile?->interests ?? [];
        $accessibilityPrefs = $userProfile?->accessibility_preferences ?? [];

        // If no skills set, return empty (modal will show setup form)
        if (empty($userSkills)) {
            return collect([]);
        }

        // Build job data for AI (limit description length to control tokens)
        $jobData = $jobs->map(fn($j) => [
            'id' => $j->id,
            'title' => $j->title,
            'description' => \Illuminate\Support\Str::limit($j->description, 300),
            'skills_required' => $j->skills_required ?? [],
            'location' => $j->location,
            'job_type' => $j->job_type,
            'remote_available' => $j->remote_work_available,
            'wheelchair_accessible' => $j->wheelchair_accessible,
            'company' => $j->employer?->employerProfile?->company_name ?? 'Company',
        ])->values()->toArray();

        // Cache key based on user profile + job count
        $cacheKey = 'ai_job_recs_' . $user->id . '_' . md5(json_encode($userSkills) . $jobs->count());
        
        return Cache::remember($cacheKey, 3600, function () use ($jobData, $userSkills, $userInterests, $accessibilityPrefs, $jobs, $limit, $user) {
            try {
                $prompt = $this->buildJobRecommendationPrompt($jobData, $userSkills, $userInterests, $accessibilityPrefs, $user->hasRole('disabled'), $limit);
                
                $response = Http::withOptions(['verify' => false, 'timeout' => 30])
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . $this->geminiApiKey
                    ])
                    ->post($this->geminiApiUrl, [
                        'model' => env('AI_MODEL', 'gpt-4o-mini'),
                        'messages' => [
                            ['role' => 'system', 'content' => 'You are a Job Recommendation AI. Analyze jobs and candidate profile to find the best matches. Return ONLY valid JSON array, no markdown.'],
                            ['role' => 'user', 'content' => $prompt]
                        ],
                        'temperature' => 0.3
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $content = $data['choices'][0]['message']['content'] ?? '[]';
                    
                    // Clean markdown code blocks if present
                    $cleanJson = preg_replace('/^```json\s*|\s*```$/', '', trim($content));
                    $recommendations = json_decode($cleanJson, true) ?? [];
                    
                    Log::info('AI Job Recommendations:', ['count' => count($recommendations)]);
                    
                    // Map AI results back to job objects
                    return collect($recommendations)->map(function ($rec) use ($jobs) {
                        $job = $jobs->firstWhere('id', $rec['id']);
                        if (!$job) return null;
                        
                        return [
                            'job' => $job,
                            'score' => min(100, max(0, $rec['score'] ?? 50)),
                            'explanation' => $rec['reason'] ?? 'AI-recommended',
                            'company_name' => $job->employer?->employerProfile?->company_name ?? 'Company Confidential'
                        ];
                    })->filter()->take($limit)->values();
                } else {
                    Log::error('AI Job Recommendation Error:', ['status' => $response->status(), 'body' => $response->body()]);
                }
            } catch (\Exception $e) {
                Log::error('AI Job Recommendation Exception:', ['error' => $e->getMessage()]);
            }
            
            // Fallback: return empty
            return collect([]);
        });
    }

    /**
     * Build the AI prompt for job recommendations.
     */
    private function buildJobRecommendationPrompt(array $jobs, array $skills, array $interests, array $accessibilityPrefs, bool $hasDisability, int $limit): string
    {
        $skillsStr = implode(', ', $skills);
        $interestsStr = !empty($interests) ? implode(', ', $interests) : 'Not specified';
        $accessibilityStr = !empty($accessibilityPrefs) ? json_encode($accessibilityPrefs) : 'None specified';
        $jobsJson = json_encode($jobs, JSON_PRETTY_PRINT);
        
        $disabilityContext = $hasDisability 
            ? "The candidate has a disability. Prioritize jobs with accessibility features (wheelchair_accessible, remote_available)."
            : "";
        
        return <<<EOT
Analyze the candidate profile and available jobs. Return the top {$limit} most relevant jobs.

**Candidate Profile:**
- Skills: {$skillsStr}
- Interests: {$interestsStr}
- Accessibility Needs: {$accessibilityStr}
{$disabilityContext}

**Available Jobs:**
{$jobsJson}

**Instructions:**
1. Match jobs semantically to candidate skills (e.g., "Laravel" relates to "PHP Developer" jobs)
2. Consider interests for better cultural fit
3. If candidate has disability, prioritize accessible workplaces
4. Score from 0-100 based on relevance
5. Provide a brief, friendly reason for each match

**Return ONLY a JSON array (no markdown, no code blocks):**
[{"id": 1, "score": 95, "reason": "Great match for your PHP and Laravel skills"}]
EOT;
    }

    /**
     * Get Course Recommendations for a User - Full AI-Powered.
     * Sends all course data to AI for semantic matching.
     */
    public function getCourseRecommendations(User $user, $limit = 5): Collection
    {
        $courses = Course::where('published_at', '<=', now())->get();
        
        if ($courses->isEmpty()) {
            return collect([]);
        }

        $userProfile = $user->profile;
        $userInterests = $userProfile?->interests ?? [];
        $learningStyle = $userProfile?->learning_style ?? 'any';
        $userRole = $user->role ?? 'user';

        // If no interests set, return empty (modal will show setup form)
        if (empty($userInterests)) {
            return collect([]);
        }

        // Build course data for AI
        $courseData = $courses->map(fn($c) => [
            'id' => $c->id,
            'title' => $c->title,
            'summary' => $c->summary,
            'category' => $c->category,
            'level' => $c->level,
        ])->values()->toArray();

        // Cache key based on user profile + course count
        $cacheKey = 'ai_course_recs_' . $user->id . '_' . md5(json_encode($userInterests) . $courses->count());
        
        return Cache::remember($cacheKey, 3600, function () use ($courseData, $userInterests, $learningStyle, $userRole, $courses, $limit) {
            try {
                $prompt = $this->buildCourseRecommendationPrompt($courseData, $userInterests, $learningStyle, $userRole, $limit);
                
                $response = Http::withOptions(['verify' => false, 'timeout' => 30])
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . $this->geminiApiKey
                    ])
                    ->post($this->geminiApiUrl, [
                        'model' => env('AI_MODEL', 'gpt-4o-mini'),
                        'messages' => [
                            ['role' => 'system', 'content' => 'You are a Course Recommendation AI. Analyze courses and user profile to find the best matches. Return ONLY valid JSON array, no markdown.'],
                            ['role' => 'user', 'content' => $prompt]
                        ],
                        'temperature' => 0.3
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $content = $data['choices'][0]['message']['content'] ?? '[]';
                    
                    // Clean markdown code blocks if present
                    $cleanJson = preg_replace('/^```json\s*|\s*```$/', '', trim($content));
                    $recommendations = json_decode($cleanJson, true) ?? [];
                    
                    Log::info('AI Course Recommendations:', ['count' => count($recommendations)]);
                    
                    // Map AI results back to course objects
                    return collect($recommendations)->map(function ($rec) use ($courses) {
                        $course = $courses->firstWhere('id', $rec['id']);
                        if (!$course) return null;
                        
                        return [
                            'course' => $course,
                            'score' => min(100, max(0, $rec['score'] ?? 50)),
                            'explanation' => $rec['reason'] ?? 'AI-recommended'
                        ];
                    })->filter()->take($limit)->values();
                } else {
                    Log::error('AI Course Recommendation Error:', ['status' => $response->status(), 'body' => $response->body()]);
                }
            } catch (\Exception $e) {
                Log::error('AI Course Recommendation Exception:', ['error' => $e->getMessage()]);
            }
            
            // Fallback: return empty (could implement basic matching here)
            return collect([]);
        });
    }

    /**
     * Build the AI prompt for course recommendations.
     */
    private function buildCourseRecommendationPrompt(array $courses, array $interests, string $learningStyle, string $role, int $limit): string
    {
        $interestsStr = implode(', ', $interests);
        $coursesJson = json_encode($courses, JSON_PRETTY_PRINT);
        
        return <<<EOT
Analyze the user profile and available courses. Return the top {$limit} most relevant courses.

**User Profile:**
- Interests: {$interestsStr}
- Preferred Learning Style: {$learningStyle}
- Role: {$role}

**Available Courses:**
{$coursesJson}

**Instructions:**
1. Match courses semantically to user interests (e.g., "Public Speaking" relates to "Communication" courses)
2. Consider the user's role (e.g., employers may prefer business courses)
3. Score from 0-100 based on relevance
4. Provide a brief, friendly reason for each match

**Return ONLY a JSON array (no markdown, no code blocks):**
[{"id": 1, "score": 95, "reason": "Perfect match for your interest in Public Speaking"}]
EOT;
    }

    private function getUserVector($user)
    {
        // Simulate a User Vector based on ID (deterministic for testing)
        // In real app, this comes from OpenAI/BERT embedding of Bio
        srand($user->id);
        return array_map(fn() => rand(0, 100) / 100, range(1, 5));
    }

    private function calculateCosineSimilarity($vecA, $vecB)
    {
        if (empty($vecA) || empty($vecB) || count($vecA) !== count($vecB)) return 0;
        
        $dotProduct = 0;
        $normA = 0;
        $normB = 0;

        for ($i = 0; $i < count($vecA); $i++) {
            $dotProduct += $vecA[$i] * $vecB[$i];
            $normA += pow($vecA[$i], 2);
            $normB += pow($vecB[$i], 2);
        }

        $normA = sqrt($normA);
        $normB = sqrt($normB);

        if ($normA * $normB == 0) return 0;
        
        return $dotProduct / ($normA * $normB);
    }

    private function checkAccessibilityMismatch($user, $job)
    {
        $prefs = $user->profile->accessibility_preferences ?? [];
        return false; // Simplified for testing
    }

    /**
     * Get Gemini AI Semantic Match Score between user profile and job description.
     * Uses caching to avoid repeated API calls.
     * 
     * @return float Score from 0-100
     */
    private function getGeminiMatchScore(User $user, Job $job): float
    {
        if (!$this->geminiApiKey || !$this->geminiApiUrl) {
            return 0; // Fallback if API not configured
        }

        // Build user profile text
        $userProfile = $user->profile;
        $userText = implode('. ', array_filter([
            $userProfile?->bio,
            $userProfile?->skills ? 'Skills: ' . implode(', ', $userProfile->skills) : null,
            $userProfile?->interests ? 'Interests: ' . implode(', ', $userProfile->interests) : null,
        ]));

        // Build job text
        $jobText = implode('. ', array_filter([
            $job->title,
            $job->description,
            $job->skills_required ? 'Required: ' . implode(', ', $job->skills_required) : null,
        ]));

        if (empty($userText) || empty($jobText)) {
            return 0;
        }

        // Cache key to avoid repeated API calls
        // Cache key to avoid repeated API calls
        $cacheKey = 'mega_match_' . md5($userText . $jobText);
        
        return Cache::remember($cacheKey, 3600, function () use ($userText, $jobText) {
            try {
                $prompt = <<<EOT
Rate matching (0-100) for Candidate vs Job.
CANDIDATE: $userText
JOB: $jobText
Return JSON: {"score": <number>, "reason": "<short text>"}
EOT;

                $response = Http::withOptions(['verify' => false, 'timeout' => 10])
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . env('AI_API_KEY')
                    ])
                    ->post(env('AI_API_URL'), [
                        'model' => env('AI_MODEL', 'openai-gpt-oss-120b'),
                        'messages' => [
                            ['role' => 'system', 'content' => 'You are a Job Matcher AI. Return valid JSON only.'],
                            ['role' => 'user', 'content' => $prompt]
                        ],
                        'temperature' => 0.2
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $content = $data['choices'][0]['message']['content'] ?? '{}';
                    
                    // Clean markdown code blocks if present
                    $cleanJson = preg_replace('/^```json\s*|\s*```$/', '', $content);
                    $result = json_decode($cleanJson, true);
                    
                    Log::info("AI Job Match Score: " . ($result['score'] ?? 0));
                    return (float) ($result['score'] ?? 0);
                } else {
                    Log::error("AI Match Error: " . $response->body());
                }
            } catch (\Exception $e) {
                Log::error('AI Match Exception: ' . $e->getMessage());
            }
            return 0;
        });
    }

    /**
     * Get Gemini AI Semantic Match Score for Courses.
     */
    private function getGeminiCourseMatchScore(User $user, Course $course): float
    {
        if (!$this->geminiApiKey || !$this->geminiApiUrl) {
            return 0;
        }

        $userProfile = $user->profile;
        $userText = implode('. ', array_filter([
            $userProfile?->bio,
            $userProfile?->interests ? 'Interests: ' . implode(', ', $userProfile->interests) : null,
            $userProfile?->learning_style ? 'Prefers: ' . $userProfile->learning_style . ' learning' : null,
        ]));

        $courseText = implode('. ', array_filter([
            $course->title,
            $course->summary,
            $course->category ? 'Category: ' . $course->category : null,
            $course->tags ? 'Topics: ' . implode(', ', $course->tags) : null,
        ]));

        if (empty($userText) || empty($courseText)) {
            return 0;
        }

        $cacheKey = 'mega_course_' . md5($userText . $courseText);
        
        return Cache::remember($cacheKey, 3600, function () use ($userText, $courseText) {
            try {
                $prompt = <<<EOT
Rate matching (0-100) for Learner vs Course.
LEARNER: $userText
COURSE: $courseText
Return JSON: {"score": <number>, "reason": "<short text>"}
EOT;

                $response = Http::withOptions(['verify' => false, 'timeout' => 10])
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . env('AI_API_KEY')
                    ])
                    ->post(env('AI_API_URL'), [
                        'model' => env('AI_MODEL', 'openai-gpt-oss-120b'),
                        'messages' => [
                            ['role' => 'system', 'content' => 'You are a Course Recommender AI. Return valid JSON only.'],
                            ['role' => 'user', 'content' => $prompt]
                        ],
                        'temperature' => 0.2
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $content = $data['choices'][0]['message']['content'] ?? '{}';
                    $cleanJson = preg_replace('/^```json\s*|\s*```$/', '', $content);
                    $result = json_decode($cleanJson, true);
                    
                    Log::info("AI Course Match Score: " . ($result['score'] ?? 0));
                    return (float) ($result['score'] ?? 0);
                }
            } catch (\Exception $e) {
                Log::error('AI Course Match Error: ' . $e->getMessage());
            }
            return 0;
        });
    }
}

