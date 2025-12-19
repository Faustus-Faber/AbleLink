<?php
//F11 - Akida Lisi

namespace App\Models\Education;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class CourseMedia extends Model
{
    use HasFactory;

    public const KIND_VIDEO = 'video';
    public const KIND_AUDIO = 'audio';
    public const KIND_DOCUMENT = 'document';
    public const KIND_LINK = 'link';

    public const KINDS = [
        self::KIND_VIDEO,
        self::KIND_AUDIO,
        self::KIND_DOCUMENT,
        self::KIND_LINK,
    ];

    protected $fillable = [
        'course_id',
        'kind',
        'title',
        'external_url',
        'media_path',
        'mime_type',
        'is_primary',
        'sort_order',
        'captions_path',
        'captions_language',
        'transcript',
        'audio_description_path',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function getMediaUrlAttribute(): ?string
    {
        if ($this->external_url) {
            return $this->external_url;
        }

        if (! $this->media_path) {
            return null;
        }

        return Storage::disk('public')->url($this->media_path);
    }

    public function getEmbedUrlAttribute(): ?string
    {
        if (! $this->external_url) {
            return null;
        }

        $videoId = self::extractYouTubeVideoId($this->external_url);
        if (! $videoId) {
            return null;
        }

        $startSeconds = self::extractYouTubeStartSeconds($this->external_url);

        $query = [
            'rel' => '0',
            'modestbranding' => '1',
            'origin' => request()->schemeAndHttpHost(), // Dynamic origin for localhost vs 127.0.0.1
        ];
        if ($startSeconds !== null && $startSeconds > 0) {
            $query['start'] = (string) $startSeconds;
        }

        return 'https://www.youtube.com/embed/'.$videoId.'?'.http_build_query($query);
    }

    private static function extractYouTubeVideoId(string $url): ?string
    {
        // 1. Regex handles: youtube.com/watch?v=, youtu.be/, embed/, shorts/
        // Added check to stop at ? or & for Ids
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        // 2. Fallback to structured parsing if regex fails (legacy support)
        $parts = parse_url($url);
        if (! is_array($parts)) {
            return null;
        }

        $host = strtolower((string) ($parts['host'] ?? ''));
        $path = (string) ($parts['path'] ?? '');

        if ($host === 'youtu.be') {
            $id = trim($path, '/');
            return $id !== '' ? $id : null;
        }

        if (str_contains($host, 'youtube.com')) {
            if (str_starts_with($path, '/watch')) {
                parse_str((string) ($parts['query'] ?? ''), $query);
                return (string) ($query['v'] ?? '') ?: null;
            }
            if (preg_match('#^/(?:embed|shorts)/([^/?]+)#', $path, $m)) {
                return $m[1] ?? null;
            }
        }

        return null;
    }

    private static function extractYouTubeStartSeconds(string $url): ?int
    {
        $parts = parse_url($url);
        if (! is_array($parts)) {
            return null;
        }

        $queryString = (string) ($parts['query'] ?? '');
        if ($queryString === '') {
            return null;
        }

        parse_str($queryString, $query);

        // Common forms: &t=90, &t=1m30s, &start=90
        $raw = $query['start'] ?? $query['t'] ?? null;
        if ($raw === null) {
            return null;
        }

        if (is_numeric($raw)) {
            return (int) $raw;
        }

        if (is_string($raw) && preg_match('/^(?:(\d+)h)?(?:(\d+)m)?(?:(\d+)s)?$/', $raw, $m)) {
            $h = (int) ($m[1] ?? 0);
            $min = (int) ($m[2] ?? 0);
            $sec = (int) ($m[3] ?? 0);
            $total = $h * 3600 + $min * 60 + $sec;
            return $total > 0 ? $total : null;
        }

        return null;
    }

    public function getCaptionsUrlAttribute(): ?string
    {
        if (! $this->captions_path) {
            return null;
        }

        return Storage::disk('public')->url($this->captions_path);
    }

    public function getAudioDescriptionUrlAttribute(): ?string
    {
        if (! $this->audio_description_path) {
            return null;
        }

        return Storage::disk('public')->url($this->audio_description_path);
    }
}
