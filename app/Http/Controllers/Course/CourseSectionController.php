<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CourseSectionController extends Controller
{
    public function createCourseSection(Request $request, string $id): RedirectResponse
    {
        try {
            $course = Course::findOrFail($id);
            $course->courseSections()->create([
                'name' => $request->input('name'),
            ]);

            return redirect()->route('course.show.edit', ['id' => $course->id, 'course' => $course]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error creating course section']);
        }
    }

    public function deleteCourseSection(string $id): RedirectResponse
    {
        try {
            $courseSection = CourseSection::findOrFail($id);
            $courseSection->delete();

            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error deleting course section ' . $e->getMessage()]);
        }
    }

    public function updateCourseSection(Request $request, string $id): RedirectResponse
    {
        try {
            $courseSection = CourseSection::findOrFail($id);
            $courseSection->update([
                'name' => $request->input('name'),
            ]);

            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error updating course section ' . $e->getMessage()]);
        }
    }

    public function toggleVisibility(string $id)
    {
        try {
            $courseSection = CourseSection::findOrFail($id);
            $courseSection->is_public = !$courseSection->is_public;
            $courseSection->save();

            // Hide all course items inside the section
            foreach ($courseSection->courseItems as $courseItem) {
                $courseItem->is_public = $courseSection->is_public;
                $courseItem->save();
            }

            return response()->json(['success' => true, 'is_public' => $courseSection->is_public, 'message' => 'Course section visibility updated']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'Error updating course section visibility'], 500);
        }
    }
}
