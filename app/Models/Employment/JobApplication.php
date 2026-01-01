<?php
// F10 - Roza Akter


namespace App\Models\Employment;

use App\Models\Auth\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'applicant_id',
        'cover_letter',
        'resume_path',
        'status',
        'employer_notes',
        'applied_at',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function applicant()
    {
        return $this->belongsTo(User::class, 'applicant_id');
    }

    public function interviews()
    {
        return $this->hasMany(Interview::class, 'job_application_id');
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isShortlisted()
    {
        return $this->status === 'shortlisted';
    }

    public function isAccepted()
    {
        return $this->status === 'accepted';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'reviewing' => 'bg-blue-100 text-blue-800',
            'shortlisted' => 'bg-green-100 text-green-800',
            'interviewed' => 'bg-purple-100 text-purple-800',
            'accepted' => 'bg-emerald-100 text-emerald-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}

