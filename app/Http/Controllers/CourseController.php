<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CourseController extends Controller
{

    public function showCourses(): View
    {
        $courses = Course::get();
        return view('course.courses', compact('courses'));
    }

    public function showCourse(
        string $id
    ): View {
        $course = Course::with(['courseSections.courseItems.itemable'])->findOrFail($id);

        return view('course.course_detail', compact('course'));
    }

    public function createNewCourse(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'class_code' => ['required', 'string', 'size:5'],
        ]);

        // Log current user
        $user = Auth::user();

        try {
            Course::create([
                'teacher_id' => $user->id,
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'class_code' => $request->input('class_code'),
            ]);

            $courses = Course::get();

            return redirect()->route('courses', compact('courses'));
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withErrors(['error' => 'Error creating course']);
        }
    }

    public function createCourseSection(Request $request, string $id): RedirectResponse
    {
        try {
            $course = Course::findOrFail($id);
            $course->courseSections()->create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
            ]);

            return redirect()->route('course.show', compact('course'));
        }
        catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withErrors(['error' => 'Error creating course section']);
        }
    }
}
