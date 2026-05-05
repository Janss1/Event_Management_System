{{-- resources/views/events/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Create New Event')

@section('content')

{{-- Breadcrumb + Actions --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <a href="{{ route('events.index') }}" class="text-sm text-gray-400 hover:text-gray-600">← Create New Event</a>
        <p class="text-xs text-gray-400 mt-0.5">Draft - Not Saved</p>
    </div>
    <div class="flex gap-2">
        <button type="submit" form="event-form" name="action" value="draft"
            class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
            Save Draft
        </button>
        <button type="submit" form="event-form" name="action" value="publish"
            class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition">
            Publish Event
        </button>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 max-w-3xl">
    <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-5">General Information</h2>

    <form id="event-form" method="POST" action="{{ route('events.store') }}" class="space-y-5">
        @csrf

        {{-- Event Title --}}
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Event Title</label>
            <input type="text" name="event_title" value="{{ old('event_title') }}"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                placeholder="Enter event title..." required>
            @error('event_title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Category + Others --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Category</label>
                <select name="category" id="category"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    onchange="document.getElementById('others-field').style.display = this.value === 'Others' ? 'block' : 'none'">
                    @foreach(['Symposium','Seminar','Workshop','Conference','Celebration','Others'] as $cat)
                        <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div id="others-field" style="{{ old('category') === 'Others' ? '' : 'display:none' }}">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Others (specify)</label>
                <input type="text" name="category_other" value="{{ old('category_other') }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    placeholder="Specify category...">
            </div>
        </div>

        {{-- Description --}}
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Description</label>
            <textarea name="description" rows="4"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                placeholder="Describe the purpose, highlight, and unique selling point of the event...">{{ old('description') }}</textarea>
        </div>

        {{-- Venue --}}
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Venue</label>
            <input type="text" name="venue" value="{{ old('venue') }}"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                placeholder="e.g. PS Building, Room 303">
        </div>

        {{-- Date & Time --}}
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Date & Time</label>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-gray-400 mb-1">Start Date & Time</label>
                    <input type="datetime-local" name="date_start" value="{{ old('date_start') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                        required>
                    @error('date_start') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-1">End Date & Time</label>
                    <input type="datetime-local" name="date_end" value="{{ old('date_end') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                        required>
                    @error('date_end') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Announcement --}}
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Announcement / Notes</label>
            <textarea name="announcement" rows="2"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                placeholder="Optional announcement for participants...">{{ old('announcement') }}</textarea>
        </div>

    </form>
</div>

@endsection
