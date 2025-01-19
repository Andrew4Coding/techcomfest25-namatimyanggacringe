<?php

namespace App\Http\Controllers;

use App\Models\ForumDiscussion;
use App\Models\ForumReply;
use Illuminate\Http\Request;

class ForumReplyController extends Controller
{
    public function replyToDiscussion(Request $request, string $discussionId)
    {
        try {
            $request->validate([
                'content' => 'required|string',
            ]);

            $forumDiscussion = ForumDiscussion::findOrFail($discussionId);

            $forumDiscussion->forum_replies()->create([
                'content' => $request->input('content'),
                'sender_id' => $request->user()->id,
            ]);

            return redirect()->back()->with('success', 'Reply posted');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to post reply');
        }
    }

    public function toggleVerified(string $forumReplyId)
    {
        try {
            $forumReply = ForumReply::findOrFail($forumReplyId);
    
            $forumReply->is_verified = !$forumReply->is_verified;
            $forumReply->save();
    
            // Return json
            return response()->json(['success' => true, 'is_verified' => $forumReply->is_verified, 'message' => 'Verified toggled successfully']);
        }
        catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to toggle verified']);
        }
    }

    public function togglePublic(string $forumReplyId)
    {
        try {
            $forumReply = ForumReply::findOrFail($forumReplyId);
    
            $forumReply->is_public = !$forumReply->is_public;
            $forumReply->save();
    
            // Return json
            return response()->json(['success' => true, 'is_public' => $forumReply->is_public, 'message' => 'Public toggled successfully']);
        }
        catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to toggle public']);
        }
    }

    public function delete(Request $request, string $forumReplyId)
    {
        $forumReply = ForumReply::findOrFail($forumReplyId);

        $forum = $forumReply->forum_discussion->forum;
        $forumCourseTeacher = $forum->courseItem->courseSection->course->teacher_id === $request->user()->userable->id;

        // Yang bisa delete cuman sender dan guru course
        if ($forumReply->sender_id !== $request->user()->id && !$forumCourseTeacher) {
            return redirect()->back()->withErrors(['You are not allowed to delete this reply']);
        }

        $forumReply->delete();

        // Return json
        return redirect()->back()->with('success', 'Reply deleted successfully');
    }

    public function edit(Request $request, string $forumReplyId)
    {
        $forumReply = ForumReply::findOrFail($forumReplyId);

        $forum = $forumReply->forum_discussion->forum;
        $forumCourseTeacher = $forum->courseItem->courseSection->course->teacher_id === $request->user()->userable->id;

        // Make sure the user is the sender of the reply
        if ($forumReply->sender_id !== $request->user()->id && !$forumCourseTeacher) {
            return redirect()->back()->withErrors(['You are not allowed to edit this reply']);
        }

        $forumReply->content = $request->input('content');
        $forumReply->save();

        // Return json
        return redirect()->back()->with('success', 'Reply edited successfully');
    }
}
