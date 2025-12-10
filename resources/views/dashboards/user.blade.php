@extends('layouts.app')

@section('sidebar')
<div style="width:260px;background:white;height:100%;padding:30px;box-shadow:2px 0 8px rgba(0,0,0,0.05);">

    <div class="text-center mb-4">
        <div style="width:90px;height:90px;border-radius:50%;
        background:linear-gradient(135deg,#4facfe,#6f86ff);
        margin:auto;"></div>

        <h6 class="mt-3 fw-bold">Demo User</h6>
        <small class="text-muted">user@ablelink.com</small>
    </div>

    <div class="d-grid gap-2">
        <a class="btn btn-primary rounded-pill">Dashboard</a>
        <a class="btn btn-light rounded-pill">Profile</a>
        <a class="btn btn-light rounded-pill">Jobs</a>
        <a class="btn btn-light rounded-pill">Learning Hub</a>
        <a class="btn btn-light rounded-pill">Community</a>
        <a class="btn btn-light rounded-pill">Safety</a>
    </div>

    <a class="btn btn-primary rounded-pill mt-4 w-100">Logout</a>
</div>
@endsection



@section('content')
<div style="width:100%;min-height:100vh;
background:linear-gradient(135deg,#7fd3ff,#b194ff);
padding:50px;">

<div class="bg-white rounded-5 p-5 shadow-sm" style="max-width:1400px;margin:auto;">

    <!-- HEADER -->
    <div class="d-flex justify-content-between mb-4">
        <div>
            <h3 class="fw-bold">User Progress</h3>
            <small class="text-muted">USER DASHBOARD OVERVIEW</small>
        </div>
        <button class="btn btn-lg rounded-pill text-white" style="background:#a78bfa;">
            Contact Support
        </button>
    </div>

    <!-- STATS -->
    <div class="row g-4 mb-4">
        @foreach(['Profile Completion','Job Applications','Learning Progress'] as $title)
        <div class="col-md-4">
            <div class="bg-white rounded-4 shadow-sm p-4 text-center">
                <div style="width:70px;height:70px;border-radius:50%;
                border:8px solid #cfe7ff;border-top-color:#3ecbff;
                margin:auto;"></div>
                <p class="mt-3 fw-semibold">{{ $title }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <!-- PROGRESS -->
    <div class="row g-4 mb-4">
        <div class="col-md-8">
            <div class="bg-white rounded-4 shadow-sm p-4">
                <h5 class="mb-3">Daily Activity Progress</h5>
                <div style="height:55px;border-radius:20px;
                background:linear-gradient(90deg,#8bffb3,#7ddaff);"></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="bg-white rounded-4 shadow-sm p-4 text-center">
                <h5 class="mb-3">Safety Status</h5>
                <div style="width:70px;height:70px;border-radius:50%;
                border:8px solid #cfe7ff;border-top-color:#3ecbff;
                margin:auto;"></div>
            </div>
        </div>
    </div>

    <!-- FEATURE CARDS -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="bg-white rounded-4 shadow-sm p-4">
                <h6>Profile Settings</h6>
                <small>Edit personal & accessibility settings</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="bg-white rounded-4 shadow-sm p-4">
                <h6>Job Applications</h6>
                <small>Search, save & track jobs</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="bg-white rounded-4 shadow-sm p-4">
                <h6>Learning Hub</h6>
                <small>Courses, certificates & skill tests</small>
            </div>
        </div>
    </div>

    <!-- BOTTOM -->
    <div class="row g-4">
        <div class="col-md-8">
            <div class="bg-white rounded-4 shadow-sm p-4">
                <h6>Community & Support</h6>
                <small>Groups, discussions & messages</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="bg-white rounded-4 shadow-sm p-4">
                <h6>Emergency & Safety</h6>
                <small>SOS, alerts & daily safety tasks</small>
            </div>
        </div>
    </div>

</div>
</div>
@endsection


