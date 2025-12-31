<?php
//F19 - Evan Munshi//

namespace App\Http\Controllers\Health;

use App\Http\Controllers\Controller;
use App\Models\Health\HealthGoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthGoalController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'target_metric' => 'nullable|string',
            'target_value' => 'nullable|numeric',
            'deadline' => 'nullable|date',
        ]);

        $validated['caregiver_id'] = Auth::id();

        HealthGoal::create($validated);

        return redirect()->back()->with('success', 'Health goal created successfully.');
    }

    public function updateStatus(Request $request, HealthGoal $goal)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,completed,cancelled',
        ]);

        $goal->update($validated);

        return redirect()->back()->with('success', 'Goal status updated.');
    }
}

