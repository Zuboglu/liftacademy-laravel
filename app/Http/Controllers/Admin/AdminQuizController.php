<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;

class AdminQuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::with('course')->withCount('questions')->orderByDesc('created_at')->paginate(20);
        return view('admin.quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        $courses = Course::where('published', true)->orderBy('title')->get();
        return view('admin.quizzes.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'course_id'     => 'nullable|exists:courses,id',
            'passing_score' => 'required|integer|min:0|max:100',
            'time_limit'    => 'nullable|integer|min:1',
            'attempts'      => 'required|integer|min:1|max:10',
        ]);

        $data['is_active'] = true;

        $quiz = Quiz::create($data);

        // Inline sorular varsa kaydet
        $questions = $request->input('questions', []);
        foreach ($questions as $i => $qdata) {
            if (empty(trim($qdata['question'] ?? ''))) continue;
            $opts = array_values(array_filter($qdata['options'] ?? [], fn($o) => trim($o) !== ''));
            if (count($opts) < 2) continue;
            QuizQuestion::create([
                'quiz_id'        => $quiz->id,
                'question'       => $qdata['question'],
                'options'        => $opts,
                'correct_answer' => (int)($qdata['correct_answer'] ?? 0),
                'explanation'    => $qdata['explanation'] ?? null,
                'order'          => $i,
            ]);
        }

        return redirect()->route('admin.quizzes.show', $quiz->id)
            ->with('success', 'Sınav oluşturuldu.');
    }

    public function show(Quiz $quiz)
    {
        $quiz->load(['questions', 'course']);
        return view('admin.quizzes.show', compact('quiz'));
    }

    public function edit(Quiz $quiz)
    {
        $courses = Course::where('published', true)->orderBy('title')->get();
        return view('admin.quizzes.edit', compact('quiz', 'courses'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'course_id'     => 'nullable|exists:courses,id',
            'passing_score' => 'required|integer|min:0|max:100',
            'time_limit'    => 'nullable|integer|min:1',
            'attempts'      => 'required|integer|min:1|max:10',
            'is_active'     => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $quiz->update($data);

        return redirect()->route('admin.quizzes.show', $quiz->id)
            ->with('success', 'Sınav güncellendi.');
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'redirect' => route('admin.quizzes.index')]);
        }
        return redirect()->route('admin.quizzes.index')->with('success', 'Sınav silindi.');
    }

    public function toggleActive(Quiz $quiz)
    {
        $quiz->update(['is_active' => !$quiz->is_active]);
        $quiz->refresh();
        if (request()->expectsJson()) {
            return response()->json([
                'success'   => true,
                'is_active' => $quiz->is_active,
                'message'   => $quiz->is_active ? 'Sınav aktif edildi.' : 'Sınav pasif edildi.',
            ]);
        }
        return back()->with('success', $quiz->is_active ? 'Sınav aktif edildi.' : 'Sınav pasif edildi.');
    }

    // ── Soru işlemleri ─────────────────────────────────────────────────
    public function storeQuestion(Request $request, Quiz $quiz)
    {
        $data = $request->validate([
            'question'       => 'required|string',
            'options'        => 'required|array|min:2|max:6',
            'options.*'      => 'required|string',
            'correct_answer' => 'required|integer|min:0',
            'explanation'    => 'nullable|string',
        ]);

        $options = array_values(array_filter($data['options'], fn($o) => trim($o) !== ''));

        $question = QuizQuestion::create([
            'quiz_id'        => $quiz->id,
            'question'       => $data['question'],
            'options'        => $options,
            'correct_answer' => $data['correct_answer'],
            'explanation'    => $data['explanation'] ?? null,
            'order'          => $quiz->questions()->count(),
        ]);

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'question' => $question->toArray(), 'message' => 'Soru eklendi.']);
        }
        return back()->with('success', 'Soru eklendi.');
    }

    public function updateQuestion(Request $request, QuizQuestion $question)
    {
        $data = $request->validate([
            'question'       => 'required|string',
            'options'        => 'required|array|min:2|max:6',
            'options.*'      => 'required|string',
            'correct_answer' => 'required|integer|min:0',
            'explanation'    => 'nullable|string',
        ]);

        $options = array_values(array_filter($data['options'], fn($o) => trim($o) !== ''));

        $question->update([
            'question'       => $data['question'],
            'options'        => $options,
            'correct_answer' => $data['correct_answer'],
            'explanation'    => $data['explanation'] ?? null,
        ]);

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'question' => $question->fresh()->toArray(), 'message' => 'Soru güncellendi.']);
        }
        return back()->with('success', 'Soru güncellendi.');
    }

    public function destroyQuestion(QuizQuestion $question)
    {
        $question->delete();
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Soru silindi.']);
        }
        return back()->with('success', 'Soru silindi.');
    }
}
