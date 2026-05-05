{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.guest')
@section('title', 'Login')

@section('content')
<div class="auth-card p-8">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">LOG IN</h2>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm mb-4">
            ✅ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 text-sm mb-4">
            ❌ {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email') }}"
                class="form-input @error('email') border-red-400 @enderror"
                placeholder="your@email.com" required autofocus>
            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="form-label">Password</label>
            <input type="password" name="password"
                class="form-input @error('password') border-red-400 @enderror"
                placeholder="••••••••" required>
            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-between pt-2">
            <a href="{{ route('register') }}" class="btn-outline">Register</a>
            <button type="submit" class="btn-primary">Log In</button>
        </div>
    </form>
</div>
@endsection
