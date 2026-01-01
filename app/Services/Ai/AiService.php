<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiService
{
    private string $apiKey;
    private string $apiUrl;
    private string $model;
    private PythonAiService $pythonAi;

    public function __construct(PythonAiService $pythonAi)
    {
        $this->apiKey = trim(env('AI_API_KEY', ''));
        $this->apiUrl = trim(env('AI_API_URL', ''));
        $this->model = trim(env('AI_MODEL', 'openai-gpt-oss-120b'));
        $this->pythonAi = $pythonAi;
    }

    public function processMessage(string $message, string $currentUrl, ?array $pageStructure = null): array
    {
        if (!$this->apiKey) {
            return ['reply' => "API Key missing!", 'action' => 'none', 'voice_summary' => "I have no brain."];
        }

        $routesJson = json_encode($this->getRouteMap());
        
        $userRole = auth()->check() ? auth()->user()->role : 'guest';
        $isAuthenticated = auth()->check() ? 'true' : 'false';

        $knowledgeContext = $this->pythonAi->retrieveContext($message);
        $ragSection = $knowledgeContext ? "\n<relevant_knowledge>\n$knowledgeContext\n</relevant_knowledge>\n" : "";
        
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
                    'name' => 'click_element',
                    'description' => 'Click a button, link, or element on the current page.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'selector' => ['type' => 'string', 'description' => 'The ID selector (e.g. #start-btn) to click.']
                        ],
                        'required' => ['selector']
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
                        ],
                        'required' => ['selector', 'value']
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'fill_form',
                    'description' => 'Fill multiple form fields at once including text inputs, selects, and CHECKBOXES. PREFERRED for registration/login forms. For checkboxes, use value "true" to check or "false" to uncheck.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'fields' => [
                                'type' => 'array',
                                'items' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'selector' => ['type' => 'string', 'description' => 'The ID selector (e.g. #email, #terms, #newsletter).'],
                                        'value' => ['type' => 'string', 'description' => 'The value to type. For checkboxes use "true" or "false".']
                                    ],
                                    'required' => ['selector', 'value']
                                ]
                            ]
                        ],
                        'required' => ['fields']
                    ]
                ]
            ],

            [
                'type' => 'function',
                'function' => [
                    'name' => 'confirm_action',
                    'description' => 'Ask user for confirmation before performing a destructive or important action (delete, submit, apply, logout).',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'action_description' => ['type' => 'string', 'description' => 'What action is about to be performed'],
                            'severity' => ['type' => 'string', 'enum' => ['low', 'medium', 'high'], 'description' => 'How risky is this action'],
                            'pending_action' => ['type' => 'string', 'description' => 'The action to execute if user confirms (navigate/click/fill_form)'],
                            'pending_args' => ['type' => 'object', 'description' => 'Arguments for the pending action']
                        ],
                        'required' => ['action_description', 'severity', 'pending_action', 'pending_args']
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'read_page',
                    'description' => 'Read and describe the current page content to the user. Use when user asks "what do you see" or "read the page".',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'summary' => ['type' => 'string', 'description' => 'A brief summary of what is on the page']
                        ],
                        'required' => ['summary']
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'upload_file',
                    'description' => 'Upload an attached file to a file input on the page. Use when user has attached a file and says "upload this".',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'selector' => ['type' => 'string', 'description' => 'The ID selector of the file input (e.g. #resume, #photo). If unknown, leave empty and first file input will be used.']
                        ],
                        'required' => []
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'send_message',
                    'description' => 'Send a message in the chat/messaging interface. This fills the message input and clicks send button in one atomic action. Use when user says "send message: X" or "message someone: X".',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'content' => ['type' => 'string', 'description' => 'The message content to send.']
                        ],
                        'required' => ['content']
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'store_field',
                    'description' => 'Store a collected form field value during interactive "help me fill the form" mode. Use this to save each field answer as user provides them.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'selector' => ['type' => 'string', 'description' => 'The field ID selector (e.g. #name, #email)'],
                            'value' => ['type' => 'string', 'description' => 'The value the user provided for this field'],
                            'next_question' => ['type' => 'string', 'description' => 'The question to ask about the next field, or empty if all fields collected'],
                            'all_collected' => ['type' => 'boolean', 'description' => 'Set to true when ALL form fields have been collected and ready to fill']
                        ],
                        'required' => ['selector', 'value']
                    ]
                ]
            ],
        ];

        $systemPrompt = <<<EOT
