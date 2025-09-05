<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('start_at', 'desc')->paginate(15);
        return view('events.index', compact('events'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'start_at' => 'required|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'type' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
            'is_published' => 'boolean',
        ]);

        $validated['is_published'] = $request->has('is_published');

        Event::create($validated);

        return redirect()->route('events.index')->with('success', __('app.event_created_successfully'));
    }

    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'start_at' => 'required|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'type' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
            'is_published' => 'boolean',
        ]);

        $validated['is_published'] = $request->has('is_published');

        $event->update($validated);

        return redirect()->route('events.index')->with('success', __('app.event_updated_successfully'));
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('events.index')->with('success', __('app.event_deleted_successfully'));
    }

    public function togglePublish(Event $event)
    {
        $event->update(['is_published' => !$event->is_published]);
        return redirect()->route('events.index')->with('success', __('app.event_status_updated'));
    }

    // API for upcoming events
    public function upcoming()
    {
        $events = Event::published()
            ->upcoming()
            ->orderBy('start_at')
            ->limit(6)
            ->get()
            ->map(function ($e) {
                return [
                    'id' => $e->id,
                    'title' => $e->title,
                    'date' => $e->start_at->format('d M Y'),
                    'location' => $e->location,
                    'type' => $e->type,
                    'color' => $e->color ?: 'green',
                ];
            });

        return response()->json($events);
    }
}


