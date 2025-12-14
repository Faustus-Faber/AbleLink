<?php

namespace App\Services\OcrAndSimplify\Ocr;

interface OcrEngine
{
    public function isAvailable(): bool;

    /**
     * @throws \RuntimeException on OCR failure
     */
    public function ocrImage(string $absolutePath): string;
}
