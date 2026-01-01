<?php

namespace App\Models\Health;

use App\Models\Auth\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//F19 - Evan Munshi
class MedicationSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'medication_name',
        'dosage',
        'frequency', 
        'scheduled_time', 
        'instructions',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logs()
    {
        return $this->hasMany(MedicationLog::class);
    }
}

