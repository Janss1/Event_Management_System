<?php
// app/Http/Controllers/AuthController.php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Show login page
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Invalid email or password.');
        }

        if ($user->status === 'pending') {
            return back()->with('error', 'Your account is pending admin approval.');
        }

        if ($user->status === 'rejected') {
            return back()->with('error', 'Your account has been rejected. Contact the administrator.');
        }

        Auth::login($user, $request->boolean('remember'));

        return redirect()->route('dashboard');
    }

    // Show register page
    public function showRegister()
    {
        return view('auth.register');
    }

    // Handle registration
    public function register(Request $request)
    {
        $request->validate([
            'first_name'     => 'required|string|max:50',
            'middle_name'    => 'nullable|string|max:50',
            'last_name'      => 'required|string|max:50',
            'contact_number' => 'required|string|max:20',
            'email'          => 'required|email|unique:users,email',
            'employee_id'    => 'required|string|unique:users,employee_id|max:50',
            'position'       => 'required|string|max:100',
            'password'       => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'first_name'     => $request->first_name,
            'middle_name'    => $request->middle_name,
            'last_name'      => $request->last_name,
            'contact_number' => $request->contact_number,
            'email'          => $request->email,
            'employee_id'    => $request->employee_id,
            'position'       => $request->position,
            'password'       => Hash::make($request->password),
            'role'           => 'organizer',
            'status'         => 'pending',
        ]);

        return redirect()->route('login')
            ->with('success', 'Account created successfully! Please wait for admin approval before logging in.');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
