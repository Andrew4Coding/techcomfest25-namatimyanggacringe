<div class="card bg-base-100 w-full shadow-xl">
    <div class="card-body flex-row items-start">
        <span
            class="min-w-12 h-12 bg-blue-500 text-white rounded-xl flex items-center justify-center">{{ $num }}</span>

        <div class="w-full">
            {{-- Pertanyaan dan nomor --}}
            <div class="flex justify-between items-center gap-2">
                {{-- Pertanyaan dan nomor --}}
                <div>
                    <small>Multiselect</small>
                    <h2 class="card-title">
                        {{ $question->content }}
                    </h2>
                    <span class="text-sm text-gray-700">
                        ({{ $weight }} poin)
                    </span>
                </div>
                <div class="flex gap-2">
                    <button class="btn btn-square"
                        onclick="edit_choice_{{ str_replace('-', '_', $question->id) }}.showModal()">
                        <x-lucide-pencil class="w-4 h-4 hover:text-red-500 duration-300 hover:rotate-12" />
                    </button>
                    <dialog id="edit_choice_{{ str_replace('-', '_', $question->id) }}" class="modal">
                        <div class="modal-box flex flex-col gap-3">
                            <input type="text" wire:model="content" placeholder="Pertanyaan..."
                                class="input input-bordered w-full" required>
                            <span class="text-sm text-gray-700">Bobot</span>
                            <input type="number" wire:model="weight" placeholder="Poin..."
                                class="input input-bordered w-full" required>
                            <div class="modal-action">
                                <form method="dialog">
                                    <!-- if there is a button in form, it will close the modal -->
                                    <button class="btn btn-primary" wire:click="updateQuestionInfo">Selesai</button>
                                </form>
                            </div>
                        </div>
                    </dialog>
                    <button class="btn btn-square" wire:click="$parent.deleteQuestion('{{ $question->id }}')">
                        <x-lucide-trash class="w-4 h-4 hover:text-red-500 duration-300 hover:rotate-12" />
                    </button>
                </div>
            </div>

            {{-- Aksi --}}
            <div class="card-actions mt-4 gap-4 flex flex-col">
                @for ($i = 0; $i < count($choices); $i++)
                    <div class="w-full flex flex-col md:flex-row items-start md:items-center">
                        <label wire:key="choices-{{ $question->questionChoices[$i]->id }}"
                            class="btn h-fit flex-1 justify-start mr-4
                        {{-- Check if answer --}}
                        @if (in_array($question->questionChoices[$i]->id, $answers)) btn-success @endif">
                            <span class="mr-2 font-bold block">{{ $this->toLetter($i) }}</span>
                            <span
                                class="basis-9/12 text-left leading-4">{{ $question->questionChoices[$i]->content }}</span>
                            <input wire:model.change="answers" type="checkbox"
                                name="checkbox-{{ $question->questionChoices[$i]->id }}" class="hidden"
                                value="{{ $question->questionChoices[$i]->id }}" />
                        </label>
                        <dialog wire:key="dialog-{{ $question->questionChoices[$i]->id }}"
                            id="my_modal_{{ str_replace('-', '_', $question->questionChoices[$i]->id) }}"
                            class="modal">
                            <div class="modal-box">
                                <form method="dialog">
                                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                                </form>
                                <h3 class="text-lg font-bold">Ganti Jawaban</h3>
                                <textarea wire:model="choices.{{ $i }}.content" placeholder="Masukkan jawaban..."
                                    class="textarea textarea-bordered w-full p-2 py-4"></textarea>
                                <form method="dialog">
                                    <button wire:click="updateChoiceContent({{ $i }})"
                                        class="w-full btn btn-primary">
                                        Simpan
                                    </button>
                                </form>
                            </div>
                        </dialog>

                        <div class="flex justify-end items-center gap-2 mt-2 md:mt-0">
                            {{-- Edit Button --}}
                            <button wire:key="button-edit-{{ $question->questionChoices[$i]->id }}"
                                class="btn self-center"
                                onclick="my_modal_{{ str_replace('-', '_', $question->questionChoices[$i]->id) }}.showModal()">
                                <x-lucide-pencil class="w-4 h-4" />
                            </button>
                            {{-- Delete Button --}}
                            @if (count($choices) !== 1)
                                <button wire:key="button-del-{{ $question->questionChoices[$i]->id }}"
                                    wire:click="deleteChoice('{{ $question->questionChoices[$i]->id }}')"
                                    class="btn btn-error self-center">
                                    <x-lucide-trash class="w-4 h-4" />
                                </button>
                            @endif
                        </div>
                    </div>
                @endfor
                <button wire:click="addChoice"
                    class="btn w-full btn-outline justify-center cursor-pointer justify-start">
                    + Tambah Pilihan
                </button>
            </div>
        </div>
    </div>
</div>
