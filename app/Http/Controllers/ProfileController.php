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

    public function updateProfile(Request $request)
    {
        try {

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'phone' => 'required|string|max:15',
                'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
    
            $user = $request->user();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
    
            $profilePicture = $user->profile_picture;
    
            // Delete old image from AWS S3
            if ($request->hasFile('profile_picture') && $profilePicture) {
                Storage::disk('s3')->delete($profilePicture);
            }
            
            if ($request->hasFile('profile_picture')) {
                $fileName = $profilePicture->getClientOriginalName();
                $filePath = $profilePicture->storeAs('profile_pictures', $fileName, 's3');
            }
    
            $user->profile_picture = $filePath ?? null;
    
            $user->save();
    
            return redirect()->back()->with('success', 'Profile updated successfully');
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating profile');
        }
    }
}
