<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Progress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount(['enrollments', 'certificates'])
            ->orderByDesc('created_at');

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->q.'%')
                  ->orWhere('email', 'like', '%'.$request->q.'%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->paginate(20)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['enrollments.course', 'certificates.course']);

        // İlerleme durumu
        $enrollmentsWithProgress = $user->enrollments->map(function ($enrollment) {
            $course = $enrollment->course;
            if (!$course) return null;

            $allLessons = $course->sections()->with('lessons')->get()
                ->flatMap(fn($s) => $s->lessons);

            $completedCount = Progress::where('user_id', $enrollment->user_id)
                ->whereIn('lesson_id', $allLessons->pluck('id'))
                ->where('completed', true)
                ->count();

            $total = $allLessons->count();
            $enrollment->progress_detail = [
                'completed' => $completedCount,
                'total'     => $total,
                'percent'   => $total > 0 ? round($completedCount / $total * 100) : 0,
            ];
            return $enrollment;
        })->filter();

        return view('admin.users.show', compact('user', 'enrollmentsWithProgress'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,'.$user->id,
            'role'       => 'required|in:STUDENT,INSTRUCTOR,ADMIN,SUPERVISOR',
            'phone'      => 'nullable|string|max:50',
            'department' => 'nullable|string|max:255',
            'employee_id'=> 'nullable|string|max:100',
            'password'   => 'nullable|string|min:8',
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'Kullanıcı güncellendi.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'Kullanıcı silindi.');
    }

    // Progress sayfası – tüm kullanıcıların ilerleme özeti
    public function progress(Request $request)
    {
        $query = Enrollment::with(['user', 'course'])
            ->orderByDesc('created_at');

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        $enrollments = $query->paginate(30)->withQueryString();

        // Her kayıt için progress hesapla
        $enrollments->each(function ($enrollment) {
            $allLessons = $enrollment->course?->sections()
                ->with('lessons')->get()
                ->flatMap(fn($s) => $s->lessons) ?? collect();

            $completed = Progress::where('user_id', $enrollment->user_id)
                ->whereIn('lesson_id', $allLessons->pluck('id'))
                ->where('completed', true)
                ->count();

            $total = $allLessons->count();
            $enrollment->progress_pct    = $total > 0 ? round($completed / $total * 100) : 0;
            $enrollment->progress_done   = $completed;
            $enrollment->progress_total  = $total;
        });

        $courses = \App\Models\Course::where('published', true)->orderBy('title')->get();
        return view('admin.users.progress', compact('enrollments', 'courses'));
    }
}
