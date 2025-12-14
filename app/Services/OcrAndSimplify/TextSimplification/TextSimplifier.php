<?php

namespace App\Services\OcrAndSimplify\TextSimplification;

final class TextSimplifier
{
    public function simplify(string $text): SimplifiedText
    {
        $clean = $this->normalize($text);

        if ($clean === '') {
            return new SimplifiedText('', []);
        }

        $paragraphs = array_values(array_filter(
            preg_split("/\n{2,}/", $clean) ?: [],
            static fn (string $p) => trim($p) !== ''
        ));

        $bullets = $this->makeBullets($paragraphs);
        $simplified = $this->rewrite($paragraphs);

        return new SimplifiedText($simplified, $bullets);
    }

    private function normalize(string $text): string
    {
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $text = preg_replace('/[ \t]+/u', ' ', $text) ?? $text;
        $text = preg_replace("/\n{3,}/u", "\n\n", $text) ?? $text;
        $text = trim($text);

        return $text;
    }

    /**
     * @param  list<string>  $paragraphs
     */
    private function makeBullets(array $paragraphs): array
    {
        $bullets = [];

        foreach ($paragraphs as $p) {
            $firstSentence = $this->firstSentence($p);
            if ($firstSentence !== '') {
                $bullets[] = $firstSentence;
            }
            if (count($bullets) >= 6) {
                break;
            }
        }

        return $bullets;
    }

    private function firstSentence(string $paragraph): string
    {
        $paragraph = trim($paragraph);
        if ($paragraph === '') {
            return '';
        }

        $paragraph = preg_replace('/\s+/u', ' ', $paragraph) ?? $paragraph;
        $parts = preg_split('/(?<=[.!?])\s+/u', $paragraph, 2) ?: [];
        $sentence = trim($parts[0] ?? '');

        return $this->simplifySentence($sentence);
    }

    /**
     * @param  list<string>  $paragraphs
     */
    private function rewrite(array $paragraphs): string
    {
        $sentencesOut = [];

        foreach ($paragraphs as $p) {
            $p = trim($p);
            if ($p === '') {
                continue;
            }

            $p = preg_replace('/\s+/u', ' ', $p) ?? $p;
            $sentences = preg_split('/(?<=[.!?])\s+/u', $p) ?: [];

            foreach ($sentences as $s) {
                $s = trim($s);
                if ($s === '') {
                    continue;
                }

                foreach ($this->splitLongSentence($s) as $chunk) {
                    $chunk = $this->simplifySentence($chunk);
                    if ($chunk !== '') {
                        $sentencesOut[] = $chunk;
                    }
                }

                if (count($sentencesOut) >= 40) {
                    break 2;
                }
            }

            $sentencesOut[] = '';
        }

        $out = trim(implode("\n", $sentencesOut));
        $out = preg_replace("/\n{3,}/u", "\n\n", $out) ?? $out;

        return $out;
    }

    /**
     * @return list<string>
     */
    private function splitLongSentence(string $sentence): array
    {
        $sentence = trim($sentence);
        if (mb_strlen($sentence) <= 140) {
            return [$sentence];
        }

        // Remove parentheticals (often extra detail).
        $sentence = preg_replace('/\s*\([^)]*\)\s*/u', ' ', $sentence) ?? $sentence;
        $sentence = preg_replace('/\s+/u', ' ', $sentence) ?? $sentence;

        // Split on common separators.
        $parts = preg_split('/\s*(?:;|:|—|-|,)\s*/u', $sentence) ?: [$sentence];
        $parts = array_values(array_filter(array_map('trim', $parts), static fn (string $p) => $p !== ''));

        if (count($parts) <= 1) {
            return [$sentence];
        }

        return array_slice($parts, 0, 6);
    }

    private function simplifySentence(string $sentence): string
    {
        $s = trim($sentence);
        if ($s === '') {
            return '';
        }

        $replacements = [
            '/\butili[sz]e\b/i' => 'use',
            '/\bapproximately\b/i' => 'about',
            '/\bprior to\b/i' => 'before',
            '/\bcommence\b/i' => 'start',
            '/\bterminate\b/i' => 'end',
            '/\bassist\b/i' => 'help',
            '/\bindividuals\b/i' => 'people',
            '/\bmultiple\b/i' => 'many',
            '/\bsubsequent\b/i' => 'next',
            '/\btherefore\b/i' => 'so',
        ];

        foreach ($replacements as $pattern => $replacement) {
            $s = preg_replace($pattern, $replacement, $s) ?? $s;
        }

        // Prefer direct voice.
        $s = preg_replace('/\bit is (?:recommended|advised) that\b/i', 'please', $s) ?? $s;
        $s = preg_replace('/\bdue to the fact that\b/i', 'because', $s) ?? $s;

        $s = trim(preg_replace('/\s+/u', ' ', $s) ?? $s);

        // Ensure it ends with punctuation (helps readability in the UI).
        if (!preg_match('/[.!?]$/u', $s)) {
            $s .= '.';
        }

        return $s;
    }
}
