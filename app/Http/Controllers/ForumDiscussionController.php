<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use App\Models\ForumDiscussion;
use App\Models\ForumReply;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class ForumDiscussionController extends Controller
{
    public function index(string $forumId, string $discussionId)
    {
        try {
            $forumDiscussion = ForumDiscussion::findOrFail($discussionId);

            // Check if user is student
            $isStudent = request()->user()->isStudent();

            $forumReplies = ForumReply::where('forum_discussion_id', $forumDiscussion->id)
                ->whereNull('reply_to_id')
                ->when($isStudent, function ($query) {
                    return $query->where('is_public', true);
                })
                ->get();


            return view('forum.forum_discussion.index', compact('forumDiscussion', 'forumReplies'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function create(Request $request, string $forumId)
    {
        try {
            $forum = Forum::findOrFail($forumId);

            $content = $request->input('content');

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
                                Saya memiliki sebuah pertanyaan, saya ingin anda menjawab pertanyaan tersebut 
                                dengan ramah dan layaknya seorang teaching assistant. Jika anda mendeteksi kata-kata
                                yang tidak pantas, tolong tulis 'y' jika pertanyaan mengandung kata-kata tidak pantas dan 'n' jika sebaliknya pada awal jawaban anda. Berikut contohnya:
                                
                                y,
                                ##### Jawaban Singkat (Overview)
                                ##### Penjelasan

                                Pertanyaannya adalah:
                                $content
                            ",
                    ],
                ],
            ]);

            // Check if y is the first character of the response
            if (strpos($response['choices'][0]['message']['content'], 'y') === 0) {
                return redirect()->back()->withErrors(['error' => 'Pertanyaan mengandung kata-kata tidak pantas']);
            }

            $createdForum = $forum->discussions()->create([
                'content' => $content,
                'creator_id' => request()->user()->id,
            ]);

            // Create new Forum Reply
            $createdForum->forum_replies()->create([
                'content' => substr($response['choices'][0]['message']['content'], 2),
            ]);

            return redirect()->route('forum.index', ['forumId' => $forumId]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to create discussion ' . $e->getMessage()]);
        }
    }

    public function delete(Request $request, string $discussionId)
    {
        $forumDiscussion = ForumDiscussion::findOrFail($discussionId);
        $forum = $forumDiscussion->forum;
        $forumDiscussion = $forum->discussions()->findOrFail($discussionId);

        $forumCourseTeacher = $forum->courseItem->courseSection->course->teacher_id === $request->user()->id;

        // Make sure the user is the creator of the discussion
        if ($forumDiscussion->creator_id !== $request->user()->id && !$forumCourseTeacher) {
            return redirect()->back()->withErrors(['You are not allowed to delete this discussion']);
        }

        $forumDiscussion->delete();

        return redirect()->back()->with('success', 'Discussion deleted successfully');
    }

    public function toggleVisibility(Request $request, string $discussionId)
    {
        $forumDiscussion = ForumDiscussion::findOrFail($discussionId);
        $forum = $forumDiscussion->forum;

        // is User teacher of this forum course?
        $forumCourseTeacher = $forum->courseItem->courseSection->course->teacher_id === $request->user()->id;

        // Make sure the user is the creator of the discussion
        if ($forumDiscussion->creator_id !== $request->user()->id && !$forumCourseTeacher) {
            return redirect()->back()->withErrors(['You are not allowed to toggle visibility of this discussion']);
        }

        $forumDiscussion->is_public = !$forumDiscussion->is_public;
        $forumDiscussion->save();

        return response()->json(['success' => true, 'is_public' => $forumDiscussion->is_public, 'message' => 'Visibility toggled successfully']);
    }

    public function edit(Request $request, string $discussionId)
    {
        $forumDiscussion = ForumDiscussion::findOrFail($discussionId);
        $forum = $forumDiscussion->forum;

        // is User teacher of this forum course?
        $forumCourseTeacher = $forum->courseItem->courseSection->course->teacher_id === $request->user()->id;

        // Make sure the user is the creator of the discussion
        if ($forumDiscussion->creator_id !== $request->user()->id && !$forumCourseTeacher) {
            return redirect()->back()->withErrors(['You are not allowed to edit this discussion']);
        }

        $forumDiscussion->content = $request->input('content');
        $forumDiscussion->save();

        return redirect()->back()->with('success', 'Discussion edited successfully');
    }
}
