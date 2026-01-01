<?php

namespace App\Services\Ai;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Log;

class PythonAiService
{
    public function retrieveContext(string $query, int $limit = 3): ?string
    {
        try {
            $scriptPath = base_path('ai_services/rag_query.py');
            $process = new Process(['python', $scriptPath, $query]);
            $process->run();

            if (!$process->isSuccessful()) {
                Log::warning("RAG Query Failed: " . $process->getErrorOutput());
                return null;
            }

            $output = json_decode($process->getOutput(), true);
            return $output['context'] ?? null;

        } catch (\Exception $e) {
            Log::error("RAG Service Error: " . $e->getMessage());
            return null;
        }
    }

    public function checkToxicity(string $text): array
    {
        try {
            $scriptPath = base_path('ai_services/toxicity.py');
            $process = new Process(['python', $scriptPath, $text]);
            $process->run();

            if (!$process->isSuccessful()) {
                Log::warning("Toxicity Check Failed: " . $process->getErrorOutput());
                return ['safe' => true, 'reason' => null, 'error' => 'Service unavailable'];
            }

            return json_decode($process->getOutput(), true);

        } catch (\Exception $e) {
            Log::error("Toxicity Service Error: " . $e->getMessage());
            return ['safe' => true, 'reason' => null, 'error' => $e->getMessage()];
        }
    }
}
