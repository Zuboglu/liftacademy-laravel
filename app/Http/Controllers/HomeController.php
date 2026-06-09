<?php

namespace App\Http\Controllers;

use App\Models\Course;

class HomeController extends Controller
{
    public function index()
    {
        $featuredCourses = Course::where('published', true)
            ->withCount('enrollments')
            ->orderByDesc('enrollments_count')
            ->take(6)
            ->get();

        return view('home', compact('featuredCourses'));
    }
}
