<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Employment\Job;
use App\Models\Employment\JobApplication;
use App\Models\Employment\EmployerProfile;
use App\Models\Employment\Interview;
use App\Models\Community\VolunteerProfile;
use App\Models\Community\AssistanceRequest;
use App\Models\Community\VolunteerMatch;
use App\Models\Community\CommunityEvent;
use App\Models\Community\MatrimonyProfile;
use App\Models\Health\DoctorAppointment;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    //F1 - Tarannum Al Akida
    public const ROLE_EMPLOYER = 'employer';
    public const ROLE_VOLUNTEER = 'volunteer';
    public const ROLE_DISABLED = 'disabled';
    public const ROLE_CAREGIVER = 'caregiver';
    public const ROLE_ADMIN = 'admin';

    public const COMMUNITY_ROLES = [
        self::ROLE_EMPLOYER,
        self::ROLE_VOLUNTEER,
        self::ROLE_DISABLED,
        self::ROLE_CAREGIVER,
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'otp_verified_at',
        'last_login_at',
        'banned_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'otp_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'banned_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    //F4 - Farhan Zarif
    public function profile(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        $relatedModel = UserProfile::class;
        $relation = $this->hasOne($relatedModel);
        
        return $relation;
    }

    public function patients(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        $relatedModel = User::class;
        $pivotTable = 'caregiver_user';
        $foreignKey = 'caregiver_id'; 
        $relatedPivotKey = 'user_id';  
        
        $relation = $this->belongsToMany($relatedModel, $pivotTable, $foreignKey, $relatedPivotKey);
        $relation->withPivot('status');
        $relation->withTimestamps();
        
        return $relation;
    }

    public function caregivers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        $relatedModel = User::class;
        $pivotTable = 'caregiver_user';
        $foreignKey = 'user_id';        
        $relatedPivotKey = 'caregiver_id'; 
        
        $relation = $this->belongsToMany($relatedModel, $pivotTable, $foreignKey, $relatedPivotKey);
        $relation->withPivot('status');
        $relation->withTimestamps();
        
        return $relation;
    }
    
    //F1 -Tarannum Al Akida
    public function hasRole($role) 
    {
        return $this->role === $role;
    }

    public function otpCodes(): HasMany
    {
        return $this->hasMany(OtpCode::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isBanned(): bool
    {
        return $this->banned_at !== null;
    }

    // F19 - Evan Munshi
    public function isCaregiver(): bool
    {
        return $this->role === self::ROLE_CAREGIVER;
    }

    public function hasCompletedOtp(): bool
    {
        return (bool) $this->otp_verified_at;
    }
    //F10 - Roza Akter
    public function jobs()
    {
        return $this->hasMany(Job::class, 'employer_id');
    }

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class, 'applicant_id');
    }

    public function employerProfile()
    {
        return $this->hasOne(EmployerProfile::class);
    }

    public function interviewsAsEmployer()
    {
        return $this->hasMany(Interview::class, 'employer_id');
    }

    public function interviewsAsApplicant()
    {
        return $this->hasMany(Interview::class, 'applicant_id');
    }

    //F14 - Roza Akter
    public function volunteerProfile()
    {
        return $this->hasOne(VolunteerProfile::class);
    }

    public function assistanceRequests()
    {
        return $this->hasMany(AssistanceRequest::class, 'user_id');
    }

    public function volunteerMatches()
    {
        return $this->hasMany(VolunteerMatch::class, 'volunteer_id');
    }
    //F16 - Evan Yuvraj Munshi
    public function communityEvents()
    {
        return $this->hasMany(CommunityEvent::class, 'organizer_id');
    }

    public function eventParticipations()
    {
        return $this->belongsToMany(CommunityEvent::class, 'event_participants')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    public function matrimonyProfile()
    {
        return $this->hasOne(MatrimonyProfile::class);
    }

    // F17 - Roza Akter
    public function doctorAppointments()
    {
        return $this->hasMany(DoctorAppointment::class);
    }

    public function caregiverAppointments()
    {
        return $this->hasMany(DoctorAppointment::class, 'caregiver_id');
    }
}
