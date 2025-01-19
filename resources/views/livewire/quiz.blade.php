@php use App\Enums\QuestionType; @endphp
<main class=" flex flex-col gap-20 min-h-full">
    @if ($isValid)
        {{-- Header: Course Name, Progress, and Timer --}}
        <div class="flex flex-col lg:flex-row items-start lg:items-center w-full gap-8">
            <h3 class="text-xl sm:text-2xl lg:text-2xl font-semibold text-gray-800">
                {{ $quiz->courseItem->name }}
            </h3>
            <div class="flex items-center flex-1 gap-4 w-full">
                <span class="text-xs sm:text-sm text-gray-700">
                    {{ intdiv($page * 100, $questionCount) }}%
                </span>
                <progress class="progress progress-primary h-3 flex-1 rounded" value="{{ $page }}"
                    max="{{ $questionCount }}"></progress>
            </div>
            <span wire:poll.500ms id="time-left" class="text-sm sm:text-lg font-medium text-red-600">
                {{ $timeLeft }}
            </span>
        </div>

        <div class="flex flex-col lg:flex-row gap-12">
            {{-- Left Section: Question Navigation --}}
            <section class="w-full lg:w-1/4">
                <div class="space-y-4">
                    <h2 class="text-lg font-semibold text-gray-800">Question Navigator</h2>
                    <div class="">
                        <div class="grid grid-cols-5 gap-2">
                            @for ($i = 1; $i <= $questionCount; $i++)
                                <button type="button" wire:key="question-circle-{{ $i }}"
                                    wire:click="moveTo({{ $i }})" id="question-circle-{{ $i }}"
                                    class="w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center rounded-xl border-2 relative
                                           shadow-sm transition-colors duration-200
                                           @if ($page == $i) bg-blue-500 text-white border-blue-500
                                           @elseif($flagged[$quiz->questions[$i - 1]->id])
                                               bg-yellow-200 text-gray-800 border-yellow-400
                                           @else
                                               bg-white text-gray-700 border-gray-300 @endif
                                           hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-blue-400">

                                    <x-lucide-flag
                                        class="absolute -top-2 -right-2 w-4 h-4 text-yellow-400 {{ $flagged[$quiz->questions[$i - 1]->id] ? 'block' : 'hidden' }}" />
                                    {{ $i }}
                                </button>
                            @endfor
                        </div>
                    </div>
                </div>
            </section>

            {{-- Right Section: Current Question --}}
            <section class="flex-1">
                @if ($curQuestion->question_type === QuestionType::MultipleChoice)
                    <livewire:quiz.multiple-choice :page="$page" :questionCount="$questionCount" :question="$curQuestion"
                        :submissionId="$submission->id" wire:key="question-{{ $page }}" />
                @elseif($curQuestion->question_type === QuestionType::ShortAnswer)
                    <livewire:quiz.short-answer :page="$page" :questionCount="$questionCount" :question="$curQuestion" :submissionId="$submission->id"
                        wire:key="question-{{ $page }}" />
                @elseif($curQuestion->question_type === QuestionType::MultiSelect)
                    <livewire:quiz.multi-select :page="$page" :questionCount="$questionCount" :question="$curQuestion"
                        :submissionId="$submission->id" wire:key="question-{{ $page }}" />
                @elseif($curQuestion->question_type === QuestionType::Essay)
                    <livewire:quiz.essay :page="$page" :questionCount="$questionCount" :question="$curQuestion" :submissionId="$submission->id"
                        wire:key="question-{{ $page }}" />
                @endif
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
