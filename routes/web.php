<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\ExamController as AdminExamController;
use App\Http\Controllers\Admin\QuestionController as AdminQuestionController;
use App\Http\Controllers\Admin\ResultController as AdminResultController;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Student\ExamController as StudentExamController;
use App\Http\Controllers\Student\ResultController as StudentResultController;
use App\Http\Controllers\Auth\GoogleController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Google Authentication Routes
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Auth::routes(['register' => true]);

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/results/{result}/code', [AdminResultController::class, 'viewCode'])->name('results.viewcode');
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::get('/results/{result}/grade', [AdminResultController::class, 'grade'])->name('results.grade');
    Route::post('/results/{result}/grade', [AdminResultController::class, 'saveGrade'])->name('results.saveGrade');

    // Exam management
    Route::resource('exams', AdminExamController::class);

    // Question management
    Route::get('exams/{exam}/questions', [AdminQuestionController::class, 'index'])->name('questions.index');
    // ... (rest of your admin routes)
    Route::get('/results', [AdminResultController::class, 'index'])->name('results.index');
    Route::get('/results/{result}/download', [AdminResultController::class, 'download'])->name('results.download');
});

// Student routes
Route::prefix('student')->name('student.')->middleware(['auth', 'role:student'])->group(function () {
    // This route is named 'student.dashboard'
    Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('dashboard');
    
    Route::post('/compile', [StudentExamController::class, 'compile'])->name('exams.compile');
    Route::get('/exams', [StudentExamController::class, 'index'])->name('exams.index');
    Route::get('/exams/{exam}/start', [StudentExamController::class, 'start'])->name('exams.start');
    Route::get('/exams/{exam}/take', [StudentExamController::class, 'take'])->name('exams.take');
    Route::post('/exams/{exam}/submit', [StudentExamController::class, 'submit'])->name('exams.submit');
    Route::get('/results', [StudentResultController::class, 'index'])->name('results.index');
    Route::get('/results/{result}/download', [StudentResultController::class, 'download'])->name('results.download');
});