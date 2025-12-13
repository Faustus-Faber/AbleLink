<?php

namespace App\Http\Controllers\Ai;

use App\Services\Ai\AiService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Http\Controllers\Controller;

//F7 - Farhan Zarif
class AiNavigationController extends Controller
{
    private AiService $aiService;

    public function __construct(AiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function chat(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'message' => 'required|string|max:500',
                'current_url' => 'required|string',
            ]);

            $message = $request->input('message');
            $currentUrl = $request->input('current_url');
            $pageStructure = $request->input('page_structure');

            $responseData = $this->aiService->processMessage($message, $currentUrl, $pageStructure);

            $jsonResponse = response()->json($responseData);
            
            return $jsonResponse;
        } 
        catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("AiNavigationController Error: " . $e->getMessage());
            
            $errorPayload = [
                'reply' => "System Error: " . $e->getMessage(),
                'action' => 'none',
                'voice_summary' => "System error occurred."
            ];
            $errorResponse = response()->json($errorPayload, 500);

            return $errorResponse;
        }
    }

}