<system>
<identity>
You are AbleBot, an Autonomous Web Agent for AbleLink.
AbleLink empowers people with disabilities through Employment, Community, Health tools, and Accessibility features.
</identity>

<website_context>
{$ragSection}
AbleLink is a comprehensive digital platform for people with disabilities. Here is what you should know:

USER ROLES:
- Guest: Can browse jobs, courses, and aid directory without login
- Disabled User: Full access to jobs, courses, community, health tools, SOS
- Caregiver: Manages health and medications for users they care for
- Employer: Posts accessible jobs, manages applications and interviews
- Volunteer: Helps users with assistance requests and tasks
- Admin: Full platform management and moderation

KEY FEATURES:
1. Employment: Job search with disability filters, employer dashboard, job applications
2. Learning Hub: Accessible courses with subtitles and audio, AI recommendations, certificates
3. Community: Forums, events, matrimony profiles, encrypted messaging
4. Health Tools: Doctor appointments, medication tracking, health goals
5. Safety: SOS emergency button with location sharing and contact alerts
6. Accessibility: Adaptive UI (fonts, contrast), voice interaction, OCR text extraction, AI text simplification
7. Volunteer System: Users can request assistance, volunteers get matched to help

COMMON QUESTIONS AND ANSWERS:
- "What is AbleLink?" → A platform empowering people with disabilities through jobs, learning, community, and health tools
- "How do I find jobs?" → Go to Jobs page, use filters for disability accommodations
- "How to contact help?" → Use the SOS button for emergencies, or volunteer assistance for daily help
- "Can I learn skills?" → Yes, the Learning Hub has accessible courses with certificates
- "How does messaging work?" → All messages are end-to-end encrypted for privacy
- "What is OCR?" → Upload images of text and extract readable text from them
- "Who can see my health data?" → Only you and your assigned caregiver with your consent
</website_context>

<context>
<current_url>{$currentUrl}</current_url>
<user_role>{$userRole}</user_role>
<authenticated>{$isAuthenticated}</authenticated>
<route_map>{$routesJson}</route_map>
<page_dom>{$domContext}</page_dom>
<form_fields>
{$inputContext}
</form_fields>
<stored_fields>
{$storedFieldsContext}
</stored_fields>
</context>

<thought_process>
Before EVERY action, reason through:
[INTENT] What does the user want?
[SECURITY] Is the user authorized? Check user_role against route.
[ELEMENTS] Which DOM elements are relevant?
[ACTION] What tool(s) should I call?
[RESPONSE] What should I tell the user?
</thought_process>

<security_gate>
BEFORE navigating, verify access based on user_role:
- guest: home, login, register, jobs, courses, aid, upload ONLY
- disabled: user routes + jobs, courses, community, health, assistance
- caregiver: user routes + caregiver dashboard, patient health
- employer: user routes + employer dashboard, post jobs, applications
- volunteer: user routes + volunteer requests, assistance
- admin: ALL routes

If unauthorized: DENY and explain: "You don't have access to [route]. As a [role], you can access: [list routes]."
</security_gate>

<protocols>
<element_fallback>
If element NOT in page_dom: List available elements and ask user to choose. NEVER hallucinate selectors.
</element_fallback>

<fast_fill trigger="fill the form">
When user says "fill the form" or "fill out the form":
1. Look at form_fields in context - identify ALL text inputs, selects, AND checkboxes
2. Generate synthetic data for text fields (names, emails, phones, passwords)
3. For EVERY checkbox in form_fields, include it with value="true"
4. Call fill_form ONCE with ALL fields including checkboxes
5. Reply: "Form filled with X fields and Y checkboxes. Ready to submit?"
IMPORTANT: Include ALL checkboxes with value="true" in your fill_form call!
</fast_fill>

<interactive_fill trigger="help me fill the form">
**CRITICAL**: When user says "help me fill the form" or "guide me through":

STEP 1 - Start interactive mode:
Reply with a list of fields and ask the first question.

STEP 2 - For EVERY user answer, you MUST call the store_field TOOL:
- DO NOT just reply with text!
- You MUST call the store_field tool function!
- selector: the field ID from form_fields (e.g. "#ai-gen-20")
- value: the user's answer
- next_question: question for next unfilled field
- all_collected: set true ONLY when it's the LAST field

**DUPLICATE PREVENTION - CRITICAL**:
- Check <stored_fields> before asking ANY question
- If a field selector is already in stored_fields, SKIP IT and move to the next field
- NEVER ask about a field that already has a value in stored_fields
- When user provides data, ALWAYS respond with "Got it!" before asking the next question

