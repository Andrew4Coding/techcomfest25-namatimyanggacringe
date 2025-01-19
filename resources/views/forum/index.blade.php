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
                    <input type="text" name="content" id="" placeholder="Tanyakan pertanyaanmu disini ... "
                        class="bg-white input w-full max-w-2xl min-w-[300px]" required>
                    <button type="submit" class="btn btn-primary w-full md:w-fit">
                        <x-lucide-send class="w-4 h-4" />
                        Kirim Pertanyaan
                    </button>
                </form>
            </div>
        </section>


        <form class="w-full flex flex-col md:flex-row items-center justify-between my-10 md:my-5">
            <h5>Pertanyaan Sebelumnya</h5>
            <input type="text" name="search" id="" placeholder="Cari pertanyaan ..."
                value="{{ request()->get('search') }}" class="input w-full md:max-w-[300px]">
        </form>
        @if (!$forum_discussions->isEmpty())
            <section class="w-full">
                @foreach ($forum_discussions as $discussion)
                    <div class="w-full bg-white shadow-smooth rounded-3xl p-8 mt-4 relative flex flex-col gap-2
                    {{
                        $discussion->is_public ? '' : 'opacity-50'
                    }}
                    ">
                        <div class="w-full flex items-center gap-4 mb-4">
                            <h3 class="font-medium text-sm">{{ $discussion->content }}</h3>
                            <span class="absolute top-8 right-8 text-sm text-gray-500">
                                {{ $discussion->created_at->diffForHumans() }}
                            </span>
                            @php
                                $isTeacherOfTheCourse =
                                    Auth::user()->userable_type == 'App\Models\Teacher' &&
                                    Auth::user()->userable->courses->contains(
                                        $forum->courseItem->courseSection->course,
                                    );
                                if ($discussion->creator) {
                                    $isSender = Auth::user()->id == $discussion->creator->id;
                                } else {
                                    $isSender = false;
                                }
                            @endphp
                            @include('forum.commons.discussion_tools')
                        </div>
                        <div class="flex flex-col gap-2">
                            @php
                                // Find first which has is_public = true
                                // If user is the teacher of the course, show all replies
                                if (Auth::user()->userable_type == 'App\Models\Teacher') {
                                    $reply = $discussion->forum_replies->first();
                                } else {
                                    $reply = $discussion->forum_replies->first(function ($reply) {
                                        return $reply->is_public;
                                    });
                                }
                            @endphp
                            <div class="w-full bg-[#F2F6F8] p-5 rounded-xl {{ $reply->is_public ? '' : 'opacity-50' }}">
                                <div class="flex flex-row gap-2 sm:items-center justify-between mb-4">
                                    <h5 class="font-semibold flex items-center gap-2">Jawaban Teratas
                                        @if ($reply->is_ai)
                                            <div class="tooltip tooltip-right font-medium" data-tip="Jawaban AI">
                                                <x-lucide-sparkles class="w-4 h-4 text-blue-400 animate-pulse" />
                                            </div>
                                        @endif
                                        @if ($reply->is_verified)
                                            <div class="tooltip tooltip-right font-medium" data-tip="Jawaban Terverifikasi">
                                                <x-lucide-check class="w-4 h-4 text-blue-400" />
                                            </div>
                                        @endif
                                    </h5>
                                    @php
                                        $isTeacherOfTheCourse =
                                            Auth::user()->userable_type == 'App\Models\Teacher' &&
                                            Auth::user()->userable->courses->contains(
                                                $forum->courseItem->courseSection->course,
                                            );
                                        if ($reply->sender) {
                                            $isSender = Auth::user()->id == $reply->sender->id;
                                        } else {
                                            $isSender = false;
                                        }
                                    @endphp
                                    @if (Auth::user()->userable_type == 'App\Models\Teacher' || $isSender)
                                        @include('forum.commons.reply_tools')
                                    @endif
                                </div>
                                <p id='reply-{{ $reply->id }}'
                                    class="max-h-[300px] overflow-y-auto text-sm leading-relaxed">
                                    {{ $reply->content }}
                                </p>
                            </div>
                            <script type="module">
                                import {
                                    marked
                                } from "https://cdn.jsdelivr.net/npm/marked/lib/marked.esm.js";
                                document.getElementById('reply-{{ $reply->id }}').innerHTML =
                                    marked(`${document.getElementById('reply-{{ $reply->id }}').innerHTML.trim()}`);
                            </script>
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
        @else
            <div class="w-full flex flex-col items-center justify-center gap-4">
                <img src="{{ asset('mascot-nunjuk.png') }}" alt="Login" class="w-[150px] py-5">
                <h3 class="text-sm font-normal text-center">Belum ada pertanyaan yang diajukan</h3>
            </div>
        @endif
    </div>
@endsection
