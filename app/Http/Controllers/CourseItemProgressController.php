<?php

namespace App\Http\Controllers;

use App\Models\CourseItem;
use Illuminate\Http\Request;

class CourseItemProgressController extends Controller
{
    public function checkCourseItem(Request $request, $courseItemId)
    {
        try {
            $user = $request->user();
            $courseItem = CourseItem::findOrFail($courseItemId);

            $is_completed = $request->input('is_completed');

            if ($is_completed) {
                // Make new course item progress if not exists, edit if exists
                $courseItem->courseItemProgress()->updateOrCreate([
                    'user_id' => $user->id,
                ], [
                    'is_completed' => true,
                ]);
            } else {
                // Delete the corresponding course item progress if exists
                $courseItem->courseItemProgress()->where('user_id', $user->id)->delete();
            }

            $courseItem->save();
    
            return response()->json(['message' => 'Course item progress updated']);
        }
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
