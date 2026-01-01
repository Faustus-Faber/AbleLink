<?php

namespace App\Models\Community;

use App\Models\Auth\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//F14 - Roza Akter
class AssistanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'type',
        'urgency',
        'location',
        'preferred_date_time',
        'status',
        'special_requirements',
    ];

    protected $casts = [
        'preferred_date_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function matches()
    {
        return $this->hasMany(VolunteerMatch::class, 'assistance_request_id');
    }

    public function acceptedMatch()
    {
        return $this->hasOne(VolunteerMatch::class, 'assistance_request_id')
            ->where('status', 'accepted');
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isMatched()
    {
        return $this->status === 'matched';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function getUrgencyColorAttribute()
    {
        return match($this->urgency) {
            'emergency' => 'bg-red-100 text-red-800',
            'high' => 'bg-orange-100 text-orange-800',
            'medium' => 'bg-yellow-100 text-yellow-800',
            'low' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}


