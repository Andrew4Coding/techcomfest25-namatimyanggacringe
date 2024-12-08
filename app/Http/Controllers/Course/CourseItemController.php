<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseItem;
use App\Models\CourseSection;
use App\Models\Material;
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
                    'description' => ['required', 'string'],
                    'file' => ['required', 'file'],
                ]);
    
                // Upload file to aws 
                $file = $request->file('file');
                $fileName = $file->getClientOriginalName();
                $filePath = $file->storeAs("uploads/{$course_section_id}", $fileName, 's3');
    
                $url = env('AWS_PATH') . $filePath;

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

                return redirect()->route('course.show', ['id' => $course->id]);
            } else {
                return redirect()->back()->withErrors(['error' => 'Invalid type']);
            }
        }
        catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
