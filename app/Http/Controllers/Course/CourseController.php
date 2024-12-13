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

    public function enrollCourse(Request $request) {
        try {

            $user = Auth::user();

            if ($user->userable_type == 'App\Models\Teacher') {
                return redirect()->back()->withErrors(['error' => 'Teachers cannot enroll in courses']);
            }
    
            // Find course by class code
            $course = Course::where('class_code', $request->input('class_code'))->first();
    
            // Check if course exists
            if (!$course) {
                return redirect()->back()->withErrors(['error' => 'Course not found']);
            }
    
            // Check if user is already enrolled in course
            if ($user->userable->courses->contains($course)) {
                return redirect()->back()->withErrors(['error' => 'You are already enrolled in this course']);
            }
    
            // Enroll user in course
            $user->userable->courses()->attach($course);
    
            $courses = $user->userable->courses;
    
            return redirect()->route('courses', compact('courses'));
        }
        catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error enrolling in course']);
        }
    }

    public function unenrollCourse(Request $request) {
        $user = Auth::user();

        // Find course by class code
        $course = Course::where('class_code', $request->input('class_code'))->first();

        // Check if course exists
        if (!$course) {
            return redirect()->back()->withErrors(['error' => 'Course not found']);
        }

        // Check if user is already enrolled in course
        if (!$user->userable->courses->contains($course)) {
            return redirect()->back()->withErrors(['error' => 'You are not enrolled in this course']);
        }

        // Unenroll user in course
        $user->userable->courses()->detach($course);

        $courses = $user->userable->courses;

        return redirect()->route('courses', compact('courses'));
    }

    public function showCourses(Request $request): View
    {
        $courses = Course::get();
        $student = Auth::user()->userable;

        if ($student) {
            $courses = $student->courses;
            return view('course.courses', compact('courses'));
        }

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

    public function updateCourse(Request $request, string $id)
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

            return redirect()->route('course.show', ['id' => $id]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withErrors(['error' => 'Error updating course']);
        }

    }
}
