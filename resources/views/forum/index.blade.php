@php
    $PATH = env('AWS_URL');
@endphp
@extends('layout.layout')
@section('content')
    <div class="w-full flex flex-col items-center justify-center">
        <section class="my-auto h-full flex flex-col items-center justify-center relative w-full">
            <img src="{{ asset('mascot-nunjuk.png') }}" alt="Login" class="w-[150px] py-5">

            <div class="flex flex-col gap-4 items-center justify-center">
                <h1 class="text-3xl md:text-5xl font-semibold text-center">
                    Welcome to
                    <span class="bg-gradient-to-r gradient-blue bg-clip-text text-transparent">
                        AI Forum!
                    </span>
                </h1>
                <p class="py-3">
                    Tanyakan apapun disini :D
                </p>
                <form class="flex flex-col md:flex-row gap-4 items-center mb-4" method="POST"
                    action="{{ route('forum.discussion.create', ['forumId' => $forum->id]) }}">
                    @csrf
                    <input type="text" name="title" id="" placeholder="Tanyakan pertanyaanmu disini ... "
                        class="bg-white input w-full max-w-2xl min-w-[300px]">
                    <input type="hidden" name="description" value="s">
                    <button type="submit" class="btn btn-primary w-full md:w-fit">
                        <x-lucide-send class="w-4 h-4" />
                        Kirim Pertanyaan
                    </button>
                </form>
            </div>
        </section>


        @if (!$forum_discussions->isEmpty())
            <section class="w-full">
                <div class="w-full flex flex-col md:flex-row items-center justify-between my-10 md:my-5">
                    <h3>Pertanyaan Sebelumnya</h3>
                    <input type="text" name="" id="" placeholder="Cari pertanyaan ..."
                        class="input w-full md:max-w-[300px]">
                </div>
                @foreach ($forum_discussions as $discussion)
                    <div class="w-full bg-white shadow-smooth rounded-3xl p-8 mt-4 relative flex flex-col gap-2">
                        <h3 class="font-medium text-sm">{{ $discussion->title }}</h3>
                        <span class="absolute top-8 right-8 text-sm text-gray-500">
                            {{ $discussion->created_at->diffForHumans() }}
                        </span>
                        <div class="flex flex-col gap-2">
                            @foreach ($discussion->forum_replies as $reply)
                                <div class="w-full bg-[#F2F6F8] p-5 rounded-xl">
                                    <h3 class="font-semibold flex items-center gap-2 mb-4">Jawaban AI
                                        <x-lucide-sparkles class="w-4 h-4 text-blue-400 animate-pulse" />
                                    </h3>
                                    <p id='reply-{{$reply->id}}' class="max-h-[300px] overflow-y-auto text-sm leading-relaxed">{{ $reply->content }}</p>
                                </div>
                                <script type="module">
                                    import {
                                        marked
                                    } from "https://cdn.jsdelivr.net/npm/marked/lib/marked.esm.js";
                                    document.getElementById('reply-{{$reply->id}}').innerHTML =
                                        marked(document.getElementById('reply-{{$reply->id}}').innerHTML);
                                </script>
                            @endforeach
                        </div>
                        <div class="w-full flex justify-end">
                            <a href="{{ route('forum.discussion.index', ['forumId' => $forum->id, 'discussionId' => $discussion->id]) }}"
                                class="w-full">
                                <p class="text-gray-300 text-right text-xs">See More</p>
                                <a>
                        </div>
                    </div>
                @endforeach
            </section>
        @endif
    </div>
@endsection
