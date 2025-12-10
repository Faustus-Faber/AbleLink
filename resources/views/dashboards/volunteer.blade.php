<!DOCTYPE html>
<html>
<head>
    <title>AbleLink Volunteer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(120deg, #79d0ff, #b58cff);
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }

        .app-wrapper {
            background: #f6f7fb;
            margin: 40px;
            border-radius: 20px;
            box-shadow: 0 30px 80px rgba(0,0,0,0.25);
            overflow: hidden;
            display: flex;
        }

        /* ✅ LEFT PROFILE SIDEBAR */
        .sidebar {
            width: 260px;
            background: white;
            padding: 30px 20px;
            text-align: center;
        }

        .profile-pic {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: linear-gradient(to right, #6dd5fa, #2980b9);
            margin: auto;
            margin-bottom: 15px;
        }

        .sidebar h5 {
            margin-bottom: 3px;
        }

        .sidebar small {
            color: gray;
        }

        .menu-btn {
            display: block;
            width: 100%;
            border: none;
            background: none;
            text-align: left;
            padding: 10px 15px;
            border-radius: 10px;
            margin: 6px 0;
            transition: 0.3s;
        }

        .menu-btn:hover {
            background: #e8edff;
        }

        .logout-btn {
            margin-top: 30px;
            background: linear-gradient(to right, #6dd5fa, #b58cff);
            border: none;
            color: white;
            padding: 10px;
            border-radius: 12px;
        }

        /* ✅ MAIN CONTENT */
        .main {
            flex: 1;
            padding: 30px;
        }

        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-title {
            color: gray;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .card-ui {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
            transition: 0.3s;
        }

        .card-ui:hover {
            transform: translateY(-6px);
        }

        .circle {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            border: 12px solid #e6edff;
            border-top: 12px solid #6dd5fa;
            margin: auto;
        }

        .chart-placeholder {
            height: 120px;
            background: linear-gradient(90deg, #84fab0, #8fd3f4);
            border-radius: 12px;
        }
    </style>
</head>

<body>

<div class="app-wrapper">

       <!-- ✅ LEFT PROFILE SIDEBAR -->
       <div class="sidebar">
        <div class="profile-pic"></div>
        <h5>Demo Volunteer</h5>
        <small>demo@ablelink.com</small>

        <div class="mt-4">
            <a class="menu-btn text-decoration-none text-dark" href="{{ url('/demo/volunteer') }}">Dashboard</a>
            <a class="menu-btn text-decoration-none text-dark" href="{{ url('/demo/volunteer/requests') }}">Help Requests</a>
            <a class="menu-btn text-decoration-none text-dark" href="{{ url('/demo/volunteer/assistance') }}">Active Assistance</a>
            <a class="menu-btn text-decoration-none text-dark" href="{{ url('/demo/volunteer/history') }}">Task History</a>
            <a class="menu-btn text-decoration-none text-dark" href="{{ url('/demo/volunteer/skills') }}">Skill Profile</a>
            <a class="menu-btn text-decoration-none text-dark" href="{{ url('/demo/volunteer/safety') }}">Safety & Reports</a>
        </div>

        <button class="logout-btn w-100">Logout</button>
    </div>

    <!-- ✅ MAIN PANEL -->
    <div class="main">

        <div class="top-header">
            <h4>Personal Progress</h4>
            <button class="btn btn-sm" style="background:#b58cff;color:white;border-radius:20px;">Contact Support</button>
        </div>

        <p class="section-title">VOLUNTEER DASHBOARD OVERVIEW</p>

        <!-- ✅ TOP 3 PROGRESS CARDS -->
        <div class="row g-4 mb-4">

            <div class="col-md-4">
                <div class="card-ui text-center">
                    <div class="circle mb-3"></div>
                    <strong>Assistance Types</strong>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-ui text-center">
                    <div class="circle mb-3"></div>
                    <strong>Task Distribution</strong>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-ui text-center">
                    <div class="circle mb-3"></div>
                    <strong>Support Requests Trend</strong>
                </div>
            </div>

        </div>

        <!-- ✅ BOTTOM PROGRESS CARDS -->
        <div class="row g-4">

            <div class="col-md-8">
                <div class="card-ui">
                    <h6>Progress Tracking</h6>
                    <div class="chart-placeholder mt-3"></div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-ui text-center">
                    <h6>Weekly Goal Achieved</h6>
                    <div class="circle mt-4"></div>
                    <p class="mt-2">— %</p>
                </div>
            </div>

        </div>

    </div>
</div>

</body>
</html>




