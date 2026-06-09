<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $categories = [
            'all'           => '★ Tümü',
            'SAFETY'        => '🦺 İSG & Güvenlik',
            'CRANE_TYPE'    => '🏗️ Vinç Türleri',
            'OPERATION'     => '⚙️ Operasyon',
            'TECHNICAL'     => '🔧 Teknik',
            'RISK'          => '⚠️ Risk Yönetimi',
            'CERTIFICATION' => '🪪 Sertifikasyon',
        ];

        $query = Course::where('published', true)->with('instructor')->withCount(['enrollments','sections']);

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('q')) {
            $query->where('title', 'like', '%'.$request->q.'%');
        }

        $courses = $query->orderByDesc('created_at')->paginate(12)->withQueryString();
        $total   = Course::where('published', true)->count();

        return view('courses.index', compact('courses', 'categories', 'total'));
    }

    public function show(string $slug)
    {
        $course = Course::where('slug', $slug)
            ->with(['sections.lessons', 'instructor', 'quizzes.questions', 'simulations'])
            ->withCount('enrollments')
            ->firstOrFail();

        $isEnrolled = false;
        $progress   = 0;

        if (Auth::check()) {
            $enrollment = Enrollment::where('user_id', Auth::id())
                ->where('course_id', $course->id)
                ->first();
            $isEnrolled = (bool) $enrollment;
            $progress   = $enrollment?->progress_percent ?? 0;
        }

        $mandatoryCourses = Course::where('is_mandatory', true)
            ->where('published', true)
            ->select('title', 'slug')
            ->take(4)
            ->get();

        return view('courses.show', compact('course', 'isEnrolled', 'progress', 'mandatoryCourses'));
    }

    public function enroll(string $slug)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $course = Course::where('slug', $slug)->firstOrFail();

        Enrollment::firstOrCreate([
            'user_id'   => Auth::id(),
            'course_id' => $course->id,
        ], [
            'status'           => 'IN_PROGRESS',
            'progress_percent' => 0,
            'enrolled_at'      => now(),
        ]);

        return redirect()->route('courses.learn', $slug);
    }

    public function learn(string $slug)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $course = Course::where('slug', $slug)
            ->with(['sections.lessons'])
            ->firstOrFail();

        $enrollment = Enrollment::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            return redirect()->route('courses.show', $slug);
        }

        return view('courses.learn', compact('course', 'enrollment'));
    }
}
