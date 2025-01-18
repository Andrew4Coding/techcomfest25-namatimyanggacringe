<?php

namespace App\Http\Controllers;

use App\Models\ForumDiscussion;
use App\Models\ForumReply;
use Illuminate\Http\Request;

class ForumDiscussionController extends Controller
{
    public function index(string $forumId, string $discussionId)
    {
        try {
            $forumDiscussion = ForumDiscussion::findOrFail($discussionId);
            
            $forumReplies = ForumReply::where('forum_discussion_id', $forumDiscussion->id)
            ->whereNull('reply_to_id')
            ->get();

            return view('forum.forum_discussion.index', compact('forumDiscussion', 'forumReplies'));
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function replyToDiscussion(Request $request, string $forumId, string $discussionId)
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
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to post reply');
        }
    }

    public function toggleVerified(Request $request, string $forumReplyId)
    {
        $forumReply = ForumReply::findOrFail($forumReplyId);

        $forumReply->is_verified = !$forumReply->is_verified;
        $forumReply->save();

        // Return json
        return response()->json(['success' => true, 'is_verified' => $forumReply->is_verified, 'message' => 'Verified toggled successfully']);
    }

    public function deleteReply(Request $request, string $forumReplyId)
    {
        $forumReply = ForumReply::findOrFail($forumReplyId);

        // Make sure the user is the sender of the reply
        if ($forumReply->sender_id !== $request->user()->id) {
            return redirect()->back()->withErrors(['You are not allowed to delete this reply']);
        }

        $forumReply->delete();

        // Return json
        return redirect()->back()->with('success', 'Reply deleted successfully');
    }

    public function editReply(Request $request, string $forumReplyId)
    {
        $forumReply = ForumReply::findOrFail($forumReplyId);

        // Make sure the user is the sender of the reply
        if ($forumReply->sender_id !== $request->user()->id) {
            return redirect()->back()->withErrors(['You are not allowed to edit this reply']);
        }

        $forumReply->content = $request->input('content');
        $forumReply->save();

        // Return json
        return redirect()->back()->with('success', 'Reply edited successfully');
    }
}
