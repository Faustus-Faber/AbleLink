@extends('layouts.admin')

@section('admin-content')

<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Add Media</h1>
            <p class="text-slate-500 mt-2 font-medium">Attach content to <span class="text-slate-900">{{ $course->title }}</span>.</p>
        </div>
        <a href="{{ route('admin.courses.edit', $course) }}" 
           class="group flex items-center gap-2 px-5 py-2.5 rounded-full bg-white border border-slate-200 text-slate-600 font-bold text-sm hover:border-slate-300 hover:text-slate-900 transition-all shadow-sm">
            <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Course
        </a>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-10">
        <form method="POST" action="{{ route('admin.courses.media.store', $course) }}" enctype="multipart/form-data" id="mediaForm">
            @csrf

            @if($errors->any())
                <div class="mb-8 bg-red-50 border border-red-100 text-red-600 rounded-2xl p-5 flex items-start gap-3">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <h4 class="font-bold text-sm uppercase tracking-wide mb-1">Please resolve errors</h4>
                        <ul class="list-disc list-inside text-sm font-medium space-y-1 opacity-90">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="space-y-8">
                <!-- Kind Selection -->
                <div x-data="{
                    kind: '{{ old('kind', 'video') }}',
                    open: false,
                    options: {
                        'video': 'Video',
                        'audio': 'Audio',
                        'document': 'Document',
                        'link': 'Link'
                    },
                    updateVisibility() {
                        const kind = this.kind;
                        const fileGroup = document.getElementById('fileUploadGroup');
                        const urlGroup = document.getElementById('urlInputGroup');
                        const divider = document.getElementById('sourceDivider');
                        const accessSection = document.getElementById('accessibilitySection');
                        const audioDesc = document.getElementById('audioDescGroup');

                        // Default State
                        if(fileGroup) fileGroup.style.display = 'block';
                        if(urlGroup) urlGroup.style.display = 'block';
                        if(divider) divider.style.display = 'flex';
                        if(accessSection) accessSection.style.display = 'block';
                        if(audioDesc) audioDesc.style.display = 'block';

                        if (kind === 'link') {
                            if(fileGroup) fileGroup.style.display = 'none';
                            if(divider) divider.style.display = 'none';
                            if(audioDesc) audioDesc.style.display = 'none';
                        } else if (kind === 'document') {
                            if(audioDesc) audioDesc.style.display = 'none';
                        } else if (kind === 'audio') {
                            if(audioDesc) audioDesc.style.display = 'none';
                        }
                    },
                    init() {
                        this.updateVisibility();
                        this.$watch('kind', () => this.updateVisibility());
                    }
                }">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Media Type</label>
                    
                    <!-- Custom Dropdown -->
                    <div class="relative">
                        <input type="hidden" name="kind" :value="kind">
                        
                        <button type="button" 
                                @click="open = !open"
                                @click.away="open = false"
                                class="w-full bg-slate-50 border-2 border-transparent focus:bg-white focus:border-slate-300 rounded-xl px-4 py-3.5 font-bold text-slate-900 transition-all cursor-pointer flex items-center justify-between group hover:bg-slate-100">
                            <span x-text="options[kind]"></span>
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-slate-600 transition-transform duration-200" 
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
                             class="absolute z-10 mt-2 w-full bg-slate-800 rounded-xl shadow-xl overflow-hidden ring-1 ring-black ring-opacity-5"
                             style="display: none;">
                            <div class="py-1">
                                <template x-for="(label, key) in options" :key="key">
                                    <button type="button"
                                            @click="kind = key; open = false"
                                            class="w-full text-left px-4 py-3 text-sm font-bold text-slate-300 hover:bg-slate-700 hover:text-white transition-colors flex items-center justify-between group">
                                        <span x-text="label"></span>
                                        <svg x-show="kind === key" class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Title <span class="text-slate-400 font-normal normal-case ml-1">(Optional)</span></label>
                        <input name="title" value="{{ old('title') }}" placeholder="Display title..."
                               class="w-full bg-slate-50 border-transparent focus:bg-white focus:border-slate-300 focus:ring-4 focus:ring-slate-100 rounded-xl px-4 py-3 font-semibold text-slate-900 transition-all placeholder:text-slate-400" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Sort Order</label>
                        <input name="sort_order" type="number" min="0" value="{{ old('sort_order', 0) }}"
                               class="w-full bg-slate-50 border-transparent focus:bg-white focus:border-slate-300 focus:ring-4 focus:ring-slate-100 rounded-xl px-4 py-3 font-semibold text-slate-900 transition-all placeholder:text-slate-400" />
                    </div>
                </div>

                <!-- Media Source Section -->
                <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
                    <h3 class="text-sm font-bold uppercase tracking-wide text-slate-900 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        Content Source
                    </h3>
                    
                    <div class="space-y-6">
                        <!-- File Upload -->
                        <div id="fileUploadGroup">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Upload File</label>
                            <input name="media_file" type="file"
                                   class="block w-full text-slate-500 text-sm file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-slate-900 file:text-white hover:file:bg-black transition-all cursor-pointer bg-white border border-slate-200 rounded-xl p-1" />
                            <p class="text-[11px] text-slate-400 mt-2 font-medium">Max size: 100MB. Supported formats depend on media type.</p>
                        </div>

                        <!-- OR Divider -->
                        <div class="relative flex py-1 items-center" id="sourceDivider">
                            <div class="flex-grow border-t border-slate-200"></div>
                            <span class="flex-shrink-0 mx-4 text-xs font-bold text-slate-400 uppercase tracking-widest">OR</span>
                            <div class="flex-grow border-t border-slate-200"></div>
                        </div>

                        <!-- External URL -->
                        <div id="urlInputGroup">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">External URL</label>
                            <input name="external_url" value="{{ old('external_url') }}" placeholder="https://..."
                                   class="w-full bg-white border border-slate-200 focus:border-slate-400 focus:ring-4 focus:ring-slate-100 rounded-xl px-4 py-3 font-medium text-slate-900 transition-all placeholder:text-slate-400" />
                            <p class="text-[11px] text-slate-400 mt-2 font-medium">YouTube, Vimeo, or direct link.</p>
                        </div>
                    </div>
                </div>

                <!-- Accessibility Section (Conditional) -->
                <div id="accessibilitySection" class="border-t border-slate-100 pt-8">
                    <h3 class="text-lg font-extrabold text-slate-900 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
                        Accessibility Assets
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Captions File (.vtt)</label>
                            <input name="captions_file" type="file"
                                   class="block w-full text-slate-500 text-sm file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all cursor-pointer bg-slate-50 border-transparent rounded-xl p-1" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Language Code</label>
                            <input name="captions_language" value="{{ old('captions_language', 'en') }}" placeholder="en"
                                   class="w-full bg-slate-50 border-transparent focus:bg-white focus:border-slate-300 focus:ring-4 focus:ring-slate-100 rounded-xl px-4 py-3 font-semibold text-slate-900 transition-all uppercase" />
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div id="audioDescGroup">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Audio Description Track</label>
                            <input name="audio_description_file" type="file"
                                   class="block w-full text-slate-500 text-sm file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 transition-all cursor-pointer bg-slate-50 border-transparent rounded-xl p-1" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Transcript / Text Alternative</label>
                            <textarea name="transcript" rows="6" placeholder="Paste full transcript here..."
                                      class="w-full bg-slate-50 border-transparent focus:bg-white focus:border-slate-300 focus:ring-4 focus:ring-slate-100 rounded-xl px-4 py-3 font-medium text-slate-900 transition-all resize-y placeholder:text-slate-400">{{ old('transcript') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Primary Checkbox -->
                <div class="flex items-center gap-4 bg-slate-50 rounded-xl p-5 border border-slate-100">
                    <div class="flex items-center h-5">
                        <input id="is_primary" name="is_primary" type="checkbox" value="1" {{ old('is_primary') ? 'checked' : '' }}
                               class="w-5 h-5 rounded border-slate-300 text-slate-900 focus:ring-slate-900 transition-all" />
                    </div>
                    <div>
                        <label for="is_primary" class="font-bold text-slate-900 block">Set as Primary Media</label>
                        <p class="text-xs text-slate-500 mt-0.5">This item will be featured first in the course viewer.</p>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="mt-10 pt-8 border-t border-slate-100 flex items-center justify-end">
                <button type="submit" 
                        class="bg-slate-900 text-white font-bold text-sm px-8 py-4 rounded-xl hover:bg-black hover:scale-[1.02] active:scale-[0.98] transition-all shadow-lg hover:shadow-xl">
                    Upload Media
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Alpine.js now handles the form state logic for media type selection
</script>
@endsection
