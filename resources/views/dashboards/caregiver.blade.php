@extends('layouts.app')

@section('content')
<div class="container-fluid caregiver-dashboard py-4">

    <!-- ✅ HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Caregiver Dashboard</h3>
        <span class="badge bg-info">Caregiver Mode</span>
    </div>

    <!-- ✅ TOP SUMMARY CARDS -->
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card soft-card">
                <h6>Assigned Users</h6>
                <h4>4</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card soft-card">
                <h6>Active Reminders</h6>
                <h4>12</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card soft-card">
                <h6>Health Alerts</h6>
                <h4 class="text-warning">2</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card soft-card emergency-card">
                <h6>Emergency Status</h6>
                <h4 class="text-danger">1 Active</h4>
            </div>
        </div>

    </div>

    <!-- ✅ MAIN PANELS -->
    <div class="row g-4">

        <!-- USER MANAGEMENT -->
        <div class="col-md-4">
            <div class="card soft-panel">
                <h5>User Care Management</h5>
                <ul>
                    <li>View Assigned Users</li>
                    <li>Update User Profiles</li>
                    <li>Manage Accessibility Preferences</li>
                </ul>
            </div>
        </div>

        <!-- REMINDER CONTROL -->
        <div class="col-md-4">
            <div class="card soft-panel">
                <h5>Reminders & Tasks</h5>
                <ul>
                    <li>Create Medication Reminders</li>
                    <li>View Task Completion</li>
                    <li>Missed Task Alerts</li>
                </ul>
            </div>
        </div>

        <!-- HEALTH MONITORING -->
        <div class="col-md-4">
            <div class="card soft-panel">
                <h5>Health Monitoring</h5>
                <ul>
                    <li>Daily Health Logs</li>
                    <li>Medication Schedules</li>
                    <li>Mental Wellness Updates</li>
                </ul>
            </div>
        </div>

    </div>

    <!-- ✅ BOTTOM SECTION -->
    <div class="row g-4 mt-3">

        <div class="col-md-6">
            <div class="card soft-panel">
                <h5>Emergency & Safety</h5>
                <ul>
                    <li>Live Emergency Alerts</li>
                    <li>Nearby Emergency Centers</li>
                    <li>Panic Mode History</li>
                </ul>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card soft-panel">
                <h5>Communication</h5>
                <ul>
                    <li>Chat with Assigned Users</li>
                    <li>Chat with Volunteers</li>
                    <li>Community Support Messages</li>
                </ul>
            </div>
        </div>

    </div>

</div>
@endsection


