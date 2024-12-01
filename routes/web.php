<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ChatController;

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
    Route::get('/chat', [ChatController::class, 'showChat'])->name('chat.show');
    Route::post('/chat', [ChatController::class, 'sendMessage'])->name('chat.send');

    Route::get('/profile', function () {
        return view('profile');
    });
});

Route::middleware(['auth'])->group(function () {

});

// Main