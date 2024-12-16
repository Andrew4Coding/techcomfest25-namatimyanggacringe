<main class="px-4 sm:px-6 md:px-8 lg:px-20 flex flex-col py-8 sm:py-10 gap-20 bg-gray-50 min-h-screen">
    @if($isValid)
        {{-- Header: Course Name, Progress, and Timer --}}
        <div class="flex flex-col lg:flex-row items-start lg:items-center w-full gap-8">
            <h3 class="text-xl sm:text-2xl lg:text-2xl font-semibold text-gray-800">
                {{ $quiz->courseItem->name }}
            </h3>
            <div class="flex items-center flex-1 gap-4 w-full">
                <span class="text-xs sm:text-sm text-gray-700">
                    {{ intdiv($page * 100, $questionCount) }}%
                </span>
                <progress class="progress progress-primary h-3 flex-1 rounded" value="{{ $page }}" max="{{ $questionCount }}"></progress>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-12">
            {{-- Left Section: Question Navigation --}}
            <section class="w-full lg:w-1/4">
                <div class="bg-white p-4 sm:p-6 lg:p-6 shadow-lg rounded-lg">
                    <h2 class="text-lg sm:text-xl font-semibold mb-4 sm:mb-6 text-gray-800">Question Navigator</h2>
                    <div class="">
                        <div class="grid grid-cols-4 gap-4">
                            @for ($i = 1; $i <= $questionCount; $i++)
                                <button
                                    type="button"
                                    wire:key="question-circle-{{ $i }}"
                                    wire:click="moveTo({{ $i }})"
                                    id="question-circle-{{ $i }}"
                                    class="w-10 sm:w-12 lg:w-12 h-10 sm:h-12 lg:h-12 flex items-center justify-center rounded-full border-2
                                           shadow-sm transition-colors duration-200
                                           @if($page == $i)
                                               bg-blue-500 text-white border-blue-500
                                           @elseif($flagged[$quiz->questions[$i - 1]->id])
                                               bg-yellow-200 text-gray-800 border-yellow-400
                                           @else
                                               bg-white text-gray-700 border-gray-300
                                           @endif
                                           hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                >
                                    {{ $i }}
                                </button>
                            @endfor
                        </div>
                    </div>
                </div>
            </section>

            {{-- Right Section: Current Question --}}
            <section class="flex-1">
                <div class="bg-white p-4 sm:p-6 lg:p-6 shadow-lg rounded-lg">
                    @if($curQuestion->question_type === \App\Enums\QuestionType::MultipleChoice)
                        <livewire:quiz-solution.multiple-choice
                            :page="$page"
                            :questionCount="$questionCount"
                            :question="$curQuestion"
                            :submissionId="$submission->id"
                            wire:key="question-{{ $page }}"
                        />
                    @elseif($curQuestion->question_type === \App\Enums\QuestionType::ShortAnswer)
                        <livewire:quiz-solution.short-answer
                            :page="$page"
                            :questionCount="$questionCount"
                            :question="$curQuestion"
                            :submissionId="$submission->id"
                            wire:key="question-{{ $page }}"
                        />
                    @elseif($curQuestion->question_type === \App\Enums\QuestionType::MultiSelect)
                        <livewire:quiz-solution.multi-select
                            :page="$page"
                            :questionCount="$questionCount"
                            :question="$curQuestion"
                            :submissionId="$submission->id"
                            wire:key="question-{{ $page }}"
                        />
                    @elseif($curQuestion->question_type === \App\Enums\QuestionType::Essay)
                        <livewire:quiz-solution.essay
                            :page="$page"
                            :questionCount="$questionCount"
                            :question="$curQuestion"
                            :submissionId="$submission->id"
                            wire:key="question-{{ $page }}"
                        />
                    @endif
                </div>
            </section>
        </div>
    @else
        {{-- Tampilan Jika User Tidak Valid --}}
        <div class="flex items-center justify-center h-full px-4 sm:px-6 lg:px-20">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-red-600 text-center">
                Tes Tidak Valid
            </h1>
        </div>
    @endif
</main>
