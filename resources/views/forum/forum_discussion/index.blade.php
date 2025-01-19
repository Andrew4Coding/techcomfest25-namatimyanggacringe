@extends('layout.layout')

@section('content')
    <a href="
        {{ route('forum.index', ['forumId' => $forumDiscussion->forum->id]) }}">
        <x-lucide-arrow-left class="w-6 h-6 text-gray-600 hover:cursor-pointer hover:scale-105" />
    </a>
    <div class="w-full mt-8 p-10 bg-white rounded-3xl shadow-smooth flex flex-col gap-8">
        <!-- Discussion Title -->
        <div class="w-full flex gap-4 items-center">
            <img class="object-cover w-12 h-12 rounded-full"
                src="
                        @if ($forumDiscussion->creator && $forumDiscussion->creator->profile_picture) {{ $PATH . $forumDiscussion->creator->profile_picture }}
                        @else
                            https://ui-avatars.com/api/?name={{ Auth::user()->name }}&color=7F9CF5&background=EBF4FF @endif
                        ">

            <div>
                <h5 class="font-medium">
                    {{ $forumDiscussion->creator->name }}
                </h5>
                <p class="text-gray-600 text-xs">
                    {{ // If user is student or teacher
                        $forumDiscussion->creator->isTeacher() ? 'Guru' : 'Siswa Kelas' . $forumDiscussion->creator->userable->class }}
                    •
                    {{ $forumDiscussion->created_at->diffForHumans() }}
                </p>
            </div>
        </div>
        <h1 class="text-xl font-semibold text-gray-800">
            {{ $forumDiscussion->content }}
        </h1>

        <form
            action="{{ route('forum.discussion.reply', ['forumId' => $forumDiscussion->forum->id, 'discussionId' => $forumDiscussion->id]) }}"
            method="POST" class="w-full flex gap-4 items-center">
            @csrf
            <input type="text" name="content" id="" placeholder="Jawab pertanyaan ini ..." class="input" required>
            <button type="submit" class="btn btn-primary">
                <x-lucide-send class="w-4 h-4" />
            </button>
        </form>
    </div>

    @php
        // Sort by AI First, then verified, then sort by created at in reverse order
        $isStudent = Auth::user()->isStudent();
        $forumReplies = $forumReplies
            ->sortBy(function ($reply) {
                return $reply->sender ? $reply->sender->is_ai : true;
            })
            ->sortBy(function ($reply) {
                return $reply->sender ? $reply->sender->is_verified : true;
            })
            // Dont show private replies to students
            ->filter(function ($reply) use ($isStudent) {
                return $isStudent ? $reply->is_public : true;
            })
            ->sortBy('created_at');
    @endphp

    @foreach ($forumReplies as $reply)
        <div
            class="w-full mt-8 p-10 bg-white rounded-3xl shadow-smooth flex flex-col gap-8
        {{ $reply->is_public ? '' : 'opacity-50' }}
        ">
            <div class="w-full flex justify-between items-center">
                <div class="w-full flex gap-4 items-center">
                    <img class="object-cover w-12 h-12 rounded-full"
                        src="
                            @if ($forumDiscussion->sender && $forumDiscussion->sender->profile_picture) {{ $PATH . $forumDiscussion->sender->profile_picture }}
                            @else
                                https://ui-avatars.com/api/?name={{ $reply->sender ? $reply->sender->name : 'MA' }}&color=7F9CF5&background=EBF4FF @endif
                            ">

                    <div>
                        <h5 class="font-medium flex items-center gap-2">
                            {{ $reply->sender ? $reply->sender->name : 'MindorAI' }}
                            @if (!$reply->sender)
                                <x-lucide-sparkles class="w-4 h-4 text-blue-400 animate-pulse" />
                            @endif

                            @if ($reply->is_verified)
                                <div class="tooltip tooltip-right font-medium" data-tip="Jawaban Terverifikasi">
                                    <x-lucide-check class="w-4 h-4 text-blue-400" />
                                </div>
                            @endif
                        </h5>
                        <p class="text-gray-600 text-xs">
                            {{ // Check if replier is AI or not
                                $reply->sender
                                    ? ($reply->sender->is_ai
                                        ? 'MindorAI'
                                        : ($reply->sender->isTeacher()
                                            ? 'Guru'
                                            : 'Siswa Kelas' . $reply->sender->userable->class))
                                    : 'MindorAI' }}
                            •
                            {{ $reply->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                @php
                    $isTeacherOfTheCourse =
                        Auth::user()->userable_type == 'App\Models\Teacher' &&
                        Auth::user()->userable->courses->contains($reply->forum_discussion->forum->courseItem->courseSection->course);

                    if ($reply->sender) {
                        $isSender = Auth::user()->id == $reply->sender->id;
                    } else {
                        $isSender = false;
                    }
                @endphp
                @include('forum.commons.reply_tools')
            </div>
            <!-- Comment Content -->
            <div class="w-full bg-[#F2F6F8] p-5 rounded-xl">
                <p id="comment-{{ $reply->id }}" class="max-h-[300px] overflow-y-auto text-sm leading-relaxed">
                    {{ $reply->content }}
                </p>
            </div>
            <script type="module">
                import {
                    marked
                } from "https://cdn.jsdelivr.net/npm/marked/lib/marked.esm.js";
                document.getElementById('comment-{{ $reply->id }}').innerHTML =
                    marked(`${document.getElementById('comment-{{ $reply->id }}').innerHTML.trim()}`);
            </script>
        </div>
    @endforeach
@endsection
