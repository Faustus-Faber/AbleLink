<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Health\DoctorAppointment;
use App\Notifications\Health\AppointmentReminder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

/**
 * Check for upcoming appointments and send reminder notifications.
 */
class CheckAppointmentReminders extends Command
{
    protected $signature = 'appointments:check-reminders';
    protected $description = 'Check for upcoming appointments and send reminder notifications';

    public function handle()
    {
        $this->info('Checking for upcoming appointments...');

        $now = now();
        $count = 0;

        $appointments = DoctorAppointment::where('status', 'scheduled')
            ->where('appointment_date', '>', $now)
            ->with(['user', 'caregiver'])
            ->get();

        foreach ($appointments as $appointment) {
            $appointmentTime = Carbon::parse($appointment->appointment_date);
            $hoursUntil = $now->diffInHours($appointmentTime, false);
            $minutesUntil = $now->diffInMinutes($appointmentTime, false);

            if ($hoursUntil >= 24 && $hoursUntil < 25) {
                $checkTime = now()->subHours(2);
                $has24hReminder = $appointment->user->notifications()
                    ->where('type', AppointmentReminder::class)
                    ->where('data->appointment_id', $appointment->id)
                    ->where('data->reminder_type', '24h')
                    ->where('created_at', '>=', $checkTime)
                    ->exists();

                if (!$has24hReminder) {
                    $appointment->user->notify(new AppointmentReminder($appointment, '24h'));
                    $this->line("Sent 24h reminder for appointment with Dr. {$appointment->doctor_name} to {$appointment->user->name}");
                    $count++;
                }
            }

            if ($hoursUntil >= 1 && $hoursUntil < 1.5) {
                $checkTime = now()->subMinutes(30);
                $has1hReminder = $appointment->user->notifications()
                    ->where('type', AppointmentReminder::class)
                    ->where('data->appointment_id', $appointment->id)
                    ->where('data->reminder_type', '1h')
                    ->where('created_at', '>=', $checkTime)
                    ->exists();

                if (!$has1hReminder) {
                    $appointment->user->notify(new AppointmentReminder($appointment, '1h'));
                    $this->line("Sent 1h reminder for appointment with Dr. {$appointment->doctor_name} to {$appointment->user->name}");
                    $count++;
                }
            }

            if ($minutesUntil >= 30 && $minutesUntil < 45) {
                $checkTime = now()->subMinutes(15);
                $has30mReminder = $appointment->user->notifications()
                    ->where('type', AppointmentReminder::class)
                    ->where('data->appointment_id', $appointment->id)
                    ->where('data->reminder_type', '30m')
                    ->where('created_at', '>=', $checkTime)
                    ->exists();

                if (!$has30mReminder) {
                    $appointment->user->notify(new AppointmentReminder($appointment, '30m'));
                    $this->line("Sent 30m reminder for appointment with Dr. {$appointment->doctor_name} to {$appointment->user->name}");
                    $count++;
                }
            }
        }

        $this->info("Done. Sent {$count} reminder notifications.");
    }
}

