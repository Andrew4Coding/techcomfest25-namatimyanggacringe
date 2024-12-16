<div class="card bg-base-100 w-full shadow-xl">
    <div class="card-body flex-row items-start">
        <span class="px-4 py-2 mr-2 mt-1 bg-black/10 rounded">{{ $num }}</span>
        <div>
            {{-- Pertanyaan dan nomor --}}
            <div class="flex items-start">
                {{-- Pertanyaan dan nomor --}}
                <h2 class="card-title mb-6">
                    {{ $question->content }}
                </h2>
                <button
                    onclick="edit_choice_{{ str_replace('-', '_', $question->id) }}.showModal()"
                >
                    <x-lucide-pencil class="w-4 h-4 hover:text-red-500 duration-300 hover:rotate-12" />
                </button>
                <dialog id="edit_choice_{{ str_replace('-', '_', $question->id) }}" class="modal">
                    <div class="modal-box">
                        <input type="text" wire:model.blur="content" placeholder="Pertanyaan..." class="input input-bordered w-full max-w-xs" required>
                        <div class="modal-action">
                            <form method="dialog">
                                <!-- if there is a button in form, it will close the modal -->
                                <button class="btn">Selesai</button>
                            </form>
                        </div>
                    </div>
                </dialog>
                <button wire:click="$parent.deleteQuestion('{{ $question->id }}')">
                    <x-lucide-trash class="w-4 h-4 hover:text-red-500 duration-300 hover:rotate-12" />
                </button>
            </div>
            {{--Aksi--}}
            <div class="card-actions mt-4 gap-4 flex-col">
                <input wire:model="answer" class="input w-full" />
                <button wire:click="updateAnswer" class="btn btn-primary self-end">Simpan</button>
            </div>
        </div>
    </div>
</div>
