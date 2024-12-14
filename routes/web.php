<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ForumDiscussionController;
use App\Http\Middleware\TeacherMiddleware;
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
        return view('dashboard.dashboard');
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
    Route::get('/quiz', Quiz::class)->name('quiz.show');

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
    Route::get('/forum/{forumId}', [ForumController::class, 'index'])->name('forum.index');
    Route::post('/forum/{forumId}/discussion/create', [ForumController::class, 'createNewDiscussion'])->name('forum.discussion.create');
    Route::get('/forum/{forumId}/discussion/{discussionId}', [ForumDiscussionController::class, 'index'])->name('forum.discussion.index');
    Route::post('/forum/{forumId}/discussion/{discussionId}/reply', [ForumDiscussionController::class, 'replyToDiscussion'])->name('forum.discussion.reply');

    Route::middleware([TeacherMiddleware::class])->group(function () {
        Route::post('/forum/{courseSectionId}/create', [ForumController::class, 'create'])->name('forum.create');
    });
});


// Submission
Route::middleware(['auth'])->group(function () {
    Route::get('/submission/{submissionId}', [SubmissionController::class, 'show'])->name('submission.show');
    Route::post('/submission/{submissionId}/submit', [SubmissionItemController::class, 'submitToSubmission'])->name('submission.submit');

    Route::middleware([TeacherMiddleware::class])->group(function () {
        Route::post('/submission/{courseSectionId}/create', [SubmissionController::class, 'createSubmissionField'])->name('submission.create');
        Route::delete('/submission/{submissionId}/delete', [SubmissionController::class, 'deleteSubmissionField'])->name('submission.delete');
        Route::put('/submission/{submissionId}/update', [SubmissionController::class, 'updateSubmissionField'])->name('submission.update');
        Route::post('/submission/{submissionItemId}/grade', [SubmissionItemController::class, 'gradeAndCommentSubmission'])->name('submission.grade');
    });
});



Route::get('/upload-pdf', [UploadFileController::class, 'showFileForm'])->name('pdf.upload.form');
Route::post('/upload-pdf', [UploadFileController::class, 'processUpload'])->name('pdf.upload.process');
