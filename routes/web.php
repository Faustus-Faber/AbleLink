<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

// ✅ Show Login Page
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

// ✅ Simple Demo Login (NO OTP)
Route::post('/login', [AuthController::class, 'simpleLogin'])->name('login.post');

// ✅ Logout
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

/*
|--------------------------------------------------------------------------
| MAIN DASHBOARD (ROLE BASED)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| DEFAULT REDIRECT
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});

/*
|--------------------------------------------------------------------------
| ✅ DEMO ROUTES (NO MODEL, NO DATABASE, NO AUTH)
|--------------------------------------------------------------------------
*/

// ✅ Demo User
Route::get('/demo/user', function () {
    $user = new stdClass();
    $user->name = "Demo User";
    $user->role = "user";
    $user->disability_type = "physical";
    return view('dashboards.user', compact('user'));
});

// ✅ Demo Caregiver
Route::get('/demo/caregiver', function () {
    $user = new stdClass();
    $user->name = "Demo Caregiver";
    $user->role = "caregiver";
    return view('dashboards.caregiver', compact('user'));
});

// ✅ Demo Volunteer
Route::get('/demo/volunteer', function () {
    $user = new stdClass();
    $user->name = "Demo Volunteer";
    $user->role = "volunteer";
    return view('dashboards.volunteer', compact('user'));
});

// ✅ Demo Employer
Route::get('/demo/employer', function () {
    $user = new stdClass();
    $user->name = "Demo Employer";
    $user->role = "employer";
    return view('dashboards.employer', compact('user'));
});

// ✅ Demo Admin (FIXES YOUR ERROR)
Route::get('/admin/dashboard', function () {
    $user = new stdClass();
    $user->name = "Demo Admin";
    $user->role = "admin";
    return view('dashboards.admin', compact('user'));
});

// ✅ Optional alias
Route::get('/demo/admin', function () {
    return redirect('/admin/dashboard');
});
