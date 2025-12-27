{{-- F15 - Akida Lisi --}}
<x-mail::message>
# Emergency SOS Alert

**{{ $event->user?->name ?? 'Unknown user' }}** triggered an SOS.

<x-mail::panel>
**Time:** {{ optional($event->created_at)->format('M j, Y g:i A') }}

**Email:** {{ $event->user?->email ?? 'N/A' }}
@if($event->user && $event->user->profile && $event->user->profile->phone_number)
**Phone:** {{ $event->user->profile->phone_number }}
@endif

@if($event->latitude !== null && $event->longitude !== null)
**Location:** {{ $event->latitude }}, {{ $event->longitude }}
@if($event->accuracy_m)
(±{{ $event->accuracy_m }}m)
@endif

**Map:** https://www.google.com/maps?q={{ $event->latitude }},{{ $event->longitude }}
@elseif($event->address)
**Address:** {{ $event->address }}
@else
**Location:** Not provided (permission denied/unavailable).
@endif

@if($event->notes)
**Notes:** {{ $event->notes }}
@endif
</x-mail::panel>

You can review active SOS alerts in the dashboard.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
