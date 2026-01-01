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

    //F18 - Farhan Zarif
    public function upload(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'file' => 'required|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx,txt'
        ]);

        $hasFile = $request->hasFile('file');
        
        if ($hasFile === true) {
            $uploadedFile = $request->file('file');
            $generatedFilename = $uploadedFile->hashName(); 
            $uploadedFile->storeAs('chat_uploads', $generatedFilename, 'public');
            
            $routeParams = ['filename' => $generatedFilename];
            $proxyUrl = route('ai.file', $routeParams);
            $originalName = $uploadedFile->getClientOriginalName();

            $successPayload = [
                'url' => $proxyUrl, 
                'filename' => $originalName
            ];
            
            $jsonResponse = response()->json($successPayload);
            
            return $jsonResponse;
        }

        $errorPayload = ['error' => 'No file uploaded'];
        $errorResponse = response()->json($errorPayload, 400);

        return $errorResponse;
    }

    public function serveFile(string $filename): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $filePath = storage_path("app/public/chat_uploads/{$filename}");
        $fileExists = file_exists($filePath);
        
        if ($fileExists === false) {
            abort(404);
        }
        
        $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $supportedMimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'pdf' => 'application/pdf',
            'txt' => 'text/plain',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];
        
        if (array_key_exists($fileExtension, $supportedMimeTypes)) {
            $determinedMimeType = $supportedMimeTypes[$fileExtension];
        } 
        else {
            $determinedMimeType = mime_content_type($filePath);
        }
        
        $responseHeaders = ['Content-Type' => $determinedMimeType];
        $fileResponse = response()->file($filePath, $responseHeaders);
        
        return $fileResponse;
    }
}
