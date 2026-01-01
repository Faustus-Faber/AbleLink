@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
    
    <nav class="mb-4">
        <a href="{{ route('forum.index') }}" class="inline-flex items-center px-5 py-2.5 bg-white text-slate-600 font-bold rounded-xl border border-slate-200 shadow-sm hover:bg-slate-50 hover:text-slate-900 hover:shadow-md transition-all">
            <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Forum
        </a>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
                <div class="p-6">
                    <div class="mb-6">
                        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-2">Start a Discussion</h1>
                        <p class="text-lg text-slate-500">Ask a question or share your thoughts within the community.</p>
                    </div>

                    <form action="{{ route('forum.store') }}" method="POST" class="space-y-5">
                        @csrf
                        
                        <div x-data="{
                            category: '{{ old('category', 'General') }}',
                            open: false,
                            options: {
                                'General': 'General Discussion',
                                'Employment': 'Employment & Jobs',
                                'Courses': 'Learning & Courses',
                                'Support': 'Support & Advice',
                                'Software': 'Software & Tech'
                            },
                            select(key) {
                                this.category = key;
                                this.open = false;
                                document.getElementById('category').value = key;
                                document.getElementById('category').dispatchEvent(new Event('change', { bubbles: true }));
                            },
                            init() {
                                document.getElementById('category').addEventListener('change', (e) => {
                                    this.category = e.target.value;
                                });
                            }
                        }">
                            <label class="block text-sm font-bold text-slate-900 mb-2 uppercase tracking-wider">Select Category</label>
                            
                            <div class="relative">
                                {{-- Hidden Native Select (AI-Compatible) --}}
                                <select name="category" id="category" class="sr-only" tabindex="-1" aria-hidden="true">
                                    <option value="General" {{ old('category', 'General') == 'General' ? 'selected' : '' }}>General Discussion</option>
                                    <option value="Employment" {{ old('category') == 'Employment' ? 'selected' : '' }}>Employment & Jobs</option>
                                    <option value="Courses" {{ old('category') == 'Courses' ? 'selected' : '' }}>Learning & Courses</option>
                                    <option value="Support" {{ old('category') == 'Support' ? 'selected' : '' }}>Support & Advice</option>
                                    <option value="Software" {{ old('category') == 'Software' ? 'selected' : '' }}>Software & Tech</option>
                                </select>
                                
                                {{-- Premium Visual Dropdown --}}
                                <button type="button" 
                                        @click="open = !open"
                                        @click.away="open = false"
                                        class="w-full px-5 py-3 rounded-2xl bg-slate-50 border border-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-900 flex items-center justify-between group hover:bg-white text-left">
                                    <span x-text="options[category]"></span>
                                    <div class="text-slate-500 group-hover:text-indigo-600 transition-colors">
                                        <svg class="w-5 h-5 transition-transform duration-200" 
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
                                     class="absolute z-10 mt-2 w-full bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden ring-1 ring-black ring-opacity-5"
                                     style="display: none;">
                                    <div class="py-1">
                                        <template x-for="(label, key) in options" :key="key">
                                            <button type="button"
                                                    @click="select(key)"
                                                    class="w-full text-left px-5 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition-colors flex items-center justify-between">
                                                <span x-text="label"></span>
                                                <svg x-show="category == key" class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            @error('category')
                                <p class="text-red-600 text-sm mt-2 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
            
                        <div>
                            <label for="title" class="block text-sm font-bold text-slate-900 mb-2 uppercase tracking-wider">Discussion Title</label>
                            <input type="text" name="title" id="title" class="block w-full rounded-2xl border-slate-400 bg-slate-50 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 sm:text-base py-3 px-5 shadow-sm transition-all" placeholder="e.g., Tips for remote work interviews..." value="{{ old('title') }}" required>
                            @error('title')
                                <p class="text-red-600 text-sm mt-2 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
            
                        <div>
                            <label for="body" class="block text-sm font-bold text-slate-900 mb-2 uppercase tracking-wider">Content</label>
                            <textarea name="body" id="body" rows="6" class="block w-full rounded-2xl border-slate-400 bg-slate-50 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 sm:text-base p-5 shadow-sm transition-all" placeholder="Write your post here... Markdown is supported for formatting." required>{{ old('body') }}</textarea>
                            @error('body')
                                <p class="text-red-600 text-sm mt-2 font-medium">{{ $message }}</p>
                            @enderror
                            <div class="mt-3 flex items-center justify-between">
                                <p class="text-sm text-slate-400 flex items-center">
                                    <svg class="w-4 h-4 mr-1.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    <span class="font-semibold text-indigo-600 mr-1">AI Moderation Enabled</span>
                                </p>
                            </div>
                        </div>
            
                        <div class="pt-4 flex items-center justify-end space-x-5">
                            <a href="{{ route('forum.index') }}" class="px-6 py-3 text-slate-600 font-bold hover:text-slate-900 hover:bg-slate-50 rounded-xl transition-all">Cancel</a>
                            <button type="submit" class="px-10 py-3.5 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 !border-b-0 text-lg">
                                Post Discussion
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 space-y-6">
            <div class="bg-indigo-50/80 backdrop-blur-sm rounded-3xl p-6 border border-indigo-100/50 sticky top-24">
                <h3 class="text-xl font-bold text-indigo-900 mb-4 flex items-center">
                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    Posting Guidelines
                </h3>
                <ul class="space-y-3 text-base text-indigo-900/80 leading-relaxed">
                    <li class="flex items-start">
                        <span class="mr-3 text-indigo-500 mt-1.5 text-lg">&bull;</span>
                        <span>Be specific in your title to help others understand your topic clearly.</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-3 text-indigo-500 mt-1.5 text-lg">&bull;</span>
                        <span>Choose the most relevant category for better visibility.</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-3 text-indigo-500 mt-1.5 text-lg">&bull;</span>
                        <span>Respect the community <a href="#" class="underline hover:text-indigo-700 decoration-indigo-400 underline-offset-2">code of conduct</a>.</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-3 text-indigo-500 mt-1.5 text-lg">&bull;</span>
                        <span>Avoid sharing personal contact information publicly.</span>
                    </li>
                </ul>
                
                <div class="mt-6 pt-6 border-t border-indigo-200">
                    <p class="text-sm text-indigo-700">Need help? <a href="#" class="font-bold hover:underline">Contact Support</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
