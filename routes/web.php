<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseItemController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\UploadFileController;

// Register
Route::get('/register', [RegisterController::class, 'showPickRole'])->name('role.select');
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

Route::post('/courses/create', [CourseController::class, 'createNewCourse'])->name('course.create');


// Quiz
Route::middleware(['auth'])->group(function () {
    Route::get('/quiz/{courseId}/session/{id}', [QuizController::class, 'showQuizSession'])->name('quiz.show');
    Route::get('/quiz/{courseId}/create', [QuizController::class, 'showQuizCreation'])->name('quiz.alter');
    Route::get('/quiz/{courseId}/edit/{id}', [QuizController::class, 'showQuizAlteration'])->name('quiz.alter');

    Route::post('/quiz/generate', [QuizController::class, 'generateQuestionsFromPDF'])->name('quiz.generate');
    Route::post('/quiz/parse', [QuizController::class, 'parseQuestionsFromCSV'])->name('quiz.parse');
});


// Course Item
Route::middleware(['auth'])->group(function () {
    Route::post('/course/{course_section_id}/item/create', [CourseItemController::class, 'createCourseItem'])->name('course.item.create');
});
