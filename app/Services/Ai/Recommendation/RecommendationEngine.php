<?php

namespace App\Services\Ai\Recommendation;

use App\Models\Auth\User;
use App\Models\Employment\Job;
use App\Models\Education\Course;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class RecommendationEngine
{
    private string $geminiApiKey;
    private string $geminiApiUrl;

    public function __construct()
    {
        $this->geminiApiKey = (string) env('AI_API_KEY', '');
        $this->geminiApiUrl = (string) env('AI_API_URL', '');
    }

    public function getJobRecommendations(User $currentUser, int $limitCount = 5): Collection
    {
        $recommendationCollection = new Collection([]);
        $userRole = $currentUser->hasRole('employer');
        
        if ($userRole === true) {
            return $recommendationCollection;
        }

        $activeJobQuery = Job::where('status', 'active');
        $activeJobQuery->with('employer.employerProfile');
        $availableJobsList = $activeJobQuery->get();
        
        $areJobsEmpty = $availableJobsList->isEmpty();
        if ($areJobsEmpty === true) {
            return $recommendationCollection;
        }

        $userProfileObject = $currentUser->profile;
        $userSkillsList = [];
        $userInterestsList = [];
        $userAccessibilityPreferences = [];
        
        if ($userProfileObject !== null) {
            if ($userProfileObject->skills !== null) {
                 $userSkillsList = $userProfileObject->skills;
            }
            
            if ($userProfileObject->interests !== null) {
                $userInterestsList = $userProfileObject->interests;
            }
            
            if ($userProfileObject->accessibility_preferences !== null) {
                $userAccessibilityPreferences = $userProfileObject->accessibility_preferences;
            }
        }

        $areSkillsEmpty = empty($userSkillsList);
        if ($areSkillsEmpty === true) {
            return $recommendationCollection;
        }

        $formattedJobDataList = [];
        
        foreach ($availableJobsList as $currentJob) {
            $currentCompanyName = 'Company';
            $jobEmployer = $currentJob->employer;
            
            if ($jobEmployer !== null) {
                $employerProfile = $jobEmployer->employerProfile;
                if ($employerProfile !== null) {
                    $profileCompanyName = $employerProfile->company_name;
                    if ($profileCompanyName !== null) {
                        $currentCompanyName = $profileCompanyName;
                    }
                }
            }
            
            $jobDescription = $currentJob->description;
            $truncatedDescription = Str::limit($jobDescription, 300);
            
            $jobSkillsRequired = [];
            if ($currentJob->skills_required !== null) {
                $jobSkillsRequired = $currentJob->skills_required;
            }

            $currentJobData = [];
            $currentJobData['id'] = $currentJob->id;
            $currentJobData['title'] = $currentJob->title;
            $currentJobData['description'] = $truncatedDescription;
            $currentJobData['skills_required'] = $jobSkillsRequired;
            $currentJobData['location'] = $currentJob->location;
            $currentJobData['job_type'] = $currentJob->job_type;
            $currentJobData['remote_available'] = $currentJob->remote_work_available;
            $currentJobData['wheelchair_accessible'] = $currentJob->wheelchair_accessible;
            $currentJobData['company'] = $currentCompanyName;
            
            $formattedJobDataList[] = $currentJobData;
        }

        $skillsJsonString = json_encode($userSkillsList);
        $totalJobCount = $availableJobsList->count();
        $uniqueCacheKey = 'ai_job_recs_' . $currentUser->id . '_' . md5($skillsJsonString . $totalJobCount);
        
        $isCached = Cache::has($uniqueCacheKey);
        if ($isCached === true) {
            $cachedResult = Cache::get($uniqueCacheKey);
            return $cachedResult;
        }
        
        $finalRecommendationsList = [];
        
        try {
            $userHasDisability = $currentUser->hasRole('disabled');
            $aiPromptString = $this->buildJobRecommendationPrompt($formattedJobDataList, $userSkillsList, $userInterestsList, $userAccessibilityPreferences, $userHasDisability, $limitCount);
            
            $httpRequestOptions = ['verify' => false, 'timeout' => 30];
            $httpRequestInfo = Http::withOptions($httpRequestOptions);
            
            $httpRequestHeaders = [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->geminiApiKey
            ];
            $httpRequestWithHeaders = $httpRequestInfo->withHeaders($httpRequestHeaders);
            
            $aiModelName = env('AI_MODEL', 'gpt-4o-mini');
            
            $requestPayload = [];
            $requestPayload['model'] = $aiModelName;
            
            $systemMessage = [];
            $systemMessage['role'] = 'system';
            $systemMessage['content'] = 'You are a Job Recommendation AI. Analyze jobs and candidate profile to find the best matches. Return ONLY valid JSON array, no markdown.';
            
            $userMessage = [];
            $userMessage['role'] = 'user';
            $userMessage['content'] = $aiPromptString;
            
            $requestPayload['messages'] = [$systemMessage, $userMessage];
            $requestPayload['temperature'] = 0.3;
            
            $apiResponse = $httpRequestWithHeaders->post($this->geminiApiUrl, $requestPayload);
            $isResponseSuccessful = $apiResponse->successful();

            if ($isResponseSuccessful === true) {
                $responseData = $apiResponse->json();
                $responseContentString = '[]';
                
                if (isset($responseData['choices'])) {
                    $choicesArray = $responseData['choices'];
                    if (isset($choicesArray[0])) {
                        $firstChoice = $choicesArray[0];
                        if (isset($firstChoice['message'])) {
                            $choiceMessage = $firstChoice['message'];
                            if (isset($choiceMessage['content'])) {
                                $responseContentString = $choiceMessage['content'];
                            }
                        }
                    }
                }
                
                $trimmedContent = trim($responseContentString);
                $cleanedJsonString = preg_replace('/^```json\s*|\s*```$/', '', $trimmedContent);
                $decodedJsonData = json_decode($cleanedJsonString, true);
                
                $rawRecommendationData = [];
                if (is_array($decodedJsonData)) {
                    $rawRecommendationData = $decodedJsonData;
                }
                
                Log::info('AI Job Recommendations:', ['count' => count($rawRecommendationData)]);
                
                foreach ($rawRecommendationData as $recommendationItem) {
                    $recommendedJobId = $recommendationItem['id'];
                    $matchedJobObject = $availableJobsList->firstWhere('id', $recommendedJobId);
                    
                    if ($matchedJobObject !== null) {
                        $matchScore = 50;
                        if (isset($recommendationItem['score'])) {
                            $rawScore = $recommendationItem['score'];
                            $minScore = max(0, $rawScore);
                            $finalBoundedScore = min(100, $minScore);
                            $matchScore = $finalBoundedScore;
                        }
                        
                        $matchExplanation = 'AI-recommended';
                        if (isset($recommendationItem['reason'])) {
                            $matchExplanation = $recommendationItem['reason'];
                        }
                        
                        $finalCompanyName = 'Company Confidential';
                        $matchedJobEmployer = $matchedJobObject->employer;
                        
                        if ($matchedJobEmployer !== null) {
                            $matchedEmployerProfile = $matchedJobEmployer->employerProfile;
                            if ($matchedEmployerProfile !== null) {
                                $matchedProfileCompanyName = $matchedEmployerProfile->company_name;
                                if ($matchedProfileCompanyName !== null) {
                                    $finalCompanyName = $matchedProfileCompanyName;
                                }
                            }
                        }

                        $processedRecommendationItem = [];
                        $processedRecommendationItem['job'] = $matchedJobObject;
                        $processedRecommendationItem['score'] = $matchScore;
                        $processedRecommendationItem['explanation'] = $matchExplanation;
                        $processedRecommendationItem['company_name'] = $finalCompanyName;

                        $finalRecommendationsList[] = $processedRecommendationItem;
                    }
                }
                
                usort($finalRecommendationsList, function($firstItem, $secondItem) {
                    $firstScore = $firstItem['score'];
                    $secondScore = $secondItem['score'];
                    return $secondScore <=> $firstScore;
                });
                
                $slicedRecommendationsList = array_slice($finalRecommendationsList, 0, $limitCount);
                $finalRecommendationsList = $slicedRecommendationsList;

            } else {
                $responseStatus = $apiResponse->status();
                $responseBody = $apiResponse->body();
                Log::error('AI Job Recommendation Error:', ['status' => $responseStatus, 'body' => $responseBody]);
            }
        } catch (\Exception $exceptionObject) {
            $exceptionMessage = $exceptionObject->getMessage();
            Log::error('AI Job Recommendation Exception:', ['error' => $exceptionMessage]);
        }
        
        $resultCollection = new Collection($finalRecommendationsList);
        Cache::put($uniqueCacheKey, $resultCollection, 3600);
        
        return $resultCollection;
    }

    private function buildJobRecommendationPrompt(array $jobDataList, array $skillsList, array $interestsList, array $accessibilityPreferences, bool $hasDisabilityFlag, int $limitCount): string
    {
        $skillsString = implode(', ', $skillsList);
        
        $interestsString = 'Not specified';
        $areInterestsNotEmpty = !empty($interestsList);
        if ($areInterestsNotEmpty === true) {
            $interestsString = implode(', ', $interestsList);
        }
        
        $accessibilityString = 'None specified';
        $arePreferencesNotEmpty = !empty($accessibilityPreferences);
        if ($arePreferencesNotEmpty === true) {
            $accessibilityString = json_encode($accessibilityPreferences);
        }
        
        $jobsJsonString = json_encode($jobDataList, JSON_PRETTY_PRINT);
        
        $disabilityContextString = "";
        if ($hasDisabilityFlag === true) {
            $disabilityContextString = "The candidate has a disability. Prioritize jobs with accessibility features (wheelchair_accessible, remote_available).";
        }
        
        $finalPromptString = "Analyze the candidate profile and available jobs. Return the top " . $limitCount . " most relevant jobs.\n\n";
        $finalPromptString .= "**Candidate Profile:**\n";
        $finalPromptString .= "- Skills: " . $skillsString . "\n";
        $finalPromptString .= "- Interests: " . $interestsString . "\n";
        $finalPromptString .= "- Accessibility Needs: " . $accessibilityString . "\n";
        $finalPromptString .= $disabilityContextString . "\n\n";
        $finalPromptString .= "**Available Jobs:**\n";
        $finalPromptString .= $jobsJsonString . "\n\n";
        $finalPromptString .= "**Instructions:**\n";
        $finalPromptString .= "1. Match jobs semantically to candidate skills (e.g., \"Laravel\" relates to \"PHP Developer\" jobs)\n";
        $finalPromptString .= "2. Consider interests for better cultural fit\n";
        $finalPromptString .= "3. If candidate has disability, prioritize accessible workplaces\n";
        $finalPromptString .= "4. Score from 0-100 based on relevance\n";
        $finalPromptString .= "5. Provide a brief, friendly reason for each match\n\n";
        $finalPromptString .= "**Return ONLY a JSON array (no markdown, no code blocks):**\n";
        $finalPromptString .= "[{\"id\": 1, \"score\": 95, \"reason\": \"Great match for your PHP and Laravel skills\"}]";

        return $finalPromptString;
    }

    public function getCourseRecommendations(User $currentUser, int $limitCount = 5): Collection
    {
        $publishedCourseQuery = Course::where('published_at', '<=', now());
        $availableCoursesList = $publishedCourseQuery->get();
        $recommendationCollection = new Collection([]);
        
        $areCoursesEmpty = $availableCoursesList->isEmpty();
        if ($areCoursesEmpty === true) {
            return $recommendationCollection;
        }

        $userProfileObject = $currentUser->profile;
        $userInterestsList = [];
        $userLearningStyle = 'any';
        
        if ($userProfileObject !== null) {
            if ($userProfileObject->interests !== null) {
                $userInterestsList = $userProfileObject->interests;
            }
            if ($userProfileObject->learning_style !== null) {
                $userLearningStyle = $userProfileObject->learning_style;
            }
        }
        
        $userRoleString = 'user';
        if ($currentUser->role !== null) {
            $userRoleString = $currentUser->role;
        }

        $areInterestsEmpty = empty($userInterestsList);
        if ($areInterestsEmpty === true) {
            return $recommendationCollection;
        }

        $formattedCourseDataList = [];
        foreach ($availableCoursesList as $currentCourse) {
            $currentCourseData = [];
            $currentCourseData['id'] = $currentCourse->id;
            $currentCourseData['title'] = $currentCourse->title;
            $currentCourseData['summary'] = $currentCourse->summary;
            $currentCourseData['category'] = $currentCourse->category;
            $currentCourseData['level'] = $currentCourse->level;
            
            $formattedCourseDataList[] = $currentCourseData;
        }

        $interestsJsonString = json_encode($userInterestsList);
        $totalCourseCount = $availableCoursesList->count();
        $uniqueCacheKey = 'ai_course_recs_' . $currentUser->id . '_' . md5($interestsJsonString . $totalCourseCount);
        
        $isCached = Cache::has($uniqueCacheKey);
        if ($isCached === true) {
            $cachedResult = Cache::get($uniqueCacheKey);
            return $cachedResult;
        }

        $finalRecommendationsList = [];

        try {
            $aiPromptString = $this->buildCourseRecommendationPrompt($formattedCourseDataList, $userInterestsList, $userLearningStyle, $userRoleString, $limitCount);
            
            $httpRequestOptions = ['verify' => false, 'timeout' => 30];
            $httpRequestInfo = Http::withOptions($httpRequestOptions);
            
            $httpRequestHeaders = [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->geminiApiKey
            ];
            $httpRequestWithHeaders = $httpRequestInfo->withHeaders($httpRequestHeaders);
            
            $aiModelName = env('AI_MODEL', 'gpt-4o-mini');
            
            $requestPayload = [];
            $requestPayload['model'] = $aiModelName;
            
            $systemMessage = [];
            $systemMessage['role'] = 'system';
            $systemMessage['content'] = 'You are a Course Recommendation AI. Analyze courses and user profile to find the best matches. Return ONLY valid JSON array, no markdown.';
            
            $userMessage = [];
            $userMessage['role'] = 'user';
            $userMessage['content'] = $aiPromptString;
            
            $requestPayload['messages'] = [$systemMessage, $userMessage];
            $requestPayload['temperature'] = 0.3;
            
            $apiResponse = $httpRequestWithHeaders->post($this->geminiApiUrl, $requestPayload);
            $isResponseSuccessful = $apiResponse->successful();

            if ($isResponseSuccessful === true) {
                $responseData = $apiResponse->json();
                $responseContentString = '[]';
                
                if (isset($responseData['choices'])) {
                    $choicesArray = $responseData['choices'];
                    if (isset($choicesArray[0])) {
                        $firstChoice = $choicesArray[0];
                        if (isset($firstChoice['message'])) {
                            $choiceMessage = $firstChoice['message'];
                            if (isset($choiceMessage['content'])) {
                                $responseContentString = $choiceMessage['content'];
                            }
                        }
                    }
                }
                
                $trimmedContent = trim($responseContentString);
                $cleanedJsonString = preg_replace('/^```json\s*|\s*```$/', '', $trimmedContent);
                $decodedJsonData = json_decode($cleanedJsonString, true);
                
                $rawRecommendationData = [];
                if (is_array($decodedJsonData)) {
                    $rawRecommendationData = $decodedJsonData;
                }
                
                Log::info('AI Course Recommendations:', ['count' => count($rawRecommendationData)]);
                
                foreach ($rawRecommendationData as $recommendationItem) {
                    $recommendedCourseId = $recommendationItem['id'];
                    $matchedCourseObject = $availableCoursesList->firstWhere('id', $recommendedCourseId);
                    
                    if ($matchedCourseObject !== null) {
                        $matchScore = 50;
                        if (isset($recommendationItem['score'])) {
                             $rawScore = $recommendationItem['score'];
                             $minScore = max(0, $rawScore);
                             $finalBoundedScore = min(100, $minScore);
                             $matchScore = $finalBoundedScore;
                        }
                        
                        $matchExplanation = 'AI-recommended';
                        if (isset($recommendationItem['reason'])) {
                            $matchExplanation = $recommendationItem['reason'];
                        }

                        $processedRecommendationItem = [];
                        $processedRecommendationItem['course'] = $matchedCourseObject;
                        $processedRecommendationItem['score'] = $matchScore;
                        $processedRecommendationItem['explanation'] = $matchExplanation;
                        
                        $finalRecommendationsList[] = $processedRecommendationItem;
                    }
                }
                
                usort($finalRecommendationsList, function($firstItem, $secondItem) {
                    $firstScore = $firstItem['score'];
                    $secondScore = $secondItem['score'];
                    return $secondScore <=> $firstScore;
                });

                $slicedRecommendationsList = array_slice($finalRecommendationsList, 0, $limitCount);
                $finalRecommendationsList = $slicedRecommendationsList;

            } else {
                $responseStatus = $apiResponse->status();
                $responseBody = $apiResponse->body();
                Log::error('AI Course Recommendation Error:', ['status' => $responseStatus, 'body' => $responseBody]);
            }
        } catch (\Exception $exceptionObject) {
            $exceptionMessage = $exceptionObject->getMessage();
            Log::error('AI Course Recommendation Exception:', ['error' => $exceptionMessage]);
        }
        
        $resultCollection = new Collection($finalRecommendationsList);
        Cache::put($uniqueCacheKey, $resultCollection, 3600);
        
        return $resultCollection;
    }

    private function buildCourseRecommendationPrompt(array $courseDataList, array $interestsList, string $learningStyle, string $userRoleString, int $limitCount): string
    {
        $interestsString = implode(', ', $interestsList);
        $coursesJsonString = json_encode($courseDataList, JSON_PRETTY_PRINT);
        
        $finalPromptString = "Analyze the user profile and available courses. Return the top " . $limitCount . " most relevant courses.\n\n";
        $finalPromptString .= "**User Profile:**\n";
        $finalPromptString .= "- Interests: " . $interestsString . "\n";
        $finalPromptString .= "- Preferred Learning Style: " . $learningStyle . "\n";
        $finalPromptString .= "- Role: " . $userRoleString . "\n\n";
        $finalPromptString .= "**Available Courses:**\n";
        $finalPromptString .= $coursesJsonString . "\n\n";
        $finalPromptString .= "**Instructions:**\n";
        $finalPromptString .= "1. Match courses semantically to user interests (e.g., \"Public Speaking\" relates to \"Communication\" courses)\n";
        $finalPromptString .= "2. Consider the user's role (e.g., employers may prefer business courses)\n";
        $finalPromptString .= "3. Score from 0-100 based on relevance\n";
        $finalPromptString .= "4. Provide a brief, friendly reason for each match\n\n";
        $finalPromptString .= "**Return ONLY a JSON array (no markdown, no code blocks):**\n";
        $finalPromptString .= "[{\"id\": 1, \"score\": 95, \"reason\": \"Perfect match for your interest in Public Speaking\"}]";

        return $finalPromptString;
    }

    private function getUserVector(User $currentUser): array
    {
        srand($currentUser->id);
        $userVector = [];
        for ($i = 0; $i < 5; $i++) {
            $randomValue = rand(0, 100) / 100;
            $userVector[] = $randomValue;
        }
        return $userVector;
    }

    private function calculateCosineSimilarity(array $vectorA, array $vectorB): float
    {
        $isVectorAEmpty = empty($vectorA);
        if ($isVectorAEmpty === true) {
            return 0.0;
        }
        
        $isVectorBEmpty = empty($vectorB);
        if ($isVectorBEmpty === true) {
            return 0.0;
        }
        
        $countVectorA = count($vectorA);
        $countVectorB = count($vectorB);
        
        if ($countVectorA !== $countVectorB) {
            return 0.0;
        }
        
        $dotProductSum = 0;
        $normASum = 0;
        $normBSum = 0;

        for ($i = 0; $i < $countVectorA; $i++) {
            $valueA = $vectorA[$i];
            $valueB = $vectorB[$i];
            
            $dotProductSum += $valueA * $valueB;
            $normASum += pow($valueA, 2);
            $normBSum += pow($valueB, 2);
        }

        $normASqrt = sqrt($normASum);
        $normBSqrt = sqrt($normBSum);
        
        $denominator = $normASqrt * $normBSqrt;

        if ($denominator == 0) {
            return 0.0;
        }
        
        return $dotProductSum / $denominator;
    }

    private function checkAccessibilityMismatch(User $currentUser, Job $targetJob): bool
    {
        $userAccessibilityPreferences = [];
        $userProfileObject = $currentUser->profile;
        
        if ($userProfileObject !== null) {
            if ($userProfileObject->accessibility_preferences !== null) {
                $userAccessibilityPreferences = $userProfileObject->accessibility_preferences;
            }
        }
        return false;
    }

    private function getGeminiMatchScore(User $currentUser, Job $targetJob): float
    {
        if ($this->geminiApiKey === '') {
            return 0.0;
        }
        if ($this->geminiApiUrl === '') {
            return 0.0;
        }

        $userProfileObject = $currentUser->profile;
        $userProfileComponents = [];
        
        if ($userProfileObject !== null) {
            if ($userProfileObject->bio !== null) {
                $userProfileComponents[] = $userProfileObject->bio;
            }
            if (!empty($userProfileObject->skills)) {
                $skillsString = implode(', ', $userProfileObject->skills);
                $userProfileComponents[] = 'Skills: ' . $skillsString;
            }
            if (!empty($userProfileObject->interests)) {
                 $interestsString = implode(', ', $userProfileObject->interests);
                $userProfileComponents[] = 'Interests: ' . $interestsString;
            }
        }
        $userProfileText = implode('. ', $userProfileComponents);

        $jobDescriptionComponents = [];
        if ($targetJob->title !== null) {
            $jobDescriptionComponents[] = $targetJob->title;
        }
        if ($targetJob->description !== null) {
            $jobDescriptionComponents[] = $targetJob->description;
        }
        if (!empty($targetJob->skills_required)) {
            $requiredSkillsString = implode(', ', $targetJob->skills_required);
            $jobDescriptionComponents[] = 'Required: ' . $requiredSkillsString;
        }
        $jobDescriptionText = implode('. ', $jobDescriptionComponents);

        if ($userProfileText === '') {
            return 0.0;
        }
        if ($jobDescriptionText === '') {
            return 0.0;
        }

        $uniqueCacheKey = 'mega_match_' . md5($userProfileText . $jobDescriptionText);
        
        $isCached = Cache::has($uniqueCacheKey);
        if ($isCached === true) {
            $cachedScore = Cache::get($uniqueCacheKey);
            return (float) $cachedScore;
        }
        
        $matchScore = 0.0;
        
        try {
            $aiPromptString = "Rate matching (0-100) for Candidate vs Job.\n";
            $aiPromptString .= "CANDIDATE: " . $userProfileText . "\n";
            $aiPromptString .= "JOB: " . $jobDescriptionText . "\n";
            $aiPromptString .= "Return JSON: {\"score\": <number>, \"reason\": \"<short text>\"}";

            $httpRequestOptions = ['verify' => false, 'timeout' => 10];
            $httpRequestInfo = Http::withOptions($httpRequestOptions);
            
            $httpRequestHeaders = [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->geminiApiKey
            ];
            $httpRequestWithHeaders = $httpRequestInfo->withHeaders($httpRequestHeaders);
            
            $aiModelName = env('AI_MODEL', 'openai-gpt-oss-120b');
            
            $requestPayload = [];
            $requestPayload['model'] = $aiModelName;
            
            $systemMessage = [];
            $systemMessage['role'] = 'system';
            $systemMessage['content'] = 'You are a Job Matcher AI. Return valid JSON only.';
            
            $userMessage = [];
            $userMessage['role'] = 'user';
            $userMessage['content'] = $aiPromptString;
            
            $requestPayload['messages'] = [$systemMessage, $userMessage];
            $requestPayload['temperature'] = 0.2;
            
            $apiResponse = $httpRequestWithHeaders->post($this->geminiApiUrl, $requestPayload);
            $isResponseSuccessful = $apiResponse->successful();

            if ($isResponseSuccessful === true) {
                $responseData = $apiResponse->json();
                $responseContentString = '{}';
                
                if (isset($responseData['choices'])) {
                    $choicesArray = $responseData['choices'];
                    if (isset($choicesArray[0])) {
                        $firstChoice = $choicesArray[0];
                        if (isset($firstChoice['message'])) {
                            $choiceMessage = $firstChoice['message'];
                            if (isset($choiceMessage['content'])) {
                                $responseContentString = $choiceMessage['content'];
                            }
                        }
                    }
                }
                
                $cleanedJsonString = preg_replace('/^```json\s*|\s*```$/', '', $responseContentString);
                $resultArray = json_decode($cleanedJsonString, true);
                
                if (isset($resultArray['score'])) {
                    $matchScore = (float) $resultArray['score'];
                }
                
                Log::info("AI Job Match Score: " . $matchScore);
            } else {
                $responseBody = $apiResponse->body();
                Log::error("AI Match Error: " . $responseBody);
            }
        } catch (\Exception $exceptionObject) {
            $exceptionMessage = $exceptionObject->getMessage();
            Log::error('AI Match Exception: ' . $exceptionMessage);
        }
        
        Cache::put($uniqueCacheKey, $matchScore, 3600);
        return $matchScore;
    }

    private function getGeminiCourseMatchScore(User $currentUser, Course $targetCourse): float
    {
        if ($this->geminiApiKey === '') {
            return 0.0;
        }
        if ($this->geminiApiUrl === '') {
            return 0.0;
        }

        $userProfileObject = $currentUser->profile;
        $userProfileComponents = [];
        
        if ($userProfileObject !== null) {
            if ($userProfileObject->bio !== null) {
                $userProfileComponents[] = $userProfileObject->bio;
            }
            if (!empty($userProfileObject->interests)) {
                $interestsString = implode(', ', $userProfileObject->interests);
                $userProfileComponents[] = 'Interests: ' . $interestsString;
            }
            if ($userProfileObject->learning_style !== null) {
                 $userProfileComponents[] = 'Prefers: ' . $userProfileObject->learning_style . ' learning';
            }
        }
        $userProfileText = implode('. ', $userProfileComponents);

        $courseDescriptionComponents = [];
        if ($targetCourse->title !== null) {
            $courseDescriptionComponents[] = $targetCourse->title;
        }
        if ($targetCourse->summary !== null) {
            $courseDescriptionComponents[] = $targetCourse->summary;
        }
        if ($targetCourse->category !== null) {
            $courseDescriptionComponents[] = 'Category: ' . $targetCourse->category;
        }
        if (!empty($targetCourse->tags)) {
            $tagsString = implode(', ', $targetCourse->tags);
            $courseDescriptionComponents[] = 'Topics: ' . $tagsString;
        }
        $courseDescriptionText = implode('. ', $courseDescriptionComponents);

        if ($userProfileText === '') {
            return 0.0;
        }
        if ($courseDescriptionText === '') {
            return 0.0;
        }

        $uniqueCacheKey = 'mega_course_' . md5($userProfileText . $courseDescriptionText);
        
        $isCached = Cache::has($uniqueCacheKey);
        if ($isCached === true) {
             $cachedScore = Cache::get($uniqueCacheKey);
             return (float) $cachedScore;
        }
        
        $matchScore = 0.0;

        try {
            $aiPromptString = "Rate matching (0-100) for Learner vs Course.\n";
            $aiPromptString .= "LEARNER: " . $userProfileText . "\n";
            $aiPromptString .= "COURSE: " . $courseDescriptionText . "\n";
            $aiPromptString .= "Return JSON: {\"score\": <number>, \"reason\": \"<short text>\"}";

            $httpRequestOptions = ['verify' => false, 'timeout' => 10];
            $httpRequestInfo = Http::withOptions($httpRequestOptions);
            
            $httpRequestHeaders = [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->geminiApiKey
            ];
            $httpRequestWithHeaders = $httpRequestInfo->withHeaders($httpRequestHeaders);
            
            $aiModelName = env('AI_MODEL', 'openai-gpt-oss-120b');

            $requestPayload = [];
            $requestPayload['model'] = $aiModelName;
            
            $systemMessage = [];
            $systemMessage['role'] = 'system';
            $systemMessage['content'] = 'You are a Course Recommender AI. Return valid JSON only.';
            
            $userMessage = [];
            $userMessage['role'] = 'user';
            $userMessage['content'] = $aiPromptString;
            
            $requestPayload['messages'] = [$systemMessage, $userMessage];
            $requestPayload['temperature'] = 0.2;
            
            $apiResponse = $httpRequestWithHeaders->post($this->geminiApiUrl, $requestPayload);
            $isResponseSuccessful = $apiResponse->successful();

            if ($isResponseSuccessful === true) {
                $responseData = $apiResponse->json();
                $responseContentString = '{}';
                
                if (isset($responseData['choices'])) {
                    $choicesArray = $responseData['choices'];
                    if (isset($choicesArray[0])) {
                        $firstChoice = $choicesArray[0];
                        if (isset($firstChoice['message'])) {
                            $choiceMessage = $firstChoice['message'];
                            if (isset($choiceMessage['content'])) {
                                $responseContentString = $choiceMessage['content'];
                            }
                        }
                    }
                }
                
                $cleanedJsonString = preg_replace('/^```json\s*|\s*```$/', '', $responseContentString);
                $resultArray = json_decode($cleanedJsonString, true);
                
                if (isset($resultArray['score'])) {
                    $matchScore = (float) $resultArray['score'];
                }
                
                Log::info("AI Course Match Score: " . $matchScore);
            }
        } catch (\Exception $exceptionObject) {
            $exceptionMessage = $exceptionObject->getMessage();
            Log::error('AI Course Match Error: ' . $exceptionMessage);
        }
        
        Cache::put($uniqueCacheKey, $matchScore, 3600);
        return $matchScore;
    }
}

