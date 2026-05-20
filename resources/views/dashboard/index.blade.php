{{-- resources/views/dashboard/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

<p class="text-sm text-gray-500 -mt-2 mb-6">Welcome back, here's what's happening with your events today.</p>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Next 3 Events --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-700">Next 3 Events</h2>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($nextEvents as $event)
                    <a href="{{ route('events.show', $event) }}"
                       class="flex items-start gap-4 px-6 py-4 hover:bg-gray-50 transition">
                        <div class="bg-indigo-100 text-indigo-700 rounded-lg px-3 py-2 text-center min-w-[56px]">
                            <div class="text-xs font-medium">{{ $event->date_start->format('M') }}</div>
                            <div class="text-xl font-bold leading-none">{{ $event->date_start->format('d') }}</div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-xs text-gray-400 mb-0.5">
                                {{ $event->date_start->format('l') }}
                                &nbsp;{{ $event->date_start->format('h:i A') }}
                            </div>
                            <div class="font-semibold text-gray-800 truncate">{{ $event->event_title }}</div>
                            <div class="text-xs text-gray-400 mt-0.5">{{ $event->venue ?? 'No venue set' }}</div>
                        </div>
                        <span class="badge badge-upcoming self-center">Upcoming</span>
                    </a>
                @empty
                    <div class="px-6 py-8 text-center text-gray-400 text-sm">
                        No upcoming events.
                        <a href="{{ route('events.create') }}" class="text-indigo-500 hover:underline ml-1">Create one →</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Events --}}
    <div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-700">Recent Events</h2>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($recentEvents as $event)
                    <a href="{{ route('events.show', $event) }}"
                       class="block px-5 py-3 hover:bg-gray-50 transition">
                        <div class="text-xs text-gray-400 mb-0.5">
                            {{ $event->date_start->format('F d') }}
                            &nbsp;{{ $event->date_start->format('h:i A') }}
                        </div>
                        <div class="text-sm font-semibold text-gray-800 truncate">{{ $event->event_title }}</div>
                        <div class="text-xs text-gray-400">{{ $event->venue ?? '—' }}</div>
                    </a>
                @empty
                    <div class="px-5 py-6 text-center text-gray-400 text-sm">No recent events.</div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
