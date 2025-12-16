<?php
// F10 - Rifat Jahan Roza
//F10 - Rifat Jahan Roza

namespace App\Models\Employment;

use App\Models\Auth\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//F10 - Employer Job Posting & Dashboard - Interviews
class Interview extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_application_id',
        'employer_id',
        'applicant_id',
        'title',
        'description',
        'scheduled_at',
        'type',
        'location',
        'meeting_link',
        'status',
        'notes',
        'feedback',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function jobApplication()
    {
        return $this->belongsTo(JobApplication::class);
    }

    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function applicant()
    {
        return $this->belongsTo(User::class, 'applicant_id');
    }

    public function isScheduled()
    {
        return $this->status === 'scheduled';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }
}

