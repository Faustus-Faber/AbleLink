<?php

namespace App\Models\Community;

use App\Models\Auth\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//F16 - Evan Yuvraj Munshi
class MatrimonyProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gender',
        'age',
        'photo_path',
        'bio',
        'occupation',
        'education',
        'marital_status',
        'religion',
        'partner_preferences',
        'hobbies',
        'privacy_level',
    ];

    protected $casts = [
        'hobbies' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

