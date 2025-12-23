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

    // F7 - AI Chat - Farhan Zarif
    Route::post('/ai/chat', [\App\Http\Controllers\Ai\AiNavigationController::class, 'chat'])->name('ai.chat');

    // F8 - Document Simplification & OCR - Tarannum Al Akida
    Route::get('/documents/upload', [\App\Http\Controllers\Document\DocumentController::class, 'showUploadForm'])->name('documents.upload');
    Route::post('/documents/process', [\App\Http\Controllers\Document\DocumentController::class, 'processDocument'])->name('documents.process');
    Route::post('/documents/simplify-text', [\App\Http\Controllers\Document\DocumentController::class, 'simplifyText'])->name('documents.simplify-text');

    // F10 - Employer Dashboard - Roza Akter
    Route::middleware(['role:employer'])->prefix('employer')->name('employer.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Employer\EmployerJobController::class, 'index'])->name('dashboard');
        Route::resource('jobs', \App\Http\Controllers\Employer\EmployerJobController::class);
        Route::resource('applications', \App\Http\Controllers\Employer\EmployerJobController::class)->only(['index', 'update']); // Handling application status via JobController for now or separate? Checking logic. 
        // Based on typical Laravel, applications might be handled in a dedicated controller or nested. 
        // Let's stick to the controller list: EmployerJobController seems to handle listing. 
        // Check if there is an ApplicationController? No, only JobApplication model.
        // Wait, "EmployerJobController@updateApplicationStatus". 
        Route::patch('/applications/{application}/status', [\App\Http\Controllers\Employer\EmployerJobController::class, 'updateApplicationStatus'])->name('applications.update-status');
        
        Route::resource('profile', \App\Http\Controllers\Employer\EmployerProfileController::class)->only(['show', 'edit', 'update']);
        Route::resource('interviews', \App\Http\Controllers\Employer\InterviewController::class);
        Route::get('/reports', [\App\Http\Controllers\Employer\ReportController::class, 'index'])->name('reports.index');
    });

    // F9 - Accessible Job Search & Applications - Evan Yuvraj Munshi
    Route::get('/jobs', [\App\Http\Controllers\Job\JobController::class, 'index'])->name('jobs.index');
    Route::get('/jobs/{job}', [\App\Http\Controllers\Job\JobController::class, 'show'])->name('jobs.show');
    Route::post('/jobs/{job}/apply', [\App\Http\Controllers\Job\JobController::class, 'apply'])->name('jobs.apply');

    Route::middleware(['auth'])->group(function () {
        Route::get('/candidate/applications', [\App\Http\Controllers\Candidate\CandidateApplicationController::class, 'index'])->name('candidate.applications.index');
        
        Route::get('/notifications', [\App\Http\Controllers\Notification\NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{id}/read', [\App\Http\Controllers\Notification\NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [\App\Http\Controllers\Notification\NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    });

    // F11 - Accessible Course Library - Tarannum Al Akida
    Route::middleware(['auth'])->group(function () {
        // Public Library
        Route::get('/courses', [\App\Http\Controllers\Course\CourseLibraryController::class, 'index'])->name('courses.index');
        Route::get('/courses/{course}', [\App\Http\Controllers\Course\CourseLibraryController::class, 'show'])->name('courses.show');

        // Admin Management
        Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
            Route::resource('courses', \App\Http\Controllers\Admin\CourseController::class);
            Route::resource('courses.media', \App\Http\Controllers\Admin\CourseMediaController::class)->shallow();
        });
    });

    // F12 - AI Recommendations & Certification - Faustus-Faber
    Route::middleware(['auth'])->group(function () {
        Route::get('/recommendations/jobs', [\App\Http\Controllers\Recommendation\RecommendationController::class, 'getJobs'])->name('recommendations.jobs');
        Route::get('/recommendations/courses', [\App\Http\Controllers\Recommendation\RecommendationController::class, 'getCourses'])->name('recommendations.courses');
        
        Route::post('/courses/{course}/certificate', [\App\Http\Controllers\Education\CertificateController::class, 'generate'])->name('courses.certificate.generate');
        Route::get('/certificates/{certificate}/download', [\App\Http\Controllers\Education\CertificateController::class, 'download'])->name('certificates.download');
    });

    // F13 - Community Forum & Messaging - Faustus-Faber
    Route::middleware(['auth', \App\Http\Middleware\CheckBanned::class])->group(function () {
        // Community Forum
        Route::resource('forum', \App\Http\Controllers\Community\ForumController::class);
        Route::post('/forum/{thread}/reply', [\App\Http\Controllers\Community\ForumController::class, 'reply'])->name('forum.reply');
        Route::post('/forum/{type}/{id}/flag', [\App\Http\Controllers\Community\ForumController::class, 'flag'])->name('forum.flag');

        // Private Messaging
        Route::resource('messages', \App\Http\Controllers\Community\MessageController::class);
        Route::post('/messages/conversation', [\App\Http\Controllers\Community\MessageController::class, 'startConversation'])->name('messages.start');
        
        // Admin Moderation
        Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
             Route::get('/moderation', [\App\Http\Controllers\Admin\AdminModerationController::class, 'index'])->name('moderation.index');
             Route::post('/moderation/{type}/{id}/{action}', [\App\Http\Controllers\Admin\AdminModerationController::class, 'handleAction'])->name('moderation.action');
             Route::post('/users/{user}/ban', [\App\Http\Controllers\Admin\AdminModerationController::class, 'banUser'])->name('users.ban');
             Route::post('/users/{user}/unban', [\App\Http\Controllers\Admin\AdminModerationController::class, 'unbanUser'])->name('users.unban');
        });
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

