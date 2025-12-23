<?php

//F13 - Farhan Zarif
namespace App\Models\Community;

use App\Models\Auth\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


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

    /**
     * Encrypt the body before saving.
     */
    public function setBodyAttribute($value)
    {
        $this->attributes['body'] = \Illuminate\Support\Facades\Crypt::encryptString($value);
    }

    /**
     * Decrypt the body when retrieving.
     */
    public function getBodyAttribute($value)
    {
        try {
            return \Illuminate\Support\Facades\Crypt::decryptString($value);
        } catch (\Exception $e) {
            // If decryption fails, return a friendly message or the value if it looks like plain text
            // Checking if it looks like an encrypted payload (basic check)
            if (str_starts_with($value, 'eyJ')) {
                 return '[Message cannot be decrypted]';
            }
            return $value;
        }
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}

