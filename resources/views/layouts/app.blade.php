{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventMS – @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* CSS Variables */
        :root {
            --sidebar-bg: #1e2a3a;
            --sidebar-hover: #2d3f55;
            --sidebar-active: #3b5bdb;
            --accent: #3b5bdb;
            --accent-hover: #2f4ac7;
        }

        /* Sidebar */
        .sidebar { background: var(--sidebar-bg); min-height: 100vh; width: 220px; flex-shrink: 0; }
        .sidebar a { display: block; padding: 0.75rem 1.25rem; color: #a8b8cc; font-size: 0.875rem; text-decoration: none; transition: all .2s; border-radius: 6px; margin: 2px 8px; }
        .sidebar a:hover { background: var(--sidebar-hover); color: #fff; }
        .sidebar a.active { background: var(--sidebar-active); color: #fff; font-weight: 600; }
        .sidebar .nav-label { font-size: 0.7rem; text-transform: uppercase; letter-spacing: .08em; color: #5a7a99; padding: 1rem 1.25rem 0.25rem; }

        /* Badges */
        .badge { display: inline-flex; align-items: center; padding: 2px 10px; border-radius: 999px; font-size: 0.72rem; font-weight: 600; }
        .badge-active    { background: #d1fae5; color: #065f46; }
        .badge-upcoming  { background: #dbeafe; color: #1e40af; }
        .badge-completed { background: #f3f4f6; color: #374151; }
        .badge-draft     { background: #fef9c3; color: #854d0e; }
        .badge-pending   { background: #fef3c7; color: #92400e; }
        .badge-approved  { background: #d1fae5; color: #065f46; }
        .badge-rejected  { background: #fee2e2; color: #991b1b; }

        /* Filter tabs */
        .filter-tab { padding: 6px 16px; border-radius: 6px; font-size: 0.8rem; font-weight: 500; cursor: pointer; border: 1px solid #e5e7eb; background: #fff; color: #6b7280; text-decoration: none; }
        .filter-tab.active { background: var(--accent); color: #fff; border-color: var(--accent); }
        .filter-tab:hover:not(.active) { background: #f3f4f6; }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">

<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="sidebar flex flex-col py-6">
        {{-- Logo --}}
        <div class="px-5 mb-8">
            <div class="text-white font-bold text-lg tracking-tight">A-Zure</div>
            <div class="text-xs text-blue-300 mt-0.5">Event Management System</div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1">
            <div class="nav-label">Main</div>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                 Home
            </a>

            <div class="nav-label">Events</div>
            <a href="{{ route('events.index') }}" class="{{ request()->routeIs('events.index') ? 'active' : '' }}">
                 Events List
            </a>
            <a href="{{ route('events.create') }}" class="{{ request()->routeIs('events.create') ? 'active' : '' }}">
                 Create Event
            </a>

            @if(Auth::user()->isAdmin())
            <div class="nav-label">Admin</div>
            <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.*') ? 'active' : '' }}">
                 Manage Users
            </a>
            @endif
        </nav>

        {{-- User info at bottom --}}
        <div class="px-4 mt-auto pt-6 border-t border-slate-700">
            <div class="text-xs text-slate-400 mb-1">Logged in as</div>
            <div class="text-sm text-white font-medium truncate">{{ Auth::user()->full_name }}</div>
            <div class="text-xs text-slate-400">{{ Auth::user()->position }}</div>
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button class="text-xs text-red-400 hover:text-red-300 transition">← Logout</button>
            </form>
        </div>
    </aside>

    {{-- Main content --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Top bar --}}
        <header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between">
            <h1 class="text-xl font-bold text-gray-800">@yield('title', 'Dashboard')</h1>
            <div class="flex items-center gap-3">
                <div class="text-right">
                    <div class="text-sm font-medium text-gray-700">{{ Auth::user()->full_name }}</div>
                    <div class="text-xs text-gray-400">{{ ucfirst(Auth::user()->role) }}</div>
                </div>
                <div class="w-9 h-9 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-sm">
                    {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}
                </div>
            </div>
        </header>

        {{-- Flash messages --}}
        <div class="px-8 pt-4">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm mb-2">
                     {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 text-sm mb-2">
                     {{ session('error') }}
                </div>
            @endif
        </div>

        {{-- Page --}}
        <main class="flex-1 overflow-y-auto px-8 py-6">
            @yield('content')
        </main>

    </div>
</div>

</body>
</html>
