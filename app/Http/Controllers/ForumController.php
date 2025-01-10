<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Forum;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    public function show_list(Request $request)
    {
        // Get All Forum which in the course enrolled by the user
        $user = $request->user();

        $courses = $user->courses()->get();

        return view('forum.list', compact('course'));
    }
    public function index(string $forumId)
    {
        $forum = Forum::findOrFail($forumId);

        $forum_discussions = $forum->discussions()->with('forum_replies')->orderBy('updated_at', 'desc')->get();

        return view('forum.index', compact('forum', 'forum_discussions'));
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

        $courseSection = CourseSection::findOrFail($courseSectionId);

        return redirect()->route('course.show.edit', ['id' => $courseSection->course->id]);
    }

    public function createNewDiscussion(Request $request, string $forumId)
    {
        try {
            $forum = Forum::findOrFail($forumId);
    
    
            $forum->discussions()->create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'creator_id' => request()->user()->id,
            ]);
    
            $forum_discussions = $forum->discussions()->get();

            return redirect()->route('forum.index', ['forumId' => $forumId]);
        }
        catch (\Exception $e) {
            dd($e);
        }
    }
}
