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
            dd($e);
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
            dd($e);
            return redirect()->back()->withErrors(['error' => 'Error deleting course section']);
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
            dd($e);
            return redirect()->back()->withErrors(['error' => 'Error updating course section']);
        }
    }

    public function toggleVisibility(string $id): RedirectResponse
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

            return redirect()->back();
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withErrors(['error' => 'Error updating course section visibility']);
        }
    }
}
