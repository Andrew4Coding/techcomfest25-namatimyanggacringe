@if (Auth::user()->userable_type == 'App\Models\Teacher' || $isSender)
    <div class="flex gap-2 items-center bg-blue-500 text-white px-5 py-2 rounded-full w-fit">
        <div class="tooltip" data-tip="Edit Jawaban">
            <x-lucide-pencil onclick="document.getElementById('edit_modal_{{ $discussion->id }}').showModal();"
                class="w-4 h-4 cursor-pointer hover:rotate-12 duration-150" />
        </div>
        <div class="tooltip" data-tip="Hapus Jawaban">
            <x-lucide-trash class="w-4 h-4 cursor-pointer hover:rotate-12 duration-150"
                onclick="document.getElementById('delete_modal_{{ $discussion->id }}').showModal();" />
        </div>
        {{-- If User is teacher --}}
        @if ($isTeacherOfTheCourse)
            <div class="tooltip" data-tip={{ $discussion->is_public ? 'Sembunyikan Jawaban' : 'Tampilkan Jawaban' }}
                onclick="document.getElementById('hide_modal_{{ $discussion->id }}').showModal();">
                @if (!$discussion->is_public)
                    <x-lucide-eye-off class="w-4 h-4 cursor-pointer hover:rotate-12 duration-150" />
                @else
                    <x-lucide-eye class="w-4 h-4 cursor-pointer hover:rotate-12 duration-150"
                        onclick="document.getElementById('hide_modal_{{ $discussion->id }}').showModal();" />
                @endif
            </div>
        @endif
    </div>
    <dialog id="edit_modal_{{ $discussion->id }}" class="modal text-black">
        <form class="modal-box lg:min-w-[800px]" method="POST"
            action="{{ route('forum.discussion.edit', ['discussionId' => $discussion->id]) }}">
            @method('PUT')
            @csrf
            <h3 class="font-medium text-base">Edit Jawaban</h3>
            <textarea id="textarea-{{ $discussion->id }}" name="content" class="textarea textarea-bordered w-full min-h-[300px]"
                rows="5" required
                oninput="document.getElementById('preview-{{ $discussion->id }}').innerHTML = marked(this.value)">{{ $discussion->content }}</textarea>
            <div class="modal-action">
                <button type="button" class="btn"
                    onclick="document.getElementById('edit_modal_{{ $discussion->id }}').close();">Batalkan</button>
                <button class="btn btn-primary" type="submit">Simpan</button>
            </div>
        </form>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
    <dialog id="delete_modal_{{ $discussion->id }}" class="modal text-black">
        <form class="modal-box" method="POST"
            action="{{ route('forum.discussion.delete', ['discussionId' => $discussion->id]) }}">
            @method('DELETE')
            @csrf
            <h3 class="font-medium text-base">Konfirmasi Hapus Jawaban</h3>
            <p>Apakah Anda yakin ingin menghapus jawaban ini?</p>
            <div class="modal-action">
                <button type="button" class="btn"
                    onclick="document.getElementById('delete_modal_{{ $discussion->id }}').close();">Batalkan</button>
                <button class="btn btn-primary" type="submit">Hapus</button>
            </div>
        </form>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
    <dialog id="hide_modal_{{ $discussion->id }}" class="modal text-black">
        <div class="modal-box">
            <h3 class="font-medium text-base">Konfirmasi @if ($discussion->is_public)
                    Sembunyikan
                @else
                    Tampilkan
                @endif Jawaban</h3>
            <p>Apakah Anda yakin ingin @if (!$discussion->is_public)
                    menyembunyikan
                @else
                    menampilkan
                @endif jawaban ini?</p>
            <div class="modal-action">
                <button type="button" class="btn"
                    onclick="document.getElementById('hide_modal_{{ $discussion->id }}').close();">Batalkan</button>
                <button class="btn btn-primary"
                    onclick="fetch('{{ route('forum.discussion.public', ['discussionId' => $discussion->id]) }}', {
                                                        method: 'POST',
                                                        headers: {
                                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                        }
                                                    }).then(response => {
                                                        if (response.ok) {
                                                            document.getElementById('hide_modal_{{ $discussion->id }}').close();
                                                            window.location.reload();
                                                        }
                                                    }).catch(error => {
                                                        console.error('There has been a problem with your fetch operation:', error);
                                                    });">
                    @if ($discussion->is_public)
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
@endif
