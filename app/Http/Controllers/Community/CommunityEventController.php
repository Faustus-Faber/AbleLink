<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;
use App\Models\Community\CommunityEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//F16 - Evan Yuvraj Munshi
class CommunityEventController extends Controller
{
    public function index(Request $request)
    {
        $query = CommunityEvent::with('organizer')->orderBy('event_date', 'asc');

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $events = $query->paginate(10);
        return view('community.events.index', compact('events'));
    }

    public function create()
    {
        return view('community.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'location' => 'nullable|string',
            'type' => 'required|in:online,offline',
            'meeting_link' => 'nullable|url|required_if:type,online',
        ]);

        $event = Auth::user()->communityEvents()->create($validated);

        return redirect()->route('community.events.index')->with('success', 'Event created successfully.');
    }

    public function show(CommunityEvent $event)
    {
        $event->load('organizer', 'participants');
        return view('community.events.show', compact('event'));
    }

    public function join(CommunityEvent $event)
    {
        if (!$event->participants()->where('user_id', Auth::id())->exists()) {
            $event->participants()->attach(Auth::id(), ['status' => 'attending']);
            return back()->with('success', 'You have joined the event.');
        }

        return back()->with('info', 'You are already attending this event.');
    }

    public function leave(CommunityEvent $event)
    {
        $event->participants()->detach(Auth::id());
        return back()->with('success', 'You have left the event.');
    }
}

