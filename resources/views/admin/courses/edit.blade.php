@extends('layouts.admin')

@section('admin-content')

<div class="max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Edit Course</h1>
            <p class="text-slate-500 mt-2 font-medium">Update course content and manage accessible media.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('courses.show', ['course' => $course->slug]) }}" target="_blank" rel="noreferrer"
               class="px-5 py-2.5 rounded-full bg-white border border-slate-200 text-slate-700 font-bold text-sm hover:border-slate-300 hover:text-slate-900 transition-all shadow-sm">
                View Public Page
            </a>
            <a href="{{ route('admin.courses.index') }}" 
               class="px-5 py-2.5 rounded-full bg-slate-900 text-white font-bold text-sm hover:bg-black transition-all shadow-md">
                Done
            </a>
        </div>
    </div>

    @if (session('status'))
        <div class="mb-8 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl p-5 font-semibold flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('status') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-8 sm:p-10">
                <form method="POST" action="{{ route('admin.courses.update', $course) }}">
                    @csrf
                    @method('PUT')

                    @if($errors->any())
                        <div class="mb-8 bg-red-50 border border-red-100 text-red-600 rounded-2xl p-5">
                            <ul class="list-disc list-inside text-sm font-medium space-y-1 opacity-90">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Title</label>
                                <input name="title" value="{{ old('title', $course->title) }}" required
                                       class="w-full bg-slate-50 border-transparent focus:bg-white focus:border-slate-300 focus:ring-4 focus:ring-slate-100 rounded-xl px-4 py-3 font-semibold text-slate-900 transition-all" />
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Slug</label>
                                <input name="slug" value="{{ old('slug', $course->slug) }}" required
                                       class="w-full bg-slate-50 border-transparent focus:bg-white focus:border-slate-300 focus:ring-4 focus:ring-slate-100 rounded-xl px-4 py-3 font-semibold text-slate-900 transition-all" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Summary</label>
                            <input name="summary" value="{{ old('summary', $course->summary) }}"
                                   class="w-full bg-slate-50 border-transparent focus:bg-white focus:border-slate-300 focus:ring-4 focus:ring-slate-100 rounded-xl px-4 py-3 font-medium text-slate-900 transition-all" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Description</label>
                            <textarea name="description" rows="8" placeholder="Markdown supported..."
                                      class="w-full bg-slate-50 border-transparent focus:bg-white focus:border-slate-300 focus:ring-4 focus:ring-slate-100 rounded-xl px-4 py-3 font-medium text-slate-900 transition-all resize-y">{{ old('description', $course->description) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Level</label>
                                <div class="relative">
                                    <input name="level" value="{{ old('level', $course->level) }}" list="levels"
                                           class="w-full bg-slate-50 border-transparent focus:bg-white focus:border-slate-300 focus:ring-4 focus:ring-slate-100 rounded-xl px-4 py-3 font-semibold text-slate-900 transition-all" />
                                    <datalist id="levels">
                                        <option value="Beginner">
                                        <option value="Intermediate">
                                        <option value="Advanced">
                                    </datalist>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Duration (mins)</label>
                                <input name="estimated_minutes" type="number" min="1" value="{{ old('estimated_minutes', $course->estimated_minutes) }}"
                                       class="w-full bg-slate-50 border-transparent focus:bg-white focus:border-slate-300 focus:ring-4 focus:ring-slate-100 rounded-xl px-4 py-3 font-semibold text-slate-900 transition-all" />
                            </div>
                        </div>

                        <div class="flex items-center gap-4 bg-slate-50 rounded-xl p-5 border border-slate-100">
                            <div class="flex items-center h-5">
                                <input id="published" name="published" type="checkbox" value="1"
                                       {{ old('published', (bool) $course->published_at) ? 'checked' : '' }}
                                       class="w-5 h-5 rounded border-slate-300 text-slate-900 focus:ring-slate-900 transition-all" />
                            </div>
                            <div>
                                <label for="published" class="font-bold text-slate-900 block">Published</label>
                                @if($course->published_at)
                                    <span class="text-xs text-slate-500">Last published: {{ $course->published_at->toDayDateTimeString() }}</span>
                                @else
                                    <span class="text-xs text-slate-500">Currently in draft mode</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 pt-8 border-t border-slate-100 flex items-center justify-end">
                        <button type="submit" class="bg-slate-900 text-white font-bold text-sm px-8 py-3.5 rounded-xl hover:bg-black hover:scale-[1.02] active:scale-[0.98] transition-all shadow-lg hover:shadow-xl">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-8 sm:p-10">
                <div class="flex items-center justify-between mb-6">
                   <h2 class="text-xl font-bold text-slate-900">Danger Zone</h2>
                </div>
                 <form method="POST" action="{{ route('admin.courses.destroy', $course) }}"
                      onsubmit="return confirm('Delete this course? This will delete all attached media too.');">
                    @csrf
                    @method('DELETE')
                    <div class="flex items-center justify-between p-5 bg-red-50 rounded-2xl border border-red-100">
                        <div>
                            <h3 class="font-bold text-red-900">Delete Course</h3>
                            <p class="text-xs text-red-600/80 mt-1">This action cannot be undone.</p>
                        </div>
                        <button type="submit"
                                class="px-5 py-2.5 rounded-xl bg-red-600 text-white font-bold text-sm hover:bg-red-700 transition-all shadow-sm">
                            Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-6 sticky top-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-extrabold text-slate-900">Course Media</h2>
                    <a href="{{ route('admin.courses.media.create', $course) }}"
                       class="text-xs font-bold text-white bg-slate-900 px-3 py-1.5 rounded-lg hover:bg-black transition">
                        + Add New
                    </a>
                </div>

                <div class="space-y-4">
                    @forelse($course->media as $media)
                        <div class="group relative bg-slate-50 hover:bg-white rounded-2xl border border-slate-100 p-4 transition-all hover:shadow-md hover:border-slate-200">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 bg-white border border-slate-100 px-1.5 py-0.5 rounded">
                                            {{ $media->kind }}
                                        </span>
                                        @if($media->is_primary)
                                            <span class="text-[10px] font-black uppercase tracking-wider text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded">
                                                Primary
                                            </span>
                                        @endif
                                    </div>
                                    <h3 class="font-bold text-slate-900 text-sm leading-tight">
                                        {{ $media->title ?: ucfirst($media->kind) }}
                                    </h3>
                                    
                                    <div class="flex flex-wrap gap-1.5 mt-3">
                                        @if($media->captions_path)
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100/50">CC</span>
                                        @endif
                                        @if($media->audio_description_path)
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-100/50">AD</span>
                                        @endif
                                        @if($media->transcript)
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100/50">TXT</span>
                                        @endif
                                        @if(!$media->captions_path && !$media->audio_description_path && !$media->transcript)
                                            <span class="text-[10px] font-medium text-slate-400 italic">No accessibility assets</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <a href="{{ route('admin.courses.media.edit', $media) }}"
                                   class="text-slate-400 hover:text-slate-900 p-1 rounded-lg hover:bg-slate-100 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 px-4 border-2 border-dashed border-slate-100 rounded-2xl">
                            <svg class="w-10 h-10 text-slate-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            <p class="text-sm font-medium text-slate-500">No media attached yet.</p>
                            <p class="text-xs text-slate-400 mt-1">Upload videos, audio, or documents.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
