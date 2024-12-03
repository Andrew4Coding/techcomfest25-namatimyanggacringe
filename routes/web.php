<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ChatController;
use App\Http\Controllers\Auth\CourseController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\UploadFileController;

// Register
Route::get('/register', [ RegisterController::class, 'showPickRole'])->name('role.select');
Route::post('/register', [RegisterController::class, 'selectRole']);

Route::get('/register/student', [RegisterController::class, 'showStudentRegistrationForm'])->name('register.student');
Route::post('/register/student', [RegisterController::class, 'register']);

Route::get('/register/teacher', [RegisterController::class, 'showTeacherRegistrationForm'])->name('register.teacher');
Route::post('/register/teacher', [RegisterController::class, 'register']);

// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('home');
    });

    Route::get('/courses', [CourseController::class, 'showCourses'])->name('courses');
    Route::get('/courses/{id}', [CourseController::class, 'showCourse'])->name('course.show');
    Route::post('/courses/{id}/sections', [CourseController::class, 'createCourseSection'])->name('course.section.create');

    Route::get('/chat', [ChatController::class, 'showChat'])->name('chat.show');
    Route::post('/chat', [ChatController::class, 'sendMessage'])->name('chat.send');

    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');


    Route::post('/upload/{courseId}', [UploadFileController::class, 'uploadFile'])->name('course.upload.file');
});



// Quiz
Route::middleware(['auth'])->group(function () {
    Route::get('/quiz/{id}', [QuizController::class, 'showQuiz'])->name('quiz.show');
});