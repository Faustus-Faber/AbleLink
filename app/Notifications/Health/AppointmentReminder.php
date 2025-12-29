<?php

// F17 - Rifat Jahan Roza
namespace App\Notifications\Health;

use App\Models\Health\DoctorAppointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentReminder extends Notification
{
    use Queueable;

    public $appointment;
    public $reminderType; // '24h', '1h', '30m'

    /**
     * Create a new notification instance.
     */
    public function __construct(DoctorAppointment $appointment, string $reminderType = '24h')
    {
        $this->appointment = $appointment;
        $this->reminderType = $reminderType;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $doctorName = $this->appointment->doctor_name;
        $appointmentDate = $this->appointment->appointment_date->format('F j, Y \a\t g:i A');
        $timeUntil = $this->getTimeUntilMessage();

        return (new MailMessage)
            ->subject("Appointment Reminder: {$timeUntil}")
            ->line("This is a reminder about your upcoming doctor appointment.")
            ->line("Doctor: {$doctorName}")
            ->line("Date & Time: {$appointmentDate}")
            ->line("Time until appointment: {$timeUntil}")
            ->action('View Appointments', route('user.appointments.index'))
            ->line('Please make sure to arrive on time!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $timeUntil = $this->getTimeUntilMessage();
        
        return [
            'appointment_id' => $this->appointment->id,
            'doctor_name' => $this->appointment->doctor_name,
            'specialization' => $this->appointment->specialization,
            'appointment_date' => $this->appointment->appointment_date->format('Y-m-d H:i:s'),
            'clinic_name' => $this->appointment->clinic_name,
            'reminder_type' => $this->reminderType,
            'message' => "Reminder: You have an appointment with Dr. {$this->appointment->doctor_name} in {$timeUntil} on " . $this->appointment->appointment_date->format('F j, Y \a\t g:i A') . ".",
        ];
    }

    /**
     * Get human-readable time until appointment message
     */
    private function getTimeUntilMessage(): string
    {
        switch ($this->reminderType) {
            case '24h':
                return '24 hours';
            case '1h':
                return '1 hour';
            case '30m':
                return '30 minutes';
            default:
                return $this->reminderType;
        }
    }
}

