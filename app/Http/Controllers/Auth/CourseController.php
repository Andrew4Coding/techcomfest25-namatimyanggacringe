<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{

    public function showCourses() {
        $courses = Course::with('sections')->get();
        return view('course.courses', compact('courses'));
    }

    public function showCourse(
        string $id
    ) {
        $course = Course::with('sections')->findOrFail($id);
        $sections = $course->sections;

        return view('course.course_detail', compact('course'));
    }

    public function createCourseSection(Request $request, string $id) {
        $course = Course::findOrFail($id);
        $section = $course->sections()->create($request->all());
        return redirect()->route('course.show', $course->id);
    }
}
