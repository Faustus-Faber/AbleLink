<?php
//F15 - Akida Lisi
namespace App\Http\Controllers\Emergency;

use App\Http\Controllers\Controller;

use App\Mail\Emergency\EmergencySosAlertMail;
use App\Models\Emergency\EmergencySosEvent;
use App\Models\Auth\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class EmergencySosController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (! $user || ! $user->hasRole(User::ROLE_DISABLED)) {
            abort(403, 'Unauthorized action.');
        }

        if (! Schema::hasTable('emergency_sos_events')) {
            return redirect()
                ->route('profile.show')
                ->with('error', 'SOS is not ready yet. Please run database migrations first.');
        }

        $validated = $request->validate([
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'accuracy_m' => 'nullable|integer|min:0|max:100000',
            'address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        $address = $validated['address'] ?? null;
        if (! $address && $user->profile && $user->profile->address) {
            $address = $user->profile->address;
        }

        // F15 - Check for existing unresolved SOS
        $existingEvent = EmergencySosEvent::where('user_id', $user->id)
            ->whereNull('resolved_at')
            ->first();

        if ($existingEvent) {
             // Update location if provided
            $existingEvent->update([
                'latitude' => $validated['latitude'] ?? $existingEvent->latitude,
                'longitude' => $validated['longitude'] ?? $existingEvent->longitude,
                'accuracy_m' => $validated['accuracy_m'] ?? $existingEvent->accuracy_m,
                'notes' => ($validated['notes'] && $validated['notes'] !== $existingEvent->notes) 
                    ? $existingEvent->notes . "\n[Update]: " . $validated['notes'] 
                    : $existingEvent->notes,
                'updated_at' => now(), // Bump it in lists
            ]);

            return redirect()
                ->route('profile.show')
                ->with([
                    'success' => 'SOS alert updated. Help is already on the way.',
                    'sos_success' => true // F15 - Trigger success modal
                ]);
        }

        $event = EmergencySosEvent::create([
            'user_id' => $user->id,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'accuracy_m' => $validated['accuracy_m'] ?? null,
            'address' => $address,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Notify admins + active linked caregivers (mail default is "log" if not configured).
        try {
            $event->loadMissing(['user.profile']);

            $adminEmails = User::query()
                ->where('role', User::ROLE_ADMIN)
                ->whereNotNull('email')
                ->pluck('email')
                ->filter()
                ->unique()
                ->values()
                ->all();

            $caregiverEmails = $user->caregivers()
                ->wherePivot('status', 'active')
                ->whereNotNull('users.email')
                ->pluck('users.email')
                ->filter()
                ->unique()
                ->values()
                ->all();

            $recipients = array_values(array_unique(array_merge($adminEmails, $caregiverEmails)));
            if (! empty($recipients)) {
                Mail::to($recipients)->send(new EmergencySosAlertMail($event));
            }
        } catch (\Throwable $e) {
            Log::warning('Failed sending SOS notification email.', [
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()
            ->route('profile.show')
            ->with([
                'success' => 'SOS alert sent. Help has been notified.',
                'sos_success' => true // F15 - Trigger success modal
            ]);
    }

    public function resolve(EmergencySosEvent $event): RedirectResponse
    {
        $user = Auth::user();

        if (! $user || ! $user->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        if ($event->resolved_at === null) {
            $event->forceFill([
                'resolved_at' => now(),
                'resolved_by' => $user->id,
            ])->save();
        }

        return redirect()->back()->with('success', 'SOS alert marked as resolved.');
    }
}


