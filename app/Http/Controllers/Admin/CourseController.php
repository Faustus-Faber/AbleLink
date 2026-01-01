<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Education\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Manage courses.
 */
class CourseController extends Controller
{
    public function index(): View
    {
        // If migrations haven't been run yet, avoid a 500 and show an empty list.
        if (! Schema::hasTable('courses')) {
            /** @var Collection<int, Course> $courses */
            $courses = collect();

            return view('admin.courses.index', compact('courses'));
        }

        $query = Course::query()->orderByDesc('published_at')->orderByDesc('id');
        if (Schema::hasTable('course_media')) {
            $query->withCount('media');
        }

        $courses = $query->get();

        // Ensure the view can always render a media_count column.
        if (! Schema::hasTable('course_media')) {
            $courses->each(function (Course $course): void {
                $course->setAttribute('media_count', 0);
            });
        }

        return view('admin.courses.index', compact('courses'));
    }

    public function create(): View
    {
        return view('admin.courses.create');
    }

    public function store(Request $request): RedirectResponse
    {
        if (! Schema::hasTable('courses')) {
            return back()->withErrors([
                'title' => 'Courses table not found. Run `php artisan migrate` first.',
            ])->withInput();
        }

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'level' => ['nullable', 'string', 'max:50'],
            'estimated_minutes' => ['nullable', 'integer', 'min:1', 'max:1000000'],
            'published' => ['nullable', 'boolean'],
        ]);

        $baseSlug = trim($data['slug'] ?? '') !== '' ? Str::slug($data['slug']) : Course::makeSlug($data['title']);
        $slug = $baseSlug;
        $i = 2;
        while (Course::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$i;
            $i++;
        }

        $course = Course::create([
            'slug' => $slug,
            'title' => $data['title'],
            'summary' => $data['summary'] ?? null,
            'description' => $data['description'] ?? null,
            'level' => $data['level'] ?? null,
            'estimated_minutes' => $data['estimated_minutes'] ?? null,
            'published_at' => ($data['published'] ?? false) ? now() : null,
        ]);

        return redirect()->route('admin.courses.edit', $course)->with('status', 'Course created.');
    }

    public function edit(Course $course): View
    {
        $course->load('media');

        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        if (! Schema::hasTable('courses')) {
            return back()->withErrors([
                'title' => 'Courses table not found. Run `php artisan migrate` first.',
            ])->withInput();
        }

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'level' => ['nullable', 'string', 'max:50'],
            'estimated_minutes' => ['nullable', 'integer', 'min:1', 'max:1000000'],
            'published' => ['nullable', 'boolean'],
        ]);

        $slug = Str::slug($data['slug']);
        if ($slug !== $course->slug && Course::where('slug', $slug)->whereKeyNot($course->id)->exists()) {
            return back()->withErrors(['slug' => 'That slug is already taken.'])->withInput();
        }

        $course->fill([
            'title' => $data['title'],
            'slug' => $slug,
            'summary' => $data['summary'] ?? null,
            'description' => $data['description'] ?? null,
            'level' => $data['level'] ?? null,
            'estimated_minutes' => $data['estimated_minutes'] ?? null,
        ]);

        $shouldBePublished = (bool) ($data['published'] ?? false);
        if ($shouldBePublished && ! $course->published_at) {
            $course->published_at = now();
        }
        if (! $shouldBePublished) {
            $course->published_at = null;
        }

        $course->save();

        return back()->with('status', 'Course updated.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();

        return redirect()->route('admin.courses.index')->with('status', 'Course deleted.');
    }
}

