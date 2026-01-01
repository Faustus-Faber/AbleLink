@extends('layouts.admin')

@section('admin-content')
<div class="w-full">
    <div class="flex items-end justify-between gap-6 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900">Manage Courses</h1>
            <p class="text-slate-600 mt-2">Create and publish accessible courses with captions, transcripts, and audio descriptions.</p>
        </div>
        <a href="{{ route('admin.courses.create') }}"
           class="px-5 py-3 rounded-full bg-slate-900 text-white font-bold shadow hover:bg-slate-800 transition">
            New course
        </a>
    </div>

    @if (session('status'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 font-semibold">
            {{ session('status') }}
        </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 text-xs font-extrabold uppercase tracking-wider text-slate-500">Course</th>
                    <th class="px-6 py-4 text-xs font-extrabold uppercase tracking-wider text-slate-500">Slug</th>
                    <th class="px-6 py-4 text-xs font-extrabold uppercase tracking-wider text-slate-500">Published</th>
                    <th class="px-6 py-4 text-xs font-extrabold uppercase tracking-wider text-slate-500">Media</th>
                    <th class="px-6 py-4 text-xs font-extrabold uppercase tracking-wider text-slate-500 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($courses as $course)
                    <tr class="hover:bg-slate-50/50">
                        <td class="px-6 py-4">
                            <div class="font-extrabold text-slate-900">{{ $course->title }}</div>
                            @if($course->summary)
                                <div class="text-sm text-slate-600 mt-1">{{ $course->summary }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700 font-mono">{{ $course->slug }}</td>
                        <td class="px-6 py-4">
                            @if($course->published_at)
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700">
                                    Yes
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                                    No
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $course->media_count }}</td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <a class="text-sm font-bold text-blue-700 hover:text-blue-900 mr-4"
                               href="{{ route('courses.show', ['course' => $course->slug]) }}" target="_blank" rel="noreferrer">
                                View
                            </a>
                            <a class="text-sm font-bold text-slate-900 hover:text-slate-700"
                               href="{{ route('admin.courses.edit', $course) }}">
                                Edit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-600 font-semibold">
                            No courses yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection