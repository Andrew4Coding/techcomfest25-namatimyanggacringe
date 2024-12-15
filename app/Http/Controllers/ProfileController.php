<?php

namespace App\Http\Controllers;

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
            ]);
    
            $user = $request->user();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone_number = $request->phone_number;
    
            $user->save();

            return redirect()->route('profile')->with('success', 'Profile updated successfully');
        }
        catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('error', 'An error occurred while updating profile');
        }
    }
}
