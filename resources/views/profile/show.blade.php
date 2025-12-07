@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white font-sans selection:bg-black selection:text-white">
    <div class="container mx-auto px-6 py-12 max-w-7xl">
        
        @if(session('success') && !session('sos_success'))
            <div class="mb-8 rounded-2xl border-l-4 border-emerald-500 bg-emerald-50 px-8 py-6">
                <div class="flex items-center gap-3 text-emerald-900 font-bold text-lg">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            
            <div class="lg:col-span-4 sticky top-24">
                <div class="bg-zinc-50 rounded-[2.5rem] p-10 border border-zinc-100 text-center relative overflow-hidden group">
                    <div class="relative z-10 flex flex-col items-center">
                        <div class="relative mb-6">
                            <div class="w-40 h-40 rounded-full p-2 bg-white border border-zinc-200 shadow-xl shadow-zinc-200/50">
                                @if($user->profile && $user->profile->avatar)
                                    <img src="{{ asset('storage/' . $user->profile->avatar) }}" alt="{{ $user->name }}" class="w-full h-full rounded-full object-cover">
                                @else
                                    <div class="w-full h-full rounded-full bg-zinc-900 flex items-center justify-center text-4xl font-black text-white">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div class="absolute bottom-2 right-2 w-6 h-6 bg-emerald-500 border-4 border-white rounded-full"></div>
                        </div>

                        <h1 class="text-3xl font-black text-zinc-900 tracking-tight mb-2">{{ $user->name }}</h1>
                         <p class="text-zinc-500 font-medium mb-6 flex items-center gap-2 bg-white px-4 py-1 rounded-full border border-zinc-100 shadow-sm text-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            {{ $user->email }}
                        </p>

                        <div class="inline-block px-4 py-1.5 rounded-lg bg-zinc-200 text-zinc-800 text-xs font-black uppercase tracking-widest mb-8">
                            {{ ucfirst($user->role) }}
                        </div>

                        <div class="flex flex-col gap-3 w-full">
                            <a href="{{ route('profile.edit') }}" class="flex items-center justify-center w-full px-6 py-4 rounded-xl bg-zinc-900 text-white font-bold shadow-lg hover:bg-black hover:scale-[1.02] transition-all">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                Edit Profile
                            </a>
                            @if($user->hasRole('disabled'))
                                <a href="{{ route('accessibility.edit') }}" class="flex items-center justify-center w-full px-6 py-4 rounded-xl bg-white border-2 border-zinc-100 text-zinc-900 font-bold hover:border-zinc-300 hover:bg-zinc-50 transition-all">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                    Accessibility
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-8 space-y-8">
                
                <div class="bg-white rounded-[2.5rem] border border-zinc-100 p-10 shadow-sm">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 bg-zinc-100 rounded-2xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-zinc-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <h2 class="text-2xl font-black text-zinc-900 tracking-tight">About Me</h2>
                    </div>

                    @if($user->profile && $user->profile->bio)
                        <p class="text-zinc-600 text-lg leading-relaxed font-medium">{{ $user->profile->bio }}</p>
                    @else
                        <div class="rounded-3xl bg-zinc-50 p-8 border-2 border-dashed border-zinc-200 text-center">
                            <h3 class="font-bold text-zinc-400 mb-2">No biography yet</h3>
                            <a href="{{ route('profile.edit') }}" class="text-zinc-900 font-black underline decoration-zinc-300 decoration-2 underline-offset-4 hover:decoration-zinc-900 transition-all">
                                Add a bio to your profile
                            </a>
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-zinc-50 rounded-3xl p-8 border border-zinc-100 hover:bg-white hover:border-zinc-200 hover:shadow-lg hover:shadow-zinc-200/50 transition-all duration-300 group">
                        <p class="text-xs font-black text-zinc-400 uppercase tracking-widest mb-3">Phone</p>
                        <p class="text-xl font-bold text-zinc-900 group-hover:text-black">
                            {{ $user->profile && $user->profile->phone_number ? $user->profile->phone_number : 'Not provided' }}
                        </p>
                    </div>

                    <div class="bg-zinc-50 rounded-3xl p-8 border border-zinc-100 hover:bg-white hover:border-zinc-200 hover:shadow-lg hover:shadow-zinc-200/50 transition-all duration-300 group">
                        <p class="text-xs font-black text-zinc-400 uppercase tracking-widest mb-3">Date of Birth</p>
                        <p class="text-xl font-bold text-zinc-900 group-hover:text-black">
                             @if($user->profile && $user->profile->date_of_birth)
                                {{ \Carbon\Carbon::parse($user->profile->date_of_birth)->format('F j, Y') }}
                            @else
                                <span class="text-zinc-400 font-normal italic">Not provided</span>
                            @endif
                        </p>
                    </div>

                    <div class="bg-zinc-50 rounded-3xl p-8 border border-zinc-100 hover:bg-white hover:border-zinc-200 hover:shadow-lg hover:shadow-zinc-200/50 transition-all duration-300 group md:col-span-2">
                         <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs font-black text-zinc-400 uppercase tracking-widest mb-3">Address</p>
                                <p class="text-xl font-bold text-zinc-900 group-hover:text-black">
                                    {{ $user->profile && $user->profile->address ? $user->profile->address : 'Not provided' }}
                                </p>
                            </div>
                            <svg class="w-6 h-6 text-zinc-300 group-hover:text-zinc-900 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                    </div>
                    
                     <div class="bg-zinc-50 rounded-3xl p-8 border border-zinc-100 hover:bg-white hover:border-zinc-200 hover:shadow-lg hover:shadow-zinc-200/50 transition-all duration-300 group md:col-span-2">
                        <p class="text-xs font-black text-zinc-400 uppercase tracking-widest mb-3">Disability Type</p>
                        <p class="text-xl font-bold text-zinc-900 group-hover:text-black">
                            {{ $user->profile && $user->profile->disability_type ? $user->profile->disability_type : 'Not specified' }}
                        </p>
                    </div>
                </div>

}
                <div class="bg-rose-50 rounded-[2.5rem] p-10 border border-rose-100 relative overflow-hidden group hover:border-rose-200 transition-colors">
                     <div class="relative z-10">
                        <div class="flex items-center justify-between mb-8">
                             <div>
                                <h3 class="text-2xl font-black text-rose-950 mb-1">Emergency Contact</h3>
                                <p class="text-rose-800/60 font-medium">Primary contact in case of emergency</p>
                            </div>
                            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-rose-500 shadow-sm">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M12 12h.01M12 6h.01M12 12a9 9 0 110-18 9 9 0 010 18z"/></svg>
                            </div>
                        </div>

                         @if($user->profile && $user->profile->emergency_contact_name)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-white p-6 rounded-3xl shadow-sm border border-rose-100">
                                    <p class="text-xs font-bold text-rose-400 uppercase tracking-wider mb-2">Name</p>
                                    <p class="text-lg font-black text-rose-950">{{ $user->profile->emergency_contact_name }}</p>
                                </div>
                                @if($user->profile->emergency_contact_phone)
                                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-rose-100">
                                        <p class="text-xs font-bold text-rose-400 uppercase tracking-wider mb-2">Phone</p>
                                        <p class="text-lg font-black text-rose-950">{{ $user->profile->emergency_contact_phone }}</p>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="bg-white/60 rounded-3xl p-8 border border-dashed border-rose-300 text-center">
                                <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-center px-6 py-3 bg-rose-100 text-rose-700 rounded-xl font-bold hover:bg-rose-200 transition-colors">
                                    Add Emergency Contact
                                </a>
                            </div>
                        @endif
                     </div>
                </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@endsection

