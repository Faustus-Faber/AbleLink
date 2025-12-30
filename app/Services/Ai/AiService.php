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
        $this->apiKey = trim(env('AI_API_KEY', ''));
        $this->apiUrl = trim(env('AI_API_URL', ''));
        $this->model = trim(env('AI_MODEL', 'openai-gpt-oss-120b'));
    }

    public function processMessage(string $message, string $currentUrl, ?array $pageStructure = null): array
    {
        if (!$this->apiKey) {
            return ['reply' => "API Key missing!", 'action' => 'none', 'voice_summary' => "I have no brain."];
        }

        // 1. Context Building
        $routesJson = json_encode($this->getRouteMap());
        
        // RBAC Context (F7 Enhancement)
        $userRole = auth()->check() ? auth()->user()->role : 'guest';
        $isAuthenticated = auth()->check() ? 'true' : 'false';
        
        $domContext = "No visible interactive elements.";
        $detectedInputs = []; // F9 - Evan Munshi (Explicit Field Extraction)

        if ($pageStructure && isset($pageStructure['elements'])) {
            $elements = $pageStructure['elements'];
            $domContext = "Visible Elements on Screen:\n" . json_encode($elements);
            
            // Extract Inputs and Options for Explicit Prompting
            foreach ($elements as $el) {
                if (in_array($el['tag'], ['input', 'textarea', 'select'])) {
                    $label = $el['text'] ?? $el['name'] ?? $el['id'];
                    $type = $el['type'] ?? $el['tag'];
                    
                    // Special handling for checkboxes - show them clearly
                    if ($type === 'checkbox') {
                        $checkedState = isset($el['checked']) && $el['checked'] ? 'CHECKED' : 'UNCHECKED';
                        $info = "- {$label} (ID: #{$el['id']}, Type: CHECKBOX, Currently: {$checkedState}) [Use value \"true\" to check, \"false\" to uncheck]";
                    } else {
                        $info = "- {$label} (ID: #{$el['id']}, Type: {$type})";
                        
                        if ($el['tag'] === 'select' && !empty($el['options'])) {
                            $opts = array_map(fn($o) => "{$o['value']} ({$o['text']})", $el['options']);
                            $info .= " [Select one of these EXACT values: " . implode(', ', $opts) . "]";
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

        // Build stored fields context (for interactive form fill duplicate prevention)
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


        // 2. Tool Definitions (OpenAI Spec)
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

            // Phase 5: Confirmation Dialog Tool
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
            // Phase 4: Read Page Tool
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
            // F18 - Farhan Zarif (Upload File Tool)
            // Upload File Tool
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
            // F7 - Composite Send Message Tool (atomic action)
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
            // Interactive Form Fill - Store a single field value
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

        // 3. System Prompt (F7/F18 - Production-Grade XML Structure)
        $systemPrompt = <<<EOT
<system>
<identity>
You are AbleBot, an Autonomous Web Agent for AbleLink.
AbleLink empowers people with disabilities through Employment, Community, Health tools, and Accessibility features.
</identity>

<website_context>
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
            // Smart History: Conditional activation for interactive mode only
            $interactiveMode = session('ablebot_interactive_mode', false);
            $history = session('ablebot_history', []);
            
            // Check if this message triggers interactive mode
            $triggerPhrases = ['help me fill the form', 'guide me through', 'help me fill', 'help fill the form'];
            $isInteractiveTrigger = false;
            foreach ($triggerPhrases as $phrase) {
                if (stripos($message, $phrase) !== false) {
                    $isInteractiveTrigger = true;
                    $interactiveMode = true;
                    session(['ablebot_interactive_mode' => true]);
                    $history = []; // Start fresh for interactive mode
                    session(['ablebot_history' => []]);
                    session(['ablebot_stored_fields' => []]); // Clear stored fields for fresh start
                    Log::info("AbleBot: Interactive mode ACTIVATED, stored fields cleared");
                    break;
                }
            }
            
            // Build messages array - only include history if in interactive mode
            $messages = [['role' => 'system', 'content' => $systemPrompt]];
            if ($interactiveMode) {
                foreach ($history as $h) {
                    $messages[] = ['role' => $h['role'], 'content' => $h['content']];
                }
            }
            $messages[] = ['role' => 'user', 'content' => $message];
            
            // OpenAI Spec Request
            $response = Http::withOptions(['verify' => false, 'timeout' => 60])
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->apiKey
                ])
                ->post($this->apiUrl, [
                    'model' => $this->model,
                    'messages' => $messages,
                    'tools' => $tools,
                    'tool_choice' => 'auto'
                ]);

            if ($response->failed()) {
                Log::error("AI Error {$response->status()}: " . $response->body());
                return ['reply' => "Connection Error ({$response->status()})", 'action' => 'none', 'voice_summary' => "Error connecting."];
            }

            $data = $response->json();
            $choice = $data['choices'][0]['message'] ?? null;
            
            Log::info("MegaLLM Choice", [$choice]);

            // 4. Handle Response
            if (isset($choice['tool_calls']) && count($choice['tool_calls']) > 0) {
                $actions = [];
                $summary = "";

                foreach ($choice['tool_calls'] as $toolCall) {
                    $func = $toolCall['function'];
                    $toolName = $func['name'];
                    $args = json_decode($func['arguments'], true);

                    // FEEDBACK OVERRIDE (Interactive Mode)
                    if (isset($args['feedback_question']) && !empty($args['feedback_question'])) {
                        $summary = $args['feedback_question'];
                    }

                    // Special Handling for "fill_form" Super Tool
                    // Explode it into fill_input for text/select fields, click_element for checkboxes
                    if ($toolName === 'fill_form') {
                         if (isset($args['fields']) && is_array($args['fields'])) {
                             $fieldNames = [];
                             $checkboxCount = 0;
                             foreach ($args['fields'] as $field) {
                                 $selector = $field['selector'] ?? '';
                                 $value = $field['value'] ?? '';
                                 
                                 // Detect checkbox by value (true/false/checked/unchecked)
                                 $isCheckbox = in_array(strtolower($value), ['true', 'false', 'checked', 'unchecked', '1', '0', 'yes', 'no']);
                                 $shouldCheck = in_array(strtolower($value), ['true', 'checked', '1', 'yes']);
                                 
                                 if ($isCheckbox) {
                                     // For checkboxes, only click if we want to CHECK it
                                     // (clicking toggles state, so we only click when shouldCheck=true)
                                     if ($shouldCheck) {
                                         $actions[] = [
                                             'name' => 'click_element',
                                             'args' => ['selector' => $selector]
                                         ];
                                         $checkboxCount++;
                                     }
                                 } else {
                                     // Regular text/select field
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
                                 $summary .= " with $checkboxCount checkbox(es) checked";
                             }
                             $summary .= ". Ready to submit?";
                         }
                         continue; // Skip adding the raw 'fill_form' action
                    }
                    
                    // F7 - Composite Send Message Tool Handler
                    // Explode into fill_input + click_element for atomic messaging
                    if ($toolName === 'send_message') {
                        $messageContent = $args['content'] ?? '';
                        if (!empty($messageContent)) {
                            // Action 1: Fill the message input
                            $actions[] = [
                                'name' => 'fill_input',
                                'args' => [
                                    'selector' => '#chat-input',
                                    'value' => $messageContent
                                ]
                            ];
                            // Action 2: Click the send button
                            $actions[] = [
                                'name' => 'click_element',
                                'args' => [
                                    'selector' => '#chat-send-btn'
                                ]
                            ];
                            $summary = "Message sent!";
                        }
                        continue; // Skip adding the raw 'send_message' action
                    }
                    
                    // Interactive Form Fill - Store field value (new handler)
                    if ($toolName === 'store_field') {
                        $selector = $args['selector'] ?? '';
                        $value = $args['value'] ?? '';
                        $nextQuestion = $args['next_question'] ?? '';
                        $allCollected = $args['all_collected'] ?? false;
                        
                        // Store the field value in session for duplicate prevention
                        $storedFields = session('ablebot_stored_fields', []);
                        if (!empty($selector) && !empty($value)) {
                            $storedFields[$selector] = $value;
                            session(['ablebot_stored_fields' => $storedFields]);
                            Log::info("AbleBot: Stored field", ['selector' => $selector, 'value' => $value, 'total_stored' => count($storedFields)]);
                        }
                        
                        // Pass the store_field action to frontend
                        $actions[] = [
                            'name' => 'store_field',
                            'args' => [
                                'selector' => $selector,
                                'value' => $value,
                                'all_collected' => $allCollected,
                                'verify_before_reset' => $allCollected // Trigger verification
                            ]
                        ];
                        
                        if ($allCollected) {
                            $summary = "Got it! All fields collected. Verifying and filling the form now...";
                            // Reset interactive mode, history, and stored fields after form fill
                            session(['ablebot_interactive_mode' => false]);
                            session(['ablebot_history' => []]);
                            session(['ablebot_stored_fields' => []]); // Clear stored fields
                            Log::info("AbleBot: Interactive mode DEACTIVATED - form complete, stored fields cleared");
                        } else {
                            $summary = $nextQuestion ?: "Got it! Stored: {$selector}. Next field?";
                        }
                        continue; // Skip adding raw action
                    }


                    // Handling for upload file (Context Extraction)
                    if ($toolName === 'upload_file') {
                        preg_match('/\[Attached File: (.*?)\|(.*?)\]/', $message, $matches);
                        if (isset($matches[1])) {
                            $args['url'] = $matches[1];
                            $args['filename'] = $matches[2] ?? 'file';
                            $summary = "Uploading your file: " . ($matches[2] ?? 'file');
                        } else {
                            preg_match('/\[Attached File: (.*?)\]/', $message, $backup);
                            if (isset($backup[1])) {
                                $args['url'] = $backup[1];
                                $summary = "Uploading the attached file.";
                            } else {
                                // No file attached
                                $summary = "I don't see an attached file. Please attach a file first using the paperclip icon.";
                                continue; // Skip adding the action
                            }
                        }
                    }

                    $actions[] = ['name' => $toolName, 'args' => $args];
                    
                    if ($summary === "") {
                        // Better default summaries
                        $toolSummaries = [
                            'navigate' => 'Navigating to page.',
                            'fill_input' => 'Filling out the field.',
                            'click_element' => 'Clicking the element.',
                            'read_page' => 'Reading the page for you.',
                            'confirm_action' => 'Asking for confirmation.',
                            'toggle_checkbox' => 'Toggling checkbox.',
                        ];
                        $summary = $toolSummaries[$toolName] ?? "Executing action.";
                    }
                }

                // User-friendly reply
                // Priority: AI's text content > Tool Summary
                $aiText = $choice['content'] ?? '';
                $userReply = !empty(trim($aiText)) ? $aiText : $summary;
                
                if (str_starts_with($summary, 'Filling form') && empty(trim($aiText))) {
                    $userReply = "Filling out the form for you.";
                }

                // Smart History: Only store in interactive mode, 100 messages (50 turns)
                if (session('ablebot_interactive_mode', false)) {
                    $history[] = ['role' => 'user', 'content' => $message];
                    $history[] = ['role' => 'assistant', 'content' => $userReply];
                    session(['ablebot_history' => array_slice($history, -100)]);
                }

                return [
                    'reply' => ucfirst($userReply),  // Clean text for display
                    'actions' => $actions,
                    'voice_summary' => $userReply    // Same clean text for voice
                ];
            } else {
                $text = $choice['content'] ?? "I didn't understand.";
                
                // Smart History: Only store in interactive mode, 100 messages (50 turns)
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

    //F7 - Farhan Zarif (RBAC-Enhanced Route Map)
    private function getRouteMap() {
        $role = auth()->check() ? auth()->user()->role : 'guest';
        
        // Public routes (all roles)
        $routes = [
            'home' => url('/'),
            'login' => url('/login'),
            'register' => url('/register'),
            'courses' => url('/courses'),
            'learning hub' => url('/courses'),
            'aid directory' => url('/aid'),
            'jobs' => url('/jobs'),
            'job search' => url('/jobs'),
            'admin login' => url('/admin/login'),
            // OCR & Text Simplification (Accessibility)
            'upload' => url('/upload'),
            'ocr' => url('/upload'),
            'simplify text' => url('/upload'),
            'text extraction' => url('/upload'),
        ];
        
        // Authenticated routes (all logged-in users)
        if (auth()->check()) {
            $routes += [
                'dashboard' => url('/dashboard'),
                'profile' => url('/profile'),
                'edit profile' => url('/profile/edit'),
                'notifications' => url('/notifications'),
                'forum' => url('/forum'),
                'messages' => url('/messages'),
                'community' => url('/community'),
                'events' => url('/community/events'),
                'create event' => url('/community/events/create'),
                'matrimony' => url('/community/matrimony'),
            ];
        }
        
        // Disabled Person routes (disabled role only)
        if ($role === 'disabled') {
            $routes += [
                'accessibility settings' => url('/accessibility'),
                'my applications' => url('/candidate/applications'),
                'assistance requests' => url('/user/assistance'),
                'request assistance' => url('/user/assistance/create'),
                'health dashboard' => url('/health/dashboard'),
                'my requests' => url('/user/requests'),
                // F17 - User Appointments
                'appointments' => url('/user/appointments'),
                'my appointments' => url('/user/appointments'),
                'appointment calendar' => url('/user/appointments/calendar'),
            ];
        }
        
        // Caregiver routes (caregiver role only)
        if ($role === 'caregiver') {
            $routes += [
                'caregiver dashboard' => url('/caregiver/dashboard'),
                // F17 - Caregiver Appointments Management
                'manage appointments' => url('/caregiver/appointments'),
                'doctor appointments' => url('/caregiver/appointments'), 
                'schedule appointment' => url('/caregiver/appointments'),
                'caregiver appointment calendar' => url('/caregiver/appointments/calendar'),
            ];
        }
        
        // Employer routes (employer role only)
        if ($role === 'employer') {
            $routes += [
                'employer dashboard' => url('/employer/jobs'),
                'my jobs' => url('/employer/jobs'),
                'post a job' => url('/employer/jobs/create'),
                'employer applications' => url('/employer/applications'),
                'employer interviews' => url('/employer/interviews'),
                'company profile' => url('/employer/profile'),
                'edit company profile' => url('/employer/profile/edit'),
                'employer reports' => url('/employer/reports'),
            ];
        }
        
        // Volunteer routes (volunteer role only)
        if ($role === 'volunteer') {
            $routes += [
                'volunteer dashboard' => url('/volunteer/requests'),
                'help requests' => url('/volunteer/requests'),
                'volunteer profile' => url('/volunteer/profile'),
                'edit volunteer profile' => url('/volunteer/profile/edit'),
                'active assistance' => url('/volunteer/assistance/active'),
                'active tasks' => url('/volunteer/assistance/active'),
                'task history' => url('/volunteer/assistance/history'),
                'assistance history' => url('/volunteer/assistance/history'),
            ];
        }
        
        // Admin routes (admin role only)
        if ($role === 'admin') {
            $routes += [
                'admin dashboard' => url('/admin/dashboard'),
                'admin users' => url('/admin/users'),
                'admin volunteers' => url('/admin/volunteers'),
                'admin employers' => url('/admin/employers'),
                'admin caregivers' => url('/admin/caregivers'),
                'admin jobs' => url('/admin/jobs'),
                'admin courses' => url('/admin/courses'),
                'admin aid' => url('/admin/aid'),
                'admin moderation' => url('/admin/moderation'),
                'admin community' => url('/admin/community'),
            ];
        }
        
        return $routes;
    }
    // F21 - AI Certificate Generation
    public function generateCertificateMessage(\App\Models\Auth\User $user, \App\Models\Education\Course $course): string
    {
        if (!$this->apiKey) {
            return "This certificate recognizes the successful completion of the course {$course->title}.";
        }

        $prompt = "Generate a short, professional, and inspiring congratulatory message for {$user->name} for completing the course '{$course->title}'. Focus on the achievement and future potential. Max 2 sentences. Do not use hashtags or emojis.";

        try {
             $response = Http::withOptions(['verify' => false, 'timeout' => 30])
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->apiKey
                ])
                ->post($this->apiUrl, [
                    'model' => $this->model,
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a professional academic assistant.'],
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'max_tokens' => 100,
                    'temperature' => 0.7
                ]);

            if ($response->successful()) {
                return trim($response->json()['choices'][0]['message']['content'] ?? "Congratulations on completing {$course->title}!");
            }
            
            Log::error("AI Certificate Error: " . $response->body());

        } catch (\Exception $e) {
            Log::error("AI Certificate Exception: " . $e->getMessage());
        }

        return "Congratulations on successfully completing the course '{$course->title}'. This certificate recognizes your dedication and hard work throughout the curriculum.";
    }
}
