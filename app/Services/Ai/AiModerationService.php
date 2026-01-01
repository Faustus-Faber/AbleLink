<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;

class AiModerationService
{
    private string $apiKey;
    private string $apiUrl;
    private string $modelName;

    public function __construct()
    {
        $this->apiKey = trim(env('AI_API_KEY', ''));
        $this->apiUrl = trim(env('AI_API_URL', ''));
        $this->modelName = trim(env('AI_MODEL', 'openai-gpt-oss-120b'));
    }

    public function isSafe(string $textContent): bool
    {
        $hasApiKey = $this->apiKey !== '';

        if ($hasApiKey === false) {
             return true;
        }

        try {
            $analysisResult = $this->callAiApi($textContent);
            $isSafe = $analysisResult['safe'];
            
            if ($isSafe === null) {
                return true;
            }
            
            return $isSafe;
        } catch (\Exception $exception) {
            $errorMessage = $exception->getMessage();
            Log::error('AI Moderation Error: ' . $errorMessage);
            return true;
        }
    }

    public function getFlagReason(string $textContent): string
    {
        $hasApiKey = $this->apiKey !== '';
        
        if ($hasApiKey === false) {
            return '';
        }

        try {
            $analysisResult = $this->callAiApi($textContent);
            $isSafe = $analysisResult['safe'];
            
            if ($isSafe === true) {
                return '';
            }

            $reason = $analysisResult['reason'];
            if ($reason !== null) {
                return $reason;
            }

            return 'Content flagged by AI.';
        } catch (\Exception $exception) {
            return '';
        }
    }

    private function callAiApi(string $textContent): array
    {
        $systemPrompt = 'You are a Content Safety Moderator for a community platform. ';
        $systemPrompt .= 'Analyze the following user input text. ';
        $systemPrompt .= 'Determine if it contains: ';
        $systemPrompt .= '- Hate speech ';
        $systemPrompt .= '- Encouragement of violence or self-harm ';
        $systemPrompt .= '- Excessive harassment or bullying ';
        $systemPrompt .= '- Explicit sexual content ';
        $systemPrompt .= 'If it contains any of these, mark it as unsafe. ';
        $systemPrompt .= 'Otherwise, mark it as safe. ';
        $systemPrompt .= 'Return ONLY raw JSON. ';
        $systemPrompt .= 'Schema: { "safe": boolean, "reason": "string (Short user-facing explanation if unsafe, e.g., \'Hate speech detected\', otherwise empty)" }';

        $requestOptions = [];
        $requestOptions['verify'] = false;
        $requestOptions['timeout'] = 30;

        $requestHeaders = [];
        $requestHeaders['Content-Type'] = 'application/json';
        $requestHeaders['Authorization'] = 'Bearer ' . $this->apiKey;

        $requestPayload = [];
        $requestPayload['model'] = $this->modelName;
        
        $messagesList = [];
        
        $systemMessage = [];
        $systemMessage['role'] = 'system';
        $systemMessage['content'] = $systemPrompt;
        $messagesList[] = $systemMessage;
        
        $userMessage = [];
        $userMessage['role'] = 'user';
        $userMessage['content'] = $textContent;
        $messagesList[] = $userMessage;

        $requestPayload['messages'] = $messagesList;
        $requestPayload['response_format'] = ['type' => 'json_object'];

        $httpClient = Http::withOptions($requestOptions);
        $httpClient->withHeaders($requestHeaders);
        
        $apiResponse = $httpClient->post($this->apiUrl, $requestPayload);

        $requestFailed = $apiResponse->failed();
        if ($requestFailed === true) {
            $statusCode = $apiResponse->status();
            $responseBody = $apiResponse->limit(100);
            throw new \Exception('API Request failed: ' . $statusCode . ' Body: ' . $responseBody);
        }

        $responseData = $apiResponse->json();
        
        $choices = $responseData['choices'];
        $firstChoice = $choices[0];
        $messageContent = $firstChoice['message'];
        $rawContent = $messageContent['content'];

        if ($rawContent === null) {
            $rawContent = '{}';
        }

        $cleanedContent = preg_replace('/^```json\s*|\s*```$/', '', trim($rawContent));

        $decodedJson = json_decode($cleanedContent, true);

        if ($decodedJson === null) {
            return ['safe' => true];
        }

        return $decodedJson;
    }
}
