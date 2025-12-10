@extends('layouts.app')

@section('sidebar')
    <div style="width:260px;background:#ffffff;height:100%;padding:30px 24px;box-shadow:2px 0 8px rgba(0,0,0,0.05);">

        <div class="text-center mb-4">
            <div style="width:90px;height:90px;border-radius:50%;
                background:linear-gradient(135deg,#4facfe,#6f86ff);
                margin:auto;">
            </div>

            <h6 class="mt-3 fw-bold mb-0">Demo Admin</h6>
            <small class="text-muted d-block">admin@ablelink.com</small>
        </div>

        <div class="d-grid gap-2">
            <a href="#" class="btn btn-primary rounded-pill">Dashboard</a>
            <a href="#" class="btn btn-light rounded-pill">Users</a>
            <a href="#" class="btn btn-light rounded-pill">Volunteers</a>
            <a href="#" class="btn btn-light rounded-pill">Employers</a>
            <a href="#" class="btn btn-light rounded-pill">Jobs</a>
            <a href="#" class="btn btn-light rounded-pill">Learning Hub</a>
            <a href="#" class="btn btn-light rounded-pill">Community</a>
            <a href="#" class="btn btn-light rounded-pill">Emergency Logs</a>
            <a href="#" class="btn btn-light rounded-pill">Reports</a>
        </div>

        <button class="btn btn-danger rounded-pill mt-4 w-100">Logout</button>
    </div>
@endsection


@section('content')
    <div style="width:100%;height:100%;
        background:linear-gradient(135deg,#7fd3ff,#b194ff);
        padding:40px;">

        <div class="bg-white rounded-5 p-5 shadow-sm" style="max-width:1400px;margin:auto;">

            <!-- HEADER -->
            <div class="d-flex justify-content-between mb-4 align-items-center">
                <div>
                    <h3 class="fw-bold mb-1">Admin Overview</h3>
                    <small class="text-muted">PLATFORM DASHBOARD</small>
                </div>

                <button class="btn btn-lg rounded-pill text-white px-4"
                        style="background:#a78bfa;">
                    Emergency Mode
                </button>
            </div>

            <!-- TOP STATS -->
            <div class="row g-4 mb-4">
                @foreach([
                    'Total Users' => '0',
                    'Active Volunteers' => '0',
                    'Employers' => '0',
                    'Emergency Alerts' => '0'
                ] as $title => $value)
                    <div class="col-md-3">
                        <div class="bg-white rounded-4 shadow-sm p-4 text-center">
                            <div style="width:70px;height:70px;border-radius:50%;
                                border:8px solid #cfe7ff;border-top-color:#3ecbff;
                                margin:auto;">
                            </div>
                            <p class="mt-3 fw-semibold mb-1">{{ $title }}</p>
                            <h4 class="fw-bold">{{ $value }}</h4>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- USER ACTIVITY + JOB PLATFORM -->
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="bg-white rounded-4 shadow-sm p-4 h-100">
                        <h5>User Activity</h5>
                        <ul class="mt-2">
                            <li>New Users Today: 0</li>
                            <li>Active Users: 0</li>
                            <li>Blocked Users: 0</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="bg-white rounded-4 shadow-sm p-4 h-100">
                        <h5>Job Platform</h5>
                        <ul class="mt-2">
                            <li>Jobs Posted Today: 0</li>
                            <li>Total Active Jobs: 0</li>
                            <li>Applications Today: 0</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- LEARNING HUB + COMMUNITY -->
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="bg-white rounded-4 shadow-sm p-4 h-100">
                        <h5>Learning Hub</h5>
                        <ul class="mt-2">
                            <li>Active Courses: 0</li>
                            <li>Enrolled Users: 0</li>
                            <li>Certificates Issued: 0</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="bg-white rounded-4 shadow-sm p-4 h-100">
                        <h5>Community & Safety</h5>
                        <ul class="mt-2">
                            <li>New Posts Today: 0</li>
                            <li>Reports Pending: 0</li>
                            <li>Banned Users: 0</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
