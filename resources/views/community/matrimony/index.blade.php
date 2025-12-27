@extends('layouts.app')


@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Hero Section -->
    <div class="text-center max-w-3xl mx-auto mb-12">
        <h1 class="text-4xl font-extrabold text-rose-950 tracking-tight mb-4">Find Your Life Partner</h1>
        <p class="text-lg text-rose-700/80 mb-8">Connect with meaningful people and start your journey together.</p>
        
        @if($myProfile)
            <a href="{{ route('community.matrimony.edit', $myProfile) }}" class="inline-flex items-center px-6 py-3 rounded-full bg-rose-900 text-white font-bold hover:bg-rose-800 transition-all shadow-lg hover:shadow-xl">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                Edit My Profile
            </a>
        @else
            <a href="{{ route('community.matrimony.create') }}" class="inline-flex items-center px-6 py-3 rounded-full bg-rose-600 text-white font-bold hover:bg-rose-700 transition-all shadow-lg hover:shadow-rose-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Create Profile
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="max-w-4xl mx-auto bg-green-50 text-green-700 p-4 rounded-2xl border border-green-100 mb-8 text-center font-bold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Modern Search Bar -->
    <div class="max-w-5xl mx-auto mb-16 bg-white rounded-3xl shadow-lg border border-rose-100 p-4 relative">
        <div class="absolute inset-0 overflow-hidden rounded-3xl pointer-events-none">
            <div class="absolute top-0 right-0 w-32 h-32 bg-rose-50 rounded-full blur-3xl -mr-16 -mt-16"></div>
        </div>
        <form action="{{ route('community.matrimony.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 relative z-10">
            <div class="flex-grow">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-rose-300 group-focus-within:text-rose-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        class="w-full pl-11 pr-4 py-3 bg-rose-50/50 border-0 rounded-xl focus:ring-2 focus:ring-rose-200 text-rose-950 font-medium placeholder-rose-300 transition-all"
                        placeholder="Search by name, occupation...">
                </div>
            </div>
            
            <div class="flex gap-4 flex-col sm:flex-row">
                <div class="relative min-w-[150px]" x-data="{
                    gender: '{{ request('gender') }}',
                    open: false,
                    options: {
                        '': 'Any Gender',
                        'Male': 'Male',
                        'Female': 'Female'
                    }
                }">
                    <input type="hidden" name="gender" :value="gender">
                    
                    <button type="button" 
                            @click="open = !open"
                            @click.away="open = false"
                            @click.away="open = false"
                            class="w-full px-4 py-3 bg-rose-50/50 border-0 rounded-xl hover:bg-rose-100 transition-colors text-rose-950 font-medium text-left flex items-center justify-between group">
                        <span x-text="options[gender] || 'Any Gender'"></span>
                        <div class="text-rose-400 group-hover:text-rose-600 transition-colors">
                            <svg class="w-4 h-4 transition-transform duration-200" 
                                 :class="{'rotate-180': open}"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>

                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute z-10 mt-2 w-full bg-white rounded-xl shadow-xl border border-rose-100 overflow-hidden ring-1 ring-black ring-opacity-5"
                         style="display: none;">
                        <div class="py-1">
                            <template x-for="(label, key) in options" :key="key">
                                <button type="button"
                                        @click="gender = key; open = false"
                                        class="w-full text-left px-4 py-2.5 text-sm font-medium text-rose-900 hover:bg-rose-50 hover:text-rose-700 transition-colors flex items-center justify-between">
                                    <span x-text="label"></span>
                                    <svg x-show="gender == key" class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="flex gap-2 items-center">
                    <input type="number" name="age_min" placeholder="Min Age" value="{{ request('age_min') }}" class="w-28 px-4 py-3 bg-rose-50/50 border-0 rounded-xl focus:ring-2 focus:ring-rose-200 text-rose-950 font-medium placeholder-rose-300">
                    <span class="text-rose-200 font-bold">-</span>
                    <input type="number" name="age_max" placeholder="Max Age" value="{{ request('age_max') }}" class="w-28 px-4 py-3 bg-rose-50/50 border-0 rounded-xl focus:ring-2 focus:ring-rose-200 text-rose-950 font-medium placeholder-rose-300">
                </div>
            </div>

            <button type="submit" class="bg-rose-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-rose-700 transition-all shadow-md hover:shadow-rose-200">
                Search
            </button>
            
            @if(request()->anyFilled(['search', 'gender', 'age_min', 'age_max']))
                <a href="{{ route('community.matrimony.index') }}" class="flex items-center justify-center px-4 py-3 rounded-xl bg-rose-50 text-rose-600 font-bold hover:bg-rose-100 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </a>
            @endif
        </form>
    </div>

    <!-- Profiles Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($profiles as $profile)
            <div class="group bg-white rounded-3xl shadow-sm border border-rose-100 overflow-hidden hover:shadow-xl hover:shadow-rose-100/50 hover:-translate-y-1 transition-all duration-300">
                <div class="h-32 bg-gradient-to-br from-rose-50 to-pink-50 relative">
                    <!-- Colored Badge -->
                   <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-rose-600 shadow-sm border border-rose-100">
                        {{ $profile->gender }}
                    </div>
                </div>
                
                <div class="px-6 pb-8 relative">
                    <!-- Avatar -->
                    <div class="w-24 h-24 mx-auto -mt-12 rounded-full border-[5px] border-white shadow-md bg-white overflow-hidden flex items-center justify-center">
                         @if($profile->photo_path)
                            <img src="{{ asset('storage/' . $profile->photo_path) }}" alt="{{ $profile->user?->name }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-3xl font-extrabold text-rose-200 select-none">
                                {{ substr($profile->user?->name ?? 'U', 0, 1) }}
                            </span>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="text-center mt-4">
                        <h2 class="text-xl font-extrabold text-rose-950 mb-1 group-hover:text-rose-600 transition-colors">
                            {{ $profile->user?->name ?? 'Unknown User' }}
                            @if(auth()->id() === $profile->user_id)
                                <span class="bg-rose-100 text-rose-700 text-[10px] px-2 py-0.5 rounded-full align-middle ml-1 uppercase tracking-wide">You</span>
                            @endif
                        </h2>
                        <p class="text-sm font-bold text-rose-500 mb-4 uppercase tracking-wide text-xs">{{ $profile->occupation ?? 'Occupation N/A' }}</p>
                        
                        <p class="text-rose-900/60 text-sm mb-6 line-clamp-2 px-2 leading-relaxed">
                            {{ $profile->bio ?? 'No bio provided yet.' }}
                        </p>

                        <!-- Info Pills -->
                        <div class="flex flex-wrap justify-center gap-2 mb-8">
                            <span class="px-3 py-1 rounded-lg bg-rose-50 text-rose-700 text-xs font-bold border border-rose-100">{{ $profile->age }} Years</span>
                            <span class="px-3 py-1 rounded-lg bg-rose-50 text-rose-700 text-xs font-bold border border-rose-100">{{ $profile->religion ?? 'N/A' }}</span>
                            <span class="px-3 py-1 rounded-lg bg-rose-50 text-rose-700 text-xs font-bold border border-rose-100">{{ $profile->marital_status ?? 'N/A' }}</span>
                        </div>

                        <a href="{{ route('community.matrimony.show', $profile) }}" class="inline-flex items-center text-sm font-bold text-rose-950 hover:text-rose-600 transition-colors">
                            View Full Profile 
                            <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center">
                <div class="w-20 h-20 bg-rose-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-rose-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-rose-950 mb-2">No profiles found</h3>
                <p class="text-rose-900/40 max-w-sm mx-auto mb-8">Try adjusting your filters or search criteria to find who you are looking for.</p>
                <a href="{{ route('community.matrimony.index') }}" class="text-rose-600 font-bold hover:underline">Clear all filters</a>
            </div>
        @endforelse
    </div>

    <div class="mt-12">
        {{ $profiles->links() }}
    </div>
</div>
@endsection
