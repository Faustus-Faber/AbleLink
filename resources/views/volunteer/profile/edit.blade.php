@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 font-sans">
    
    {{-- Header --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl pt-16 pb-12">
        <a href="{{ route('volunteer.profile.show') }}" class="inline-flex items-center text-sm font-bold text-slate-400 hover:text-indigo-600 transition-colors mb-6">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Profile
        </a>
        <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight">
            Edit Profile
        </h1>
        <p class="text-slate-500 mt-4 text-lg">
            Update your public volunteer information.
        </p>
    </div>

    {{-- Form Section --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl pb-20">
        <form action="{{ route('volunteer.profile.update') }}" method="POST" class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 md:p-12 space-y-12">
            @csrf
            @method('PUT')

            {{-- Basic Info --}}
            <div class="space-y-8">
                <h2 class="text-xl font-black text-slate-900 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm">01</span>
                    Basic Information
                </h2>
                
                <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest">Bio</label>
                    <textarea name="bio" rows="4"
                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-600 placeholder-slate-400 leading-relaxed"
                        placeholder="Tell us about yourself...">{{ old('bio', $profile->bio) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest">Location</label>
                        <input type="text" name="location" value="{{ old('location', $profile->location) }}"
                            class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-900 placeholder-slate-400"
                            placeholder="City, State">
                    </div>
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest">Max Distance (km)</label>
                        <input type="number" name="max_distance_km" value="{{ old('max_distance_km', $profile->max_distance_km) }}" min="1" max="100"
                            class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-900 placeholder-slate-400">
                    </div>
                </div>

                <label class="flex items-center p-6 border-2 border-slate-100 rounded-2xl hover:border-indigo-100 hover:bg-indigo-50/30 cursor-pointer transition-all group">
                    <input type="checkbox" name="available_for_emergency" value="1" {{ old('available_for_emergency', $profile->available_for_emergency) ? 'checked' : '' }}
                        class="w-6 h-6 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500 transition-all">
                    <span class="ml-4 font-bold text-slate-700 group-hover:text-indigo-900">Available for Emergency Requests</span>
                </label>
            </div>

            <hr class="border-slate-100">

            {{-- Skills --}}
            <div class="space-y-8">
                <h2 class="text-xl font-black text-slate-900 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm">02</span>
                    Skills
                </h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @php
                        $allSkills = ['transportation', 'companionship', 'errands', 'technical_support', 'medical_assistance', 'household_tasks', 'shopping', 'pet_care', 'childcare'];
                        $selectedSkills = old('skills', $profile->skills ?? []);
                    @endphp
                    @foreach ($allSkills as $skill)
                        <label class="flex items-center p-4 bg-slate-50 rounded-xl hover:bg-slate-100 cursor-pointer transition-all">
                            <input type="checkbox" name="skills[]" value="{{ $skill }}" {{ in_array($skill, $selectedSkills) ? 'checked' : '' }}
                                class="w-5 h-5 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500 bg-white">
                            <span class="ml-3 text-sm font-bold text-slate-600">{{ ucfirst(str_replace('_', ' ', $skill)) }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <hr class="border-slate-100">

            {{-- Availability --}}
            <div class="space-y-8">
                <h2 class="text-xl font-black text-slate-900 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm">03</span>
                    Availability
                </h2>
                
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @php
                        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                        $selectedDays = old('availability', $profile->availability ?? []);
                    @endphp
                    @foreach ($days as $day)
                        <label class="flex items-center p-4 bg-slate-50 rounded-xl hover:bg-slate-100 cursor-pointer transition-all">
                            <input type="checkbox" name="availability[]" value="{{ $day }}" {{ in_array($day, $selectedDays) ? 'checked' : '' }}
                                class="w-5 h-5 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500 bg-white">
                            <span class="ml-3 text-sm font-bold text-slate-600">{{ ucfirst($day) }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <hr class="border-slate-100">

            {{-- Specializations --}}
            <div class="space-y-8">
                <h2 class="text-xl font-black text-slate-900 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm">04</span>
                    Details
                </h2>
                
                <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest">Specializations / Notes</label>
                    <textarea name="specializations" rows="3"
                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-600 placeholder-slate-400 leading-relaxed"
                        placeholder="e.g., Fluent in Spanish, Experience with dementia care...">{{ old('specializations', $profile->specializations) }}</textarea>
                </div>
            </div>

            <div class="flex justify-end items-center gap-6 pt-8">
                <a href="{{ route('volunteer.profile.show') }}" class="text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors">Cancel</a>
                <button type="submit" 
                        class="px-8 py-4 rounded-xl bg-slate-900 text-white font-bold shadow-xl shadow-slate-900/20 hover:shadow-2xl hover:bg-slate-800 hover:scale-[1.02] transition-all duration-300">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
