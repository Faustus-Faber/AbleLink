<?php
//F11 - Akida Lisi

namespace App\Models\Education;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'summary',
        'description',
        'level',
        'estimated_minutes',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'estimated_minutes' => 'integer',
        ];
    }

    public function media(): HasMany
    {
        return $this->hasMany(CourseMedia::class)->orderBy('sort_order')->orderBy('id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public static function makeSlug(string $title): string
    {
        return Str::slug($title);
    }
}
