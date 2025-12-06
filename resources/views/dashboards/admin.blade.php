@extends('layouts.admin')

@section('admin-content')
    <!-- HEADER -->
    <div class="flex justify-between items-end mb-8">
        <div>
            <h3 class="text-2xl font-extrabold text-slate-900">Admin Overview</h3>
            <p class="text-slate-500 font-medium uppercase tracking-wide text-xs">PLATFORM DASHBOARD</p>
        </div>
    </div>

    <!-- TOP STATS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 text-center hover:shadow-md transition-all">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Total Users</p>
            <h3 class="text-3xl font-extrabold text-slate-800">{{ array_sum($counts) }}</h3>
        </div>

        <!-- Volunteers -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 text-center hover:shadow-md transition-all">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Active Volunteers</p>
            <h3 class="text-3xl font-extrabold text-slate-800">{{ $counts['volunteer'] }}</h3>
        </div>

        <!-- Employers -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 text-center hover:shadow-md transition-all">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Employers</p>
            <h3 class="text-3xl font-extrabold text-slate-800">{{ $counts['employer'] }}</h3>
        </div>

        <!-- Caregivers (Replaced Emergency Alerts) -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 text-center hover:shadow-md transition-all">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Caregivers</p>
            <h3 class="text-3xl font-extrabold text-slate-800">{{ $counts['caregiver'] }}</h3>
        </div>
    </div>

    <!-- ACTIVE SOS ALERTS -->
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 mb-8">
        <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3">
            <div>
                <h5 class="text-lg font-bold text-slate-800">Active SOS Alerts</h5>
                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Newest first</p>
            </div>
            <div class="px-3 py-1 rounded-full bg-red-50 text-red-700 text-xs font-bold border border-red-100">
                {{ $activeSosCount ?? 0 }} Active
            </div>
        </div>

        @if(empty($activeSos) || $activeSos->isEmpty())
            <div class="text-slate-500 font-medium">No active SOS alerts right now.</div>
        @else
            <div class="space-y-4">
                @foreach($activeSos as $event)
                    <div class="rounded-2xl border border-red-100 bg-red-50/40 p-5">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-red-700 uppercase tracking-wide">SOS</p>
                                <p class="text-lg font-extrabold text-slate-900 truncate">
                                    {{ $event->user?->name ?? 'Unknown user' }}
                                </p>
                                <p class="text-sm text-slate-600">
                                    <span class="font-bold">Email:</span> {{ $event->user?->email ?? 'N/A' }}
                                    @if($event->user && $event->user->profile && $event->user->profile->phone_number)
                                        <span class="mx-2 text-slate-300">|</span>
                                        <span class="font-bold">Phone:</span> {{ $event->user->profile->phone_number }}
                                    @endif
                                </p>
                                <p class="text-sm text-slate-600 mt-1">
                                    <span class="font-bold">Time:</span> {{ $event->created_at?->format('M j, Y g:i A') }}
                                </p>

                                <div class="mt-3 text-sm text-slate-700 space-y-1">
                                    @if($event->latitude !== null && $event->longitude !== null)
                                        <p>
                                            <span class="font-bold">Location:</span>
                                            {{ $event->latitude }}, {{ $event->longitude }}
                                            @if($event->accuracy_m)
                                                <span class="text-slate-500">(±{{ $event->accuracy_m }}m)</span>
                                            @endif
                                        </p>
                                        <p>
                                            <a class="text-blue-700 font-bold hover:underline"
                                                target="_blank"
                                                href="https://www.google.com/maps?q={{ $event->latitude }},{{ $event->longitude }}">
                                                Open in Google Maps
                                            </a>
                                        </p>
                                    @elseif($event->address)
                                        <p><span class="font-bold">Address:</span> {{ $event->address }}</p>
                                    @else
                                        <p class="text-slate-500 italic">No location provided (permission denied/unavailable).</p>
                                    @endif

                                    @if($event->notes)
                                        <p><span class="font-bold">Notes:</span> {{ $event->notes }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex-shrink-0">
                                <form method="POST" action="{{ route('admin.sos.resolve', $event) }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full md:w-auto px-5 py-3 rounded-xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition-all">
                                        Mark Resolved
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- DETAILED STATS GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        
        <!-- User Activity -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all group">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-50">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-800">User Activity</h2>
                    <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">Platform Growth</p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="text-center p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="text-2xl font-extrabold text-slate-800">{{ $statsUser['new_today'] }}</div>
                    <div class="text-xs font-bold text-slate-500 uppercase">New Today</div>
                </div>
                <div class="text-center p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="text-2xl font-extrabold text-blue-600">{{ $statsUser['active_30d'] }}</div>
                    <div class="text-xs font-bold text-slate-500 uppercase">Active (30d)</div>
                </div>
                <div class="text-center p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="text-2xl font-extrabold text-slate-400">{{ $statsUser['blocked'] }}</div>
                    <div class="text-xs font-bold text-slate-400 uppercase">Blocked</div>
                </div>
            </div>
        </div>

        <!-- Job Platform -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all group">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-50">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Job Platform</h2>
                    <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">Employment Stats</p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="text-center p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="text-2xl font-extrabold text-slate-800">{{ $statsJobs['posted_today'] }}</div>
                    <div class="text-xs font-bold text-slate-500 uppercase">New Jobs</div>
                </div>
                <div class="text-center p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="text-2xl font-extrabold text-indigo-600">{{ $statsJobs['active_total'] }}</div>
                    <div class="text-xs font-bold text-slate-500 uppercase">Active</div>
                </div>
                <div class="text-center p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="text-2xl font-extrabold text-slate-800">{{ $statsJobs['apps_today'] }}</div>
                    <div class="text-xs font-bold text-slate-500 uppercase">Applications</div>
                </div>
            </div>
        </div>

        <!-- Learning Hub -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all group">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-50">
                <div class="w-10 h-10 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600 group-hover:bg-teal-600 group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Learning Hub</h2>
                    <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">Education Metrics</p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="text-center p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="text-2xl font-extrabold text-teal-600">{{ $statsLearning['courses_active'] }}</div>
                    <div class="text-xs font-bold text-slate-500 uppercase">Courses</div>
                </div>
                <div class="text-center p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="text-2xl font-extrabold text-slate-800">{{ $statsLearning['enrolled_total'] }}</div>
                    <div class="text-xs font-bold text-slate-500 uppercase">Students</div>
                </div>
                <div class="text-center p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="text-2xl font-extrabold text-slate-400">{{ $statsLearning['certs_issued'] }}</div>
                    <div class="text-xs font-bold text-slate-400 uppercase">Certificates</div>
                </div>
            </div>
        </div>

        <!-- Community & Safety -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all group">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-50">
                <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Community & Safety</h2>
                    <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">Moderation Status</p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="text-center p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="text-2xl font-extrabold text-slate-800">{{ $statsCommunity['posts_today'] }}</div>
                    <div class="text-xs font-bold text-slate-500 uppercase">New Posts</div>
                </div>
                <div class="text-center p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="text-2xl font-extrabold text-orange-600">{{ $statsCommunity['reports_pending'] }}</div>
                    <div class="text-xs font-bold text-slate-500 uppercase">Reports</div>
                </div>
                <div class="text-center p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="text-2xl font-extrabold text-slate-400">{{ $statsCommunity['banned_users'] }}</div>
                    <div class="text-xs font-bold text-slate-400 uppercase">Banned</div>
                </div>
            </div>
        </div>

    </div>
@endsection
