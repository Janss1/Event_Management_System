<?php
// app/Http/Controllers/Admin/UserController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $pending   = User::where('status', 'pending')->orderBy('created_at')->get();
        $approved  = User::where('status', 'approved')->where('role', 'organizer')->orderBy('last_name')->get();
        $rejected  = User::where('status', 'rejected')->orderBy('last_name')->get();

        return view('admin.users', compact('pending', 'approved', 'rejected'));
    }

    public function approve(User $user)
    {
        $user->update(['status' => 'approved']);
        return back()->with('success', "{$user->full_name} has been approved.");
    }

    public function reject(User $user)
    {
        $user->update(['status' => 'rejected']);
        return back()->with('success', "{$user->full_name} has been rejected.");
    }

    public function destroy(User $user)
    {
        if ($user->isAdmin()) abort(403);
        $user->delete();
        return back()->with('success', 'User deleted.');
    }
}
