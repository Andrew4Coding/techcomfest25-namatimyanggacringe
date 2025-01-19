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
            <input type="text" name="content" id="" placeholder="Jawab pertanyaan ini ..." class="input">
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
        <div class="w-full mt-8 p-10 bg-white rounded-3xl shadow-smooth flex flex-col gap-8
        {{
            $reply->is_public ? '' : 'opacity-50'
        }}
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
                @if (Auth::user()->userable_type == 'App\Models\Teacher')
                    <div class="flex gap-2 items-center bg-blue-500 text-white px-5 py-2 rounded-full w-fit">
                        <div class="tooltip" data-tip="Edit Jawaban">
                            <x-lucide-pencil
                                onclick="document.getElementById('edit_modal_{{ $reply->id }}').showModal();"
                                class="w-4 h-4 cursor-pointer hover:rotate-12 duration-150" />
                        </div>
                        <div class="tooltip" data-tip="Hapus Jawaban">
                            <x-lucide-trash class="w-4 h-4 cursor-pointer hover:rotate-12 duration-150"
                                onclick="document.getElementById('delete_modal_{{ $reply->id }}').showModal();" />
                        </div>
                        <div class="tooltip" data-tip={{ $reply->is_public ? 'Sembunyikan Jawaban' : 'Tampilkan Jawaban' }}
                            onclick="document.getElementById('hide_modal_{{ $reply->id }}').showModal();">
                            @if (!$reply->is_public)
                                <x-lucide-eye-off class="w-4 h-4 cursor-pointer hover:rotate-12 duration-150" />
                            @else
                                <x-lucide-eye class="w-4 h-4 cursor-pointer hover:rotate-12 duration-150"
                                    onclick="document.getElementById('hide_modal_{{ $reply->id }}').showModal();" />
                            @endif
                        </div>
                        <div class="tooltip"
                            data-tip="@if (!$reply->is_verified) Verifikasi Jawaban @else Batalkan Verifikasi @endif">
                            <div class="cursor-pointer hover:rotate-12 duration-150"
                                onclick="document.getElementById('verify_modal_{{ $reply->id }}').showModal();">
                                @if (!$reply->is_verified)
                                    <x-lucide-check class="w-4 h-4" />
                                @else
                                    <x-lucide-x class="w-4 h-4" />
                                @endif
                            </div>
                        </div>
                    </div>
                    <dialog id="edit_modal_{{ $reply->id }}" class="modal text-black">
                        <form class="modal-box lg:min-w-[800px]" method="POST"
                            action="{{ route('forum.reply.edit', ['forumReplyId' => $reply->id]) }}">
                            @method('PUT')
                            @csrf
                            <h3 class="font-medium text-base">Edit Jawaban</h3>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <div id="preview-{{ $reply->id }}"
                                    class="p-4 bg-gray-100 rounded-md text-sm leading-relaxed">
                                </div>
                                <textarea name="content" class="textarea textarea-bordered w-full min-h-[600px]" rows="5" required
                                    oninput="document.getElementById('preview-{{ $reply->id }}').innerHTML = marked(this.value)">{{ $reply->content }}</textarea>
                            </div>
                            <div class="modal-action">
                                <button type="button" class="btn"
                                    onclick="document.getElementById('edit_modal_{{ $reply->id }}').close();">Batalkan</button>
                                <button class="btn btn-primary" type="submit">Simpan</button>
                            </div>
                        </form>
                        <script type="module">
                            import {
                                marked
                            } from "https://cdn.jsdelivr.net/npm/marked/lib/marked.esm.js";
                            const textarea = document.querySelector('textarea[name="content"]');
                            const preview = document.getElementById('preview-{{ $reply->id }}');
                            textarea.addEventListener('input', () => {
                                preview.innerHTML = marked(textarea.value);
                            });
                            // Initial render
                            preview.innerHTML = marked(textarea.value);
                        </script>
                        <form method="dialog" class="modal-backdrop">
                            <button>close</button>
                        </form>
                    </dialog>
                    <dialog id="delete_modal_{{ $reply->id }}" class="modal text-black">
                        <form class="modal-box" method="POST"
                            action="{{ route('forum.reply.delete', ['forumReplyId' => $reply->id]) }}">
                            @method('DELETE')
                            @csrf
                            <h3 class="font-medium text-base">Konfirmasi Hapus Jawaban</h3>
                            <p>Apakah Anda yakin ingin menghapus jawaban ini?</p>
                            <div class="modal-action">
                                <button type="button" class="btn"
                                    onclick="document.getElementById('delete_modal_{{ $reply->id }}').close();">Batalkan</button>
                                <button class="btn btn-primary" type="submit">Hapus</button>
                            </div>
                        </form>
                        <form method="dialog" class="modal-backdrop">
                            <button>close</button>
                        </form>
                    </dialog>
                    <dialog id="hide_modal_{{ $reply->id }}" class="modal text-black">
                        <div class="modal-box">
                            <h3 class="font-medium text-base">Konfirmasi @if ($reply->is_public)
                                    Sembunyikan
                                @else
                                    Tampilkan
                                @endif Jawaban</h3>
                            <p>Apakah Anda yakin ingin @if (!$reply->is_public)
                                    menyembunyikan
                                @else
                                    menampilkan
                                @endif jawaban ini?</p>
                            <div class="modal-action">
                                <button type="button" class="btn"
                                    onclick="document.getElementById('hide_modal_{{ $reply->id }}').close();">Batalkan</button>
                                <button class="btn btn-primary"
                                    onclick="fetch('{{ route('forum.reply.public', ['forumReplyId' => $reply->id]) }}', {
                                                        method: 'POST',
                                                        headers: {
                                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                        }
                                                    }).then(response => {
                                                        if (response.ok) {
                                                            document.getElementById('hide_modal_{{ $reply->id }}').close();
                                                            window.location.reload();
                                                        }
                                                    }).catch(error => {
                                                        console.error('There has been a problem with your fetch operation:', error);
                                                    });">
                                    @if ($reply->is_public)
                                        Sembunyikan
                                    @else
                                        Tampilkan
                                    @endif
                                </button>
                            </div>
                        </div>
                        <form method="dialog" class="modal-backdrop">
                            <button>close</button>
                        </form>
                    </dialog>
                    <dialog id="verify_modal_{{ $reply->id }}" class="modal text-black">
                        <div class="modal-box">
                            <h3 class="font-medium text-base">Konfirmasi
                                @if (!$reply->is_verified)
                                    Verifikasi Jawaban
                                @else
                                    Pembatalan Verifikasi Jawaban
                                @endif
                            </h3>
                            <p>Apakah
                                @if (!$reply->is_verified)
                                    Anda yakin ingin memverifikasi jawaban ini?
                                @else
                                    Anda yakin ingin membatalkan verifikasi jawaban ini?
                                @endif
                            </p>
                            <div class="modal-action">
                                <button type="button" class="btn"
                                    onclick="document.getElementById('verify_modal_{{ $reply->id }}').close();">Batalkan</button>
                                <button class="btn btn-primary" {{-- Fetch On Click --}}
                                    onclick="fetch('{{ route('forum.reply.verify', ['forumReplyId' => $reply->id]) }}', {
                                                            method: 'POST',
                                                            headers: {
                                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                            }
                                                        }).then(response => {
                                                            if (response.ok) {
                                                                return response.json();
                                                            }
                                                            throw new Error('Network response was not ok.');
                                                        }).then(data => {
                                                            if (data.success) {
                                                                document.getElementById('verify_modal_{{ $reply->id }}').close();
                                                                window.location.reload();
                                                            }
                                                        }).catch(error => {
                                                            console.error('There has been a problem with your fetch operation:', error);
                                                        });">
                                    @if (!$reply->is_verified)
                                        Verify
                                    @else
                                        Unverify
                                    @endif
                                </button>
                            </div>
                        </div>
                        <form method="dialog" class="modal-backdrop">
                            <button>close</button>
                        </form>
                    </dialog>
                @endif
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
