<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseItem;
use App\Models\CourseSection;
use App\Models\Material;
use App\Models\Quiz;
use Illuminate\Http\Request;

class CourseItemController extends Controller
{
    public function createCourseItem(Request $request, string $course_section_id)
    {
        try {
            $type = $request->query('type');

            if ($type == "material") {
                // Validate input
                $request->validate([
                    'name' => ['required', 'string'],
                    'file' => ['required', 'file'],
                ]);

                // Upload file to aws
                $file = $request->file('file');
                $fileName = $file->getClientOriginalName();
                $filePath = $file->storeAs("uploads/{$course_section_id}", $fileName, 's3');

                $url = env('AWS_URL') . $filePath;

                $newCourseItem = new Material();
                $newCourseItem->file_url = $url;
                $newCourseItem->material_type = 'pdf';
                $newCourseItem->save();

                $newCourseItem->courseItem()->create([
                    'name' => $request->input('name'),
                    'description' => $request->input('description'),
                    'course_section_id' => $course_section_id,
                ]);

                $course_section = CourseSection::findOrFail($course_section_id);
                $course = Course::findOrFail($course_section->course_id);

                return redirect()->route('course.show.edit', ['id' => $course->id]);
            } else if ($type === 'quiz') {
                $request->validate([
                    'name' => ['required', 'string'],
                    'description' => ['required', 'string'],
                    'start' => ['required', 'string'],
                    'end' => ['required', 'string'],
                ]);

                $newCourseItem = new Quiz();

                $newCourseItem->courseItem()->create([
                    'name' => $request->input('name'),
                    'description' => $request->input('description'),
                    'course_section_id' => $course_section_id,
                ]);

                $course_section = CourseSection::findOrFail($course_section_id);
                $course = Course::findOrFail($course_section->course_id);

                $newCourseItem->save([
                   'start' => $request->input('start'),
                   'end' => $request->input('end'),
                   'duration' => 3600,
                ]);

                return redirect()->route('quiz.edit', ['id' => $newCourseItem->id]);
            } else {
                return redirect()->back()->withErrors(['error' => 'Invalid type']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function deleteCourseItem(string $id)
    {
        try {
            $courseItem = CourseItem::findOrFail($id);
            $courseItem->courseItemable->delete();
            $courseItem->delete();

            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Error deleting course item']);
        }
    }

    public function toggleVisibility(string $id)
    {
        try {
            $courseItem = CourseItem::findOrFail($id);
            $courseItem->is_public = !$courseItem->is_public;
            $courseItem->save();

            return response()->json(['success' => true, 'is_public' => $courseItem->is_public, 'message' => 'Visibility toggled successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'Error toggling visibility'], 500);
        }
    }
}
