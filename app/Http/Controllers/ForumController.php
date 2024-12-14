<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Forum;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    public function index(string $forumId)
    {
        $forum = Forum::findOrFail($forumId);

        return view('forum.index', ['forum' => $forum]);
    }

    public function show($id)
    {
        return view('forum.show', ['id' => $id]);
    }

    public function create(Request $request, string $courseSectionId)
    {
        $newCourseItem = new Forum();
        $newCourseItem->creator_id = request()->user()->id;
        $newCourseItem->save();

        $newCourseItem->courseItem()->create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'course_section_id' => $courseSectionId,
        ]);

        return redirect()->route('course.show', ['id' => $courseSectionId]);
        
    }
}
