<?php

namespace App\Services\OcrAndSimplify\Ocr;

use Symfony\Component\Process\Process;

final class TesseractOcrEngine implements OcrEngine
{
    public function __construct(
        private readonly string $language = 'eng',
        private readonly int $timeoutSeconds = 60,
    ) {
    }

    public function isAvailable(): bool
    {
        $process = new Process(['tesseract', '--version']);
        $process->setTimeout(5);
        $process->run();

        return $process->isSuccessful();
    }

    public function ocrImage(string $absolutePath): string
    {
        $process = new Process([
            'tesseract',
            $absolutePath,
            'stdout',
            '-l',
            $this->language,
        ]);
        $process->setTimeout($this->timeoutSeconds);
        $process->run();

        if (!$process->isSuccessful()) {
            $error = trim($process->getErrorOutput()) !== '' ? trim($process->getErrorOutput()) : trim($process->getOutput());
            throw new \RuntimeException('OCR failed: '.$error);
        }

        return $process->getOutput();
    }
}
