<?php

// F17 - Roza Akter
namespace App\Notifications\Health;

use App\Models\Health\DoctorAppointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentScheduled extends Notification
{
    use Queueable;

    public $appointment;

    public function __construct(DoctorAppointment $appointment)
    {
        $this->appointment = $appointment;
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
        $caregiverName = $this->appointment->caregiver ? $this->appointment->caregiver->name : 'Your caregiver';

        return (new MailMessage)
            ->subject('New Doctor Appointment Scheduled')
            ->line("A new doctor appointment has been scheduled for you by {$caregiverName}.")
            ->line("Doctor: {$doctorName}")
            ->line("Date & Time: {$appointmentDate}")
            ->action('View Appointments', route('user.appointments.index'))
            ->line('Thank you for using AbleLink!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'doctor_name' => $this->appointment->doctor_name,
            'specialization' => $this->appointment->specialization,
            'appointment_date' => $this->appointment->appointment_date->format('Y-m-d H:i:s'),
            'clinic_name' => $this->appointment->clinic_name,
            'caregiver_name' => $this->appointment->caregiver ? $this->appointment->caregiver->name : null,
            'message' => "A new appointment with Dr. {$this->appointment->doctor_name} has been scheduled for " . $this->appointment->appointment_date->format('F j, Y \a\t g:i A') . ".",
        ];
    }
}

