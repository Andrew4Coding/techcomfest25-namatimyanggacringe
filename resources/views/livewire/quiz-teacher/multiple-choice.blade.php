<div class="card bg-base-100 w-full shadow-xl">
    <div class="card-body flex flex-col md:flex-row gap-4 items-start w-full">
        <span class="min-w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">{{ $num }}</span>
        <div class="w-full">
            {{-- Pertanyaan dan nomor --}}
            <div class="flex items-center gap-2">
                <h2 class="card-title">
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

            {{-- Aksi --}}
            <div class="card-actions mt-4 gap-4 flex flex-col">
                @for ($i = 0; $i < count($choices); $i++)
                    <div class="w-full flex flex-col md:flex-row items-start md:items-center">
                        <label wire:key="choices-{{ $question->questionChoices[$i]->id }}"
                            class="btn h-fit flex-1 justify-start"
                            @if (!($question->questionChoices[$i]->id === $answer)) btn-outline @endif">
                            <span class="mr-2 font-bold block">{{ $this->toLetter($i) }}</span>
                            <span class="text-left leading-4">{{ $question->questionChoices[$i]->content }}</span>
                            <input wire:model.change="answer" type="radio"
                                name="radio-{{ $question->questionChoices[$i]->id }}" class="hidden"
                                value="{{ $question->questionChoices[$i]->id }}" />
                        </label>

                        <dialog wire:key="dialog-{{ $question->questionChoices[$i]->id }}"
                            id="my_modal_{{ str_replace('-', '_', $question->questionChoices[$i]->id) }}" class="modal">
                            <div class="modal-box">
                                <form method="dialog">
                                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                                </form>
                                <h3 class="text-lg font-bold">Ganti Jawaban!</h3>
                                <textarea wire:model="choices.{{ $i }}.content" placeholder="aku adalah..."
                                    class="textarea textarea-bordered w-full p-2 py-4"></textarea>
                                <div class="modal-action">
                                    <form method="dialog">
                                        <button wire:click="updateChoice('{{ $question->questionChoices[$i]->id }}')"
                                            class="btn">Simpan</button>
                                    </form>
                                </div>
                            </div>
                        </dialog>

                        <div class="flex items-center gap-2 mt-2 md:mt-0">
                            {{-- Edit Button --}}
                            <button wire:key="button-edit-{{ $question->questionChoices[$i]->id }}" class="btn self-center"
                                onclick="my_modal_{{ str_replace('-', '_', $question->questionChoices[$i]->id) }}.showModal()">
                                <x-lucide-pencil class="w-4 h-4" />
                            </button>
                            {{-- Delete Button --}}
                            <button wire:key="button-del-{{ $question->questionChoices[$i]->id }}"
                                wire:click="deleteChoice('{{ $question->questionChoices[$i]->id }}')"
                                class="btn btn-error self-center">
                                <x-lucide-trash class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                @endfor
                <button wire:click="addChoice" class="btn w-full btn-primary cursor-pointer mt-4">
                    + Tambah Pilihan
                </button>
            </div>
        </div>
    </div>
</div>
