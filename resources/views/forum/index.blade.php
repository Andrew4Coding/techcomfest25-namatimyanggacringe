@php
    $PATH = env('AWS_URL');
@endphp
@extends('layout.layout')
@section('content')
    <div class="w-full flex flex-col items-center justify-center">
        <section class="h-screen flex flex-col items-center justify-center relative w-full">
            <img src="{{ asset('mascot-nunjuk.png') }}" alt="Login" class="absolute w-[200px] h-auto right-20">

            <h1 class="text-5xl font-semibold">
                Welcome to
                <span class="bg-gradient-to-r gradient-blue bg-clip-text text-transparent">
                    AI Forum!
                </span>
            </h1>
            <p>
                Tanyakan apapun disini :D
            </p>
            <div class="flex gap-4 items-center">
                <input type="text" name="" id="" placeholder="Tanyakan pertanyaamu disini ... "
                    class="input w-full max-w-2xl min-w-[300px]">
                <button type="button" class="btn btn-primary"
                    onclick="document.getElementById('add_discussion_modal').showModal();">
                    Create New Discussion
                </button>
            </div>
        </section>


        @foreach ($forum_discussions as $discussion)
            <a href="{{ route('forum.discussion.index', ['forumId' => $forum->id, 'discussionId' => $discussion->id]) }}" class="w-full">
                <div class="w-full bg-white shadow-smooth rounded-3xl p-8 mt-4 relative">
                    <h3 class="font-semibold text-lg">{{ $discussion->title }}</h3>
                    <p>{{ $discussion->description }}</p>
                    <span class="absolute top-8 right-8 text-sm text-gray-500">
                        {{ $discussion->created_at->diffForHumans() }}
                    </span>
                </div>
            </a>
        @endforeach

        @if ($forum_discussions->isEmpty())
            <div class="mt-20 text-center flex flex-col items-center gap-2">
                <img src="{{ asset('mindora-mascot.png') }}" alt="Icon" class="w-52 h-auto">
                <h1 class="text-xl font-medium">
                    Belum ada forum baru saat ini
                </h1>
                <p
                    class="font-medium bg-gradient-to-r from-[#3A4EC1] via-[#5298ED] to-[#945AC6] text-transparent bg-clip-text">
                    Belajar menjadi menyenangkan dan praktis.
                </p>
            </div>
        @endif

    </div>
    <dialog id="add_discussion_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-semibold text-lg">Create New Discussion</h3>
            <form method="POST" action="{{ route('forum.discussion.create', ['forumId' => $forum->id]) }}">
                @csrf
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" name="title" id="title" class="input input-bordered w-full" required />
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea name="description" id="description" rows="3" class="textarea textarea-bordered w-full" required></textarea>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn"
                        onclick="document.getElementById('add_discussion_modal').close();">Batalkan</button>
                    <button type="submit" class="btn btn-primary">+ Buat</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
@endsection
