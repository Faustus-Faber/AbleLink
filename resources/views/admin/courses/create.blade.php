@extends('layouts.admin')

@section('admin-content')

<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Create Course</h1>
            <p class="text-slate-500 mt-2 font-medium">Add a new course to the catalog.</p>
        </div>
        <a href="{{ route('admin.courses.index') }}" 
           class="group flex items-center gap-2 px-5 py-2.5 rounded-full bg-white border border-slate-200 text-slate-600 font-bold text-sm hover:border-slate-300 hover:text-slate-900 transition-all shadow-sm">
            <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Courses
        </a>
    </div>

    <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-10">
        <form method="POST" action="{{ route('admin.courses.store') }}">
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

            <div class="grid grid-cols-1 gap-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Course Title</label>
                        <input name="title" value="{{ old('title') }}" required placeholder="e.g. Advanced AI Accessibility"
                               class="w-full bg-slate-50 border-transparent focus:bg-white focus:border-slate-300 focus:ring-4 focus:ring-slate-100 rounded-xl px-4 py-3 font-semibold text-slate-900 transition-all placeholder:text-slate-400" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                            Slug 
                            <span class="text-slate-400 font-normal normal-case ml-1">(auto-generated if empty)</span>
                        </label>
                        <input name="slug" value="{{ old('slug') }}" placeholder="e.g. advanced-ai-accessibility"
                               class="w-full bg-slate-50 border-transparent focus:bg-white focus:border-slate-300 focus:ring-4 focus:ring-slate-100 rounded-xl px-4 py-3 font-semibold text-slate-900 transition-all placeholder:text-slate-400" />
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Short Summary</label>
                    <input name="summary" value="{{ old('summary') }}" placeholder="Brief one-line overview of the course content..."
                           class="w-full bg-slate-50 border-transparent focus:bg-white focus:border-slate-300 focus:ring-4 focus:ring-slate-100 rounded-xl px-4 py-3 font-medium text-slate-900 transition-all placeholder:text-slate-400" />
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Full Description</label>
                    <textarea name="description" rows="6" placeholder="Detailed curriculum explanation... (Markdown supported)"
                              class="w-full bg-slate-50 border-transparent focus:bg-white focus:border-slate-300 focus:ring-4 focus:ring-slate-100 rounded-xl px-4 py-3 font-medium text-slate-900 transition-all placeholder:text-slate-400 resize-y">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Difficulty Level</label>
                        <div class="relative">
                            <input name="level" value="{{ old('level') }}" list="levels" placeholder="Select level"
                                   class="w-full bg-slate-50 border-transparent focus:bg-white focus:border-slate-300 focus:ring-4 focus:ring-slate-100 rounded-xl px-4 py-3 font-semibold text-slate-900 transition-all placeholder:text-slate-400" />
                            <datalist id="levels">
                                <option value="Beginner">
                                <option value="Intermediate">
                                <option value="Advanced">
                            </datalist>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Duration <span class="text-slate-400 font-normal normal-case ml-1">(minutes)</span></label>
                        <input name="estimated_minutes" type="number" min="1" value="{{ old('estimated_minutes') }}" placeholder="e.g. 60"
                               class="w-full bg-slate-50 border-transparent focus:bg-white focus:border-slate-300 focus:ring-4 focus:ring-slate-100 rounded-xl px-4 py-3 font-semibold text-slate-900 transition-all placeholder:text-slate-400" />
                    </div>
                </div>

                <div class="flex items-center gap-4 bg-slate-50 rounded-xl p-5 border border-slate-100">
                    <div class="flex items-center h-5">
                        <input id="published" name="published" type="checkbox" value="1" {{ old('published') ? 'checked' : '' }}
                               class="w-5 h-5 rounded border-slate-300 text-slate-900 focus:ring-slate-900 transition-all" />
                    </div>
                    <div>
                        <label for="published" class="font-bold text-slate-900 block">Publish Immediately</label>
                        <p class="text-xs text-slate-500 mt-0.5">If unchecked, the course will be saved as draft.</p>
                    </div>
                </div>
            </div>

            <div class="mt-10 pt-10 border-t border-slate-100 flex items-center justify-end">
                <button type="submit" 
                        class="bg-slate-900 text-white font-bold text-sm px-8 py-4 rounded-xl hover:bg-black hover:scale-[1.02] active:scale-[0.98] transition-all shadow-lg shadow-slate-900/20">
                    Create Course
                </button>
            </div>
        </form>
    </div>
</div>
@endsection