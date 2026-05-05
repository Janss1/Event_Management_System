<?php
// app/Http/Controllers/EventController.php
namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Attendee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    // Event list with status filter + search
    public function index(Request $request)
    {
        $user   = Auth::user();
        $filter = $request->get('filter', 'all');
        $search = $request->get('search', '');
        $now    = now();

        // Organizers see all published events; admins see everything
        $base = Event::when(
            !$user->isAdmin(),
            fn($q) => $q->where('publish_status', 'published')
        );

        $query = (clone $base)->withCount('attendees');

        match ($filter) {
            'draft'     => $query->where('publish_status', 'draft'),
            'active'    => $query->where('publish_status', 'published')
                                 ->where('date_start', '<=', $now)
                                 ->where('date_end', '>=', $now),
            'completed' => $query->where('publish_status', 'published')
                                 ->where('date_end', '<', $now),
            'upcoming'  => $query->where('publish_status', 'published')
                                 ->where('date_start', '>', $now),
            default     => null,
        };

        if ($search) {
            $query->where('event_title', 'like', "%{$search}%");
        }

        $events = $query->orderBy('date_start')->get();

        $counts = [
            'all'       => (clone $base)->count(),
            'active'    => (clone $base)->where('publish_status', 'published')->where('date_start', '<=', $now)->where('date_end', '>=', $now)->count(),
            'draft'     => $user->isAdmin() ? Event::where('publish_status', 'draft')->count() : 0,
            'completed' => (clone $base)->where('publish_status', 'published')->where('date_end', '<', $now)->count(),
        ];

        return view('events.index', compact('events', 'filter', 'search', 'counts'));
    }

    public function create()
    {
        $this->adminOnly();
        return view('events.create');
    }

    public function store(Request $request)
    {
        $this->adminOnly();
        $validated = $this->validateEvent($request);
        $validated['user_id']        = Auth::id();
        $validated['publish_status'] = $request->action === 'publish' ? 'published' : 'draft';

        Event::create($validated);

        $msg = $validated['publish_status'] === 'published' ? 'Event published!' : 'Event saved as draft.';
        return redirect()->route('events.index')->with('success', $msg);
    }

    public function show(Event $event)
    {
        $this->gateView($event);
        $attendees = $event->attendees()->orderByDesc('created_at')->get();
        return view('events.show', compact('event', 'attendees'));
    }

    public function edit(Event $event)
    {
        $this->adminOnly();
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $this->adminOnly();
        $validated = $this->validateEvent($request);
        $validated['publish_status'] = $request->action === 'publish' ? 'published' : 'draft';
        $event->update($validated);

        $msg = $validated['publish_status'] === 'published' ? 'Event published!' : 'Draft saved.';
        return redirect()->route('events.show', $event)->with('success', $msg);
    }

    public function destroy(Event $event)
    {
        $this->adminOnly();
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted.');
    }

    // Attendees
    public function storeAttendee(Request $request, Event $event)
    {
        $this->gateView($event);
        $request->validate([
            'first_name'     => 'required|string|max:50',
            'middle_name'    => 'nullable|string|max:50',
            'last_name'      => 'required|string|max:50',
            'contact_number' => 'nullable|string|max:20',
            'email'          => 'nullable|email',
        ]);

        Attendee::create([
            'event_id'       => $event->id,
            'first_name'     => $request->first_name,
            'middle_name'    => $request->middle_name,
            'last_name'      => $request->last_name,
            'contact_number' => $request->contact_number,
            'email'          => $request->email,
        ]);

        return redirect()->route('events.show', $event)->with('success', 'Attendee registered.');
    }

    public function checkIn(Event $event, Attendee $attendee)
    {
        $this->gateView($event);
        if ($attendee->checked_in) {
            $attendee->update(['checked_in' => false, 'checked_in_at' => null]);
        } else {
            $attendee->update(['checked_in' => true, 'checked_in_at' => now()]);
        }
        return back()->with('success', 'Check-in status updated.');
    }

    public function checkInByCode(Request $request, Event $event)
    {
        $this->gateView($event);
        $request->validate(['ticket_code' => 'required|string']);

        $attendee = Attendee::where('event_id', $event->id)
            ->where('ticket_code', strtoupper($request->ticket_code))
            ->first();

        if (!$attendee) {
            return back()->with('error', 'Ticket code not found.');
        }

        $attendee->update(['checked_in' => true, 'checked_in_at' => now()]);
        return back()->with('success', "{$attendee->full_name} checked in!");
    }

    public function removeAttendee(Event $event, Attendee $attendee)
    {
        $this->gateView($event);
        $attendee->delete();
        return back()->with('success', 'Attendee removed.');
    }

    // -------

    private function validateEvent(Request $request): array
    {
        return $request->validate([
            'event_title'    => 'required|string|max:99',
            'category'       => 'required|in:Symposium,Seminar,Workshop,Conference,Celebration,Others',
            'category_other' => 'nullable|string|max:100',
            'description'    => 'nullable|string',
            'venue'          => 'nullable|string|max:255',
            'date_start'     => 'required|date',
            'date_end'       => 'required|date|after_or_equal:date_start',
            'announcement'   => 'nullable|string',
        ]);
    }

    private function adminOnly(): void
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Only administrators can perform this action.');
        }
    }

    private function gateView(Event $event): void
    {
        $user = Auth::user();
        if (!$user->isAdmin() && $event->publish_status !== 'published') {
            abort(403);
        }
    }
}