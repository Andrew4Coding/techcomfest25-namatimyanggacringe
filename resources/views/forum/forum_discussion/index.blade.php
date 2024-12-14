@extends('layout.layout')

@section('content')
    <div class="max-w-4xl mx-auto mt-8 p-6 bg-white rounded-lg shadow-md">
        <!-- Discussion Title -->
        <h1 class="text-2xl font-bold text-gray-800 mb-4">
            {{ $forumDiscussion->title }}
        </h1>

        <!-- Discussion Description -->
        <p class="text-gray-600 text-lg mb-8">
            {{ $forumDiscussion->description }}
        </p>

        <!-- Comments Section -->
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Comments</h2>

        @foreach ($forumReplies as $comment)
            <div class="flex items-start gap-4 mb-6">
                <!-- Profile Picture -->
                <img class="object-cover w-12 h-12 rounded-full"
                    src="
                            @if ($comment->sender->profile_picture) {{ $PATH . auth()->user()->profile_picture }}                        
                            @else
                                https://ui-avatars.com/api/?name={{ Auth::user()->name }}&color=7F9CF5&background=EBF4FF @endif
                        ">
                <!-- Comment Content -->
                <div class="flex-1 bg-gray-100 p-4 rounded-lg shadow-md">
                    <p class="font-semibold text-gray-800">{{ $comment->sender->name }}</p>
                    <p class="text-gray-600">{{ $comment->content }}</p>
                </div>
            </div>
        @endforeach

        <!-- Add Comment Form -->
        <form
            action="{{ route('forum.discussion.reply', ['forumId' => $forumDiscussion->forum->id, 'discussionId' => $forumDiscussion->id]) }}"
            method="POST" class="mt-8">
            @csrf
            <textarea name="content"
                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent mb-4"
                placeholder="Add a comment..." required></textarea>
            <button type="submit"
                class="px-6 py-2 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Submit
            </button>
        </form>
    </div>
@endsection
