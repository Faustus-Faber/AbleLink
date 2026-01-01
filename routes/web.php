<?php

use App\Http\Controllers\Caregiver\CaregiverController;
use App\Http\Controllers\Caregiver\ConnectionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Profile\AccessibilityController;
use App\Http\Controllers\User\DashboardController; 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Document\DocumentController;
use App\Http\Controllers\Course\CourseLibraryController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\CourseMediaController as AdminCourseMediaController;
use App\Http\Controllers\Aid\AidDirectoryController;
use App\Http\Controllers\Admin\AdminAidProgramController;
use App\Http\Controllers\Admin\AdminStatsController;


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
    //F9 - Evan Yuvraj Munshi
    Route::get('/notifications', [\App\Http\Controllers\Notification\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\Notification\NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/read-all', [\App\Http\Controllers\Notification\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');

    //F10 - Roza Akter
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // F13 - Farhan Zarif
    Route::resource('messages', \App\Http\Controllers\Community\MessageController::class)->only(['index', 'show', 'store', 'destroy']);

    Route::middleware(['banned'])->group(function () {
        // F13 - Farhan Zarif
        Route::resource('forum', \App\Http\Controllers\Community\ForumController::class);
        Route::post('forum/{id}/reply', [\App\Http\Controllers\Community\ForumController::class, 'reply'])->name('forum.reply');
        Route::delete('forum/reply/{id}', [\App\Http\Controllers\Community\ForumController::class, 'destroyReply'])->name('forum.reply.destroy');
    
        // F16 - Evan Yuvraj Munshi
        Route::get('/community', [\App\Http\Controllers\Community\CommunityController::class, 'index'])->name('community.index');
    
        // F16 - Evan Yuvraj Munshi
        Route::resource('community/events', \App\Http\Controllers\Community\CommunityEventController::class)->names('community.events');
        Route::post('community/events/{event}/join', [\App\Http\Controllers\Community\CommunityEventController::class, 'join'])->name('community.events.join');
        Route::post('community/events/{event}/leave', [\App\Http\Controllers\Community\CommunityEventController::class, 'leave'])->name('community.events.leave');
        Route::resource('community/matrimony', \App\Http\Controllers\Community\MatrimonyController::class)->names('community.matrimony');
    });
});
    
Route::get('/banned', function () {
    return view('errors.banned');
})->name('banned');

Route::get('/', function () {
    return view('welcome');
})->name('home');

// F20 - Tarannum Al Akida
Route::get('/aid', [AidDirectoryController::class, 'index'])->name('aid.index');
Route::get('/aid/{slug}', [AidDirectoryController::class, 'show'])->name('aid.show');

// F11 - Tarannum Al Akida
Route::get('/courses', [CourseLibraryController::class, 'index'])->name('courses.index');
Route::get('/courses/{course:slug}', [CourseLibraryController::class, 'show'])->name('courses.show');

