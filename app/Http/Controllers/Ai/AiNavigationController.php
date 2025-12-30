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
            $request->validate([
                'message' => 'required|string|max:500',
                'current_url' => 'required|string',
            ]);

            $message = $request->input('message');
            $currentUrl = $request->input('current_url');
            $pageStructure = $request->input('page_structure');

            $response = $this->aiService->processMessage($message, $currentUrl, $pageStructure);

            return response()->json($response);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("AiNavigationController Error: " . $e->getMessage());
            return response()->json([
                'reply' => "System Error: " . $e->getMessage(),
                'action' => 'none',
                'voice_summary' => "System error occurred."
            ], 500);
        }
    }

    //F18 - Farhan Zarif
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx,txt'
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = $file->hashName(); // Storing as hash to prevent collision
            $file->storeAs('chat_uploads', $filename, 'public');
            
            // Return PROXY URL to bypass symlink issues
            return response()->json([
                'url' => route('ai.file', ['filename' => $filename]), // Use proxy route
                'filename' => $file->getClientOriginalName()
            ]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }

    public function serveFile(string $filename)
    {
        $path = storage_path("app/public/chat_uploads/{$filename}");
        if (!file_exists($path)) {
            abort(404);
        }
        
        // Determine MIME type from extension for proper file handling
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'pdf' => 'application/pdf',
            'txt' => 'text/plain',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];
        $mimeType = $mimeTypes[$ext] ?? mime_content_type($path);
        
        return response()->file($path, ['Content-Type' => $mimeType]);
    }
}
