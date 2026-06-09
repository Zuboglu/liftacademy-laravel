<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Section;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminCourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('instructor')->withCount(['enrollments', 'sections'])->orderByDesc('created_at')->paginate(20);
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        $instructors = User::whereIn('role', ['INSTRUCTOR', 'ADMIN'])->orderBy('name')->get();
        return view('admin.courses.create', compact('instructors'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'category'     => 'required|in:SAFETY,CRANE_TYPE,OPERATION,TECHNICAL,RISK,CERTIFICATION,COMPANY',
            'crane_type'   => 'nullable|in:MOBILE,TOWER,PORTAL,HIAB,AERIAL,TELESCOPIC',
            'level'        => 'required|in:BEGINNER,INTERMEDIATE,ADVANCED,ALL_LEVELS',
            'price'        => 'nullable|numeric|min:0',
            'instructor_id'=> 'required|exists:users,id',
            'passing_score'=> 'nullable|integer|min:0|max:100',
            'is_mandatory' => 'boolean',
            'published'    => 'boolean',
        ]);

        $data['slug']         = Str::slug($data['title']) . '-' . Str::random(5);
        $data['price']        = $data['price'] ?? 0;
        $data['passing_score']= $data['passing_score'] ?? 70;
        $data['is_mandatory'] = $request->boolean('is_mandatory');
        $data['published']    = $request->boolean('published');

        $course = Course::create($data);

        return redirect()->route('admin.courses.show', $course->id)
            ->with('success', 'Kurs oluşturuldu.');
    }

    public function show(Course $course)
    {
        $course->load(['sections.lessons', 'instructor', 'quizzes']);
        return view('admin.courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        $instructors = User::whereIn('role', ['INSTRUCTOR', 'ADMIN'])->orderBy('name')->get();
        return view('admin.courses.edit', compact('course', 'instructors'));
    }

    public function update(Request $request, Course $course)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'category'     => 'required|in:SAFETY,CRANE_TYPE,OPERATION,TECHNICAL,RISK,CERTIFICATION,COMPANY',
            'crane_type'   => 'nullable|in:MOBILE,TOWER,PORTAL,HIAB,AERIAL,TELESCOPIC',
            'level'        => 'required|in:BEGINNER,INTERMEDIATE,ADVANCED,ALL_LEVELS',
            'price'        => 'nullable|numeric|min:0',
            'instructor_id'=> 'required|exists:users,id',
            'passing_score'=> 'nullable|integer|min:0|max:100',
            'is_mandatory' => 'boolean',
            'published'    => 'boolean',
        ]);

        $data['is_mandatory'] = $request->boolean('is_mandatory');
        $data['published']    = $request->boolean('published');

        $course->update($data);

        return redirect()->route('admin.courses.show', $course->id)
            ->with('success', 'Kurs güncellendi.');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Kurs silindi.');
    }

    // ── Bölüm (Section) işlemleri ─────────────────────────────────────
    public function storeSection(Request $request, Course $course)
    {
        $data = $request->validate(['title' => 'required|string|max:255', 'order' => 'nullable|integer']);
        $course->sections()->create(['title' => $data['title'], 'order' => $data['order'] ?? 0]);
        return back()->with('success', 'Bölüm eklendi.');
    }

    public function destroySection(Section $section)
    {
        $section->delete();
        return back()->with('success', 'Bölüm silindi.');
    }

    // ── Ders (Lesson) işlemleri ────────────────────────────────────────
    public function storeLesson(Request $request, Section $section)
    {
        $data = $request->validate([
            'title'     => 'required|string|max:255',
            'type'      => 'required|in:VIDEO,DOCUMENT,QUIZ,SIMULATION',
            'video_url' => 'nullable|url',
            'video'     => 'nullable|file|mimes:mp4,mov,avi,mkv,webm|max:512000',
            'duration'  => 'nullable|integer|min:0',
            'order'     => 'nullable|integer',
            'is_free'   => 'boolean',
            'content'   => 'nullable|string',
        ]);

        $lesson = new Lesson();
        $lesson->section_id = $section->id;
        $lesson->title      = $data['title'];
        $lesson->type       = $data['type'];
        $lesson->video_url  = $data['video_url'] ?? null;
        $lesson->duration   = $data['duration'] ?? null;
        $lesson->order      = $data['order'] ?? 0;
        $lesson->is_free    = $request->boolean('is_free');
        $lesson->content    = $data['content'] ?? null;

        // Video dosyası yükleme
        if ($request->hasFile('video') && $request->file('video')->isValid()) {
            $path = $request->file('video')->store('lessons/videos', 'public');
            $lesson->video_path = $path;
            $lesson->video_disk = 'public';
            // Süreyi videonun boyutundan tahmin etme (gerçek süre için ffprobe gerekir)
        }

        $lesson->save();

        return back()->with('success', 'Ders eklendi.');
    }

    public function updateLesson(Request $request, Lesson $lesson)
    {
        $data = $request->validate([
            'title'     => 'required|string|max:255',
            'type'      => 'required|in:VIDEO,DOCUMENT,QUIZ,SIMULATION',
            'video_url' => 'nullable|url',
            'video'     => 'nullable|file|mimes:mp4,mov,avi,mkv,webm|max:512000',
            'duration'  => 'nullable|integer|min:0',
            'order'     => 'nullable|integer',
            'is_free'   => 'boolean',
            'content'   => 'nullable|string',
        ]);

        $lesson->fill([
            'title'     => $data['title'],
            'type'      => $data['type'],
            'video_url' => $data['video_url'] ?? null,
            'duration'  => $data['duration'] ?? null,
            'order'     => $data['order'] ?? $lesson->order,
            'is_free'   => $request->boolean('is_free'),
            'content'   => $data['content'] ?? null,
        ]);

        if ($request->hasFile('video') && $request->file('video')->isValid()) {
            // Eski dosyayı sil
            if ($lesson->video_path) {
                Storage::disk('public')->delete($lesson->video_path);
            }
            $path = $request->file('video')->store('lessons/videos', 'public');
            $lesson->video_path = $path;
            $lesson->video_disk = 'public';
        }

        $lesson->save();

        return back()->with('success', 'Ders güncellendi.');
    }

    public function destroyLesson(Lesson $lesson)
    {
        if ($lesson->video_path) {
            Storage::disk('public')->delete($lesson->video_path);
        }
        $lesson->delete();
        return back()->with('success', 'Ders silindi.');
    }
}
