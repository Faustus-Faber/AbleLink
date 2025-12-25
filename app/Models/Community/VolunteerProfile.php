<?php

namespace App\Models\Community;

use App\Models\Auth\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//F14 - Volunteer Matching System
class VolunteerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bio',
        'skills',
        'availability',
        'location',
        'max_distance_km',
        'available_for_emergency',
        'specializations',
    ];

    protected $casts = [
        'skills' => 'array',
        'availability' => 'array',
        'available_for_emergency' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function matches()
    {
        return $this->hasMany(VolunteerMatch::class, 'volunteer_id');
    }

    public function activeMatches()
    {
        return $this->hasMany(VolunteerMatch::class, 'volunteer_id')
            ->whereIn('status', ['accepted', 'in_progress']);
    }
}


