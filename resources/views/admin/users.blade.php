{{-- resources/views/admin/users.blade.php --}}
@extends('layouts.app')
@section('title', 'Manage Users')

@section('content')

{{-- Pending Approvals --}}
<div class="mb-8">
    <h2 class="text-base font-bold text-gray-700 mb-3 flex items-center gap-2">
         Pending Approvals
        @if($pending->count())
            <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-0.5 rounded-full">{{ $pending->count() }}</span>
        @endif
    </h2>

    @if($pending->isEmpty())
        <div class="bg-white rounded-xl border border-dashed border-gray-200 py-8 text-center text-gray-400 text-sm">
            No pending approvals.
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wide">
                    <tr>
                        <th class="px-5 py-3 text-left">Name</th>
                        <th class="px-5 py-3 text-left">Employee ID</th>
                        <th class="px-5 py-3 text-left">Position</th>
                        <th class="px-5 py-3 text-left">Email</th>
                        <th class="px-5 py-3 text-left">Registered</th>
                        <th class="px-5 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($pending as $user)
                    <tr class="hover:bg-yellow-50 transition">
                        <td class="px-5 py-4 font-medium text-gray-800">{{ $user->full_name }}</td>
                        <td class="px-5 py-4 text-gray-500">{{ $user->employee_id }}</td>
                        <td class="px-5 py-4 text-gray-500">{{ $user->position }}</td>
                        <td class="px-5 py-4 text-gray-500">{{ $user->email }}</td>
                        <td class="px-5 py-4 text-gray-400 text-xs">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="px-5 py-4 flex gap-2">
                            <form method="POST" action="{{ route('admin.users.approve', $user) }}">
                                @csrf
                                <button class="px-3 py-1.5 text-xs font-semibold bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                     Approve
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.users.reject', $user) }}">
                                @csrf
                                <button class="px-3 py-1.5 text-xs font-semibold bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                                     Reject
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- Approved Organizers --}}
<div class="mb-8">
    <h2 class="text-base font-bold text-gray-700 mb-3"> Approved Organizers</h2>
    @if($approved->isEmpty())
        <div class="bg-white rounded-xl border border-dashed border-gray-200 py-8 text-center text-gray-400 text-sm">
            No approved organizers yet.
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wide">
                    <tr>
                        <th class="px-5 py-3 text-left">Name</th>
                        <th class="px-5 py-3 text-left">Employee ID</th>
                        <th class="px-5 py-3 text-left">Position</th>
                        <th class="px-5 py-3 text-left">Email</th>
                        <th class="px-5 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($approved as $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-4 font-medium text-gray-800">{{ $user->full_name }}</td>
                        <td class="px-5 py-4 text-gray-500">{{ $user->employee_id }}</td>
                        <td class="px-5 py-4 text-gray-500">{{ $user->position }}</td>
                        <td class="px-5 py-4 text-gray-500">{{ $user->email }}</td>
                        <td class="px-5 py-4 flex gap-2">
                            <form method="POST" action="{{ route('admin.users.reject', $user) }}">
                                @csrf
                                <button class="px-3 py-1.5 text-xs font-semibold border border-red-300 text-red-500 rounded-lg hover:bg-red-50 transition">
                                    Revoke
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                  onsubmit="return confirm('Delete {{ $user->full_name }}?')">
                                @csrf @method('DELETE')
                                <button class="px-3 py-1.5 text-xs font-semibold border border-gray-300 text-gray-500 rounded-lg hover:bg-gray-100 transition">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- Rejected --}}
@if($rejected->count())
<div>
    <h2 class="text-base font-bold text-gray-700 mb-3"> Rejected</h2>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3 text-left">Name</th>
                    <th class="px-5 py-3 text-left">Email</th>
                    <th class="px-5 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($rejected as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-4 font-medium text-gray-600">{{ $user->full_name }}</td>
                    <td class="px-5 py-4 text-gray-400">{{ $user->email }}</td>
                    <td class="px-5 py-4 flex gap-2">
                        <form method="POST" action="{{ route('admin.users.approve', $user) }}">
                            @csrf
                            <button class="px-3 py-1.5 text-xs font-semibold border border-green-300 text-green-600 rounded-lg hover:bg-green-50 transition">
                                Re-approve
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                              onsubmit="return confirm('Delete user?')">
                            @csrf @method('DELETE')
                            <button class="px-3 py-1.5 text-xs font-semibold border border-gray-300 text-gray-500 rounded-lg hover:bg-gray-100 transition">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection
