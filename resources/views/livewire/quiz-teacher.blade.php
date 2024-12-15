<main class="px-20 flex flex-col ">
    <button wire:click="back" class="btn btn-success self-end">Simpan</button>
    <div class="flex flex-col items-center gap-2 mb-8">
        <h1 class="items-center text-center">{{ $quiz->courseItem->name }}</h1>
        <p class="text-center">{{ $quiz->courseItem->description }}</p>
    </div>
    @foreach($quiz->questions as $index => $question)
        @php $i = $index + 1; @endphp
        @if($question->question_type === \App\Enums\QuestionType::MultipleChoice)
            <livewire:quiz-teacher.multiple-choice
                :num="$i"
                :question="$question"
                :choices="$question->questionChoices->toArray()"
                wire:key="question-{{ $question->id }}-{{ $i }}"
            />
        @elseif($question->question_type === \App\Enums\QuestionType::ShortAnswer)
            <livewire:quiz-teacher.short-answer
                :num="$i"
                :question="$question"
                :choices="$question->questionChoices->toArray()"
                wire:key="question-{{ $question->id }}-{{ $i }}"
            />
        @elseif($question->question_type === \App\Enums\QuestionType::MultiSelect)
            <livewire:quiz-teacher.multi-select
                :num="$i"
                :question="$question"
                :choices="$question->questionChoices->toArray()"
                wire:key="question-{{ $question->id  }}-{{ $i }}"
            />
        @elseif($question->question_type === \App\Enums\QuestionType::Essay)
            <livewire:quiz-teacher.essay
                :num="$i"
                :question="$question"
                :choices="$question->questionChoices->toArray()"
                wire:key="question-{{ $question->id }}-{{ $i }}"
            />
        @endif
    @endforeach

    <button class="btn btn-outline btn-primary w-full outline outline-1" onclick="question_add_modal.showModal()">Tambah Soal</button>
    <dialog id="question_add_modal" class="modal">
        <div class="modal-box">
            <h3 class="text-lg font-bold">Pilih jenis soal</h3>
            <select wire:model="questionType" class="select select-bordered w-full">
                <option value="{{ \App\Enums\QuestionType::MultipleChoice }}">Multiple Choice</option>
                <option value="{{ \App\Enums\QuestionType::ShortAnswer }}">Short Answer</option>
                <option value="{{ \App\Enums\QuestionType::MultiSelect }}">Multi Select</option>
                <option value="{{ \App\Enums\QuestionType::Essay }}">Essay</option>
            </select>
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <div class="modal-action">
                <form method="dialog" class="w-full">
                    <!-- if there is a button in form, it will close the modal -->
                    <button wire:click="addQuestion" class="btn w-full">Tambah</button>
                </form>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
</main>
