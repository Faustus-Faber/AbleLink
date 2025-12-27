@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-extrabold text-slate-900 mb-4">Community Hub</h1>
        <p class="text-xl text-slate-600 max-w-2xl mx-auto">Connect, engage, and grow with the AbleLink community. Choose where you'd like to go.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
        <!-- Community Forum -->
        <a href="{{ route('forum.index') }}" class="group bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all p-8 flex flex-col items-center text-center border border-slate-100 hover:border-orange-200">
            <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center text-orange-600 mb-6 group-hover:scale-110 transition-transform">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 mb-3 group-hover:text-orange-600 transition-colors">Discussion Forum</h2>
            <p class="text-slate-600">Join conversations, ask questions, and share your experiences with others.</p>
            <span class="mt-6 text-orange-600 font-semibold group-hover:underline">Enter Forum &rarr;</span>
        </a>

        <!-- Events -->
        <a href="{{ route('community.events.index') }}" class="group bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all p-8 flex flex-col items-center text-center border border-slate-100 hover:border-blue-200">
            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 mb-6 group-hover:scale-110 transition-transform">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 mb-3 group-hover:text-blue-600 transition-colors">Events & Meetups</h2>
            <p class="text-slate-600">Discover online and offline events, workshops, and social gatherings.</p>
            <span class="mt-6 text-blue-600 font-semibold group-hover:underline">Browse Events &rarr;</span>
        </a>

        <!-- Matrimony -->
        <a href="{{ route('community.matrimony.index') }}" class="group bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all p-8 flex flex-col items-center text-center border border-slate-100 hover:border-pink-200">
            <div class="w-20 h-20 bg-pink-100 rounded-full flex items-center justify-center text-pink-600 mb-6 group-hover:scale-110 transition-transform">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 mb-3 group-hover:text-pink-600 transition-colors">Matrimony</h2>
            <p class="text-slate-600">Find your life partner in a safe and inclusive environment.</p>
            <span class="mt-6 text-pink-600 font-semibold group-hover:underline">Find Partner &rarr;</span>
        </a>
    </div>
</div>
@endsection
