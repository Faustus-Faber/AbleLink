<?php

namespace App\Models\Health;

use App\Models\Auth\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// F17 - Roza Akter
class DoctorAppointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'caregiver_id',
        'doctor_name',
        'specialization',
        'clinic_name',
        'clinic_address',
        'contact_phone',
        'appointment_date',
        'reason',
        'notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'appointment_date' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function caregiver()
    {
        return $this->belongsTo(User::class, 'caregiver_id');
    }
}

