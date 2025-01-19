@if (Auth::user()->userable_type == 'App\Models\Teacher' || $isSender)
    <div class="flex gap-2 items-center bg-blue-500 text-white px-5 py-2 rounded-full w-fit">
        <div class="tooltip" data-tip="Edit Jawaban">
            <x-lucide-pencil onclick="document.getElementById('edit_modal_{{ $reply->id }}').showModal();"
                class="w-4 h-4 cursor-pointer hover:rotate-12 duration-150" />
        </div>
        <div class="tooltip" data-tip="Hapus Jawaban">
            <x-lucide-trash class="w-4 h-4 cursor-pointer hover:rotate-12 duration-150"
                onclick="document.getElementById('delete_modal_{{ $reply->id }}').showModal();" />
        </div>
        {{-- If User is teacher --}}
        @if ($isTeacherOfTheCourse)
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
        @endif
    </div>
    <dialog id="edit_modal_{{ $reply->id }}" class="modal text-black">
        <form class="modal-box lg:min-w-[800px]" method="POST"
            action="{{ route('forum.reply.edit', ['forumReplyId' => $reply->id]) }}">
            @method('PUT')
            @csrf
            <h3 class="font-medium text-base">Edit Jawaban</h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div id="preview-{{ $reply->id }}" class="p-4 bg-gray-100 rounded-md text-sm leading-relaxed">
                </div>
                <textarea id="textarea-{{$reply->id}}" name="content" class="textarea textarea-bordered w-full min-h-[600px]" rows="5" required
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
            const textarea = document.getElementById('textarea-{{ $reply->id }}');
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
