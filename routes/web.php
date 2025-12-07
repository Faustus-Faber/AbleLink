<?php



use App\Http\Controllers\User\DashboardController; 
use Illuminate\Support\Facades\Route;


//F1 - Akida Lisi
Route::get('/register', [RegistrationController::class, 'create'])->name('register');
Route::post('/register', [RegistrationController::class, 'store'])->name('register.store');

Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login/send-otp', [LoginController::class, 'sendOtp'])->name('login.send-otp');

Route::get('/otp', [OtpController::class, 'show'])->name('otp.show');
Route::post('/otp/verify', [OtpController::class, 'verify'])->name('otp.verify');
Route::post('/otp/resend', [OtpController::class, 'resend'])->name('otp.resend');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    
    //F2 - Role Based Dashboards (Roza Akter)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //F3 - Evan Yuvraj Munshi
    Route::get('/profile', [App\Http\Controllers\Profile\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [App\Http\Controllers\Profile\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\Profile\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [App\Http\Controllers\Profile\ProfileController::class, 'uploadAvatar'])->name('profile.upload_avatar');

    Route::get('/accessibility/edit', [App\Http\Controllers\Profile\AccessibilityController::class, 'edit'])->name('accessibility.edit');
    Route::put('/accessibility', [App\Http\Controllers\Profile\AccessibilityController::class, 'update'])->name('accessibility.update');
    Route::post('/accessibility/apply', [App\Http\Controllers\Profile\AccessibilityController::class, 'apply'])->name('accessibility.apply');

    //F4 - Farhan Zarif
    Route::middleware(['verified', 'role:caregiver'])->prefix('caregiver')->name('caregiver.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Caregiver\CaregiverController::class, 'index'])->name('dashboard');
        Route::post('/request', [\App\Http\Controllers\Caregiver\CaregiverController::class, 'sendRequest'])->name('request');
        Route::post('/sos/{event}/resolve', [\App\Http\Controllers\Caregiver\CaregiverController::class, 'resolveSos'])->name('sos.resolve');
        Route::get('/patient/{user}/edit', [\App\Http\Controllers\Caregiver\CaregiverController::class, 'editPatient'])->name('patient.edit');
        Route::put('/patient/{user}', [\App\Http\Controllers\Caregiver\CaregiverController::class, 'updatePatient'])->name('patient.update');
        Route::delete('/patient/{user}', [\App\Http\Controllers\Caregiver\CaregiverController::class, 'unlink'])->name('patient.unlink');
    });

    Route::middleware(['verified'])->group(function () {
         Route::get('/requests', [\App\Http\Controllers\Caregiver\ConnectionController::class, 'index'])->name('requests.index');
         Route::post('/requests/{user}/approve', [\App\Http\Controllers\Caregiver\ConnectionController::class, 'approve'])->name('requests.approve');
         Route::post('/requests/{user}/reject', [\App\Http\Controllers\Caregiver\ConnectionController::class, 'reject'])->name('requests.reject');
    });
});
    
Route::get('/banned', function () {
    return view('errors.banned');
})->name('banned');

Route::get('/', function () {
    return view('welcome');
})->name('home');

//F1 - Tarannum Al Akida
Route::get('/admin/login', [App\Http\Controllers\Admin\AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [App\Http\Controllers\Admin\AdminController::class, 'login'])->name('admin.login.submit');
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('admin.dashboard');
});

