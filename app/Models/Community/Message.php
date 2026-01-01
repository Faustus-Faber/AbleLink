<?php

namespace App\Models\Community;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'body',
        'is_read',
        'attachment_path',
        'attachment_type',
        'attachment_original_name',
    ];

    public function setBodyAttribute(string $plainTextBody): void
    {
        $this->attributes['body'] = Crypt::encryptString($plainTextBody);
    }

    public function getBodyAttribute(string $encryptedBody): string
    {
        try {
            return Crypt::decryptString($encryptedBody);
        } catch (\Exception $exception) {
            $isPotentialEncryptedString = Str::startsWith($encryptedBody, 'eyJ');
            
            if ($isPotentialEncryptedString === true) {
                 return '[Message cannot be decrypted]';
            }
            
            return $encryptedBody;
        }
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}

