<?php

//F13 - Farhan Zarif
namespace App\Services\Core;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class EncryptionService
{
    /**
     * Encrypt a string.
     *
     * @param string $text
     * @return string
     */
    public function encrypt(string $text): string
    {
        return Crypt::encryptString($text);
    }

    /**
     * Decrypt a string.
     *
     * @param string $encryptedText
     * @return string|null
     */
    public function decrypt(string $encryptedText): ?string
    {
        try {
            return Crypt::decryptString($encryptedText);
        } catch (DecryptException $e) {
            return null; // Or handle error appropriately
        }
    }
}
