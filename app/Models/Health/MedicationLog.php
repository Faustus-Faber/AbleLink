<?php

namespace App\Models\Health;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//F19 - Evan Munshi
class MedicationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'medication_schedule_id',
        'taken_at',
        'status', 
        'notes'
    ];

    protected $casts = [
        'taken_at' => 'datetime',
    ];

    public function schedule()
    {
        return $this->belongsTo(MedicationSchedule::class, 'medication_schedule_id');
    }
}
