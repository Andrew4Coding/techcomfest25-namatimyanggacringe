<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{   
    public function showProfile()
    {
        return view('profile.profile');
    }

    public function showProfileEdit()
    {
        return view('profile.profile_edit');
    }

    public function updateProfile(Request $request)
    {

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'phone_number' => 'required|string|max:15',
                'nisn' => ['required_if:role,student', 'string', 'max:255'],
                'class' => ['required_if:role,student', 'string', 'max:255'],
            ]);

            // Check if edited phone number is unique in the database
            $phone_number = $request->phone_number;
            $email = $request->email;
            $user = User::where('phone_number', $phone_number)->orWhere('email', $email)->first();

            if ($user && $user->id != $request->user()->id) {
                return redirect()->back()->withErrors(['phone_number' => 'Phone number or email already exists']);
            }

            $user = $request->user();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone_number = $request->phone_number;
    
            $user->save();

            // Edit Student
            if ($user->userable_type == 'App\Models\Student') {
                // Check if nisn is unique in the database
                $nisn = $request->nisn;
                $student = Student::where('nisn', $nisn)->first();

                if ($student && $student->user->id != $request->user()->id) {
                    return redirect()->back()->with('error', 'NISN already exists');
                }

                $student = $user->userable;
                $student->nisn = $request->nisn;
                $student->class = $request->class;
                $student->save();
            }

            return redirect()->route('profile')->with('success', 'Profile updated successfully');
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating profile ' . $e->getMessage());
        }
    }
}
