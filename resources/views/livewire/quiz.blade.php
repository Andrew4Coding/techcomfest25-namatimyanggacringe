<main class="px-20 flex flex-col py-10 gap-20">
    @if($isValid)
    {{-- Left Section: Question Navigation --}}
        <div class="flex w-full justify-start items-center">
            <h3 class="text-xl w-44 mr-12">{{ $quiz->courseItem->name }}</h3>
            <div class="flex items-center">
                <span class="w-10 flex items-center">{{ intdiv($page * 100, $questionCount) }}%</span>
                <progress class="progress h-3 w-56" value="{{ $page }}" max="{{ $questionCount }}"></progress>
            </div>
            <span wire:poll.500ms id="time-left" class="ml-auto text-gray-800">{{ $timeLeft }}</span>
        </div>
        <section class="flex flex-col w-1/4 gap-4">
        <section class="h-full w-full bg-white p-6 shadow-md rounded-lg">
            {{-- Timer --}}

            <h2 class="text-lg font-semibold mb-4">Question Navigator</h2>
            <div class="grid grid-cols-5 gap-3">
                @for ($i = 1; $i <= $questionCount; $i++)
                    <button type="button"
                            wire:key="question-circle-{{ $i }}"
                            wire:click="moveTo({{ $i }})"
                            id="question-circle-{{ $i }}"
                            class="question-circles w-12 h-12 flex items-center justify-center rounded-full border-2
                                    shadow-sm
                                    hover:bg-blue-100 hover:cursor-pointer
                                    @if($page == $i)
                                        bg-blue-500 text-white hover:bg-blue-600
                                    @elseif($flagged[$quiz->questions[$i - 1]->id])
                                        bg-yellow-200 text-gray-700 hover:bg-yellow-300 border-yellow-400
                                    @else
                                        text-gray-700 border-gray-300
                                    @endif">
                            {{ $i }}
                    </button>
                @endfor
            </div>
        </section>
    </section>

        {{-- Right Section: Current Question --}}
        @if($curQuestion->question_type === \App\Enums\QuestionType::MultipleChoice)
            <livewire:quiz.multiple-choice
                :page="$page"
                :questionCount="$questionCount"
                :question="$curQuestion"
                :submissionId="$submission->id"
                wire:key="question-{{ $page }}"
            />
        @elseif($curQuestion->question_type === \App\Enums\QuestionType::ShortAnswer)
            <livewire:quiz.short-answer
                :page="$page"
                :questionCount="$questionCount"
                :question="$curQuestion"
                :submissionId="$submission->id"
                wire:key="question-{{ $page }}"
            />
        @elseif($curQuestion->question_type === \App\Enums\QuestionType::MultiSelect)
            <livewire:quiz.multi-select
                :page="$page"
                :questionCount="$questionCount"
                :question="$curQuestion"
                :submissionId="$submission->id"
                wire:key="question-{{ $page }}"
            />
        @elseif($curQuestion->question_type === \App\Enums\QuestionType::Essay)
            <livewire:quiz.essay
                :page="$page"
                :questionCount="$questionCount"
                :question="$curQuestion"
                :submissionId="$submission->id"
                wire:key="question-{{ $page }}"
            />
        @endif

{{--FIXME: INI KALAU USERNYA TIDAK VALDI--}}
    @else
    <h1>Tes</h1>
    @endif
</main>
