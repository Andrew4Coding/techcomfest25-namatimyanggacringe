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
            dd($e);
            return redirect()->back()->with('error', 'Discussion not found');
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
}
