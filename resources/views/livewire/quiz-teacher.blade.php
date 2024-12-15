<main class="px-20 flex flex-col py-24 gap-20 bg-gray-50">
    @for($i = 0; $i < 10; $i++)
        @if($curQuestion->question_type === \App\Enums\QuestionType::MultipleChoice)
            <livewire:quiz-teacher.multiple-choice
                :page="$page"
                :questionCount="$questionCount"
                :question="$curQuestion"
                :submissionId="$submission->id"
                wire:key="question-{{ $page }}"
            />
            ui
        @elseif($curQuestion->question_type === \App\Enums\QuestionType::ShortAnswer)
            <livewire:quiz-teacher.short-answer
                :page="$page"
                :questionCount="$questionCount"
                :question="$curQuestion"
                :submissionId="$submission->id"
                wire:key="question-{{ $page }}"
            />
        @elseif($curQuestion->question_type === \App\Enums\QuestionType::MultiSelect)
            <livewire:quiz-teacher.multi-select
                :page="$page"
                :questionCount="$questionCount"
                :question="$curQuestion"
                :submissionId="$submission->id"
                wire:key="question-{{ $page }}"
            />
        @elseif($curQuestion->question_type === \App\Enums\QuestionType::Essay)
            <livewire:quiz-teacher.essay
                :page="$page"
                :questionCount="$questionCount"
                :question="$curQuestion"
                :submissionId="$submission->id"
                wire:key="question-{{ $page }}"
            />
        @endif
    @endfor
</main>
