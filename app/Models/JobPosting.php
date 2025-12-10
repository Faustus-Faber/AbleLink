<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobPosting extends Model
{
    use HasFactory;

    protected $fillable = [
        'employer_id',
        'title',
        'description',
        'location',
        'is_remote',
        'job_type',
        'salary_min',
        'salary_max',
        'salary_currency',
        'required_skills',
        'accessibility_features',
        'remote_work_options',
        'is_active',
        'closes_at',
    ];

    protected $casts = [
        'required_skills' => 'array',
        'accessibility_features' => 'array',
        'remote_work_options' => 'array',
        'is_remote' => 'boolean',
        'is_active' => 'boolean',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'closes_at' => 'datetime',
    ];

    /**
     * Get the employer who posted this job.
     */
    public function employer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    /**
     * Get all applications for this job.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }
}





