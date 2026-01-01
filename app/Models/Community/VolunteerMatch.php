<?php

namespace App\Models\Community;

use App\Models\Auth\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//F14 - Roza Akter
class VolunteerMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'assistance_request_id',
        'volunteer_id',
        'status',
        'volunteer_notes',
        'user_feedback',
        'rating',
        'matched_at',
        'completed_at',
    ];

    protected $casts = [
        'matched_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function assistanceRequest()
    {
        return $this->belongsTo(AssistanceRequest::class);
    }

    public function volunteer()
    {
        return $this->belongsTo(User::class, 'volunteer_id');
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isAccepted()
    {
        return $this->status === 'accepted';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'accepted' => 'bg-green-100 text-green-800',
            'declined' => 'bg-red-100 text-red-800',
            'completed' => 'bg-blue-100 text-blue-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}


