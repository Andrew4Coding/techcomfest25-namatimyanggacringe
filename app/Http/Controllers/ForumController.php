<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Forum;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

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
    
            $title = $request->input('title');

            $createdForum = $forum->discussions()->create([
                'title' => $title,
                'description' => $request->input('description'),
                'creator_id' => request()->user()->id,
            ]);

            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a helpful assistant.',
                    ],
                    [
                        'role' => 'user',
                        'content' => "
                                Saya memiliki sebuah pertanyaan, mohon jawab dalam struktur:
                                - Jawaban Secara Singkat (Overview)
                                - Jawaban Lengkap serta Penjabaran

                                Pertanyaannya adalah:
                                $title
                            ",
                    ],
                ],
            ]);

            // Create new Forum Reply
            $createdForum->forum_replies()->create([
                'content' => $response['choices'][0]['message']['content'],
            ]);

            return redirect()->route('forum.index', ['forumId' => $forumId]);
        }
        catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withErrors(['error' => 'Failed to create discussion']);
        }
    }
}
