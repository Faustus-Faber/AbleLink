<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    //F3 - Evan Yuvraj Munshi
    protected $fillable = [
        'user_id',
        'bio',
        'disability_type',
        'accessibility_preferences',
        'avatar',
        'phone_number',
        'address',
        'date_of_birth',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_phone',
    ];
    
    protected $casts = [
        'accessibility_preferences' => 'array',
        'date_of_birth' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //F3 - Evan Yuvraj Munshi
    public function getAccessibilityPreference($key, $default = null)
    {
        return $this->accessibility_preferences[$key] ?? $default;
    }

    //F3 - Evan Yuvraj Munshi
    public function setAccessibilityPreference($key, $value)
    {
        $preferences = $this->accessibility_preferences ?? [];
        $preferences[$key] = $value;
        $this->accessibility_preferences = $preferences;
    }
}
