<?php

namespace App\Http\Controllers;

use App\Models\CourseSection;
use App\Models\Forum;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class ForumController extends Controller
{
    public function show_list_main()
    {
        $user = request()->user();
        $courses = $user->userable->courses()->get();

        // Get All Forum which in the course enrolled by the user
        $forums = [];

        $allForums = Forum::all();

        foreach ($allForums as $forum) {
            if ($courses && $courses->contains($forum->courseItem->courseSection->course)) {
                $forums[] = $forum;
            }
        }

        return view('forum.show', compact('forums'));
    }

    // Show all discussions in a forum
    public function index(string $forumId)
    {
        $forum = Forum::findOrFail($forumId);


        if (!$forum->courseItem->is_public) {
            return redirect()->route('forum.show', ['id' => $forumId])->withErrors(['error' => 'This forum is not public']);
        }

        $search = request()->input('search');

        $forum_discussions = $forum->discussions()->with('forum_replies')->orderBy('created_at', 'asc')
            ->when($search, function ($query, $search) {
                return $query->where('content', 'like', '%' . $search . '%');
            })->get();

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
}
