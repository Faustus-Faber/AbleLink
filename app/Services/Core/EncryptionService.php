<?php

namespace App\Services\Core;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class EncryptionService
{
    public function encrypt(string $plainText): string
    {
        return Crypt::encryptString($plainText);
    }

    public function decrypt(string $encryptedText): ?string
    {
        try {
            return Crypt::decryptString($encryptedText);
        } catch (DecryptException $exception) {
            return null;
        }
    }
}
