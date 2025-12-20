<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    //F1 - Akida Lisi
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
        // F12 - Farhan Zarif
        'skills',
        'interests',
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
            // F12 - Farhan Zarif
            'skills' => 'array',
            'interests' => 'array',
        ];
    }

    //F1 - Akida Lisi
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

    public function hasCompletedOtp(): bool
    {
        return (bool) $this->otp_verified_at;
    }

    //F3 - Evan Yuvraj Munshi
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    //F4 - Farhan Zarif
    public function patients()
    {
        return $this->belongsToMany(User::class, 'caregiver_user', 'caregiver_id', 'user_id')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    public function caregivers()
    {
        return $this->belongsToMany(User::class, 'caregiver_user', 'user_id', 'caregiver_id')
                    ->withPivot('status')
                    ->withTimestamps();
    }
}
