<?php
 
namespace App\Http\Controllers\Document;
 
use App\Services\OcrAndSimplify\DocumentProcessing\DocumentTextExtractor;
use App\Services\OcrAndSimplify\TextSimplification\TextSimplifier;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
 
use App\Http\Controllers\Controller;

class DocumentController extends Controller
{
    public function __construct(
        private readonly DocumentTextExtractor $extractor,
        private readonly TextSimplifier $simplifier,
    ) {
    }
 
    public function showUploadForm()
    {
        return view('documents.upload');
    }
 
    public function processDocument(Request $request)
    {
        $validated = $request->validate([
            'document' => [
                'required',
                'file',
                'max:10240', // 10MB
                'mimes:jpg,jpeg,png,pdf,txt',
            ],
            'auto_simplify' => ['nullable', 'boolean'],
        ]);
 
        /** @var UploadedFile $file */
        $file = $validated['document'];
 
        $result = $this->extractor->extract($file);
 
        $viewData = [
            'original_text' => $result->text,
            'warnings' => $result->warnings,
            'source_filename' => $file->getClientOriginalName(),
        ];
 
        if (($validated['auto_simplify'] ?? false) && trim($result->text) !== '') {
            $simplified = $this->simplifier->simplify($result->text);
            $viewData['simplified_text'] = $simplified->simplifiedText;
            $viewData['simplified_bullets'] = $simplified->bullets;
        }
 
        return view('documents.result', $viewData);
    }
 
    public function simplifyText(Request $request)
    {
        $validated = $request->validate([
            'text' => ['required', 'string', 'max:200000'],
        ]);
 
        $text = $validated['text'];
 
        $simplified = $this->simplifier->simplify($text);
 
        return view('documents.result', [
            'original_text' => $text,
            'simplified_text' => $simplified->simplifiedText,
            'simplified_bullets' => $simplified->bullets,
            'warnings' => [],
        ]);
    }
}

