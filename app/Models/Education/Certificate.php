<?php

namespace App\Models\Education;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'certificate_code',
        'ai_generated_message',
        'issued_at',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Auth\User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Education\Course::class);
    }
}
