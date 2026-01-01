@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 font-sans">
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl pt-12 pb-8">
        <a href="{{ route('employer.profile.show') }}" class="inline-flex items-center text-sm font-bold text-slate-400 hover:text-indigo-600 transition-colors mb-6">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Profile
        </a>
        <h1 class="text-4xl font-black text-slate-900 tracking-tight leading-tight">
            Edit Company Profile
        </h1>
        <p class="text-slate-500 mt-3 text-lg font-medium">
            Update your company's accessibility information
        </p>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl pb-20">
        <form action="{{ route('employer.profile.update') }}" method="POST" class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 md:p-12 space-y-12">
            @csrf
            @method('PUT')

            <div class="space-y-8">
                <h2 class="text-xl font-black text-slate-900 border-b border-slate-100 pb-4">Company Information</h2>
                
                <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest">Company Name *</label>
                    <input type="text" name="company_name" value="{{ old('company_name', $profile->company_name) }}" required
                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-900 placeholder-slate-400">
                    @error('company_name') <p class="text-red-500 text-sm font-bold pl-2">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest">Company Description</label>
                    <textarea name="company_description" rows="5"
                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-600 placeholder-slate-400 leading-relaxed">{{ old('company_description', $profile->company_description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest">Website</label>
                        <input type="url" name="website" value="{{ old('website', $profile->website) }}" placeholder="https://example.com"
                            class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-900 placeholder-slate-400">
                    </div>
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $profile->phone) }}"
                            class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-900 placeholder-slate-400">
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest">Address</label>
                    <textarea name="address" rows="2"
                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-600 placeholder-slate-400">{{ old('address', $profile->address) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest">Industry</label>
                        <input type="text" name="industry" value="{{ old('industry', $profile->industry) }}"
                            class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-900 placeholder-slate-400">
                    </div>
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest">Company Size</label>
                        <input type="number" name="company_size" value="{{ old('company_size', $profile->company_size) }}" min="1"
                            class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-900 placeholder-slate-400">
                    </div>
                </div>
            </div>

            <div class="space-y-8">
                <h2 class="text-xl font-black text-slate-900 border-b border-slate-100 pb-4">Accessibility Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                     @foreach(['wheelchair_accessible_office' => 'Wheelchair Accessible Office', 'sign_language_available' => 'Sign Language Available', 'assistive_technology_support' => 'Assistive Technology Support'] as $field => $label)
                        <label class="flex items-center p-4 bg-slate-50 rounded-xl hover:bg-slate-100 cursor-pointer transition-all border border-transparent hover:border-slate-200">
                            <input type="checkbox" name="{{ $field }}" value="1" {{ old($field, $profile->$field) ? 'checked' : '' }}
                                class="w-5 h-5 text-indigo-600 rounded-md border-slate-300 focus:ring-indigo-500 bg-white">
                            <span class="ml-3 font-bold text-slate-700">{{ $label }}</span>
                        </label>
                     @endforeach
                </div>

                <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest">Accessibility Accommodations</label>
                    <textarea name="accessibility_accommodations" rows="4"
                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-600 placeholder-slate-400"
                        placeholder="Describe accessibility accommodations available at your company...">{{ old('accessibility_accommodations', $profile->accessibility_accommodations) }}</textarea>
                </div>

                <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest">Inclusive Hiring Practices</label>
                    <textarea name="inclusive_hiring_practices" rows="4"
                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-600 placeholder-slate-400"
                        placeholder="Describe your inclusive hiring practices...">{{ old('inclusive_hiring_practices', $profile->inclusive_hiring_practices) }}</textarea>
                </div>
            </div>

            <div class="flex justify-end items-center gap-6 pt-4">
                <a href="{{ route('employer.profile.show') }}" class="text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors">Cancel</a>
                <button type="submit" 
                        class="px-8 py-4 rounded-xl bg-indigo-600 text-white font-bold shadow-xl shadow-indigo-600/20 hover:shadow-2xl hover:bg-indigo-700 hover:scale-[1.02] transition-all duration-300">
                    Update Profile
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
