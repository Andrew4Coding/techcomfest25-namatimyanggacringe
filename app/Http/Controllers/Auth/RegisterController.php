<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect('/');
    }
}
