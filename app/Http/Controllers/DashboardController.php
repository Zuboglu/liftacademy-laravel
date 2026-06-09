<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $enrollments = Enrollment::where('user_id', $user->id)
            ->with(['course.sections.lessons'])
            ->latest()
            ->get();

        $certificates = Certificate::where('user_id', $user->id)
            ->with('course')
            ->latest('issued_at')
            ->take(5)
            ->get();

        $stats = [
            'enrollments'  => $enrollments->count(),
            'completed'    => $enrollments->whereNotNull('completed_at')->count(),
            'certificates' => $certificates->count(),
            'progress'     => $enrollments->isEmpty()
                ? 0
                : (int) $enrollments->avg(fn($e) => $e->progress_percent),
        ];

        return view('dashboard', compact('enrollments', 'certificates', 'stats'));
    }
}
