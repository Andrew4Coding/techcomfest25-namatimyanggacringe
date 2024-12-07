<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseItem;
use App\Models\CourseSection;
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
        $course = Course::findOrFail($id);
        $courseSections = CourseSection::with('courseItems')->where('course_id', $id)->orderBy('created_at', 'asc')->get();

        return view('course.course_detail', compact('course', 'courseSections'));
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

    public function deleteCourse(string $id)
    {
        try {
            Course::findOrFail($id)->delete();
            return redirect()->route('courses');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error deleting course']);
        }
    }

    public function editCourse(Request $request, string $id)
    {
        // Validate input
        $request->validate([
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'class_code' => ['required', 'string', 'size:5'],
        ]);

        try {
            Course::findOrFail($id)->update([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'class_code' => $request->input('class_code'),
            ]);

            return redirect()->route('course', ['id' => $id]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error updating course']);
        }

    }
}
