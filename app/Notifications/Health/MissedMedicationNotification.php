<?php

// F19 - Evan Yuvraj Munshi

namespace App\Notifications\Health;

use App\Models\Health\MedicationSchedule;
use App\Models\Auth\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MissedMedicationNotification extends Notification
{
    use Queueable;

    protected $medication;
    protected $patient;

    public function __construct(MedicationSchedule $medication, User $patient)
    {
        $this->medication = $medication;
        $this->patient = $patient;
    }

    public function via($notifiable)
    {
        return ['database']; // Keeping it simple for local testing, can add 'mail' later
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "Patient {$this->patient->name} missed medication: {$this->medication->medication_name} at {$this->medication->scheduled_time}",
            'action_url' => route('caregiver.patient.health', $this->patient),
            'type' => 'missed_medication'
        ];
    }
}

