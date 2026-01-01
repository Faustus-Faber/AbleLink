<?php

namespace App\Console\Commands;

use App\Services\Ai\AiModerationService;
use Illuminate\Console\Command;

/**
 * Test the AI Moderation Service with a given text.
 */
class TestAiModeration extends Command
{
    protected $signature = 'ai:test-moderation {text : The text to analyze}';

    protected $description = 'Test the AI Moderation Service with a given text';

    public function handle(AiModerationService $moderationService)
    {
        $text = $this->argument('text');

        $this->info("Analyzing text: \"{$text}\"");

        $isSafe = $moderationService->isSafe($text);
        
        if ($isSafe) {
            $this->info("Result: SAFE ✅");
        } else {
            $this->error("Result: UNSAFE ❌");
            $reason = $moderationService->getFlagReason($text);
            $this->line("Reason: " . $reason);
        }
    }
}
