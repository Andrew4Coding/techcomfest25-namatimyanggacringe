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
            <h3>Pertanyaan Sebelumnya</h3>
            <input type="text" name="search" id="" placeholder="Cari pertanyaan ..."
                value="{{ request()->get('search') }}"
                class="input w-full md:max-w-[300px]">
        </form>
        @if (!$forum_discussions->isEmpty())
            <section class="w-full">
                @foreach ($forum_discussions as $discussion)
                    <div class="w-full bg-white shadow-smooth rounded-3xl p-8 mt-4 relative flex flex-col gap-2">
                        <div class="w-full flex items-center gap-4 mb-4">
                            <h3 class="font-medium text-sm">{{ $discussion->content }}</h3>
                            <span class="absolute top-8 right-8 text-sm text-gray-500">
                                {{ $discussion->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <div class="flex flex-col gap-2">
                            @php
                                $reply = $discussion->forum_replies->first();
                            @endphp
                            <div class="w-full bg-[#F2F6F8] p-5 rounded-xl {{ $reply->is_public ? '' : 'opacity-50' }}">
                                <div class="flex flex-row gap-2 sm:items-center justify-between mb-4">
                                    <h3 class="font-semibold flex items-center gap-2">Jawaban
                                        <div class="tooltip tooltip-right font-medium" data-tip="Jawaban AI">
                                            <x-lucide-sparkles class="w-4 h-4 text-blue-400 animate-pulse" />
                                        </div>
                                        @if ($reply->is_verified)
                                            <div class="tooltip tooltip-right font-medium"
                                                data-tip="Jawaban Terverifikasi">
                                                <x-lucide-check class="w-4 h-4 text-blue-400" />
                                            </div>
                                        @endif
                                    </h3>
                                    @if (Auth::user()->userable_type == 'App\Models\Teacher')
                                        <div
                                            class="flex gap-2 items-center bg-blue-500 text-white px-5 py-2 rounded-full w-fit">
                                            <div class="tooltip" data-tip="Edit Jawaban">
                                                <x-lucide-pencil
                                                    onclick="document.getElementById('edit_modal_{{ $reply->id }}').showModal();"
                                                    class="w-4 h-4 cursor-pointer hover:rotate-12 duration-150" />
                                            </div>
                                            <div class="tooltip" data-tip="Hapus Jawaban">
                                                <x-lucide-trash
                                                    class="w-4 h-4 cursor-pointer hover:rotate-12 duration-150"
                                                    onclick="document.getElementById('delete_modal_{{ $reply->id }}').showModal();" />
                                            </div>
                                            <div class="tooltip"
                                                data-tip={{ $reply->is_public ? 'Sembunyikan Jawaban' : 'Tampilkan Jawaban' }}
                                                onclick="document.getElementById('hide_modal_{{ $reply->id }}').showModal();">
                                                @if (!$reply->is_public)
                                                    <x-lucide-eye-off
                                                        class="w-4 h-4 cursor-pointer hover:rotate-12 duration-150" />
                                                @else
                                                    <x-lucide-eye
                                                        class="w-4 h-4 cursor-pointer hover:rotate-12 duration-150"
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
                                    marked(`${"‎" + document.getElementById('reply-{{ $reply->id }}').innerHTML}`);
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
                <h3 class="text-lg text-center">Belum ada pertanyaan yang diajukan</h3>
            </div>
        @endif
    </div>
@endsection
