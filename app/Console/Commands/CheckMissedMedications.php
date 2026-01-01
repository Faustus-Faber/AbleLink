<?php



namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Health\MedicationSchedule;
use App\Models\Auth\User;
use App\Notifications\Health\MissedMedicationNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

/**
 * Check for missed medications and notify caregivers.
 */
class CheckMissedMedications extends Command
{
    protected $signature = 'health:check-missed';
    protected $description = 'Check for missed medications and notify caregivers';

    public function handle()
    {
        $this->info('Checking for missed medications...');

        $now = now();
        
        $medications = MedicationSchedule::where('is_active', true)
            ->whereNotNull('scheduled_time')
            ->get();

        $count = 0;

        foreach ($medications as $med) {
            if (!$med->scheduled_time) continue;
            
            $scheduledTime = Carbon::parse($med->scheduled_time);
            $todayScheduled = now()->setTimeFrom($scheduledTime);
            
            if ($now->greaterThan($todayScheduled->copy()->addMinutes(30))) {
                $isLogged = $med->logs()
                    ->whereDate('created_at', today())
                    ->exists();

                if (!$isLogged) {
                    $med->logs()->create([
                        'status' => 'missed',
                        'taken_at' => null,
                        'notes' => 'Auto-detected as missed'
                    ]);

                    $patient = $med->user;
                    
                    $caregivers = $patient->caregivers()
                        ->wherePivot('status', 'active')
                        ->get();
                    
                    if ($caregivers->isNotEmpty()) {
                        Notification::send($caregivers, new MissedMedicationNotification($med, $patient));
                    }

                    $patient->notify(new MissedMedicationNotification($med, $patient));

                    $this->line("Notified patient {$patient->name} and caregivers for - {$med->medication_name}");
                    $count++;
                }
            }
        }

        $this->info("Done. Sent {$count} notifications.");
    }
}

