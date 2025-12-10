<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'learning_objectives',
        'category',
        'difficulty_level',
        'estimated_duration_minutes',
        'thumbnail_url',
        'accessibility_features',
        'is_active',
        'order',
    ];

    protected $casts = [
        'accessibility_features' => 'array',
        'is_active' => 'boolean',
        'estimated_duration_minutes' => 'integer',
        'order' => 'integer',
    ];

    /**
     * Get all lessons for this course.
     */
    public function lessons(): HasMany
    {
        return $this->hasMany(CourseLesson::class)->orderBy('order');
    }

    /**
     * Get all enrollments for this course.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    /**
     * Get all certificates issued for this course.
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }
}





