<?php
//F11 - Akida Lisi

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Education\Course;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Throwable;

class CourseLibraryController extends Controller
{
    public function index(): View
    {
        $courses = collect();

        try {
            // If migrations haven't been run yet, avoid a 500 and show an empty library.
            if (Schema::hasTable('courses')) {
                $query = Course::query()
                    ->published()
                    ->with(['media']);

                if ($search = request('search')) {
                    $query->where(function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%")
                          ->orWhere('summary', 'like', "%{$search}%");
                    });
                }

                $courses = $query->orderByDesc('published_at')
                    ->orderByDesc('id')
                    ->get();
            }
        } catch (Throwable $e) {
            Log::warning('Course library query failed.', [
                'error' => $e->getMessage(),
            ]);
        }

        return view('courses.index', compact('courses'));
    }

    public function show(Course $course): View
    {
        // Public-facing library only shows published courses.
        abort_unless($course->published_at && $course->published_at->isPast(), 404);

        $course->load(['media']);

        return view('courses.show', compact('course'));
    }
}

