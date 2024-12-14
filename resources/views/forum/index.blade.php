@php
    $PATH = env('AWS_URL');
@endphp
@extends('layout.layout')
@section('content')
    <div class="w-full flex flex-col items-center justify-center">
        <h1 class="text-5xl font-bold">
            Welcome to
            <span class="bg-gradient-to-r from-purple-400 via-pink-500 to-red-500 bg-clip-text text-transparent">
                AI Forum!
            </span>
        </h1>
        <p>
            Tanyakan apapun disini :D
        </p>
        <input type="text" name="" id="" placeholder="Tanyakan pertanyaamu disini ... " class="input w-full">
        <button type="button" class="btn btn-primary" onclick="document.getElementById('add_discussion_modal').showModal();">
            Create New Discussion
        </button>

        <dialog id="add_discussion_modal" class="modal">
            <div class="modal-box">
                <h3 class="font-bold text-lg">Create New Discussion</h3>
                <form method="POST" action="{{ route('forum.discussion.create', ['forumId' => $forum->id]) }}">
                    @csrf
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" id="title" class="input input-bordered w-full" required />
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3" class="textarea textarea-bordered w-full" required></textarea>
                    </div>
                    <div class="modal-action">
                        <button type="button" class="btn"
                            onclick="document.getElementById('add_discussion_modal').close();">Cancel</button>
                        <button type="submit" class="btn btn-primary">+ Create</button>
                    </div>
                </form>
            </div>
            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        </dialog>

        @foreach ($forum_discussions as $discussion)
            <div class="w-full bg-white shadow-md rounded p-4 mt-4">
                <h3 class="font-bold text-lg">{{ $discussion->title }}</h3>
                <p>{{ $discussion->description }}</p>
                <div class="flex justify-between items-center mt-4">
                    <div class="flex items center">
                        <img src="{{ $discussion->creator->profile_picture ? $PATH . $discussion->creator->profile_picture : 'https://ui-avatars.com/api/?name=' . $discussion->creator->name . '&color=7F9CF5&background=EBF4FF' }}"
                            alt="profile" class="w-8 h-8 rounded-full">
                        <p class="ml-2">{{ $discussion->creator->name }}</p>
                    </div>
                    <a href="{{ route('forum.discussion.index', ['forumId' => $forum->id, 'discussionId' => $discussion->id]) }}"
                    class="btn btn-primary">View Discussion</a>
                </div>
            </div>
        @endforeach

    </div>
@endsection
