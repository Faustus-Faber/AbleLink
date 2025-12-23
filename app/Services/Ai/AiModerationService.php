<?php

//F13 - Farhan Zarif
namespace App\Services\Ai;

class AiModerationService
{
    /**
     * Analyze content for moderation.
     * Returns true if safe, false if flagged.
     *
     * @param string $text
     * @return bool
     */
    private string $apiKey;
    private string $apiUrl;
    private string $model;

    public function __construct()
    {
        $this->apiKey = trim(env('AI_API_KEY', ''));
        $this->apiUrl = trim(env('AI_API_URL', ''));
        $this->model = trim(env('AI_MODEL', 'openai-gpt-oss-120b'));
    }

    /**
     * Analyze content for moderation using AI API.
     * Returns true if safe, false if flagged.
     *
     * @param string $text
     * @return bool
     */
    public function isSafe(string $text): bool
    {
        if (!$this->apiKey) {
            // Fallback for development if no key provided
            return true;
        }

        try {
            $response = $this->callAi($text);
            return $response['safe'] ?? true;
        } catch (\Exception $e) {
            // Log error and fail open (allow content) or closed (block) depending on policy.
            \Illuminate\Support\Facades\Log::error("AI Moderation Error: " . $e->getMessage());
            return true;
        }
    }

    /**
     * Get reasoning for flagging.
     */
    public function getFlagReason(string $text): string
    {
        if (!$this->apiKey) {
            return "";
        }

        try {
            $response = $this->callAi($text);
            return $response['safe'] ? "" : ($response['reason'] ?? "Content flagged by AI.");
        } catch (\Exception $e) {
            return "";
        }
    }

    private function callAi(string $text): array
    {
        $systemPrompt = <<<EOT
You are a Content Safety Moderator for a community platform.
Analyze the following user input text.
Determine if it contains:
- Hate speech
- Encouragement of violence or self-harm
- Excessive harassment or bullying
- Explicit sexual content

If it contains any of these, mark it as unsafe.
Otherwise, mark it as safe.

Return ONLY raw JSON.
Schema:
{
    "safe": boolean,
    "reason": "string (Short user-facing explanation if unsafe, e.g., 'Hate speech detected', otherwise empty)"
}
EOT;

        $response = \Illuminate\Support\Facades\Http::withOptions([
            'verify' => false,
            'timeout' => 30,
        ])->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->apiKey
        ])->post($this->apiUrl, [
            'model' => $this->model,
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $text]
            ],
            'response_format' => ['type' => 'json_object'] // Attempt to force JSON if supported
        ]);

        if ($response->failed()) {
            throw new \Exception("API Request failed: " . $response->status() . " Body: " . $response->limit(100));
        }

        $data = $response->json();
        $rawContent = $data['choices'][0]['message']['content'] ?? '{}';
        
        // Clean markdown code blocks if present (common with some models)
        $rawContent = preg_replace('/^```json\s*|\s*```$/', '', trim($rawContent));

        $decoded = json_decode($rawContent, true);
        return $decoded ?? ['safe' => true];
    }
}
