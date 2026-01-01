<?php

namespace App\Services\OcrAndSimplify\DocumentProcessing;

use App\Services\OcrAndSimplify\Ocr\OcrEngine;
use Illuminate\Http\UploadedFile;
use Symfony\Component\Process\Process;

final class DocumentTextExtractor
{
    public function __construct(
        private readonly OcrEngine $ocr,
    ) {
    }

    public function extract(UploadedFile $file): ExtractionResult
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: '');
        $mime = strtolower((string) $file->getMimeType());
        $path = $file->getRealPath() ?: $file->getPathname();

        $warnings = [];

        if ($extension === 'txt' || str_starts_with($mime, 'text/')) {
            $contents = @file_get_contents($path);
            return new ExtractionResult($contents === false ? '' : $contents, $contents === false ? ['Could not read the text file.'] : []);
        }

        if ($extension === 'pdf' || $mime === 'application/pdf') {
            try {
                $parser = new \Smalot\PdfParser\Parser();
                $pdf = $parser->parseFile($path);
                $text = trim($pdf->getText());

                if ($text === '') {
                    $warnings[] = 'No selectable text was found in this PDF. If it is a scanned PDF, export it as images (PNG/JPG) and upload those for OCR.';
                }

                return new ExtractionResult($text, $warnings);
            } catch (\Throwable $e) {
                return new ExtractionResult('', ['Failed to parse PDF: ' . $e->getMessage()]);
            }
        }

        $isImage = in_array($extension, ['jpg', 'jpeg', 'png'], true) || str_starts_with($mime, 'image/');
        if ($isImage) {
            if (!$this->ocr->isAvailable()) {
                return new ExtractionResult('', [
                    'OCR is not available on this server (missing the `tesseract` binary). You can still paste text below to simplify it.',
                ]);
            }

            try {
                $text = trim($this->ocr->ocrImage($path));
                if ($text === '') {
                    $warnings[] = 'OCR ran successfully but no text was detected. Try a higher-resolution image with better contrast.';
                }

                return new ExtractionResult($text, $warnings);
            } catch (\Throwable $e) {
                return new ExtractionResult('', ['OCR failed to run on this image.']);
            }
        }

        return new ExtractionResult('', ['Unsupported file type. Please upload a PDF, PNG, JPG, or TXT file.']);
    }
}

