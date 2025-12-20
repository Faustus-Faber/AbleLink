<?php

namespace App\Models\Education;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    // F21 - AI Certificate Generation
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

    public function user()
    {
        return $this->belongsTo(\App\Models\Auth\User::class);
    }

    public function course()
    {
        return $this->belongsTo(\App\Models\Education\Course::class);
    }
}
