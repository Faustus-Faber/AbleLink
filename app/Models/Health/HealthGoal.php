<?php

namespace App\Models\Health;

use App\Models\Auth\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//F19 - Evan Munshi
class HealthGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'caregiver_id',
        'title',
        'description',
        'target_metric',
        'target_value',
        'deadline',
        'status',
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

