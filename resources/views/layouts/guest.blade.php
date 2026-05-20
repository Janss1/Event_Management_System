{{-- resources/views/layouts/guest.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventMS – @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { background: #f0f4ff; }
        .auth-left {
            background: linear-gradient(135deg, #1e2a3a 0%, #2d3f6b 100%);
            min-height: 100vh;
        }
        .auth-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.12);
        }
        .btn-primary {
            background: #1e2a3a;
            color: #fff;
            padding: 9px 22px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: background .2s;
        }
        .btn-primary:hover { background: #2d3f55; }
        .btn-outline {
            background: transparent;
            color: #1e2a3a;
            padding: 9px 22px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            border: 2px solid #1e2a3a;
            cursor: pointer;
            transition: all .2s;
            text-decoration: none;
        }
        .btn-outline:hover { background: #1e2a3a; color: #fff; }
        .form-input {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 9px 12px;
            font-size: 0.875rem;
            outline: none;
            transition: border-color .2s;
            box-sizing: border-box;
        }
        .form-input:focus { border-color: #3b5bdb; box-shadow: 0 0 0 3px rgba(59,91,219,0.1); }
        .form-label { font-size: 0.78rem; font-weight: 600; color: #374151; margin-bottom: 4px; display: block; text-transform: uppercase; letter-spacing: .04em; }
    </style>
</head>
<body>
<div class="flex min-h-screen">
    {{-- Left panel --}}
    <div class="auth-left w-2/5 hidden lg:flex flex-col items-center justify-center px-12 text-white">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-40 h-40 object-contain mb-4">
        <div class="text-4xl font-bold mb-4 leading-tight">A-Zure</div>
        <div class="text-2xl font-bold mb-4 leading-tight">Event Management System</div>
        <div class="text-blue-300 text-sm leading-relaxed mt-2 max-w-xs">
            Organize, manage, and track your events efficiently in one centralized platform.
        </div>
        <div class="mt-12 space-y-3 w-full max-w-xs">
            <div class="h-2 bg-white/10 rounded-full w-full"></div>
            <div class="h-2 bg-white/10 rounded-full w-4/5"></div>
            <div class="h-2 bg-white/10 rounded-full w-3/5"></div>
        </div>
    </div>

    {{-- Right panel --}}
    <div class="flex-1 flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-md">
            @yield('content')
        </div>
    </div>
</div>
</body>
</html>
