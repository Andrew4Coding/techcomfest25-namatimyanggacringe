<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Database\Factories\StudentFactory;
use Database\Factories\TeacherFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showPickRole()
    {
        return view('auth.register.pick_role');
    }

    public function showStudentRegistrationForm()
    {
        return view('auth.register.register_student');
    }

    public function showTeacherRegistrationForm()
    {
        return view('auth.register.register_teacher');
    }

    public function selectRole(Request $request)
    {
        // Validate the role input
        $validated = $request->validate([
            'role' => ['required', 'in:student,teacher'],
        ]);

        // Redirect based on the selected role
        if ($validated['role'] == 'student') {
            return redirect()->route('register.student');
        } elseif ($validated['role'] == 'teacher') {
            return redirect()->route('register.teacher');
        }

        // Default fallback (if role is not valid)
        return redirect()->route('role.select')->withErrors(['role' => 'Invalid role selected']);
    }

    public function register(Request $request)
    {
        try {
            $role = $request->input('role');

            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'phone_number' => ['required', 'string', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            ]);

            $profilePicture = $request->file('profile_picture');

            // Upload to s3
            if ($profilePicture) {
                $fileName = $profilePicture->getClientOriginalName();
                $filePath = $profilePicture->storeAs('profile_pictures', $fileName, 's3');
            }

            $data = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'verified' => 1,
                'phone_number' => $request->input('phone_number'),
                'password' => Hash::make($request->input('password')),
                'profile_picture' => $filePath ?? null,
            ];

            if ($role == 'student') {
                $newUser = new Student();
                $newUser->save();
                $newUser->user()->create($data);
            } else {
                $newUser = new Teacher();
                $newUser->save();
                $newUser->user()->create($data);
            }

            Auth::login($newUser->user);
            return redirect('/');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Registration failed. Please try again.']);
        }
    }
}
