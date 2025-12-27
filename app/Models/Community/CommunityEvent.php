<?php

namespace App\Models\Community;

use App\Models\Auth\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//F16 - Evan Yuvraj Munshi
class CommunityEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id',
        'title',
        'description',
        'event_date',
        'location',
        'type',
        'meeting_link',
    ];

    protected $casts = [
        'event_date' => 'datetime',
    ];

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'event_participants')
                    ->withPivot('status')
                    ->withTimestamps();
    }
}