//F4 - Farhan Zarif
Route::middleware(['auth', 'role:caregiver'])->group(function () {
    
    // F19 - Caregiver Health Management
    Route::get('/caregiver/patient/{user}/health', [\App\Http\Controllers\Caregiver\CaregiverController::class, 'manageHealth'])->name('caregiver.patient.health');
    Route::post('/caregiver/patient/{user}/health/diagnosis', [\App\Http\Controllers\Caregiver\CaregiverController::class, 'updateDiagnosis'])->name('caregiver.patient.diagnosis.update');
    
    //F4 - Farhan Zarif
    Route::prefix('caregiver')->name('caregiver.')->group(function () {
        Route::get('/dashboard', [CaregiverController::class, 'index'])->name('dashboard');
        Route::post('/request', [CaregiverController::class, 'sendRequest'])->name('request');
        Route::get('/patient/{user}/edit', [CaregiverController::class, 'editPatient'])->name('patient.edit');
        Route::put('/patient/{user}', [CaregiverController::class, 'updatePatient'])->name('patient.update');
        Route::delete('/patient/{user}', [CaregiverController::class, 'unlink'])->name('patient.unlink');
        Route::post('/sos/{event}/resolve', [CaregiverController::class, 'resolveSos'])->name('sos.resolve');
        
        // F17 - Roza Akter
        Route::get('/appointments', [\App\Http\Controllers\Caregiver\DoctorAppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/calendar', [\App\Http\Controllers\Caregiver\DoctorAppointmentController::class, 'getCalendarData'])->name('appointments.calendar');
        Route::get('/appointments/{user}/create', [\App\Http\Controllers\Caregiver\DoctorAppointmentController::class, 'create'])->name('appointments.create');
        Route::post('/appointments/{user}', [\App\Http\Controllers\Caregiver\DoctorAppointmentController::class, 'store'])->name('appointments.store');
        Route::get('/appointments/{appointment}/edit', [\App\Http\Controllers\Caregiver\DoctorAppointmentController::class, 'edit'])->name('appointments.edit');
        Route::put('/appointments/{appointment}', [\App\Http\Controllers\Caregiver\DoctorAppointmentController::class, 'update'])->name('appointments.update');
        Route::delete('/appointments/{appointment}', [\App\Http\Controllers\Caregiver\DoctorAppointmentController::class, 'destroy'])->name('appointments.destroy');
    });
});

Route::middleware(['auth', 'role:disabled'])->group(function () {
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/requests', [ConnectionController::class, 'index'])->name('requests');
        Route::post('/requests/{caregiver}/approve', [ConnectionController::class, 'approve'])->name('requests.approve');
        Route::post('/requests/{caregiver}/deny', [ConnectionController::class, 'deny'])->name('requests.deny');
        
        // F17 - Roza Akter
        Route::get('/appointments', [\App\Http\Controllers\User\AppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/calendar', [\App\Http\Controllers\User\AppointmentController::class, 'getCalendarData'])->name('appointments.calendar');
    });
});

Route::middleware(['auth'])->group(function () {
    //F3 - Evan Yuvraj Munshi
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar');
    
    //F3 - Evan Yuvraj Munshi
    Route::get('/accessibility', [AccessibilityController::class, 'edit'])->name('accessibility.edit');
    Route::put('/accessibility', [AccessibilityController::class, 'update'])->name('accessibility.update');

    // F21 - Certificate Generation
    Route::post('/courses/{course}/certificate', [\App\Http\Controllers\Education\CertificateController::class, 'generate'])->name('courses.certificate');
});

//F10 -Roza Akter
Route::middleware(['auth', 'role:employer'])->group(function () {
    Route::prefix('employer')->name('employer.')->group(function () {
        Route::get('/jobs', [\App\Http\Controllers\Employer\EmployerJobController::class, 'index'])->name('jobs.index');
        Route::get('/jobs/create', [\App\Http\Controllers\Employer\EmployerJobController::class, 'create'])->name('jobs.create');
        Route::post('/jobs', [\App\Http\Controllers\Employer\EmployerJobController::class, 'store'])->name('jobs.store');
        Route::get('/jobs/{job}', [\App\Http\Controllers\Employer\EmployerJobController::class, 'show'])->name('jobs.show');
        Route::get('/jobs/{job}/edit', [\App\Http\Controllers\Employer\EmployerJobController::class, 'edit'])->name('jobs.edit');
        Route::put('/jobs/{job}', [\App\Http\Controllers\Employer\EmployerJobController::class, 'update'])->name('jobs.update');
        Route::delete('/jobs/{job}', [\App\Http\Controllers\Employer\EmployerJobController::class, 'destroy'])->name('jobs.destroy');
        Route::put('/jobs/applications/{application}/status', [\App\Http\Controllers\Employer\EmployerJobController::class, 'updateApplicationStatus'])->name('jobs.update-application-status');
        Route::get('/applications', [\App\Http\Controllers\Employer\EmployerJobController::class, 'applications'])->name('applications');
        Route::get('/interviews', [\App\Http\Controllers\Employer\InterviewController::class, 'index'])->name('interviews.index');
        Route::get('/interviews/create/{application}', [\App\Http\Controllers\Employer\InterviewController::class, 'create'])->name('interviews.create');
        Route::post('/interviews/{application}', [\App\Http\Controllers\Employer\InterviewController::class, 'store'])->name('interviews.store');
        Route::put('/interviews/{interview}/status', [\App\Http\Controllers\Employer\InterviewController::class, 'updateStatus'])->name('interviews.update-status');
        Route::get('/profile', [\App\Http\Controllers\Employer\EmployerProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [\App\Http\Controllers\Employer\EmployerProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [\App\Http\Controllers\Employer\EmployerProfileController::class, 'update'])->name('profile.update');
        Route::get('/reports', [\App\Http\Controllers\Employer\ReportController::class, 'index'])->name('reports');
    });
});

//F7 - Farhan Zarif
Route::middleware(['web'])->group(function () {
    Route::post('/ai/chat', [\App\Http\Controllers\Ai\AiNavigationController::class, 'chat'])->name('ai.chat');
    //F18 - Farhan Zarif
    Route::post('/ai/upload', [\App\Http\Controllers\Ai\AiNavigationController::class, 'upload'])->name('ai.upload');
    Route::get('/ai/file/{filename}', [\App\Http\Controllers\Ai\AiNavigationController::class, 'serveFile'])->name('ai.file');
});

//F15 - Tarannum Al Akida
Route::middleware(['auth'])->group(function () {
    Route::post('/sos', [\App\Http\Controllers\Emergency\EmergencySosController::class, 'store'])->name('sos.store');
    Route::post('/admin/sos/{event}/resolve', [\App\Http\Controllers\Emergency\EmergencySosController::class, 'resolve'])->name('admin.sos.resolve');
});

//F1 - Tarannum Al Akida
Route::get('/admin/login', [App\Http\Controllers\Admin\AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [App\Http\Controllers\Admin\AdminController::class, 'login'])->name('admin.login.submit');
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('admin.dashboard');

    // F11 - Tarannum Al Akida
    Route::prefix('admin')->name('admin.')->group(function () {
        // F20 - Tarannum Al Akida
        Route::get('/users', [AdminStatsController::class, 'users'])->name('users.list');
        Route::get('/volunteers', [AdminStatsController::class, 'volunteers'])->name('volunteers.list');
        Route::get('/employers', [AdminStatsController::class, 'employers'])->name('employers.list');
        Route::get('/caregivers', [AdminStatsController::class, 'caregivers'])->name('caregivers.list');
        Route::get('/jobs', [AdminStatsController::class, 'jobs'])->name('jobs.count');
        
        Route::get('/users/create/{role?}', [AdminStatsController::class, 'create'])->name('users.create');
        Route::post('/users', [AdminStatsController::class, 'store'])->name('users.store');
        Route::delete('/users/{user}', [AdminStatsController::class, 'destroy'])->name('users.destroy');

        // F20 - Tarannum Al Akida
        Route::get('/aid', [AdminAidProgramController::class, 'index'])->name('aid.index');
        Route::get('/aid/create', [AdminAidProgramController::class, 'create'])->name('aid.create');
        Route::post('/aid', [AdminAidProgramController::class, 'store'])->name('aid.store');
        Route::get('/aid/{id}/edit', [AdminAidProgramController::class, 'edit'])->name('aid.edit');
        Route::put('/aid/{id}', [AdminAidProgramController::class, 'update'])->name('aid.update');
        Route::delete('/aid/{id}', [AdminAidProgramController::class, 'destroy'])->name('aid.destroy');
        Route::post('/aid/{id}/toggle', [AdminAidProgramController::class, 'toggle'])->name('aid.toggle');

        Route::get('/courses', [AdminCourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/create', [AdminCourseController::class, 'create'])->name('courses.create');
        Route::post('/courses', [AdminCourseController::class, 'store'])->name('courses.store');
        Route::get('/courses/{course}/edit', [AdminCourseController::class, 'edit'])->name('courses.edit');
        Route::put('/courses/{course}', [AdminCourseController::class, 'update'])->name('courses.update');
        Route::delete('/courses/{course}', [AdminCourseController::class, 'destroy'])->name('courses.destroy');

        Route::get('/courses/{course}/media/create', [AdminCourseMediaController::class, 'create'])->name('courses.media.create');
        Route::post('/courses/{course}/media', [AdminCourseMediaController::class, 'store'])->name('courses.media.store');
        Route::get('/media/{media}/edit', [AdminCourseMediaController::class, 'edit'])->name('courses.media.edit');
        Route::put('/media/{media}', [AdminCourseMediaController::class, 'update'])->name('courses.media.update');
        Route::delete('/media/{media}', [AdminCourseMediaController::class, 'destroy'])->name('courses.media.destroy');
    });

    // F13 - Farhan Zarif
    Route::get('/admin/moderation', [\App\Http\Controllers\Admin\AdminModerationController::class, 'index'])->name('admin.moderation.index');
    Route::patch('/admin/moderation/thread/{thread}', [\App\Http\Controllers\Admin\AdminModerationController::class, 'approveThread'])->name('admin.moderation.thread.approve');
    Route::delete('/admin/moderation/thread/{thread}', [\App\Http\Controllers\Admin\AdminModerationController::class, 'deleteThread'])->name('admin.moderation.thread.delete');
    Route::patch('/admin/moderation/reply/{reply}', [\App\Http\Controllers\Admin\AdminModerationController::class, 'approveReply'])->name('admin.moderation.reply.approve');
    Route::delete('/admin/moderation/reply/{reply}', [\App\Http\Controllers\Admin\AdminModerationController::class, 'deleteReply'])->name('admin.moderation.reply.delete');
    Route::post('/admin/moderation/user/{user}/ban', [\App\Http\Controllers\Admin\AdminModerationController::class, 'banUser'])->name('admin.moderation.user.ban');
    Route::post('/admin/moderation/user/{user}/unban', [\App\Http\Controllers\Admin\AdminModerationController::class, 'unbanUser'])->name('admin.moderation.user.unban');
    Route::get('/admin/community', [\App\Http\Controllers\Admin\AdminCommunityController::class, 'index'])->name('admin.community.index');
    Route::delete('/admin/community/threads/{thread}', [\App\Http\Controllers\Admin\AdminCommunityController::class, 'destroy'])->name('admin.community.threads.destroy');
    Route::get('/admin/jobs', [\App\Http\Controllers\Admin\AdminJobController::class, 'index'])->name('admin.jobs.index');
    Route::delete('/admin/jobs/posts/{job}', [\App\Http\Controllers\Admin\AdminJobController::class, 'destroy'])->name('admin.jobs.destroy');
});

Route::get('/upload', [DocumentController::class, 'showUploadForm'])->name('documents.upload');
Route::post('/upload', [DocumentController::class, 'processDocument'])->name('documents.process');
Route::post('/simplify', [DocumentController::class, 'simplifyText'])->name('documents.simplify');

// F12 - Farhan Zarif
Route::group(['middleware' => 'auth'], function () {
    Route::post('/recommendations/preferences', [\App\Http\Controllers\Recommendation\RecommendationController::class, 'updatePreferences'])->name('recommendations.preferences');
    Route::post('/recommendations/dismiss', [\App\Http\Controllers\Recommendation\RecommendationController::class, 'dismiss'])->name('recommendations.dismiss');
});

Route::get('/recommendations/jobs', [\App\Http\Controllers\Recommendation\RecommendationController::class, 'getJobs'])->name('recommendations.jobs');
Route::get('/recommendations/courses', [\App\Http\Controllers\Recommendation\RecommendationController::class, 'getCourses'])->name('recommendations.courses');

// F9 - Evan Yuvraj Munshi
Route::get('/jobs', [\App\Http\Controllers\Job\JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job}', [\App\Http\Controllers\Job\JobController::class, 'show'])->name('jobs.show');
Route::post('/jobs/{job}/apply', [\App\Http\Controllers\Job\JobController::class, 'apply'])->name('jobs.apply')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    // F9 - Evan Yuvraj Munshi
    Route::prefix('candidate')->name('candidate.')->group(function () {
        Route::get('/applications', [\App\Http\Controllers\Candidate\CandidateApplicationController::class, 'index'])->name('applications');
    });
});

//F14 - Roza Akter
Route::middleware(['auth', 'role:volunteer'])->group(function () {
    
    // F14 - Roza Akter
    Route::prefix('volunteer')->name('volunteer.')->group(function () {
        Route::get('/profile', [\App\Http\Controllers\Volunteer\VolunteerProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [\App\Http\Controllers\Volunteer\VolunteerProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [\App\Http\Controllers\Volunteer\VolunteerProfileController::class, 'update'])->name('profile.update');
        Route::get('/requests', [\App\Http\Controllers\Volunteer\AssistanceRequestController::class, 'index'])->name('requests.index');
        Route::post('/requests/{assistanceRequest}/accept', [\App\Http\Controllers\Volunteer\AssistanceRequestController::class, 'accept'])->name('requests.accept');
        Route::post('/requests/{assistanceRequest}/decline', [\App\Http\Controllers\Volunteer\AssistanceRequestController::class, 'decline'])->name('requests.decline');
        Route::get('/assistance/active', [\App\Http\Controllers\Volunteer\AssistanceRequestController::class, 'active'])->name('assistance.active');
        Route::put('/assistance/{match}/complete', [\App\Http\Controllers\Volunteer\AssistanceRequestController::class, 'complete'])->name('assistance.complete');
        Route::get('/assistance/history', [\App\Http\Controllers\Volunteer\AssistanceRequestController::class, 'history'])->name('assistance.history');
    });

});

Route::middleware(['auth', 'role:disabled,caregiver'])->group(function () {
    Route::prefix('user/assistance')->name('user.assistance.')->group(function () {
        Route::get('/', [\App\Http\Controllers\User\UserAssistanceController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\User\UserAssistanceController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\User\UserAssistanceController::class, 'store'])->name('store');
        Route::get('/{assistanceRequest}', [\App\Http\Controllers\User\UserAssistanceController::class, 'show'])->name('show');
    });

    //F19 - Evan Yuvraj Munshi
    Route::prefix('health')->name('health.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Health\HealthDashboardController::class, 'index'])->name('dashboard');
        Route::post('/medications', [\App\Http\Controllers\Health\MedicationController::class, 'store'])->name('medications.store');
        Route::post('/medications/{schedule}/log', [\App\Http\Controllers\Health\MedicationController::class, 'log'])->name('medications.log');
        Route::post('/goals', [\App\Http\Controllers\Health\HealthGoalController::class, 'store'])->name('goals.store');
        Route::put('/goals/{goal}/status', [\App\Http\Controllers\Health\HealthGoalController::class, 'updateStatus'])->name('goals.update-status');
        Route::post('/metrics', [\App\Http\Controllers\Health\HealthMetricController::class, 'store'])->name('metrics.store');
    });
});

