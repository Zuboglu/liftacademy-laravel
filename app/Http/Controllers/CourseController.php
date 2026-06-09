<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Progress;
use App\Models\Quiz;
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
        if (!Auth::check()) return redirect()->route('login');

        $course = Course::where('slug', $slug)->firstOrFail();

        Enrollment::firstOrCreate(
            ['user_id' => Auth::id(), 'course_id' => $course->id],
            ['status' => 'ACTIVE']
        );

        return redirect()->route('courses.learn', $slug);
    }

    public function learn(string $slug)
    {
        if (!Auth::check()) return redirect()->route('login');

        $course = Course::where('slug', $slug)
            ->with(['sections.lessons', 'quizzes'])
            ->firstOrFail();

        $enrollment = Enrollment::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) return redirect()->route('courses.show', $slug);

        // Tamamlanan ders ID'leri
        $allLessonIds = $course->sections->flatMap(fn($s) => $s->lessons->pluck('id'));
        $completedIds = Progress::where('user_id', Auth::id())
            ->whereIn('lesson_id', $allLessonIds)
            ->where('completed', true)
            ->pluck('lesson_id')
            ->toArray();

        // Tüm video dersler tamamlandı mı?
        $videoLessonIds = $course->sections->flatMap(
            fn($s) => $s->lessons->where('type', 'VIDEO')->pluck('id')
        );
        $allVideosCompleted = $videoLessonIds->isEmpty()
            ? true
            : $videoLessonIds->every(fn($id) => in_array($id, $completedIds));

        // Kursa bağlı sınavlar
        $quizzes = Quiz::where('course_id', $course->id)->get();

        return view('courses.learn', compact('course', 'enrollment', 'completedIds', 'allVideosCompleted', 'quizzes'));
    }

    // POST /courses/{slug}/lessons/{lesson}/complete — AJAX
    public function completeLesson(Request $request, string $slug, Lesson $lesson)
    {
        if (!Auth::check()) return response()->json(['error' => 'Unauthorized'], 401);

        $course = Course::where('slug', $slug)->firstOrFail();

        // Kayıtlı mı?
        $enrolled = Enrollment::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->exists();

        if (!$enrolled) return response()->json(['error' => 'Not enrolled'], 403);

        Progress::updateOrCreate(
            ['user_id' => Auth::id(), 'lesson_id' => $lesson->id],
            ['completed' => true, 'watched_sec' => $request->input('watched_sec', 0)]
        );

        // Tüm video dersler tamamlandı mı kontrol et
        $videoLessons = $course->sections()
            ->with('lessons')
            ->get()
            ->flatMap(fn($s) => $s->lessons)
            ->where('type', 'VIDEO');

        $completedVideoCount = Progress::where('user_id', Auth::id())
            ->whereIn('lesson_id', $videoLessons->pluck('id'))
            ->where('completed', true)
            ->count();

        $allVideosCompleted = $completedVideoCount >= $videoLessons->count();

        // Bağlı quiz'ler
        $quizIds = Quiz::where('course_id', $course->id)->pluck('id');

        return response()->json([
            'success'             => true,
            'all_videos_completed' => $allVideosCompleted,
            'quiz_ids'            => $quizIds,
        ]);
    }
}
