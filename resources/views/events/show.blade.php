{{-- resources/views/events/show.blade.php --}}
@extends('layouts.app')
@section('title', $event->event_title)

@section('content')

{{-- Header --}}
<div class="flex flex-wrap items-start justify-between gap-3 mb-6">
    <div>
        <a href="{{ route('events.index') }}" class="text-sm text-gray-400 hover:text-gray-600">← Back to Events</a>
        <div class="flex items-center gap-3 mt-1">
            <h2 class="text-xl font-bold text-gray-800">{{ $event->event_title }}</h2>
            <span class="badge badge-{{ $event->event_status }}">{{ ucfirst($event->event_status) }}</span>
            @if($event->publish_status === 'draft')
                <span class="badge badge-draft">Draft</span>
            @endif
        </div>
        <div class="text-sm text-gray-400 mt-1 flex flex-wrap gap-4">
            <span> {{ $event->date_start->format('M d, Y h:i A') }} – {{ $event->date_end->format('M d, Y h:i A') }}</span>
            @if($event->venue) <span> {{ $event->venue }}</span> @endif
            <span> {{ $event->category_label }}</span>
        </div>
    </div>
    @if(Auth::user()->isAdmin())
    <div class="flex gap-2">
        <a href="{{ route('events.edit', $event) }}"
           class="px-4 py-2 text-sm font-medium border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
             Edit
        </a>
        <form method="POST" action="{{ route('events.destroy', $event) }}"
              onsubmit="return confirm('Delete this event?')">
            @csrf @method('DELETE')
            <button class="px-4 py-2 text-sm font-medium border border-red-200 text-red-500 rounded-lg hover:bg-red-50 transition">
                 Delete
            </button>
        </form>
    </div>
    @endif
</div>

@if($event->announcement)
    <div class="bg-blue-50 border border-blue-100 rounded-xl px-5 py-4 mb-6 text-sm text-blue-800">
         <strong>Announcement:</strong> {{ $event->announcement }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left: Description + Check-in tools + Add Attendee --}}
    <div class="space-y-5">

        @if($event->description)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Description</h3>
            <p class="text-sm text-gray-600 leading-relaxed">{{ $event->description }}</p>
        </div>
        @endif

        {{-- Check-in by code --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-3"> Check-in by Ticket Code</h3>
            <form method="POST" action="{{ route('attendees.checkinByCode', $event) }}" class="flex gap-2">
                @csrf
                <input type="text" name="ticket_code" maxlength="10"
                    class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm uppercase focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    placeholder="Ticket code..." required>
                <button type="submit"
                    class="px-3 py-2 text-sm font-medium bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    Go
                </button>
            </form>
        </div>

        {{-- Register Attendee --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-3"> Register Attendee</h3>
            <form method="POST" action="{{ route('attendees.store', $event) }}" class="space-y-3">
                @csrf
                <div>
                    <input type="text" name="first_name"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                        placeholder="First Name *" required maxlength="50">
                    @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <input type="text" name="middle_name"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                        placeholder="Middle Name (optional)" maxlength="50">
                </div>
                <div>
                    <input type="text" name="last_name"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                        placeholder="Last Name *" required maxlength="50">
                    @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <input type="text" name="contact_number"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    placeholder="Contact Number">
                <input type="email" name="email"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    placeholder="Email (optional)">
                <button type="submit"
                    class="w-full py-2 text-sm font-medium bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                    Register
                </button>
            </form>
        </div>

    </div>

    {{-- Right: Attendees Table --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-700">Attendees ({{ $attendees->count() }})</h3>
                <span class="text-sm text-green-600 font-medium">
                    {{ $attendees->where('checked_in', true)->count() }} / {{ $attendees->count() }} checked in
                </span>
            </div>

            @if($attendees->isEmpty())
                <div class="py-12 text-center text-gray-400 text-sm">
                    No attendees registered yet.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wide">
                            <tr>
                                <th class="px-4 py-3 text-left">Name</th>
                                <th class="px-4 py-3 text-left">Contact</th>
                                <th class="px-4 py-3 text-left">Ticket Code</th>
                                <th class="px-4 py-3 text-left">Status</th>
                                <th class="px-4 py-3 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($attendees as $attendee)
                            <tr class="hover:bg-gray-50 {{ $attendee->checked_in ? 'bg-green-50' : '' }}">
                                <td class="px-4 py-3 font-medium text-gray-800">{{ $attendee->attendee_name }}</td>
                                <td class="px-4 py-3 text-gray-500 text-xs">{{ $attendee->contact_number ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    <code class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded text-xs font-mono">
                                        {{ $attendee->ticket_code }}
                                    </code>
                                </td>
                                <td class="px-4 py-3">
                                    @if($attendee->checked_in)
                                        <span class="badge badge-active"> Checked In</span>
                                    @else
                                        <span class="badge badge-draft">Pending</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 flex items-center gap-3">
                                    <form method="POST" action="{{ route('attendees.checkin', [$event, $attendee]) }}">
                                        @csrf
                                        <button class="text-xs {{ $attendee->checked_in ? 'text-yellow-600' : 'text-indigo-600' }} hover:underline">
                                            {{ $attendee->checked_in ? 'Undo' : 'Check In' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('attendees.destroy', [$event, $attendee]) }}"
                                          onsubmit="return confirm('Remove attendee?')">
                                        @csrf @method('DELETE')
                                        <button class="text-xs text-red-400 hover:underline">Remove</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection