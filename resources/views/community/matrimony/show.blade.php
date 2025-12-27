@extends('layouts.app')



@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Back Button -->
    <a href="{{ route('community.matrimony.index') }}" class="inline-flex items-center text-sm font-bold text-rose-500 hover:text-rose-700 mb-8 transition-colors">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Profiles
    </a>

    <div class="bg-white rounded-3xl shadow-sm border border-rose-100 overflow-hidden relative">
        <!-- Banner -->
        <div class="h-48 bg-gradient-to-br from-rose-50 to-pink-100 border-b border-rose-100 relative overflow-hidden">
             <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(#e11d48 1px, transparent 1px); background-size: 20px 20px;"></div>
             <div class="absolute top-0 right-0 w-64 h-64 bg-white/20 rounded-full blur-3xl -mr-12 -mt-12 pointer-events-none"></div>
        </div>

        <div class="px-8 md:px-12 pb-12 relative">
            <!-- Avatar -->
            <div class="w-40 h-40 mx-auto -mt-20 rounded-full border-[6px] border-white shadow-lg bg-white overflow-hidden flex items-center justify-center relative z-10">
                @if($profile->photo_path)
                    <img src="{{ asset('storage/' . $profile->photo_path) }}" alt="{{ $profile->user?->name ?? 'User' }}" class="w-full h-full object-cover">
                @else
                    <span class="text-5xl font-extrabold text-rose-200 select-none">
                        {{ substr($profile->user?->name ?? 'U', 0, 1) }}
                    </span>
                @endif
            </div>

            <div class="text-center mt-6 mb-10">
                <h1 class="text-4xl font-extrabold text-rose-950 mb-2">{{ $profile->user?->name ?? 'Unknown User' }}</h1>
                <p class="text-xl font-bold text-rose-600 uppercase tracking-wide">{{ $profile->occupation ?? 'Occupation N/A' }}</p>
                
                <div class="flex flex-wrap justify-center gap-3 mt-6">
                    <span class="px-4 py-2 rounded-xl bg-rose-50 text-rose-700 font-bold border border-rose-100 flex items-center">
                        <span class="text-rose-500 mr-2">⚤</span> {{ $profile->gender ?? 'N/A' }}
                    </span>
                    <span class="px-4 py-2 rounded-xl bg-rose-50 text-rose-700 font-bold border border-rose-100 flex items-center">
                        <span class="text-rose-500 mr-2">🎂</span> {{ $profile->age ? $profile->age . ' Years' : 'N/A' }}
                    </span>
                    <span class="px-4 py-2 rounded-xl bg-rose-50 text-rose-700 font-bold border border-rose-100 flex items-center">
                        <span class="text-rose-500 mr-2">💍</span> {{ $profile->marital_status ?? 'N/A' }}
                    </span>
                    <span class="px-4 py-2 rounded-xl bg-rose-50 text-rose-700 font-bold border border-rose-100 flex items-center">
                        <span class="text-rose-500 mr-2">🙏</span> {{ $profile->religion ?? 'N/A' }}
                    </span>
                    <span class="px-4 py-2 rounded-xl bg-rose-50 text-rose-700 font-bold border border-rose-100 flex items-center">
                        <span class="text-rose-500 mr-2">🎓</span> {{ $profile->education ?? 'N/A' }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 border-t border-rose-100 pt-10">
                <div>
                    <h3 class="text-lg font-extrabold text-rose-900 mb-4 flex items-center">
                         <span class="w-8 h-8 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mr-3 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </span>
                        About Me
                    </h3>
                    <div class="prose prose-rose prose-p:text-rose-800/80 leading-relaxed bg-rose-50/50 p-6 rounded-2xl border border-rose-100">
                        {!! nl2br(e($profile->bio ?? 'No bio provided.')) !!}
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-extrabold text-rose-900 mb-4 flex items-center">
                        <span class="w-8 h-8 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mr-3 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        </span>
                        Partner Preferences
                    </h3>
                    <div class="prose prose-rose prose-p:text-rose-800/80 leading-relaxed bg-rose-50/50 p-6 rounded-2xl border border-rose-100">
                        {!! nl2br(e($profile->partner_preferences ?? 'No specific preferences listed.')) !!}
                    </div>
                </div>
            </div>

            @if(auth()->id() !== $profile->user_id)
                <div class="mt-12 flex justify-center">
                    <form action="{{ route('messages.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="recipient_id" value="{{ $profile->user_id }}">
                        <textarea name="body" class="hidden">Hi, I viewed your profile on the Matrimony section and I'm interested in connecting.</textarea>
                        <button type="submit" class="px-10 py-4 rounded-full bg-rose-600 text-white text-lg font-bold hover:bg-rose-700 transition-all shadow-xl hover:shadow-rose-200 flex items-center gap-3 transform hover:-translate-y-1">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                            Send Interest / Message
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
