<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Auth\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Assuming this might be needed or just preserving
use Illuminate\View\View;
use Illuminate\Support\Facades\Schema; // F15 - Akida Lisi
use Illuminate\Support\Facades\DB; // F20 - Akida Lisi
use App\Models\Emergency\EmergencySosEvent; // F15 - Akida Lisi

class AdminController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.admin-login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Those credentials do not match our records.',
            ])->onlyInput('email');
        }

        if (! Auth::user()?->isAdmin()) {
            Auth::logout();

            return back()->withErrors([
                'email' => 'This portal is only for admin users.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function dashboard(): View
    {
        // F15 - Akida Lisi
        $activeSosCount = 0;
        $activeSos = collect();

        if (Schema::hasTable('emergency_sos_events')) {
            $activeSosCount = EmergencySosEvent::query()
                ->whereNull('resolved_at')
                ->count();

            $activeSos = EmergencySosEvent::query()
                ->whereNull('resolved_at')
                ->with(['user.profile'])
                ->latest()
                ->take(10)
                ->get();
        }

        // F20 - Akida Lisi
        $aidProgramsCount = 0;
        if (Schema::hasTable('aid_programs')) {
            $aidProgramsCount = DB::table('aid_programs')->count();
        }

        // Real Stats Calculation
        $today = now()->startOfDay();

        // User Activity
        $statsUser = [
            'new_today' => User::whereDate('created_at', $today)->count(),
            'active_30d' => User::where('updated_at', '>=', now()->subDays(30))->count(),
            'blocked' => 0, // Placeholder as ban logic varies
        ];

        // Job Platform
        $statsJobs = [
            'posted_today' => \App\Models\Employment\Job::whereDate('created_at', $today)->count(),
            'active_total' => \App\Models\Employment\Job::where('status', 'active')->count(),
            'apps_today' => \App\Models\Employment\JobApplication::whereDate('created_at', $today)->count(),
        ];

        // Learning Hub
        $statsLearning = [
            'courses_active' => \App\Models\Education\Course::whereNotNull('published_at')->count(),
            'enrolled_total' => Schema::hasTable('course_user') ? DB::table('course_user')->count() : 0,
            'certs_issued' => \App\Models\Education\Certificate::count(),
        ];

        // Community
        $statsCommunity = [
            'posts_today' => \App\Models\Community\ForumThread::whereDate('created_at', $today)->count(),
            'reports_pending' => \App\Models\Community\ForumThread::where('status', 'flagged')->count() + \App\Models\Community\ForumReply::where('status', 'flagged')->count(),
            'banned_users' => User::whereNotNull('banned_at')->count(),
        ];

        return view('dashboards.admin', [
            'counts' => [
                'employer' => User::where('role', User::ROLE_EMPLOYER)->count(),
                'volunteer' => User::where('role', User::ROLE_VOLUNTEER)->count(),
                'disabled' => User::where('role', User::ROLE_DISABLED)->count(),
                'caregiver' => User::where('role', User::ROLE_CAREGIVER)->count(),
            ],
            'recentUsers' => User::whereIn('role', User::COMMUNITY_ROLES)
                ->latest()
                ->take(5)
                ->get(),
            'activeSos' => $activeSos, // F15
            'activeSosCount' => $activeSosCount, // F15
            'aidProgramsCount' => $aidProgramsCount, // F20
            'statsUser' => $statsUser,
            'statsJobs' => $statsJobs,
            'statsLearning' => $statsLearning,
            'statsCommunity' => $statsCommunity,
        ]);
    }
}


