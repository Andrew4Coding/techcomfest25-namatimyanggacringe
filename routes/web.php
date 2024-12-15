<?php

use App\Http\Controllers\AttendanceController;
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

Route::get('/', function () {
    return view('home');
});

// Register
Route::prefix('register')->middleware(['guest'])->group(function () {
    Route::get('/', [RegisterController::class, 'showPickRole'])->name('role.select');
    Route::post('/', [RegisterController::class, 'selectRole']);

    Route::get('/student', [RegisterController::class, 'showStudentRegistrationForm'])->name('register.student');
    Route::post('/student', [RegisterController::class, 'register']);

    Route::get('/teacher', [RegisterController::class, 'showTeacherRegistrationForm'])->name('register.teacher');
    Route::post('/teacher', [RegisterController::class, 'register']);
});

// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


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

// Courses
Route::prefix('courses')->middleware(['auth'])->group(function () {
    Route::get('/', [CourseController::class, 'showCourses'])->name('courses');
    Route::get('/{id}', [CourseController::class, 'showCourse'])->name('course.show');
    Route::post('/enroll', [CourseController::class, 'enrollCourse'])->name('course.enroll');
    Route::post('/unenroll', [CourseController::class, 'unenrollCourse'])->name('course.unenroll');

    Route::middleware([TeacherMiddleware::class])->group(function () {
        // Course
        Route::get('/{id}/edit', [CourseController::class, 'showCourseEdit'])->name('course.show.edit');
        Route::post('/create', [CourseController::class, 'createNewCourse'])->name('course.create');
        Route::delete('/delete/{id}', [CourseController::class, 'deleteCourse'])->name('course.delete');
        Route::put('/update/{id}', [CourseController::class, 'updateCourse'])->name('course.update');

        // Course Section
        Route::post('/{id}/sections', [CourseSectionController::class, 'createCourseSection'])->name('course.section.create');
        Route::delete('/sections/delete/{id}', [CourseSectionController::class, 'deleteCourseSection'])->name('course.section.delete');
        Route::put('/sections/update/{id}', [CourseSectionController::class, 'updateCourseSection'])->name('course.section.update');
        Route::delete('/items/delete/{id}', [CourseItemController::class, 'deleteCourseItem'])->name('course.item.delete');
        Route::post('/sections/toggle/{id}', [CourseSectionController::class, 'toggleVisibility'])->name('course.section.toggle');

        // Course Item
        Route::post('/course/{course_section_id}/item/create', [CourseItemController::class, 'createCourseItem'])->name('course.item.create');
        Route::post('/items/toggle/{id}', [CourseItemController::class, 'toggleVisibility'])->name('course.item.toggle');
    });
});

// Quiz
Route::prefix('quiz')->middleware(['auth'])->group(function () {
    Route::get('/', Quiz::class)->name('quiz.show');

    Route::get('/{courseId}/create', [QuizController::class, 'showQuizCreation'])->name('quiz.alter');
    Route::get('/{courseId}/edit/{id}', [QuizController::class, 'showQuizAlteration'])->name('quiz.alter');

    Route::post('/generate', [QuizController::class, 'generateQuestionsFromPDF'])->name('quiz.generate');
    Route::post('/parse', [QuizController::class, 'parseQuestionsFromCSV'])->name('quiz.parse');

    Route::middleware([TeacherMiddleware::class])->group(function () {
        Route::post('/{courseSectionId}/store', [QuizController::class, 'store'])->name('quiz.store');
        Route::put('/{id}/update', [QuizController::class, 'update'])->name('quiz.update');
        Route::delete('/{id}/delete', [QuizController::class, 'destroy'])->name('quiz.delete');
    });
});

// Forum
Route::prefix('forum')->middleware(['auth'])->group(function () {
    Route::get('/{forumId}', [ForumController::class, 'index'])->name('forum.index');
    Route::post('/{forumId}/discussion/create', [ForumController::class, 'createNewDiscussion'])->name('forum.discussion.create');
    Route::get('/{forumId}/discussion/{discussionId}', [ForumDiscussionController::class, 'index'])->name('forum.discussion.index');
    Route::post('/{forumId}/discussion/{discussionId}/reply', [ForumDiscussionController::class, 'replyToDiscussion'])->name('forum.discussion.reply');

    Route::middleware([TeacherMiddleware::class])->group(function () {
        Route::post('/{courseSectionId}/create', [ForumController::class, 'create'])->name('forum.create');
    });
});

// Submission
Route::prefix('submission')->middleware(['auth'])->group(function () {
    Route::get('/{submissionId}', [SubmissionController::class, 'show'])->name('submission.show');
    Route::post('/{submissionId}/submit', [SubmissionItemController::class, 'submitToSubmission'])->name('submission.submit');

    Route::middleware([TeacherMiddleware::class])->group(function () {
        Route::post('/{courseSectionId}/create', [SubmissionController::class, 'createSubmissionField'])->name('submission.create');
        Route::delete('/{submissionId}/delete', [SubmissionController::class, 'deleteSubmissionField'])->name('submission.delete');
        Route::put('/{submissionId}/update', [SubmissionController::class, 'updateSubmissionField'])->name('submission.update');
        Route::post('/{submissionItemId}/grade', [SubmissionItemController::class, 'gradeAndCommentSubmission'])->name('submission.grade');
    });
});

// Attendance
Route::prefix('attendance')->middleware(['auth'])->group(function () {
    Route::get('/{id}', [AttendanceController::class, 'show'])->name('attendance.show');
    Route::post('/{id}/submit', [AttendanceController::class, 'submitAttendance'])->name('attendance.submit');
    
    Route::middleware([TeacherMiddleware::class])->group(function () {
        Route::post('/{courseSectionId}/store', [AttendanceController::class, 'store'])->name('attendance.store');
        Route::put('/{id}/update', [AttendanceController::class, 'update'])->name('attendance.update');
        Route::delete('/{id}/delete', [AttendanceController::class, 'destroy'])->name('attendance.delete');
    });
});

Route::get('/upload-pdf', [UploadFileController::class, 'showFileForm'])->name('pdf.upload.form');
Route::post('/upload-pdf', [UploadFileController::class, 'processUpload'])->name('pdf.upload.process');
