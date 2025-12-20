<?php
// F10 - Rifat Jahan Roza
//F10 - Rifat Jahan Roza

namespace App\Models\Employment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Auth\User;


//F10 - Employer Job Posting & Dashboard
class Job extends Model
{
    use HasFactory;

    protected $table = 'employer_jobs';

    protected $fillable = [
        'employer_id',
        'title',
        'description',
        'location',
        'job_type',
        'salary_min',
        'salary_max',
        'salary_currency',
        'application_deadline',
        'wheelchair_accessible',
        'sign_language_support',
        'screen_reader_compatible',
        'flexible_hours',
        'remote_work_available',
        'accessibility_accommodations',
        'additional_requirements',
        'status',
        // F12 - Farhan Zarif
        'skills_required',
        'embedding_vector',
    ];

    protected $casts = [
        'application_deadline' => 'date',
        'wheelchair_accessible' => 'boolean',
        'sign_language_support' => 'boolean',
        'screen_reader_compatible' => 'boolean',
        'flexible_hours' => 'boolean',
        'remote_work_available' => 'boolean',
        'salary_max' => 'decimal:2',
        // F12 - Farhan Zarif
        'skills_required' => 'array',
        'embedding_vector' => 'array',
    ];

    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }
    // F9 - Evan Munshi
    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function pendingApplications()
    {
        return $this->hasMany(JobApplication::class, 'job_id')->where('status', 'pending');
    }

    public function shortlistedApplications()
    {
        return $this->hasMany(JobApplication::class, 'job_id')->where('status', 'shortlisted');
    }
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isClosed()
    {
        return in_array($this->status, ['closed', 'filled']);
    }

    public function getAccessibilityFeaturesAttribute()
    {
        $features = [];
        
        if ($this->wheelchair_accessible) {
            $features[] = 'Wheelchair Accessible';
        }
        if ($this->sign_language_support) {
            $features[] = 'Sign Language Support';
        }
        if ($this->screen_reader_compatible) {
            $features[] = 'Screen Reader Compatible';
        }
        if ($this->flexible_hours) {
            $features[] = 'Flexible Hours';
        }
        if ($this->remote_work_available) {
            $features[] = 'Remote Work Available';
        }
        
        return $features;
    }
}
