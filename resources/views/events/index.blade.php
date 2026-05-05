{{-- resources/views/events/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Event List')

@section('content')

<div class="flex items-center justify-between mb-5">
    <p class="text-sm text-gray-500">There are <strong>{{ $counts['all'] }}</strong> total events.</p>
    @if(Auth::user()->isAdmin())
        <a href="{{ route('events.create') }}"
           class="inline-flex items-center gap-1 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
            + Create New Event
        </a>
    @endif
</div>

{{-- Filters + Search --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-5">
    <div class="flex flex-wrap gap-2 items-center">
        <span class="text-xs text-gray-500 font-semibold uppercase tracking-wide mr-1">Status Filter</span>
        <a href="{{ route('events.index', ['filter' => 'all', 'search' => $search]) }}"
           class="filter-tab {{ $filter === 'all' ? 'active' : '' }}">
            All <span class="ml-1 opacity-70">({{ $counts['all'] }})</span>
        </a>
        <a href="{{ route('events.index', ['filter' => 'active', 'search' => $search]) }}"
           class="filter-tab {{ $filter === 'active' ? 'active' : '' }}">
            Active <span class="ml-1 opacity-70">({{ $counts['active'] }})</span>
        </a>
        <a href="{{ route('events.index', ['filter' => 'draft', 'search' => $search]) }}"
           class="filter-tab {{ $filter === 'draft' ? 'active' : '' }}">
            Drafted <span class="ml-1 opacity-70">({{ $counts['draft'] }})</span>
        </a>
        <a href="{{ route('events.index', ['filter' => 'completed', 'search' => $search]) }}"
           class="filter-tab {{ $filter === 'completed' ? 'active' : '' }}">
            Completed <span class="ml-1 opacity-70">({{ $counts['completed'] }})</span>
        </a>
    </div>

    <form method="GET" action="{{ route('events.index') }}">
        <input type="hidden" name="filter" value="{{ $filter }}">
        <input type="text" name="search" value="{{ $search }}"
            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 w-56"
            placeholder=" Search events...">
    </form>
</div>

{{-- Events Table --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    @if($events->isEmpty())
        <div class="py-16 text-center text-gray-400">
            <div class="text-4xl mb-3"></div>
            <p>No events found.</p>
            <a href="{{ route('events.create') }}" class="text-indigo-500 text-sm hover:underline mt-1 inline-block">Create your first event →</a>
        </div>
    @else
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3 text-left">Event Details</th>
                    <th class="px-5 py-3 text-left">Status</th>
                    <th class="px-5 py-3 text-left">Date / Timeline</th>
                    <th class="px-5 py-3 text-left">Venue</th>
                    <th class="px-5 py-3 text-left">Attendees</th>
                    <th class="px-5 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($events as $event)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-4">
                        <a href="{{ route('events.show', $event) }}" class="font-semibold text-gray-800 hover:text-indigo-600">
                            {{ $event->event_title }}
                        </a>
                        <div class="text-xs text-gray-400 mt-0.5">{{ $event->category_label }}</div>
                    </td>
                    <td class="px-5 py-4">
                        <span class="badge badge-{{ $event->event_status }}">
                            {{ ucfirst($event->event_status) }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-gray-500">
                        <div>{{ $event->date_start->format('M d, Y') }}</div>
                        <div class="text-xs text-gray-400">
                            {{ $event->date_start->format('h:i A') }} – {{ $event->date_end->format('h:i A') }}
                        </div>
                    </td>
                    <td class="px-5 py-4 text-gray-500">{{ $event->venue ?? '—' }}</td>
                    <td class="px-5 py-4 text-gray-500">
                        {{ $event->attendees_count }}
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('events.show', $event) }}" class="text-indigo-500 hover:underline text-xs">View</a>
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('events.edit', $event) }}" class="text-yellow-500 hover:underline text-xs">Edit</a>
                                <form method="POST" action="{{ route('events.destroy', $event) }}"
                                      onsubmit="return confirm('Delete {{ $event->event_title }}?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-400 hover:underline text-xs">Delete</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@endsection