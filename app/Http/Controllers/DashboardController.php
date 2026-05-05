<?php
// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $now  = now();

        // Next 3 upcoming published events
        $nextEvents = Event::where('publish_status', 'published')
            ->where('date_start', '>=', $now)
            ->when(!$user->isAdmin(), fn($q) => $q->where('user_id', $user->id))
            ->orderBy('date_start')
            ->take(3)
            ->get();

        // Recent finished events (date_end in the past)
        $recentEvents = Event::where('publish_status', 'published')
            ->where('date_end', '<', $now)
            ->when(!$user->isAdmin(), fn($q) => $q->where('user_id', $user->id))
            ->orderByDesc('date_end')
            ->take(5)
            ->get();

        return view('dashboard.index', compact('nextEvents', 'recentEvents'));
    }
}
