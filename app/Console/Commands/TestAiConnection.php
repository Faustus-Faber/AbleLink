<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Ai\AiService;

/**
 * Test the connection to the Gemini AI API.
 */
class TestAiConnection extends Command
{
    protected $signature = 'ai:test';

    protected $description = 'Test the connection to the Gemini AI API';

    public function handle(AiService $aiService)
    {
        $this->info("Testing AI Connection...");
        $this->info("API Key configured: " . (env('AI_API_KEY') ? 'YES' : 'NO'));
        $this->info("Model: " . env('AI_MODEL'));
        $this->info("URL: " . env('AI_API_URL'));

        $this->line("Sending request: 'Hello world'...");

        try {
            $response = $aiService->processMessage("Hello world", "/home", []);
            
            $this->info("Response received!");
            $this->line("Reply: " . $response['reply']);
            $this->line("Action: " . $response['action']);
            
            if (str_contains($response['reply'], 'Error') || str_contains($response['reply'], 'trouble connecting')) {
                $this->error("Connection Failed Logic Triggered.");
            } else {
                $this->info("SUCCESS: Connection established.");
            }

        } catch (\Exception $e) {
            $this->error("CRITICAL EXCEPTION:");
            $this->error($e->getMessage());
            $this->line($e->getTraceAsString());
        }
    }
}
