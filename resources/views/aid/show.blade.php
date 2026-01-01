@extends('layouts.app')

<!-- F20 - Akida Lisi -->

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('aid.index') }}" class="text-sm font-bold text-slate-600 hover:text-slate-900">← Back to directory</a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
            <div class="min-w-0">
                <h1 class="text-3xl font-extrabold text-slate-900">{{ $program->title }}</h1>
                <p class="text-slate-600 mt-2">
                    @if($program->agency)
                        <span class="font-bold">{{ $program->agency }}</span>
                    @endif
                    @if($program->category)
                        <span class="mx-2 text-slate-300">|</span>
                        <span>{{ $program->category }}</span>
                    @endif
                    @if($program->region)
                        <span class="mx-2 text-slate-300">|</span>
                        <span>{{ $program->region }}</span>
                    @endif
                </p>
            </div>

            <div class="flex-shrink-0">
                @if($program->application_url)
                    <a href="{{ $program->application_url }}" target="_blank" rel="noopener"
                       class="inline-flex items-center px-5 py-3 rounded-xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition-all">
                        Apply / Learn more
                    </a>
                @endif
            </div>
        </div>

        @if($program->summary)
            <div class="mt-6">
                <h2 class="text-sm font-bold text-slate-500 uppercase tracking-wide">Summary</h2>
                <p class="text-slate-800 mt-2 whitespace-pre-line">{{ $program->summary }}</p>
            </div>
        @endif

        @if($program->eligibility)
            <div class="mt-6">
                <h2 class="text-sm font-bold text-slate-500 uppercase tracking-wide">Eligibility</h2>
                <p class="text-slate-800 mt-2 whitespace-pre-line">{{ $program->eligibility }}</p>
            </div>
        @endif

        @if($program->benefits)
            <div class="mt-6">
                <h2 class="text-sm font-bold text-slate-500 uppercase tracking-wide">Benefits</h2>
                <p class="text-slate-800 mt-2 whitespace-pre-line">{{ $program->benefits }}</p>
            </div>
        @endif

        @if($program->how_to_apply)
            <div class="mt-6">
                <h2 class="text-sm font-bold text-slate-500 uppercase tracking-wide">How to apply</h2>
                <p class="text-slate-800 mt-2 whitespace-pre-line">{{ $program->how_to_apply }}</p>
            </div>
        @endif

        @if($program->contact_phone || $program->contact_email)
            <div class="mt-6">
                <h2 class="text-sm font-bold text-slate-500 uppercase tracking-wide">Contact</h2>
                <div class="mt-2 text-slate-800 space-y-1">
                    @if($program->contact_phone)
                        <p><span class="font-bold">Phone:</span> {{ $program->contact_phone }}</p>
                    @endif
                    @if($program->contact_email)
                        <p><span class="font-bold">Email:</span> {{ $program->contact_email }}</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
