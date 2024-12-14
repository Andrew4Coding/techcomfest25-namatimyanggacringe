<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Livewire\Quiz;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Course\CourseController;
use App\Http\Controllers\Course\CourseItemController;
use App\Http\Controllers\Course\CourseSectionController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\SubmissionItemController;
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

Route::get('/', function () {
    return view('home');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/courses', [CourseController::class, 'showCourses'])->name('courses');
    Route::get('/courses/{id}', [CourseController::class, 'showCourse'])->name('course.show');
    Route::delete('/courses/delete/{id}', [CourseController::class, 'deleteCourse'])->name('course.delete');
    Route::put('/courses/update/{id}', [CourseController::class, 'updateCourse'])->name('course.update');

    Route::post('/courses/enroll', [CourseController::class, 'enrollCourse'])->name('course.enroll');
    Route::post('/courses/unenroll', [CourseController::class, 'unenrollCourse'])->name('course.unenroll');

    Route::post('/courses/{id}/sections', [CourseSectionController::class, 'createCourseSection'])->name('course.section.create');
    Route::delete('/courses/sections/delete/{id}', [CourseSectionController::class, 'deleteCourseSection'])->name('course.section.delete');
    Route::put('/courses/sections/update/{id}', [CourseSectionController::class, 'updateCourseSection'])->name('course.section.update');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });

    Route::get('/chat', [ChatController::class, 'showChat'])->name('chat.show');
    Route::post('/chat', [ChatController::class, 'sendMessage'])->name('chat.send');

    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');


    Route::post('/upload/{courseId}', [UploadFileController::class, 'uploadFile'])->name('course.upload.file');
});

Route::post('/courses/create', [CourseController::class, 'createNewCourse'])->name('course.create');

// Quiz
Route::middleware([])->group(function () {
    Route::get('/quiz', Quiz::class);

//    Route::get('/quiz/{id}', [QuizController::class, 'showQuizSession'])->name('quiz.show');
    Route::get('/quiz/{courseId}/create', [QuizController::class, 'showQuizCreation'])->name('quiz.alter');
    Route::get('/quiz/{courseId}/edit/{id}', [QuizController::class, 'showQuizAlteration'])->name('quiz.alter');

    Route::post('/quiz/generate', [QuizController::class, 'generateQuestionsFromPDF'])->name('quiz.generate');
    Route::post('/quiz/parse', [QuizController::class, 'parseQuestionsFromCSV'])->name('quiz.parse');
});


// Course Item
Route::middleware(['auth'])->group(function () {
    Route::post('/course/{course_section_id}/item/create', [CourseItemController::class, 'createCourseItem'])->name('course.item.create');
});


// Forum
Route::middleware(['auth'])->group(function () {
    Route::get('/forum/{courseId}', [ForumController::class, 'index'])->name('forum.index');
});


// Submission
Route::middleware(['auth'])->group(function () {
    Route::get('/submission/{submissionId}', [SubmissionController::class, 'show'])->name('submission.show');
    Route::post('/submission/{courseSectionId}/create', [SubmissionController::class, 'createSubmissionField'])->name('submission.create');
    Route::delete('/submission/{submissionId}/delete', [SubmissionController::class, 'deleteSubmissionField'])->name('submission.delete');

    Route::post('/submission/{submissionId}/submit', [SubmissionItemController::class, 'submitToSubmission'])->name('submission.submit');
});



Route::get('/upload-pdf', [UploadFileController::class, 'showFileForm'])->name('pdf.upload.form');
Route::post('/upload-pdf', [UploadFileController::class, 'processUpload'])->name('pdf.upload.process');
