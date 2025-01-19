<div class="card bg-base-100 w-full shadow-xl">
    <div class="card-body flex flex-col md:flex-row gap-4 items-start w-full">
        <span class="min-w-12 h-12 bg-blue-500 text-white rounded-xl flex items-center justify-center">{{ $num }}</span>
        <div class="w-full">
            {{-- Pertanyaan dan nomor --}}
            <div class="flex justify-between items-center gap-2">
                <div>
                    <small>Short Answer</small>
                    <h2 class="card-title">
                        {{ $question->content }}
                    </h2>
                    <span class="text-sm text-gray-700">
                        ({{ $question->weight }} poin)
                    </span>
                </div>
                <div class="flex gap-2">
                    <button class="btn btn-square"
                            onclick="edit_choice_{{ str_replace('-', '_', $question->id) }}.showModal()"
                    >
                        <x-lucide-pencil class="w-4 h-4 hover:text-red-500 duration-300 hover:rotate-12"/>
                    </button>
                    <dialog id="edit_choice_{{ str_replace('-', '_', $question->id) }}" class="modal">
                        <div class="modal-box flex flex-col gap-3">
                            <span class="text-sm text-gray-700">Pertanyaan</span>
                            <input type="text" wire:model="content" placeholder="Pertanyaan..."
                                   class="input w-full" required>
                            <span class="text-sm text-gray-700">Bobot</span>
                            <input type="number" wire:model="weight" placeholder="Poin..."
                                   class="input w-full" required>
                            <div class="modal-action">
                                <form method="dialog">
                                    <!-- if there is a button in form, it will close the modal -->
                                    <button class="btn btn-primary" wire:click="updateQuestionInfo">Selesai</button>
                                </form>
                            </div>
                        </div>
                    </dialog>
                    <button class="btn btn-square" wire:click="$parent.deleteQuestion('{{ $question->id }}')">
                        <x-lucide-trash class="w-4 h-4 hover:text-red-500 duration-300 hover:rotate-12"/>
                    </button>
                </div>
            </div>

            {{-- Aksi --}}
            <div class="card-actions mt-4 gap-4 flex flex-col">
                <span class="text-sm text-gray-700">Kunci Jawaban</span>
                <input wire:model.live="answer" class="input w-full"/>
            </div>
        </div>
    </div>
</div>
