<?php

namespace App\Services\OcrAndSimplify\TextSimplification;

final class SimplifiedText
{
    /**
     * @param  list<string>  $bullets
     */
    public function __construct(
        public readonly string $simplifiedText,
        public readonly array $bullets,
    ) {
    }
}
