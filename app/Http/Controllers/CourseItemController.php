<?php

namespace App\Http\Controllers;

use App\Models\CourseItem;
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

                $courseItem = CourseItem::create([
                    'name' => $request->input('name'),
                    'description' => $request->input('description'),
                    'course_section_id' => $course_section_id,
                ]);
    
                // Create new material
                Material::create([
                    'id' => $courseItem->id,
                    'file_url' => $url,
                    'material_type' => 'pdf',
                ]);
    
                return redirect()->route('course.show', ['id' => $courseItem->course_section_id]);
            } else {
                return redirect()->back()->withErrors(['error' => 'Invalid type']);
            }
        }
        catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
