<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ForumDiscussionController;
use App\Http\Middleware\TeacherMiddleware;
use App\Livewire\Quiz;
use App\Livewire\QuizSolution;
use App\Livewire\QuizTeacher;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Course\CourseController;
use App\Http\Controllers\Course\CourseItemController;
use App\Http\Controllers\Course\CourseSectionController;
use App\Http\Controllers\CourseItemProgressController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FlashCardController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\SubmissionItemController;
use App\Http\Controllers\UploadFileController;

Route::get('/', function () {
    return view('home.home');
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
    Route::get('/chat', [ChatController::class, 'showChat'])->name('chat.show');
    Route::post('/chat', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::post('/upload/{courseId}', [UploadFileController::class, 'uploadFile'])->name('course.upload.file');
});

// Profile
Route::prefix('profile')->middleware(['auth'])->group(function () {
    Route::get('/', [ProfileController::class, 'showProfile'])->name('profile');
    Route::get('/edit', [ProfileController::class, 'showProfileEdit'])->name('profile.update');
    Route::post('/edit/post', [ProfileController::class, 'updateProfile'])->name('profile.update.update');
});

// Courses
Route::prefix('courses')->middleware(['auth'])->group(function () {
    Route::get('/', [CourseController::class, 'showCourses'])->name('courses');
    Route::get('/{id}', [CourseController::class, 'showCourse'])->name('course.show');
    Route::post('/enroll', [CourseController::class, 'enrollCourse'])->name('course.enroll');
    Route::post('/unenroll/{courseId}', [CourseController::class, 'unenrollCourse'])->name('course.unenroll');

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

    Route::get('/submit/{quizId}', [QuizController::class, 'submitQuiz'])->name('quiz.submit');

    Route::get('/check/{quizId}', QuizSolution::class)->name('quiz.solution');


    Route::middleware([TeacherMiddleware::class])->group(function () {
        Route::get('/edit/{quizId}', QuizTeacher::class)->name('quiz.edit');
    });
    Route::post('/{courseSectionId}/store', [QuizController::class, 'store'])->name('quiz.store');
});

// Forum
Route::prefix('forum')->middleware(['auth'])->group(function () {
    Route::get('/', [ForumController::class, 'show_list_main'])->name('forum.show');
    Route::get('/{forumId}', [ForumController::class, 'index'])->name('forum.index');
    Route::post('/{forumId}/discussion/create', [ForumController::class, 'createNewDiscussion'])->name('forum.discussion.create');
    Route::get('/{forumId}/discussion/{discussionId}', [ForumDiscussionController::class, 'index'])->name('forum.discussion.index');
    Route::post('/{forumId}/discussion/{discussionId}/reply', [ForumDiscussionController::class, 'replyToDiscussion'])->name('forum.discussion.reply');

    Route::middleware([TeacherMiddleware::class])->group(function () {
        Route::post('/{courseSectionId}/create', [ForumController::class, 'create'])->name('forum.create');
        Route::post('/forum-reply/verify/{forumReplyId}', [ForumDiscussionController::class, 'toggleVerified'])->name('forum.reply.verify');
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

// FlashCard
Route::prefix('flashcard')->middleware(['auth'])->group(function () {
    Route::get('/', [FlashCardController::class, 'show'])->name('flashcard.show');
    Route::get('/{id}', [FlashCardController::class, 'index'])->name('flashcard.index');
    Route::post('/create', [FlashCardController::class, 'create'])->name('flashcard.create');
    Route::delete('/delete/{id}', [FlashCardController::class, 'delete'])->name('flashcard.delete');
    Route::put('/update/{id}', [FlashCardController::class, 'update'])->name('flashcard.update');
});

// Course Item Progress
Route::middleware(['auth'])->group(function () {
    Route::post('/course-item/check/{courseItemId}', [CourseItemProgressController::class, 'checkCourseItem'])->name('course.item.check');
});

// Dashboard
Route::prefix('dashboard')->middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'show'])->name('dashboard');

    Route::middleware([TeacherMiddleware::class])->group(function () {
        Route::post('/sendMessage/{studentId}', [DashboardController::class, 'sendStudentMessage'])->name('dashboard.teacher.send');
    });
});


// Forum
Route::prefix('forum')->middleware(['auth'])->group(function () {
    Route::get('/{forumId}', [ForumController::class, 'index'])->name('forum.index');
});
