<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login',   [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register',[AuthController::class, 'register']);
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');

// Courses (public listing)
Route::get('/courses',               [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{slug}',        [CourseController::class, 'show'])->name('courses.show');

// Authenticated
Route::middleware('auth')->group(function () {
    Route::get('/dashboard',                [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/courses/{slug}/enroll',   [CourseController::class, 'enroll'])->name('courses.enroll');
    Route::get('/courses/{slug}/learn',     [CourseController::class, 'learn'])->name('courses.learn');
    Route::get('/certificates',             fn() => view('certificates.index'))->name('certificates.index');
    Route::get('/certificates/{id}',        fn() => view('certificates.show'))->name('certificates.show');
});

// Admin
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn() => redirect()->route('admin.dashboard'))->name('index');
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
});