Example - User says "John":
YOU MUST CALL: store_field(selector="#ai-gen-20", value="John", next_question="Got it! What is your email?", all_collected=false)

Example - Last field answer:
YOU MUST CALL: store_field(selector="#ai-gen-25", value="Some notes", next_question="", all_collected=true)

**NEVER just reply with text during interactive fill - ALWAYS call store_field tool!**
**NEVER ask about a field that is already in stored_fields!**
</interactive_fill>

<messaging trigger="send message">
When user says "send message: [content]" or "message [name]: [content]":
Use the send_message tool with content="[the message text]"
This is a SINGLE atomic tool call that handles fill + click automatically.
</messaging>

<file_upload trigger="upload this">
Look for [Attached File: URL|filename] in user message, then execute upload_file
</file_upload>

<screen_reader trigger="what is on this page">
When user asks "what is on this page", "describe this page", "read the page", or "what do you see":
Use read_page tool and provide a TTS-OPTIMIZED summary:

FORMAT FOR BLIND USERS:
1. Start with page name: "You are on the [Page Name]."
2. Describe purpose in one sentence: "This page lets you [main action]."
3. List key elements: "There are [N] main options: [list them naturally]."
4. Mention any forms: "There is a form to fill with [N] fields."
5. End with next action: "You can [suggest what to do next]."

EXAMPLE:
"You are on the Job Search page. This page lets you find accessible jobs. There are 12 job listings shown. You can filter by location, job type, and disability accommodations. Say 'go to jobs' to browse or 'help me apply' on any job page."

KEEP IT:
- Short (max 3-4 sentences)
- Natural speech (no bullet points, no asterisks)
- Actionable (tell them what they can do next)
</screen_reader>
</protocols>

