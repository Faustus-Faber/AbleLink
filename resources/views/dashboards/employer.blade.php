@extends('layouts.app')

@section('sidebar')
    <div style="width:260px;background:#ffffff;height:100%;padding:30px 24px;box-shadow:2px 0 8px rgba(0,0,0,0.05);">

        <div class="text-center mb-4">
            <div style="width:90px;height:90px;border-radius:50%;
                background:linear-gradient(135deg,#4facfe,#6f86ff);
                margin:auto;">
            </div>

            <h6 class="mt-3 fw-bold mb-0">Demo Employer</h6>
            <small class="text-muted d-block">employer@ablelink.com</small>
        </div>

        <div class="d-grid gap-2">
            <a href="#" class="btn btn-primary rounded-pill">Dashboard</a>
            <a href="#" class="btn btn-light rounded-pill">Job Management</a>
            <a href="#" class="btn btn-light rounded-pill">Applications</a>
            <a href="#" class="btn btn-light rounded-pill">Interviews</a>
            <a href="#" class="btn btn-light rounded-pill">Company Profile</a>
            <a href="#" class="btn btn-light rounded-pill">Reports</a>
        </div>

        <button class="btn btn-primary rounded-pill mt-4 w-100">Logout</button>
    </div>
@endsection


@section('content')
    <!-- Full-height gradient background for the dashboard area -->
    <div style="width:100%;height:100%;
        background:linear-gradient(135deg,#7fd3ff,#b194ff);
        padding:40px 40px 32px;">

        <!-- Main white card container -->
        <div class="bg-white rounded-5 p-5 shadow-sm" style="max-width:1400px;margin:0 auto;">

            <!-- HEADER -->
            <div class="d-flex justify-content-between mb-4 align-items-center">
                <div>
                    <h3 class="fw-bold mb-1">Employer Progress</h3>
                    <small class="text-muted">EMPLOYER DASHBOARD OVERVIEW</small>
                </div>

                <button class="btn btn-lg rounded-pill text-white px-4"
                        style="background:#a78bfa;">
                    Contact Support
                </button>
            </div>

            <!-- TOP STATS -->
            <div class="row g-4 mb-4">
                @foreach(['Total Jobs Posted','Active Jobs','Total Applications'] as $title)
                    <div class="col-md-4">
                        <div class="bg-white rounded-4 shadow-sm p-4 text-center">
                            <div style="width:70px;height:70px;border-radius:50%;
                                border:8px solid #cfe7ff;border-top-color:#3ecbff;
                                margin:auto;">
                            </div>
                            <p class="mt-3 fw-semibold mb-0">{{ $title }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- PROGRESS + SHORTLIST -->
            <div class="row g-4 mb-4">
                <div class="col-md-8">
                    <div class="bg-white rounded-4 shadow-sm p-4 h-100">
                        <h5 class="mb-3">Hiring Progress</h5>
                        <div style="height:55px;border-radius:20px;
                            background:linear-gradient(90deg,#8bffb3,#7ddaff);">
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="bg-white rounded-4 shadow-sm p-4 text-center h-100">
                        <h5 class="mb-3">Shortlisted Candidates</h5>
                        <div style="width:70px;height:70px;border-radius:50%;
                            border:8px solid #cfe7ff;border-top-color:#3ecbff;
                            margin:auto;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- FEATURE CARDS -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="bg-white rounded-4 shadow-sm p-4 h-100">
                        <h6 class="mb-1">Job Management</h6>
                        <small class="text-muted">Create, edit & close job posts</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="bg-white rounded-4 shadow-sm p-4 h-100">
                        <h6 class="mb-1">Application Handling</h6>
                        <small class="text-muted">View, shortlist & reject candidates</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="bg-white rounded-4 shadow-sm p-4 h-100">
                        <h6 class="mb-1">Interview Panel</h6>
                        <small class="text-muted">Schedule & conduct interviews</small>
                    </div>
                </div>
            </div>

            <!-- BOTTOM CARDS -->
            <div class="row g-4">
                <div class="col-md-8">
                    <div class="bg-white rounded-4 shadow-sm p-4 h-100">
                        <h6 class="mb-1">Company Accessibility Profile</h6>
                        <small class="text-muted">
                            Update accessibility & accommodation details
                        </small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="bg-white rounded-4 shadow-sm p-4 h-100">
                        <h6 class="mb-1">Reports & Communication</h6>
                        <small class="text-muted">
                            Chat & download hiring reports
                        </small>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection








