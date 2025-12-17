@extends('layouts.app')

@section('content')
{{-- F9 - Evan Yuvraj Munshi --}}
<div class="min-h-screen bg-zinc-50 py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-10 gap-4">
            <div>
                <h1 class="text-4xl font-extrabold text-blue-900 tracking-tight">My Applications</h1>
                <p class="text-slate-500 mt-2 text-lg">Track the status of your job applications</p>
            </div>
            <a href="{{ route('jobs.index') }}" class="flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-blue-900/10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                Find More Jobs
            </a>
        </div>

        @if($applications->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-zinc-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-zinc-100">
                                <th class="px-8 py-5 font-bold text-slate-700 text-sm uppercase tracking-wider">Job Title</th>
                                <th class="px-8 py-5 font-bold text-slate-700 text-sm uppercase tracking-wider">Employer</th>
                                <th class="px-8 py-5 font-bold text-slate-700 text-sm uppercase tracking-wider">Applied Date</th>
                                <th class="px-8 py-5 font-bold text-slate-700 text-sm uppercase tracking-wider">Status</th>
                                <th class="px-8 py-5 font-bold text-slate-700 text-sm uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100">
                            @foreach($applications as $application)
                            <tr class="hover:bg-blue-50/30 transition-colors group">
                                <td class="px-8 py-6">
                                    <a href="{{ route('jobs.show', $application->job) }}" class="font-bold text-blue-900 hover:text-blue-700 text-lg transition-colors">
                                        {{ $application->job->title }}
                                    </a>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center text-slate-600 font-medium">
                                        <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        {{ $application->job->employer->name ?? 'Confidential' }}
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-slate-500 font-medium">
                                    {{ $application->applied_at->format('M d, Y') }}
                                </td>
                                <td class="px-8 py-6">
                                    @php
                                        $statusStyles = [
                                            'pending' => 'bg-amber-50 text-amber-700 border-amber-100',
                                            'reviewing' => 'bg-blue-50 text-blue-700 border-blue-100',
                                            'shortlisted' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                            'interviewed' => 'bg-purple-50 text-purple-700 border-purple-100',
                                            'accepted' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                            'rejected' => 'bg-rose-50 text-rose-700 border-rose-100',
                                        ];
                                        $style = $statusStyles[$application->status] ?? 'bg-slate-50 text-slate-700 border-slate-100';
                                    @endphp
                                    <span class="px-3 py-1.5 rounded-lg border text-xs font-bold uppercase tracking-wide {{ $style }}">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <a href="{{ route('jobs.show', $application->job) }}" class="text-sm font-bold text-blue-600 hover:text-blue-800 hover:underline decoration-2 underline-offset-4 decoration-blue-200 transition-all">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="px-8 py-6 border-t border-zinc-100 bg-slate-50/50">
                    {{ $applications->links() }}
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-sm border border-zinc-200 p-16 text-center">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-2">No Applications Yet</h3>
                <p class="text-slate-500 mb-8 max-w-sm mx-auto">You haven't applied to any jobs yet. Start your search today and take the next step in your career!</p>
                <a href="{{ route('jobs.index') }}" class="inline-flex items-center px-8 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold transition-all shadow-lg shadow-blue-900/10">
                    Browse Jobs
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
