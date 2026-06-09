<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Progress;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::with(['course'])
            ->where('is_active', true)
            ->withCount('questions')
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('quizzes.index', compact('quizzes'));
    }

    public function show(Quiz $quiz)
    {
        $quiz->load(['course', 'questions']);
        $userAttempts = [];
        $bestScore    = null;
        $passed       = false;
        $canTake      = true;

        if (Auth::check()) {
            $userAttempts = QuizAttempt::where('user_id', Auth::id())
                ->where('quiz_id', $quiz->id)
                ->orderByDesc('created_at')
                ->get();

            $bestScore = $userAttempts->max('score');
            $passed    = $userAttempts->where('passed', true)->count() > 0;
            $canTake   = $quiz->is_active && ($userAttempts->count() < $quiz->attempts);
        }

        $videoCompleted = true;
        if ($quiz->course_id && Auth::check()) {
            $videoCompleted = $this->isCourseVideoCompleted($quiz->course_id, Auth::id());
        }

        return view('quizzes.show', compact('quiz', 'userAttempts', 'bestScore', 'passed', 'canTake', 'videoCompleted'));
    }

    public function take(Quiz $quiz)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!$quiz->is_active) {
            return redirect()->route('quizzes.show', $quiz->id)
                ->with('error', 'Bu sınav şu anda aktif değil. Yönetici tarafından açılması bekleniyor.');
        }

        $quiz->load(['questions']);

        $attemptCount = QuizAttempt::where('user_id', Auth::id())
            ->where('quiz_id', $quiz->id)
            ->count();

        if ($attemptCount >= $quiz->attempts) {
            return redirect()->route('quizzes.show', $quiz->id)
                ->with('error', 'Maksimum deneme hakkınızı kullandınız.');
        }

        if ($quiz->course_id && !$this->isCourseVideoCompleted($quiz->course_id, Auth::id())) {
            return redirect()->route('quizzes.show', $quiz->id)
                ->with('error', 'Sınava girebilmek için önce tüm videoları izlemelisiniz.');
        }

        return view('quizzes.take', compact('quiz'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!$quiz->is_active) {
            return redirect()->route('quizzes.show', $quiz->id)
                ->with('error', 'Sınav süresi doldu veya yönetici tarafından kapatıldı.');
        }

        $quiz->load(['questions']);

        $attemptCount = QuizAttempt::where('user_id', Auth::id())
            ->where('quiz_id', $quiz->id)
            ->count();

        if ($attemptCount >= $quiz->attempts) {
            return redirect()->route('quizzes.show', $quiz->id)
                ->with('error', 'Maksimum deneme hakkınızı kullandınız.');
        }

        $answers      = $request->input('answers', []);
        $totalQ       = $quiz->questions->count();
        $correctCount = 0;

        foreach ($quiz->questions as $q) {
            $given = $answers[$q->id] ?? null;
            if (!is_null($given) && (int)$given === (int)$q->correct_answer) {
                $correctCount++;
            }
        }

        $score  = $totalQ > 0 ? (int) round(($correctCount / $totalQ) * 100) : 0;
        $passed = $score >= $quiz->passing_score;

        $attempt = QuizAttempt::create([
            'user_id'         => Auth::id(),
            'quiz_id'         => $quiz->id,
            'score'           => $score,
            'passed'          => $passed,
            'answers'         => $answers,
            'correct_count'   => $correctCount,
            'total_questions' => $totalQ,
            'started_at'      => now()->subMinutes(1),
            'finished_at'     => now(),
        ]);

        // Süre dolunca otomatik kapat
        if ($quiz->time_limit) {
            $quiz->update(['is_active' => false]);
        }

        if ($passed && $quiz->course_id) {
            $this->tryIssueCertificate($quiz->course_id, Auth::id());
        }

        return redirect()->route('quizzes.result', $attempt->id);
    }

    public function result(QuizAttempt $attempt)
    {
        if ($attempt->user_id !== Auth::id()) {
            abort(403);
        }

        $attempt->load(['quiz.questions', 'quiz.course']);

        $certificate = null;
        if ($attempt->passed && $attempt->quiz->course_id) {
            $certificate = Certificate::where('user_id', Auth::id())
                ->where('course_id', $attempt->quiz->course_id)
                ->latest()
                ->first();
        }

        return view('quizzes.result', compact('attempt', 'certificate'));
    }

    private function isCourseVideoCompleted(int $courseId, int $userId): bool
    {
        $course = Course::with('sections.lessons')->find($courseId);
        if (!$course) return true;

        $videoLessons = $course->sections->flatMap(fn($s) => $s->lessons)
            ->where('type', 'VIDEO');

        if ($videoLessons->isEmpty()) return true;

        $completedIds = Progress::where('user_id', $userId)
            ->whereIn('lesson_id', $videoLessons->pluck('id'))
            ->where('completed', true)
            ->pluck('lesson_id');

        return $completedIds->count() >= $videoLessons->count();
    }

    private function tryIssueCertificate(int $courseId, int $userId): void
    {
        $existing = Certificate::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        if ($existing) return;

        $course = Course::find($courseId);
        if (!$course) return;

        $quizIds = Quiz::where('course_id', $courseId)->pluck('id');
        foreach ($quizIds as $qId) {
            $hasPassed = QuizAttempt::where('user_id', $userId)
                ->where('quiz_id', $qId)
                ->where('passed', true)
                ->exists();
            if (!$hasPassed) return;
        }

        $user = \App\Models\User::find($userId);
        Certificate::create([
            'user_id'         => $userId,
            'course_id'       => $courseId,
            'cert_number'     => 'LIFT-' . strtoupper(Str::random(4)) . '-' . date('Y') . '-' . str_pad($userId, 4, '0', STR_PAD_LEFT),
            'level'           => 'OPERATOR',
            'status'          => 'ACTIVE',
            'recipient_name'  => $user->name,
            'instructor_name' => $course->instructor->name ?? 'LiftAcademy',
            'training_hours'  => 8,
            'completed_at'    => now(),
            'expires_at'      => now()->addYear(),
            'issued_at'       => now(),
        ]);

        Enrollment::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->update(['status' => 'COMPLETED', 'completed_at' => now()]);
    }
}
