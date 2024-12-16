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
            <div class="card-actions mt-4 gap-4">
                @for($i = 0; $i < count($choices); $i++)
                    <label
                        wire:key="choices-{{ $question->questionChoices[$i]->id }}"
                        class="btn h-fit btn-success basis-10/12 px-6 py-3 cursor-pointer justify-start items-center outline outline-1
                        {{--Check if answer--}}
                        @if(!(in_array($question->questionChoices[$i]->id, $answers))) btn-outline @endif"
                    >
                        <span class="mr-2 font-bold block">{{ $this->toLetter($i) }}</span>
                        <span class="basis-9/12 text-left leading-4">{{ $question->questionChoices[$i]->content }}</span>
                        <input
                            wire:model.change="answers"
                            type="checkbox"
                            name="checkbox-{{ $question->questionChoices[$i]->id }}"
                            class="hidden"
                            value="{{ $question->questionChoices[$i]->id }}"
                        />
                    </label>
                    <dialog
                        wire:key="dialog-{{ $question->questionChoices[$i]->id }}"
                        id="my_modal_{{ str_replace('-', '_', $question->questionChoices[$i]->id) }}" class="modal"
                    >
                        <div class="modal-box">
                            <form method="dialog">
                                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                            </form>
                            <h3 class="text-lg font-bold">Ganti Jawaban!</h3>
                            <textarea
                                wire:model="choices.{{$i}}.content"
                                placeholder="aku adalah..."
                                class="textarea textarea-bordered w-full p-2 py-4"
                            ></textarea>
                            <div class="modal-action">
                                <form method="dialog">
                                    <!-- if there is a button in form, it will close the modal -->
                                       <button wire:click="updateChoice('{{ $question->questionChoices[$i]->id }}')" class="btn">Simpan</button>
                                </form>
                            </div>
                        </div>
                    </dialog>

                    {{--Edit Button--}}
                    <button
                        wire:key="button-edit-{{ $question->questionChoices[$i]->id }}"
                        class="btn self-center"
                        onclick="my_modal_{{ str_replace('-', '_', $question->questionChoices[$i]->id) }}.showModal()"
                    >
                        P
                    </button>
                    {{--Delete Button--}}
                    <button
                        wire:key="button-del-{{ $question->questionChoices[$i]->id }}"
                        wire:click="deleteChoice('{{ $question->questionChoices[$i]->id }}')"

                        class="btn btn-error self-center"
                    >
                        <x-lucide-trash class="w-4 h-4" />
                    </button>
                @endfor
                <button wire:click="addChoice" class="btn w-full btn-accent cursor-pointer justify-start">
                    + Tambah Pilihan
                </button>
            </div>
        </div>
    </div>
</div>
