@php use App\Enums\QuestionType;use Illuminate\Support\Facades\Session; @endphp
<main class="px-8 py-6 flex flex-col gap-6 bg-base-200 rounded-xl">
    <h1 class="mb-4">
        Mindora Quiz Editor
    </h1>
    <!-- Alert -->
    <div class="flex justify-between items-start">
        <div class="card w-3/4 bg-base-100 shadow-xl">
            <div class="card-body flex-row justify-between">
                <div class="flex flex-col gap-5">
                    @if(!$isInfoEditable)
                        <div>
                            <span class="text-sm text-gray-700">Judul</span>
                            <h2 class="card-title text-2xl">{{ $quiz->courseItem->name }}</h2>
                        </div>
                        <div>
                            <span class="text-sm text-gray-700">Deskripsi</span>
                            <p>{{ $quiz->courseItem->description }}</p>
                        </div>
                    @else
                        <div>
                            <label for="nama_quiz" class="text-sm text-gray-700">Judul</label>
                            <input type="text" id="nama_quiz" wire:model.defer="name" required
                                   placeholder="Masukkan Judul..."
                                   class="input input-ghost !text-2xl !font-semibold"/>
                        </div>
                        <div>
                            <label for="desc_quiz" class="text-sm text-gray-700">Deskripsi</label>
                            <textarea type="text" id="desc_quiz" wire:model.defer="description" rows="5"
                                      placeholder="Masukkan Deskripsi..."
                                      class="textarea textarea-ghost resize-y"> </textarea>
                        </div>
                    @endif
                </div>
                <div class="flex items-start gap-2">
                    @if($isInfoEditable)
                        <button class="btn btn-sm btn-error"
                                wire:click="$toggle('isInfoEditable')">
                            <x-lucide-x class="w-4 h-4"/>
                        </button>
                        <button class="btn btn-sm btn-success"
                                wire:click="saveInfo">
                            <x-lucide-check class="w-4 h-4"/>
                        </button>
                    @else
                        <button class="btn btn-sm"
                                wire:click="$toggle('isInfoEditable')">
                            <x-lucide-pencil class="w-4 h-4"/>
                        </button>
                    @endif
                </div>
            </div>
        </div>
        <button wire:click="simpan"  class="btn btn-primary flex items-center gap-2">
            <x-lucide-save class="w-5 h-5"/>
            <span>Simpan</span>
        </button>
    </div>

    <!-- Section untuk pengaturan waktu quiz -->
    <section class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-medium mb-4">Pengaturan Waktu</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex flex-col gap-2">
                <label for="start_time" class="text-sm font-medium">Waktu Mulai</label>
                <input wire:model.live="startTime" type="datetime-local" id="start_time"
                       class="input input-bordered w-full"/>
            </div>
            <div class="flex flex-col gap-2">
                <label for="start_time" class="text-sm font-medium">Waktu Mulai</label>
                <input wire:model.live="endTime" type="datetime-local" id="start_time"
                       class="input input-bordered w-full"/>
            </div>

            <div class="flex flex-col gap-2">
                <label for="duration" class="text-sm font-medium">Durasi (dalam menit)</label>
                <input wire:model.live="duration" type="number" id="duration" class="input input-bordered w-full"
                       min="1"/>
            </div>
        </div>
    </section>

    <section class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-medium mb-4">Daftar Soal</h2>
        <div class="flex flex-col gap-6">
            @foreach($quiz->questions as $index => $question)
                @php $i = $index + 1; @endphp
                @if($question->question_type === QuestionType::MultipleChoice)
                    <livewire:quiz-teacher.multiple-choice
                        :num="$i"
                        :question="$question"
                        :choices="$question->questionChoices->toArray()"
                        wire:key="question-{{ $question->id }}-{{ $i }}"
                    />
                @elseif($question->question_type === QuestionType::ShortAnswer)
                    <livewire:quiz-teacher.short-answer
                        :num="$i"
                        :question="$question"
                        :choices="$question->questionChoices->toArray()"
                        wire:key="question-{{ $question->id }}-{{ $i }}"
                    />
                @elseif($question->question_type === QuestionType::MultiSelect)
                    <livewire:quiz-teacher.multi-select
                        :num="$i"
                        :question="$question"
                        :choices="$question->questionChoices->toArray()"
                        wire:key="question-{{ $question->id  }}-{{ $i }}"
                    />
                @elseif($question->question_type === QuestionType::Essay)
                    <livewire:quiz-teacher.essay
                        :num="$i"
                        :question="$question"
                        :choices="$question->questionChoices->toArray()"
                        wire:key="question-{{ $question->id }}-{{ $i }}"
                    />
                @endif
            @endforeach
        </div>
    </section>

    <button class="btn btn-outline w-full flex items-center justify-center gap-2 mt-6"
            onclick="question_add_modal.showModal()">
        <x-lucide-plus class="w-5 h-5"/>
        <span>Tambah Soal</span>
    </button>

    <dialog id="question_add_modal" class="modal">
        <div class="modal-box">
            <h3 class="text-lg font-medium mb-4">Pilih jenis soal</h3>
            <select wire:model="questionType" class="select w-full mb-4">
                <option value="{{ QuestionType::MultipleChoice }}">Multiple Choice</option>
                <option value="{{ QuestionType::ShortAnswer }}">Short Answer</option>
                <option value="{{ QuestionType::MultiSelect }}">Multi Select</option>
                <option value="{{ QuestionType::Essay }}">Essay</option>
            </select>
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <div class="modal-action">
                <form method="dialog" class="w-full">
                    <!-- if there is a button in form, it will close the modal -->
                    <button wire:click="addQuestion" class="btn btn-primary w-full">Tambah</button>
                </form>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
</main>
