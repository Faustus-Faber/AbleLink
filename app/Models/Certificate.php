<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'certificate_number',
        'qr_code_path',
        'pdf_path',
        'issued_at',
        'expires_at',
        'is_verified',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    /**
     * Get the user who received this certificate.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the course this certificate is for.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}





