@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 font-sans">
    
    {{-- Header --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl pt-16 pb-12">
        <a href="{{ route('user.assistance.index') }}" class="inline-flex items-center text-sm font-bold text-slate-400 hover:text-indigo-600 transition-colors mb-6">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Requests
        </a>
        <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight">
            Request Assistance
        </h1>
        <p class="text-slate-500 mt-4 text-lg">
            Connect with our volunteer network for support with daily tasks.
        </p>
    </div>

    {{-- Form Section --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl pb-20">
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 md:p-12">
            <form action="{{ route('user.assistance.store') }}" method="POST" class="space-y-10">
                @csrf

                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-bold text-red-800">
                                    There were errors with your submission
                                </h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Request Title -->
                <div class="space-y-3">
                    <label for="title" class="block text-xs font-bold text-slate-900 uppercase tracking-widest">
                        Request Title
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                           class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-900 placeholder-slate-400 @error('title') border-red-500 @enderror" 
                           placeholder="e.g., Need help reading documents" required>
                    @error('title')
                        <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="space-y-3">
                    <label for="description" class="block text-xs font-bold text-slate-900 uppercase tracking-widest">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="5" 
                              class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-600 placeholder-slate-400 leading-relaxed @error('description') border-red-500 @enderror" 
                              placeholder="Describe specifically what you need help with... (Markdown supported)" required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Type of Assistance --}}
                    <div class="space-y-3">
                        <label for="type" class="block text-xs font-bold text-slate-900 uppercase tracking-widest">
                            Type of Assistance
                        </label>
                        {{-- Hybrid Dropdown: Hidden native select (AI) + Premium visual dropdown --}}
                        <div class="relative" x-data="{
                            selected: null,
                            open: false,
                            options: {
                                'transportation': 'Transportation / Mobility',
                                'companionship': 'Companionship / Guidance',
                                'errands': 'Errands / Shopping',
                                'technical_support': 'Technical / Reading Support',
                                'medical_assistance': 'Medical Assistance',
                                'other': 'Other'
                            },
                            select(key) {
                                this.selected = key;
                                this.open = false;
                                document.getElementById('type').value = key;
                                document.getElementById('type').dispatchEvent(new Event('change', { bubbles: true }));
                            },
                            init() {
                                this.selected = '{{ old('type') }}' || Object.keys(this.options)[0];
                                document.getElementById('type').addEventListener('change', (e) => {
                                    this.selected = e.target.value;
                                });
                            }
                        }">
                            {{-- Hidden Native Select (AI-Compatible) --}}
                            <select name="type" id="type" class="sr-only" tabindex="-1" aria-hidden="true">
                                <option value="transportation" {{ old('type', 'transportation') == 'transportation' ? 'selected' : '' }}>Transportation / Mobility</option>
                                <option value="companionship" {{ old('type') == 'companionship' ? 'selected' : '' }}>Companionship / Guidance</option>
                                <option value="errands" {{ old('type') == 'errands' ? 'selected' : '' }}>Errands / Shopping</option>
                                <option value="technical_support" {{ old('type') == 'technical_support' ? 'selected' : '' }}>Technical / Reading Support</option>
                                <option value="medical_assistance" {{ old('type') == 'medical_assistance' ? 'selected' : '' }}>Medical Assistance</option>
                                <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            
                            {{-- Premium Visual Dropdown --}}
                            <button type="button" 
                                    @click="open = !open"
                                    @click.away="open = false"
                                    class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:bg-white focus:border-indigo-500 transition-all font-bold text-slate-700 flex items-center justify-between group hover:bg-slate-100 @error('type') border-red-500 @enderror">
                                <span x-text="options[selected] || 'Select Option'"></span>
                                <svg class="w-5 h-5 text-slate-400 group-hover:text-indigo-500 transition-transform duration-200" 
                                     :class="{'rotate-180': open}"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute z-10 mt-2 w-full bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden ring-1 ring-black ring-opacity-5"
                                 style="display: none;">
                                <div class="max-h-60 overflow-y-auto py-1">
                                    <template x-for="(label, key) in options" :key="key">
                                        <button type="button"
                                                @click="select(key)"
                                                class="w-full text-left px-6 py-3 text-sm font-bold text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors flex items-center justify-between group">
                                            <span x-text="label"></span>
                                            <svg x-show="selected === key" class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                        @error('type')
                            <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Urgency Level --}}
                    <div class="space-y-3">
                        <label for="urgency" class="block text-xs font-bold text-slate-900 uppercase tracking-widest">
                            Urgency Level
                        </label>
                        {{-- Hybrid Dropdown: Hidden native select (AI) + Premium visual dropdown --}}
                        <div class="relative" x-data="{
                            selected: null,
                            open: false,
                            options: {
                                'low': 'Low (Flexible)',
                                'medium': 'Medium',
                                'high': 'High (Ideally today)',
                                'emergency': 'Emergency (Immediate)'
                            },
                            select(key) {
                                this.selected = key;
                                this.open = false;
                                document.getElementById('urgency').value = key;
                                document.getElementById('urgency').dispatchEvent(new Event('change', { bubbles: true }));
                            },
                            init() {
                                this.selected = '{{ old('urgency') }}' || Object.keys(this.options)[0];
                                document.getElementById('urgency').addEventListener('change', (e) => {
                                    this.selected = e.target.value;
                                });
                            }
                        }">
                            {{-- Hidden Native Select (AI-Compatible) --}}
                            <select name="urgency" id="urgency" class="sr-only" tabindex="-1" aria-hidden="true">
                                <option value="low" {{ old('urgency', 'low') == 'low' ? 'selected' : '' }}>Low (Flexible)</option>
                                <option value="medium" {{ old('urgency') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('urgency') == 'high' ? 'selected' : '' }}>High (Ideally today)</option>
                                <option value="emergency" {{ old('urgency') == 'emergency' ? 'selected' : '' }}>Emergency (Immediate)</option>
                            </select>
                            
                            {{-- Premium Visual Dropdown --}}
                            <button type="button" 
                                    @click="open = !open"
                                    @click.away="open = false"
                                    class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-2 border-transparent focus:bg-white focus:border-indigo-500 transition-all font-bold text-slate-700 flex items-center justify-between group hover:bg-slate-100 @error('urgency') border-red-500 @enderror">
                                <span x-text="options[selected] || 'Select Priority'"></span>
                                <svg class="w-5 h-5 text-slate-400 group-hover:text-indigo-500 transition-transform duration-200" 
                                     :class="{'rotate-180': open}"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute z-10 mt-2 w-full bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden ring-1 ring-black ring-opacity-5"
                                 style="display: none;">
                                <div class="py-1">
                                    <template x-for="(label, key) in options" :key="key">
                                        <button type="button"
                                                @click="select(key)"
                                                class="w-full text-left px-6 py-3 text-sm font-bold text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors flex items-center justify-between group">
                                            <span x-text="label"></span>
                                            <svg x-show="selected === key" class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                        @error('urgency')
                            <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Location -->
                    <div class="space-y-3">
                        <label for="location" class="block text-xs font-bold text-slate-900 uppercase tracking-widest">
                            Location
                        </label>
                        <input type="text" name="location" id="location" value="{{ old('location') }}"
                               class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-900 placeholder-slate-400 @error('location') border-red-500 @enderror" 
                               placeholder="e.g., 123 Main St, Apt 4B" required>
                        @error('location')
                            <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date/Time -->
                    <div class="space-y-3">
                        <label for="preferred_date_time" class="block text-xs font-bold text-slate-900 uppercase tracking-widest">
                            When do you need help?
                        </label>
                        <input type="datetime-local" name="preferred_date_time" id="preferred_date_time" value="{{ old('preferred_date_time') }}"
                               class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-900 placeholder-slate-400 @error('preferred_date_time') border-red-500 @enderror" required>
                        @error('preferred_date_time')
                            <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Special Requirements -->
                <div class="space-y-3">
                    <label for="special_requirements" class="block text-xs font-bold text-slate-900 uppercase tracking-widest">
                        Any Special Requirements? (Optional)
                    </label>
                    <textarea name="special_requirements" id="special_requirements" rows="3" 
                              class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-600 placeholder-slate-400 leading-relaxed @error('special_requirements') border-red-500 @enderror" 
                              placeholder="e.g., Need a volunteer who speaks Spanish">{{ old('special_requirements') }}</textarea>
                     @error('special_requirements')
                        <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-8 flex justify-end items-center gap-6">
                    <a href="{{ route('user.assistance.index') }}" class="text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors">Cancel</a>
                    <button type="submit" 
                            class="px-8 py-4 rounded-xl bg-slate-900 text-white font-bold shadow-xl shadow-slate-900/20 hover:shadow-2xl hover:bg-slate-800 hover:scale-[1.02] transition-all duration-300">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
