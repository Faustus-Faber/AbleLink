<?php
//F19 - Evan Munshi//

namespace App\Http\Controllers\Health;

use App\Http\Controllers\Controller;
use App\Models\Health\HealthMetric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthMetricController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'metric_type' => 'required|string',
            'value' => 'required|string',
            'unit' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id(); // Assuming user logs their own metrics
        $validated['measured_at'] = now();

        HealthMetric::create($validated);

        return redirect()->back()->with('success', 'Health metric logged successfully.');
    }
}

