<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;

class AiModerationService
{
    private PythonAiService $pythonAi;

    public function __construct(PythonAiService $pythonAi)
    {
        $this->pythonAi = $pythonAi;
    }

    public function isSafe(string $textContent): bool
    {
        try {
            $result = $this->pythonAi->checkToxicity($textContent);
            return $result['safe'] ?? true;
        } catch (\Exception $exception) {
            Log::error('AI Moderation Error: ' . $exception->getMessage());
            return true; 
        }
    }

    public function getFlagReason(string $textContent): string
    {
        try {
            $result = $this->pythonAi->checkToxicity($textContent);
            if (!($result['safe'] ?? true)) {
                return $result['reason'] ?? 'Content flagged by AI.';
            }
            return '';
        } catch (\Exception $exception) {
            return '';
        }
    }
}
