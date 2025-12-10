<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return match ($user->role) {
            'user' => view('dashboards.user', compact('user')),
            'caregiver' => view('dashboards.caregiver', compact('user')),
            'volunteer' => view('dashboards.volunteer', compact('user')),
            'employer' => view('dashboards.employer', compact('user')),
            'admin' => view('dashboards.admin', compact('user')),
            default => view('dashboards.user', compact('user')),
        };
    }
}


