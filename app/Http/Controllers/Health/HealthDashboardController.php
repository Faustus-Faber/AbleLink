<?php
//F19 - Evan Munshi//

namespace App\Http\Controllers\Health;

use App\Http\Controllers\Controller;
use App\Models\Health\MedicationSchedule;
use App\Models\Health\HealthGoal;
use App\Models\Health\HealthMetric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ensure profile exists
        if (!$user->profile) {
            $user->profile()->create([]);
        }
        
        $medications = MedicationSchedule::where('user_id', $user->id)->where('is_active', true)->with(['logs' => function($q) {
            $q->whereDate('taken_at', today());
        }])->get();

        $goals = HealthGoal::where('user_id', $user->id)->where('status', 'active')->get();
        $recentMetrics = HealthMetric::where('user_id', $user->id)->latest()->take(5)->get();

        // Calculate Missed Medications (Simple Logic for Demo)
        $missedMedications = collect();
        $now = now();
        
        foreach ($medications as $med) {
            if ($med->frequency === 'daily' && $med->scheduled_time) {
                $scheduledTime = \Carbon\Carbon::parse($med->scheduled_time);
                $todayScheduled = now()->setTimeFrom($scheduledTime);
                
                if ($now->greaterThan($todayScheduled)) {
                    // Check if logged today
                    if ($med->logs->isEmpty()) {
                        $missedMedications->push($med);
                    }
                }
            }
        }

        return view('health.dashboard', compact('medications', 'goals', 'recentMetrics', 'missedMedications', 'user'));
    }
}

