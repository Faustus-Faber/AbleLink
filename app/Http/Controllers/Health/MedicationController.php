<?php
//F19 - Evan Munshi//

namespace App\Http\Controllers\Health;

use App\Http\Controllers\Controller;
use App\Models\Health\MedicationSchedule;
use App\Models\Health\MedicationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'medication_name' => 'required|string',
            'dosage' => 'required|string',
            'frequency' => 'required|string',
            'scheduled_time' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string',
        ]);

        $validated['caregiver_id'] = Auth::id(); // Assuming caregiver is creating this

        MedicationSchedule::create($validated);

        return redirect()->back()->with('success', 'Medication schedule added successfully.');
    }

    public function log(Request $request, MedicationSchedule $schedule)
    {
        $validated = $request->validate([
            'status' => 'required|in:taken,missed,skipped',
            'notes' => 'nullable|string',
        ]);

        MedicationLog::create([
            'medication_schedule_id' => $schedule->id,
            'taken_at' => now(),
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Medication logged successfully.');
    }
}

