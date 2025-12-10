<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\CourseLesson;
use App\Services\RecommendationService;
use App\Services\CertificateGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    protected $recommendationService;
    protected $certificateGenerator;

    public function __construct(
        RecommendationService $recommendationService,
        CertificateGeneratorService $certificateGenerator
    ) {
        $this->recommendationService = $recommendationService;
        $this->certificateGenerator = $certificateGenerator;
    }

    /**
     * Show course library.
     */
    public function index(Request $request)
    {
        $query = Course::where('is_active', true);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty_level', $request->difficulty);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $courses = $query->withCount('lessons')->latest()->paginate(12);

        // Get recommendations if user is logged in
        $recommendations = null;
        if (Auth::check() && Auth::user()->isDisabledUser()) {
            $recommendations = $this->recommendationService->getCourseRecommendations(Auth::user());
        }

        return view('courses.index', compact('courses', 'recommendations'));
    }

    /**
     * Show course details.
     */
    public function show(Course $course)
    {
        $course->load('lessons');
        
        $isEnrolled = false;
        $enrollment = null;
        $certificate = null;

        if (Auth::check()) {
            $enrollment = CourseEnrollment::where('course_id', $course->id)
                ->where('user_id', Auth::id())
                ->first();
            
            $isEnrolled = $enrollment !== null;

            if ($enrollment && $enrollment->completed_at) {
                $certificate = $course->certificates()
                    ->where('user_id', Auth::id())
                    ->first();
                
                // Auto-generate certificate if not exists
                if (!$certificate) {
                    $certificate = $this->certificateGenerator->generateCertificate(Auth::user(), $course);
                }
            }
        }

        return view('courses.show', compact('course', 'isEnrolled', 'enrollment', 'certificate'));
    }

    /**
     * Enroll in a course.
     */
    public function enroll(Course $course)
    {
        $user = Auth::user();

        if (!$user->isDisabledUser()) {
            abort(403, 'Only users can enroll in courses.');
        }

        // Check if already enrolled
        $enrollment = CourseEnrollment::where('course_id', $course->id)
            ->where('user_id', $user->id)
            ->first();

        if ($enrollment) {
            return redirect()->route('courses.show', $course)
                ->with('status', 'You are already enrolled in this course.');
        }

        CourseEnrollment::create([
            'course_id' => $course->id,
            'user_id' => $user->id,
            'enrolled_at' => now(),
            'started_at' => now(),
            'progress_percentage' => 0,
            'completed_lessons' => [],
        ]);

        return redirect()->route('courses.show', $course)
            ->with('status', 'Successfully enrolled in the course.');
    }

    /**
     * Show lesson details.
     */
    public function lesson(Course $course, CourseLesson $lesson)
    {
        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        $user = Auth::user();
        $isEnrolled = false;
        $enrollment = null;

        if ($user) {
            $enrollment = CourseEnrollment::where('course_id', $course->id)
                ->where('user_id', $user->id)
                ->first();
            
            $isEnrolled = $enrollment !== null;
        }

        if (!$isEnrolled) {
            return redirect()->route('courses.show', $course)
                ->withErrors(['error' => 'You must enroll in the course first.']);
        }

        return view('courses.lesson', compact('course', 'lesson', 'enrollment'));
    }

    /**
     * Mark lesson as completed.
     */
    public function completeLesson(Request $request, Course $course, CourseLesson $lesson)
    {
        $user = Auth::user();

        $enrollment = CourseEnrollment::where('course_id', $course->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $completedLessons = $enrollment->completed_lessons ?? [];
        
        if (!in_array($lesson->id, $completedLessons)) {
            $completedLessons[] = $lesson->id;
        }

        $totalLessons = $course->lessons()->where('is_active', true)->count();
        $progressPercentage = count($completedLessons) > 0 
            ? (int) ((count($completedLessons) / $totalLessons) * 100)
            : 0;

        $updateData = [
            'completed_lessons' => $completedLessons,
            'progress_percentage' => $progressPercentage,
        ];

        // Mark course as completed if all lessons are done
        if (count($completedLessons) === $totalLessons && !$enrollment->completed_at) {
            $updateData['completed_at'] = now();
            
            // Generate certificate
            $this->certificateGenerator->generateCertificate($user, $course);
        }

        $enrollment->update($updateData);

        return back()->with('status', 'Lesson marked as completed.');
    }
}





