<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiService
{
    private string $apiKey;
    private string $apiUrl;
    private string $model;

    public function __construct()
    {
        $apiKeyEnv = env('AI_API_KEY', '');
        $this->apiKey = trim($apiKeyEnv);
        
        $apiUrlEnv = env('AI_API_URL', '');
        $this->apiUrl = trim($apiUrlEnv);
        
        $modelEnv = env('AI_MODEL', 'openai-gpt-oss-120b');
        $this->model = trim($modelEnv);
    }

    public function processMessage(string $message, string $currentUrl, ?array $pageStructure = null): array
    {
        if (!$this->apiKey) {
            return ['reply' => "API Key missing!", 'action' => 'none', 'voice_summary' => "I have no brain."];
        }

        $routesJson = json_encode($this->getRouteMap());
        
        $userRole = auth()->check() ? auth()->user()->role : 'guest';
        $isAuthenticated = auth()->check() ? 'true' : 'false';
        
        $domContext = "No visible interactive elements.";
        $detectedInputs = [];

        if ($pageStructure && isset($pageStructure['elements'])) {
            $elements = $pageStructure['elements'];
            $domContext = "Visible Elements on Screen:\n" . json_encode($elements);
            
            foreach ($elements as $el) {
                $tagType = $el['tag'];
                $isInput = $tagType === 'input';
                $isTextArea = $tagType === 'textarea';
                $isSelect = $tagType === 'select';
                
                if ($isInput || $isTextArea || $isSelect) {
                    $label = $el['id'];
                    $hasText = false;
                    $hasName = false;
                    
                    if (array_key_exists('text', $el)) {
                        $label = $el['text'];
                        $hasText = true;
                    }
                    if ($hasText === false) {
                        if (array_key_exists('name', $el)) {
                            $label = $el['name'];
                            $hasName = true;
                        }
                    }

                    $type = $tagType;
                    if (array_key_exists('type', $el)) {
                        $type = $el['type'];
                    }
                    
                    if ($type === 'checkbox') {
                        $checkedState = 'UNCHECKED';
                        if (isset($el['checked'])) {
                           if ($el['checked']) {
                               $checkedState = 'CHECKED';
                           }
                        }
                        
                        $info = "- {$label} (ID: #{$el['id']}, Type: CHECKBOX, Currently: {$checkedState}) [Use value \"true\" to check, \"false\" to uncheck]";
                    } else {
                        $info = "- {$label} (ID: #{$el['id']}, Type: {$type})";
                        
                        $isSelectTag = $el['tag'] === 'select';
                        $hasOptions = !empty($el['options']);
                        
                        if ($isSelectTag) {
                            if ($hasOptions) {
                                $opts = [];
                                foreach ($el['options'] as $o) {
                                    $opts[] = "{$o['value']} ({$o['text']})";
                                }
                                $joinedOpts = implode(', ', $opts);
                                $info = $info . " [Select one of these EXACT values: " . $joinedOpts . "]";
                            }
                        }
                    }
                    
                    $detectedInputs[] = $info;
                }
            }
        }
        
        $inputContext = "";
        if (!empty($detectedInputs)) {
            $inputContext = "DETECTED FORM FIELDS:\n" . implode("\n", $detectedInputs);
            Log::info("AI Input Context Built", ['context' => $inputContext]);
        } else {
            Log::warning("No inputs detected in PageStructure", ['structure' => $pageStructure]);
        }

        $storedFields = session('ablebot_stored_fields', []);
        $storedFieldsContext = "No fields stored yet.";
        if (!empty($storedFields)) {
            $storedFieldsList = [];
            foreach ($storedFields as $selector => $value) {
                $storedFieldsList[] = "- {$selector}: \"{$value}\" (ALREADY COLLECTED - DO NOT ASK AGAIN)";
            }
            $storedFieldsContext = "ALREADY COLLECTED FIELDS (DO NOT ASK ABOUT THESE):\n" . implode("\n", $storedFieldsList);
            Log::info("AI Stored Fields Context", ['stored' => $storedFields]);
        }


        $tools = [
            [
                'type' => 'function',
                'function' => [
                    'name' => 'navigate',
                    'description' => 'Navigate to a specific URL based on user intent.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'url' => ['type' => 'string', 'description' => 'The full absolute URL to navigate to.']
                        ],
                        'required' => ['url']
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'navigate',
                    'description' => 'Navigate to a specific URL based on user intent.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'url' => ['type' => 'string', 'description' => 'The full absolute URL to navigate to.']
                        ],
                        'required' => ['url']
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'fill_input',
                    'description' => 'Fill a text input field, textarea, or select box.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'selector' => ['type' => 'string', 'description' => 'The ID selector (e.g. #email) to fill.'],
                            'value' => ['type' => 'string', 'description' => 'The value to type.'],
                            'feedback_question' => ['type' => 'string', 'description' => 'Verbal response to speak to the user. REQUIRED for assisted form filling to ask the next question.']
            ]
        ];

        $systemPrompt = <<<EOT
You are an AI Navigation Assistant for AbleLink.
Your ONLY goal is to help users navigate the website.

CONTEXT:
Current URL: {$currentUrl}
Available Routes: {$routesJson}

INSTRUCTIONS:
1. Analyze the user's request.
2. If they want to go somewhere, find the best matching URL from 'Available Routes'.
3. Call the 'navigate' tool with that URL.
4. If the request is not about navigation, strictly reply: "I can only help you navigate. Where would you like to go?"

Do NOT hallucinate URLs. Use ONLY the provided Available Routes.
EOT;

        try {
            // OpenAI Spec Request
            $messages = [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $message]
            ];
            
            // OpenAI Spec Request
            $httpRequest = Http::withOptions(['verify' => false, 'timeout' => 60]);
            $httpRequestWithHeaders = $httpRequest->withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->apiKey
                ]);
            
            $postData = [
                    'model' => $this->model,
                    'messages' => $messages,
                    'tools' => $tools,
                    'tool_choice' => 'auto'
                ];
            
            $response = $httpRequestWithHeaders->post($this->apiUrl, $postData);

            if ($response->failed()) {
                Log::error("AI Error {$response->status()}: " . $response->body());
                return ['reply' => "Connection Error ({$response->status()})", 'action' => 'none', 'voice_summary' => "Error connecting."];
            }

            $data = $response->json();
            $choice = null;
            if (isset($data['choices'][0]['message'])) {
                 $choice = $data['choices'][0]['message'];
            }
            
            Log::info("MegaLLM Choice", [$choice]);

            $hasToolCalls = false;
            if (isset($choice['tool_calls'])) {
                if (count($choice['tool_calls']) > 0) {
                    $hasToolCalls = true;
                }
            }

            if ($hasToolCalls === true) {
                $actions = [];
                $summary = "";

                foreach ($choice['tool_calls'] as $toolCall) {
                    $func = $toolCall['function'];
                    $toolName = $func['name'];
                    $args = json_decode($func['arguments'], true);






                    $actions[] = ['name' => $toolName, 'args' => $args];
                    
                    if ($summary === "") {
                        $toolSummaries = [
                            'navigate' => 'Navigating to page.',
                        ];
                        
                        $summary = "Executing action.";
                        if (array_key_exists($toolName, $toolSummaries)) {
                            $summary = $toolSummaries[$toolName];
                        }
                    }
                }

                $aiText = '';
                if (isset($choice['content'])) {
                    $aiText = $choice['content'];
                    if ($aiText === null) {
                         $aiText = '';
                    }
                }
                
                $userReply = $summary;
                if (!empty(trim($aiText))) {
                    $userReply = $aiText;
                }
                
                if (str_starts_with($summary, 'Filling form')) {
                    if (empty(trim($aiText))) {
                         $userReply = "Filling out the form for you.";
                    }
                }

                if (session('ablebot_interactive_mode', false)) {
                    $history[] = ['role' => 'user', 'content' => $message];
                    $history[] = ['role' => 'assistant', 'content' => $userReply];
                    session(['ablebot_history' => array_slice($history, -100)]);
                }

                return [
                    'reply' => ucfirst($userReply),     
                    'actions' => $actions,
                    'voice_summary' => $userReply       
                ];
            } else {
                $text = "I didn't understand.";
                if (isset($choice['content'])) {
                    if ($choice['content'] !== null) {
                        $text = $choice['content'];
                    }
                }
                
                if (session('ablebot_interactive_mode', false)) {
                    $history[] = ['role' => 'user', 'content' => $message];
                    $history[] = ['role' => 'assistant', 'content' => $text];
                    session(['ablebot_history' => array_slice($history, -100)]);
                }
                
                return [
                    'reply' => $text,
                    'action' => 'message',
                    'voice_summary' => $text
                ];
            }

        } catch (\Exception $e) {
            Log::error("AiService Exception: " . $e->getMessage());
            return ['reply' => "Error: " . $e->getMessage(), 'action' => 'none', 'voice_summary' => "System error."];
        }
    }

    private function getRouteMap(): array
    {
        $role = 'guest';
        $isAuthenticated = auth()->check();
        
        if ($isAuthenticated) {
            $user = auth()->user();
            $role = $user->role;
        }
        

        $routes = [];
        $routes['home'] = url('/');
        $routes['login'] = url('/login');
        $routes['register'] = url('/register');
        $routes['courses'] = url('/courses');
        $routes['learning hub'] = url('/courses');
        $routes['aid directory'] = url('/aid');
        $routes['jobs'] = url('/jobs');
        $routes['job search'] = url('/jobs');
        $routes['admin login'] = url('/admin/login');
        $routes['upload'] = url('/upload');
        $routes['ocr'] = url('/upload');
        $routes['simplify text'] = url('/upload');
        $routes['text extraction'] = url('/upload');
        

        if ($isAuthenticated) {
            $routes['dashboard'] = url('/dashboard');
            $routes['profile'] = url('/profile');
            $routes['edit profile'] = url('/profile/edit');
            $routes['notifications'] = url('/notifications');
            $routes['forum'] = url('/forum');
            $routes['messages'] = url('/messages');
            $routes['community'] = url('/community');
            $routes['events'] = url('/community/events');
            $routes['create event'] = url('/community/events/create');
            $routes['matrimony'] = url('/community/matrimony');
        }
        
        if ($role === 'disabled') {
            $routes['accessibility settings'] = url('/accessibility');
            $routes['my applications'] = url('/candidate/applications');
            $routes['assistance requests'] = url('/user/assistance');
            $routes['request assistance'] = url('/user/assistance/create');
            $routes['health dashboard'] = url('/health/dashboard');
            $routes['my requests'] = url('/user/requests');
            $routes['appointments'] = url('/user/appointments');
            $routes['my appointments'] = url('/user/appointments');
            $routes['appointment calendar'] = url('/user/appointments/calendar');
        }
        
        if ($role === 'caregiver') {
            $routes['caregiver dashboard'] = url('/caregiver/dashboard');
            $routes['manage appointments'] = url('/caregiver/appointments');
            $routes['doctor appointments'] = url('/caregiver/appointments'); 
            $routes['schedule appointment'] = url('/caregiver/appointments');
            $routes['caregiver appointment calendar'] = url('/caregiver/appointments/calendar');
        }
        
        if ($role === 'employer') {
            $routes['employer dashboard'] = url('/employer/jobs');
            $routes['my jobs'] = url('/employer/jobs');
            $routes['post a job'] = url('/employer/jobs/create');
            $routes['employer applications'] = url('/employer/applications');
            $routes['employer interviews'] = url('/employer/interviews');
            $routes['company profile'] = url('/employer/profile');
            $routes['edit company profile'] = url('/employer/profile/edit');
            $routes['employer reports'] = url('/employer/reports');
        }
        
        if ($role === 'volunteer') {
            $routes['volunteer dashboard'] = url('/volunteer/requests');
            $routes['help requests'] = url('/volunteer/requests');
            $routes['volunteer profile'] = url('/volunteer/profile');
            $routes['edit volunteer profile'] = url('/volunteer/profile/edit');
            $routes['active assistance'] = url('/volunteer/assistance/active');
            $routes['active tasks'] = url('/volunteer/assistance/active');
            $routes['task history'] = url('/volunteer/assistance/history');
            $routes['assistance history'] = url('/volunteer/assistance/history');
        }
        
        if ($role === 'admin') {
            $routes['admin dashboard'] = url('/admin/dashboard');
            $routes['admin users'] = url('/admin/users');
            $routes['admin volunteers'] = url('/admin/volunteers');
            $routes['admin employers'] = url('/admin/employers');
            $routes['admin caregivers'] = url('/admin/caregivers');
            $routes['admin jobs'] = url('/admin/jobs');
            $routes['admin courses'] = url('/admin/courses');
            $routes['admin aid'] = url('/admin/aid');
            $routes['admin moderation'] = url('/admin/moderation');
            $routes['admin community'] = url('/admin/community');
        }
        
        return $routes;
    }

    public function generateCertificateMessage(\App\Models\Auth\User $user, \App\Models\Education\Course $course): string
    {
        if ($this->apiKey === '') {
            $courseTitle = $course->title;
            return "This certificate recognizes the successful completion of the course {$courseTitle}.";
        }

        $userFullName = $user->name;
        $courseTitle = $course->title;
        $promptMessage = "Generate a short, professional, and inspiring congratulatory message for {$userFullName} for completing the course '{$courseTitle}'. Focus on the achievement and future potential. Max 2 sentences. Do not use hashtags or emojis.";

        try {
            $httpRequest = Http::withOptions(['verify' => false, 'timeout' => 30]);
            $httpRequestWithHeaders = $httpRequest->withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->apiKey
            ]);
            
            $requestJson = [
                'model' => $this->model,
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a professional academic assistant.'],
                    ['role' => 'user', 'content' => $promptMessage]
                ],
                'max_tokens' => 100,
                'temperature' => 0.7
            ];

            $apiResponse = $httpRequestWithHeaders->post($this->apiUrl, $requestJson);

            if ($apiResponse->successful()) {
                $responseData = $apiResponse->json();
                $generatedContent = "Congratulations on completing {$courseTitle}!";
                
                if (isset($responseData['choices'][0]['message']['content'])) {
                    $generatedContent = $responseData['choices'][0]['message']['content'];
                }
                
                return trim($generatedContent);
            }
            
            $responseBody = $apiResponse->body();
            Log::error("AI Certificate Error: " . $responseBody);

        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            Log::error("AI Certificate Exception: " . $errorMsg);
        }

        return "Congratulations on successfully completing the course '{$courseTitle}'. This certificate recognizes your dedication and hard work throughout the curriculum.";
    }
}
