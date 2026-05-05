<?php
// app/Http/Middleware/EnsureUserIsApproved.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsApproved
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->status === 'pending') {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Your account is pending admin approval. Please wait.');
        }

        if ($user->status === 'rejected') {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Your account has been rejected. Please contact the administrator.');
        }

        return $next($request);
    }
}
