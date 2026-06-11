<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\Admin\AdminCertificateController;
use App\Http\Controllers\Admin\AdminCourseController;
use App\Http\Controllers\Admin\AdminQuizController;
use App\Http\Controllers\Admin\AdminUserController;
use Illuminate\Support\Facades\Route;

// ── Dil değiştir ────────────────────────────────────────────────────────
Route::get('/lang/{locale}', function (string $locale) {
    $allowed = ['tr','en','de','zh','az','ru','ar','ka'];
    if (in_array($locale, $allowed)) {
        session(['locale' => $locale]);
    }
    return back();
})->name('lang.switch');

// ── Chat ────────────────────────────────────────────────────────────────
Route::post('/chat', [ChatController::class, 'send'])->name('chat.send');

// ── Public ──────────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login',   [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register',[AuthController::class, 'register']);
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');

// Courses
Route::get('/courses',        [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('courses.show');

// Quizzes public
Route::get('/quizzes',        [QuizController::class, 'index'])->name('quizzes.index');
Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');

// ── Authenticated ────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/dashboard',               [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/courses/{slug}/enroll',  [CourseController::class, 'enroll'])->name('courses.enroll');
    Route::get('/courses/{slug}/learn',    [CourseController::class, 'learn'])->name('courses.learn');

    // Video ders tamamlama (AJAX POST)
    Route::post('/courses/{slug}/lessons/{lesson}/complete', [CourseController::class, 'completeLesson'])->name('courses.lesson.complete');

    // Kısmi video ilerleme kaydet (AJAX POST)
    Route::post('/courses/{slug}/lessons/{lesson}/progress', [CourseController::class, 'saveProgress'])->name('courses.lesson.progress');

    // Sınavlar
    Route::get('/quizzes/{quiz}/take',    [QuizController::class, 'take'])->name('quizzes.take');
    Route::post('/quizzes/{quiz}/submit', [QuizController::class, 'submit'])->name('quizzes.submit');
    Route::get('/quiz-results/{attempt}', [QuizController::class, 'result'])->name('quizzes.result');

    // Sertifikalar
    Route::get('/certificates', fn() => view('certificates.index'))->name('certificates.index');
    Route::get('/certificates/{id}', function ($id) {
        $cert = \App\Models\Certificate::with(['user', 'course'])->findOrFail($id);
        abort_if($cert->user_id !== auth()->id(), 403);
        return view('certificates.show', compact('cert'));
    })->name('certificates.show');
});

// ── Admin ────────────────────────────────────────────────────────────────
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',          fn() => redirect()->route('admin.dashboard'))->name('index');
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');

    // Kurs CRUD
    Route::get('/courses',              [AdminCourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/create',       [AdminCourseController::class, 'create'])->name('courses.create');
    Route::post('/courses',             [AdminCourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}',     [AdminCourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/{course}/edit',[AdminCourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}',     [AdminCourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}',  [AdminCourseController::class, 'destroy'])->name('courses.destroy');

    // Sertifika ön koşulları
    Route::post('/courses/{course}/cert-config', [AdminCourseController::class, 'updateCertConfig'])->name('courses.cert-config');

    // Bölüm işlemleri
    Route::post('/courses/{course}/sections',  [AdminCourseController::class, 'storeSection'])->name('sections.store');
    Route::delete('/sections/{section}',        [AdminCourseController::class, 'destroySection'])->name('sections.destroy');

    // Ders işlemleri
    Route::post('/sections/{section}/lessons', [AdminCourseController::class, 'storeLesson'])->name('lessons.store');
    Route::put('/lessons/{lesson}',             [AdminCourseController::class, 'updateLesson'])->name('lessons.update');
    Route::delete('/lessons/{lesson}',           [AdminCourseController::class, 'destroyLesson'])->name('lessons.destroy');

    // Quiz CRUD
    Route::get('/quizzes',                [AdminQuizController::class, 'index'])->name('quizzes.index');
    Route::get('/quizzes/create',         [AdminQuizController::class, 'create'])->name('quizzes.create');
    Route::post('/quizzes',               [AdminQuizController::class, 'store'])->name('quizzes.store');
    Route::get('/quizzes/{quiz}',         [AdminQuizController::class, 'show'])->name('quizzes.show');
    Route::get('/quizzes/{quiz}/edit',    [AdminQuizController::class, 'edit'])->name('quizzes.edit');
    Route::put('/quizzes/{quiz}',         [AdminQuizController::class, 'update'])->name('quizzes.update');
    Route::delete('/quizzes/{quiz}',      [AdminQuizController::class, 'destroy'])->name('quizzes.destroy');
    Route::post('/quizzes/{quiz}/toggle', [AdminQuizController::class, 'toggleActive'])->name('quizzes.toggle');

    // Soru işlemleri
    Route::post('/quizzes/{quiz}/questions',   [AdminQuizController::class, 'storeQuestion'])->name('questions.store');
    Route::put('/questions/{question}',         [AdminQuizController::class, 'updateQuestion'])->name('questions.update');
    Route::delete('/questions/{question}',       [AdminQuizController::class, 'destroyQuestion'])->name('questions.destroy');

    // Sertifika CRUD
    Route::get('/certificates',                       [AdminCertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/create',                [AdminCertificateController::class, 'create'])->name('certificates.create');
    Route::post('/certificates',                      [AdminCertificateController::class, 'store'])->name('certificates.store');
    Route::get('/certificates/{certificate}',         [AdminCertificateController::class, 'show'])->name('certificates.show');
    Route::get('/certificates/{certificate}/edit',    [AdminCertificateController::class, 'edit'])->name('certificates.edit');
    Route::put('/certificates/{certificate}',         [AdminCertificateController::class, 'update'])->name('certificates.update');
    Route::delete('/certificates/{certificate}',      [AdminCertificateController::class, 'destroy'])->name('certificates.destroy');

    // Kullanıcı yönetimi
    Route::get('/users',                    [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/progress',           [AdminUserController::class, 'progress'])->name('users.progress');
    Route::get('/users/{user}',             [AdminUserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit',        [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}',             [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}',          [AdminUserController::class, 'destroy'])->name('users.destroy');
});
