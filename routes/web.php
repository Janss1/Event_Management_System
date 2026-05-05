<?php
// routes/web.php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});;

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Authenticated + approved routes
Route::middleware(['auth', 'approved'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Events
    Route::get('/events',                                    [EventController::class, 'index'])->name('events.index');
    Route::get('/events/create',                             [EventController::class, 'create'])->name('events.create');
    Route::post('/events',                                   [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}',                            [EventController::class, 'show'])->name('events.show');
    Route::get('/events/{event}/edit',                       [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}',                            [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}',                         [EventController::class, 'destroy'])->name('events.destroy');

    // Attendees (nested)
    Route::post('/events/{event}/attendees',                 [EventController::class, 'storeAttendee'])->name('attendees.store');
    Route::post('/events/{event}/attendees/{attendee}/checkin', [EventController::class, 'checkIn'])->name('attendees.checkin');
    Route::post('/events/{event}/checkin-by-code',           [EventController::class, 'checkInByCode'])->name('attendees.checkinByCode');
    Route::delete('/events/{event}/attendees/{attendee}',    [EventController::class, 'removeAttendee'])->name('attendees.destroy');

    // Admin only
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users',              [AdminUserController::class, 'index'])->name('users.index');
        Route::post('/users/{user}/approve', [AdminUserController::class, 'approve'])->name('users.approve');
        Route::post('/users/{user}/reject',  [AdminUserController::class, 'reject'])->name('users.reject');
        Route::delete('/users/{user}',    [AdminUserController::class, 'destroy'])->name('users.destroy');
    });
});