<response_rules>
- PLAIN TEXT ONLY: No markdown, no asterisks, no formatting. Output clean readable text.
- BE AUTONOMOUS: Never ask for selectors/IDs
- BE CONCISE: 1-2 sentences max
- USE TOOLS: Execute immediately, don't describe what you would do
- CONFIRMATIONS: Use confirm_action for delete/logout (severity: high)
</response_rules>
</system>
EOT;

        try {
            $interactiveMode = session('ablebot_interactive_mode', false);
            $history = session('ablebot_history', []);
            
            $triggerPhrases = ['help me fill the form', 'guide me through', 'help me fill', 'help fill the form'];
            $isInteractiveTrigger = false;
            foreach ($triggerPhrases as $phrase) {
                if (stripos($message, $phrase) !== false) {
                    $isInteractiveTrigger = true;
                    $interactiveMode = true;
                    session(['ablebot_interactive_mode' => true]);
                    $history = []; 
                    session(['ablebot_history' => []]);
                    session(['ablebot_stored_fields' => []]); 
                    Log::info("AbleBot: Interactive mode ACTIVATED, stored fields cleared");
                    break;
                }
            }
            

            $messages = [['role' => 'system', 'content' => $systemPrompt]];
            if ($interactiveMode) {
                foreach ($history as $h) {
                    $messages[] = ['role' => $h['role'], 'content' => $h['content']];
                }
            }
            $messages[] = ['role' => 'user', 'content' => $message];
            

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

                    if (isset($args['feedback_question'])) {
                        if (!empty($args['feedback_question'])) {
                             $summary = $args['feedback_question'];
                        }
                    }

                    if ($toolName === 'fill_form') {
                         if (isset($args['fields'])) {
                             if (is_array($args['fields'])) {
                                 $fieldNames = [];
                                 $checkboxCount = 0;
                                 foreach ($args['fields'] as $field) {
                                     $selector = '';
                                     if (array_key_exists('selector', $field)) {
                                         $selector = $field['selector'];
                                     }
                                     
                                     $value = '';
                                     if (array_key_exists('value', $field)) {
                                         $value = $field['value'];
                                     }
                                     
                                     $lowerValue = strtolower($value);
                                     $checkBoxValues = ['true', 'false', 'checked', 'unchecked', '1', '0', 'yes', 'no'];
                                     $isCheckbox = in_array($lowerValue, $checkBoxValues);
                                     
                                     $checkValues = ['true', 'checked', '1', 'yes'];
                                     $shouldCheck = in_array($lowerValue, $checkValues);
                                     
                                     if ($isCheckbox) {
                                         if ($shouldCheck) {
                                             $actions[] = [
                                                 'name' => 'click_element',
                                                 'args' => ['selector' => $selector]
                                             ];
                                             $checkboxCount = $checkboxCount + 1;
                                         }
                                     } else {
                                         $actions[] = [
                                             'name' => 'fill_input',
                                             'args' => [
                                                 'selector' => $selector,
                                                 'value' => $value
                                             ]
                                         ];
                                     }
                                     $fieldNames[] = $selector;
                                 }
                                 
                                 $summary = "Form filled";
                                 if ($checkboxCount > 0) {
                                     $summary = $summary . " with $checkboxCount checkbox(es) checked";
                                 }
                                 $summary = $summary . ". Ready to submit?";
                             }
                         }
                         continue;
                    }
                    
                    if ($toolName === 'send_message') {
                        $messageContent = '';
                        if (array_key_exists('content', $args)) {
                            $messageContent = $args['content'];
                        }
                        
                        if (!empty($messageContent)) {
                            $actions[] = [
                                'name' => 'fill_input',
                                'args' => [
                                    'selector' => '#chat-input',
                                    'value' => $messageContent
                                ]
                            ];
                            $actions[] = [
                                'name' => 'click_element',
                                'args' => [
                                    'selector' => '#chat-send-btn'
                                ]
                            ];
                            $summary = "Message sent!";
                        }
                        continue;   
                    }
                    
                    if ($toolName === 'store_field') {
                        $selector = '';
                        if (array_key_exists('selector', $args)) {
                            $selector = $args['selector'];
                        }
                        
                        $value = '';
                        if (array_key_exists('value', $args)) {
                            $value = $args['value'];
                        }
                        
                        $nextQuestion = '';
                        if (array_key_exists('next_question', $args)) {
                            $nextQuestion = $args['next_question'];
                        }
                        
                        $allCollected = false;
                        if (array_key_exists('all_collected', $args)) {
                            $allCollected = $args['all_collected'];
                        }
                        
                        $storedFields = session('ablebot_stored_fields', []);
                        if (!empty($selector)) {
                            if (!empty($value)) {
                                $storedFields[$selector] = $value;
                                session(['ablebot_stored_fields' => $storedFields]);
                                Log::info("AbleBot: Stored field", ['selector' => $selector, 'value' => $value, 'total_stored' => count($storedFields)]);
                            }
                        }
                        
                        $actions[] = [
                            'name' => 'store_field',
                            'args' => [
                                'selector' => $selector,
                                'value' => $value,
                                'all_collected' => $allCollected,
                                'verify_before_reset' => $allCollected  
                            ]
                        ];
                        
                        if ($allCollected) {
                            $summary = "Got it! All fields collected. Verifying and filling the form now...";
                            session(['ablebot_interactive_mode' => false]);
                            session(['ablebot_history' => []]);
                            session(['ablebot_stored_fields' => []]);
                            Log::info("AbleBot: Interactive mode DEACTIVATED - form complete, stored fields cleared");
                        } else {
                            $summary = "Got it! Stored: {$selector}. Next field?";
                            if (!empty($nextQuestion)) {
                                $summary = $nextQuestion;
                            }
                        }
                        continue; 
                    }


                    if ($toolName === 'upload_file') {
                        preg_match('/\[Attached File: (.*?)\|(.*?)\]/', $message, $matches);
                        if (isset($matches[1])) {
                            $args['url'] = $matches[1];
                            $args['filename'] = 'file';
                            if (isset($matches[2])) {
                                $args['filename'] = $matches[2];
                            }
                            $summary = "Uploading your file: " . $args['filename'];
                        } else {
                            preg_match('/\[Attached File: (.*?)\]/', $message, $backup);
                            if (isset($backup[1])) {
                                $args['url'] = $backup[1];
                                $summary = "Uploading the attached file.";
                            } else {
                                $summary = "I don't see an attached file. Please attach a file first using the paperclip icon.";
                                continue; 
                            }
                        }
                    }

                    $actions[] = ['name' => $toolName, 'args' => $args];
                    
                    if ($summary === "") {
                        $toolSummaries = [
                            'navigate' => 'Navigating to page.',
                            'fill_input' => 'Filling out the field.',
                            'click_element' => 'Clicking the element.',
                            'read_page' => 'Reading the page for you.',
                            'confirm_action' => 'Asking for confirmation.',
                            'toggle_checkbox' => 'Toggling checkbox.',
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
