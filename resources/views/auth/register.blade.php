{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.guest')
@section('title', 'Register')

@section('content')
<div class="auth-card p-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-1">Register Account</h2>
    <p class="text-sm text-gray-500 mb-6">Fill in your details to create an organizer account.</p>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm mb-4">
            Please fix the errors below.
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        {{-- Row 1: First Name | Contact Number --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="form-label">First Name *</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}"
                    class="form-input @error('first_name') border-red-400 @enderror"
                    placeholder="Juan" required>
                @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="form-label">Contact Number *</label>
                <input type="text" name="contact_number" value="{{ old('contact_number') }}"
                    class="form-input @error('contact_number') border-red-400 @enderror"
                    placeholder="09XXXXXXXXX" required>
                @error('contact_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Row 2: Middle Name | Email --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="form-label">Middle Name</label>
                <input type="text" name="middle_name" value="{{ old('middle_name') }}"
                    class="form-input" placeholder="(Optional)">
            </div>
            <div>
                <label class="form-label">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="form-input @error('email') border-red-400 @enderror"
                    placeholder="juan@email.com" required>
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Row 3: Last Name | Position --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="form-label">Last Name *</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}"
                    class="form-input @error('last_name') border-red-400 @enderror"
                    placeholder="Dela Cruz" required>
                @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="form-label">Position *</label>
                <input type="text" name="position" value="{{ old('position') }}"
                    class="form-input @error('position') border-red-400 @enderror"
                    placeholder="e.g. Event Coordinator" required>
                @error('position') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Row 4: Employee ID | Password --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="form-label">Employee ID *</label>
                <input type="text" name="employee_id" value="{{ old('employee_id') }}"
                    class="form-input @error('employee_id') border-red-400 @enderror"
                    placeholder="EMP-0001" required>
                @error('employee_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="form-label">Password *</label>
                <input type="password" name="password"
                    class="form-input @error('password') border-red-400 @enderror"
                    placeholder="Min. 8 characters" required>
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Confirm Password --}}
        <div>
            <label class="form-label">Confirm Password *</label>
            <input type="password" name="password_confirmation"
                class="form-input" placeholder="Re-enter password" required>
        </div>

        <div class="flex items-center justify-between pt-2">
            <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:underline">← Back to Login</a>
            <button type="submit" class="btn-primary">Register</button>
        </div>
    </form>
</div>
@endsection
