@extends('layouts.app')



@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-10">
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Create Event</h1>
        <p class="text-slate-500 font-medium mt-1">Host a new event for the community to join.</p>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <form action="{{ route('community.events.store') }}" method="POST" class="p-8 md:p-10 space-y-6">
            @csrf
            
            <div class="space-y-2">
                <label for="title" class="block text-sm font-bold text-slate-700">Event Title</label>
                <input type="text" name="title" id="title" 
                    class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-4 focus:ring-blue-50 focus:border-blue-500 outline-none transition-all font-medium text-slate-900 placeholder:text-slate-400" 
                    placeholder="e.g., Community Gardening Workshop" required>
            </div>

            <div class="space-y-2">
                <label for="description" class="block text-sm font-bold text-slate-700">Description</label>
                <textarea name="description" id="description" rows="5" 
                    class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-4 focus:ring-blue-50 focus:border-blue-500 outline-none transition-all font-medium text-slate-900 placeholder:text-slate-400 resize-none" 
                    placeholder="Describe what your event is about... (Markdown supported)" required></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="event_date" class="block text-sm font-bold text-slate-700">Date & Time</label>
                    <input type="datetime-local" name="event_date" id="event_date" 
                        class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-4 focus:ring-blue-50 focus:border-blue-500 outline-none transition-all font-medium text-slate-900" 
                        required>
                </div>

                <div class="space-y-2">
                    <label for="type" class="block text-sm font-bold text-slate-700">Event Type</label>
                    {{-- Hybrid Dropdown: Hidden native select (AI) + Premium visual dropdown --}}
                    <div class="relative" x-data="{
                        type: 'offline',
                        open: false,
                        options: {
                            'offline': 'In-Person',
                            'online': 'Online'
                        },
                        select(key) {
                            this.type = key;
                            this.open = false;
                            document.getElementById('type').value = key;
                            document.getElementById('type').dispatchEvent(new Event('change', { bubbles: true }));
                            this.updateFields(key);
                        },
                        init() {
                            this.$watch('type', value => this.updateFields(value));
                            this.updateFields(this.type);
                            document.getElementById('type').addEventListener('change', (e) => {
                                this.type = e.target.value;
                            });
                        },
                        updateFields(value) {
                            const locationField = document.getElementById('locationField');
                            const linkField = document.getElementById('linkField');
                            
                            if (value === 'online') {
                                locationField.classList.add('hidden');
                                linkField.classList.remove('hidden');
                            } else {
                                locationField.classList.remove('hidden');
                                linkField.classList.add('hidden');
                            }
                        }
                    }">
                        {{-- Hidden Native Select (AI-Compatible) --}}
                        <select name="type" id="type" class="sr-only" tabindex="-1" aria-hidden="true">
                            <option value="offline" selected>In-Person</option>
                            <option value="online">Online</option>
                        </select>
                        
                        {{-- Premium Visual Dropdown --}}
                        <button type="button" 
                                @click="open = !open"
                                @click.away="open = false"
                                class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all font-medium text-slate-900 flex items-center justify-between group hover:bg-white text-left">
                            <span x-text="options[type]"></span>
                            <div class="text-slate-400 group-hover:text-blue-500 transition-colors">
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
                             class="absolute z-10 mt-2 w-full bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden ring-1 ring-black ring-opacity-5"
                             style="display: none;">
                            <div class="py-1">
                                <template x-for="(label, key) in options" :key="key">
                                    <button type="button"
                                            @click="select(key)"
                                            class="w-full text-left px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-blue-50 hover:text-blue-700 transition-colors flex items-center justify-between">
                                        <span x-text="label"></span>
                                        <svg x-show="type == key" class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="locationField" class="space-y-2 transition-all duration-300">
                <label for="location" class="block text-sm font-bold text-slate-700">Location</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <input type="text" name="location" id="location" 
                        class="w-full pl-11 pr-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-4 focus:ring-blue-50 focus:border-blue-500 outline-none transition-all font-medium text-slate-900 placeholder:text-slate-400" 
                        placeholder="e.g., Central Park Community Center">
                </div>
            </div>

            <div id="linkField" class="space-y-2 hidden transition-all duration-300">
                <label for="meeting_link" class="block text-sm font-bold text-slate-700">Meeting Link</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                    </div>
                    <input type="url" name="meeting_link" id="meeting_link" 
                        class="w-full pl-11 pr-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-4 focus:ring-blue-50 focus:border-blue-500 outline-none transition-all font-medium text-slate-900 placeholder:text-slate-400" 
                        placeholder="e.g., https://zoom.us/j/123456789">
                </div>
            </div>

            <div class="pt-6 border-t border-slate-50 flex items-center justify-end gap-4">
                <a href="{{ route('community.events.index') }}" class="px-6 py-3 rounded-xl text-slate-600 font-bold hover:bg-slate-50 transition-all">Cancel</a>
                <button type="submit" class="px-8 py-3 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 transition-all shadow-lg hover:shadow-blue-200">
                    Create Event
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Alpine.js now handles the event type change logic
</script>
@endsection
