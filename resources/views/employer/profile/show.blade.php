@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 font-sans">
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl pt-12 pb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">
                    Company Profile
                </h1>
                <p class="text-slate-500 mt-2 font-medium">
                     Your company's accessibility and accommodation information
                </p>
            </div>
            
            <a href="{{ route('employer.profile.edit') }}" 
               class="inline-flex items-center px-8 py-3 rounded-full bg-indigo-600 text-white font-bold shadow-lg hover:bg-indigo-700 hover:shadow-indigo-600/30 transition-all transform hover:-translate-y-0.5">
                Edit Profile
            </a>
        </div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl pb-20">
         @if (session('success'))
            <div class="mb-8 bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-2xl text-sm font-bold shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 md:p-12">
            <h2 class="text-3xl font-black text-slate-900 mb-8 border-b border-slate-100 pb-6">{{ $profile->company_name }}</h2>

            @if ($profile->company_description)
                <div class="mb-10">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Company Description</h3>
                    <p class="text-slate-600 leading-relaxed font-medium text-lg text-justify">{{ $profile->company_description }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8 mb-10">
                @if ($profile->website)
                    <div>
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Website</h3>
                        <a href="{{ $profile->website }}" target="_blank" class="text-indigo-600 font-bold hover:underline flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            {{ $profile->website }}
                        </a>
                    </div>
                @endif
                @if ($profile->phone)
                    <div>
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Phone</h3>
                        <p class="text-slate-900 font-bold">{{ $profile->phone }}</p>
                    </div>
                @endif
                @if ($profile->address)
                    <div>
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Address</h3>
                        <p class="text-slate-900 font-bold">{{ $profile->address }}</p>
                    </div>
                @endif
                @if ($profile->industry)
                    <div>
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Industry</h3>
                        <p class="text-slate-900 font-bold">{{ $profile->industry }}</p>
                    </div>
                @endif
                @if ($profile->company_size)
                    <div>
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Company Size</h3>
                        <p class="text-slate-900 font-bold">{{ number_format($profile->company_size) }} employees</p>
                    </div>
                @endif
            </div>

            <div class="mb-10 p-8 bg-indigo-50/30 rounded-[2rem] border border-indigo-50">
                <h3 class="text-xl font-black text-indigo-900 mb-6">Accessibility Features</h3>
                <div class="flex flex-wrap gap-3">
                    @if ($profile->wheelchair_accessible_office)
                        <span class="inline-flex items-center px-4 py-2 rounded-xl bg-white border border-indigo-100 text-indigo-700 font-bold shadow-sm">
                             <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Wheelchair Accessible Office
                        </span>
                    @endif
                    @if ($profile->sign_language_available)
                        <span class="inline-flex items-center px-4 py-2 rounded-xl bg-white border border-indigo-100 text-indigo-700 font-bold shadow-sm">
                             <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Sign Language Available
                        </span>
                    @endif
                    @if ($profile->assistive_technology_support)
                        <span class="inline-flex items-center px-4 py-2 rounded-xl bg-white border border-indigo-100 text-indigo-700 font-bold shadow-sm">
                             <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Assistive Technology Support
                        </span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @if ($profile->accessibility_accommodations)
                    <div>
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Accessibility Accommodations</h3>
                        <p class="text-slate-600 leading-relaxed font-medium bg-slate-50 p-6 rounded-2xl border border-slate-100 min-h-[100px]">
                            {{ $profile->accessibility_accommodations }}
                        </p>
                    </div>
                @endif

                @if ($profile->inclusive_hiring_practices)
                    <div>
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Inclusive Hiring Practices</h3>
                        <p class="text-slate-600 leading-relaxed font-medium bg-slate-50 p-6 rounded-2xl border border-slate-100 min-h-[100px]">
                            {{ $profile->inclusive_hiring_practices }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
