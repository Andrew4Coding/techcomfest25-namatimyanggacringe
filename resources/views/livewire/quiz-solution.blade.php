@php use App\Enums\QuestionType;use App\Models\Teacher;use Illuminate\Support\Facades\Auth; @endphp
<main class="flex flex-col gap-20 min-h-full">
    @if($isValid)
        {{-- Header: Course Name, Progress, and Timer --}}
        <div class="flex flex-col items-start w-full gap-4">
            <h3 class="text-xl sm:text-2xl lg:text-2xl font-semibold text-gray-800">
                {{ $quiz->courseItem->name }}
            </h3>
            <div class="flex w-full justify-between">
                <div class="px-4 py-2 rounded-xl bg-base-200 flex">
            <span class="font-bold flex flex-col">
                <span class="text-sm text-gray-700">Nama Siswa</span>
                {{ $submission->student->user->name }}
            </span>
                    <div class="divider lg:divider-horizontal"></div>
                    <span class="flex font-bold flex-col">
                <span class="text-sm text-gray-700">Kelas</span>
                {{ $submission->student->class }}
                </span>
                </div>
                <div class="flex justify-between items-center gap-3">
                    @if(Auth::user()->userable_type === Teacher::class)
                        <button
                            wire:click="toggleChecked"
                            class="btn shadow-sm transition-colors duration-200
                                           @if($isCheckedByTeacher)
                                               bg-green-500
                                           @else
                                               bg-white text-gray-700
                                           @endif
                                           hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        >
                            @if($isCheckedByTeacher)
                                <x-lucide-check class="w-4 h-4"/> Terverifikasi
                            @else
                                <x-lucide-x class="w-4 h-4"/> Belum Diverifikasi
                            @endif
                        </button>
                        <a href="{{ route('quiz.submission.list', ['quizId' => $quiz->id]) }}" class="btn btn-primary">
                            Simpan
                        </a>
                    @else
                        <button
                            class="btn shadow-sm transition-colors duration-200 cursor-default
                                           @if($isCheckedByTeacher)
                                               bg-green-500 hover:bg-green-500
                                           @else
                                               bg-white text-gray-700
                                           @endif
                                           "
                        >
                            @if($isCheckedByTeacher)
                                <x-lucide-check class="w-4 h-4"/> Terverifikasi
                            @else
                                <x-lucide-x class="w-4 h-4"/> Belum Diverifikasi
                            @endif
                        </button>
                        <a href="{{ route('course.show', ['id' => $quiz->courseItem->courseSection->course->id]) }}"
                           class="btn btn-primary">
                            Kembali
                        </a>
                    @endif
                </div>
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
                    <span
                        class="px-4 py-2 text-white font-bold rounded-t-xl bg-primary">{{ $curSubmissionItem->score }} / {{ $curQuestion->weight }}</span>
                    @if($curQuestion->question_type === QuestionType::MultipleChoice)
                        <livewire:quiz-solution.multiple-choice
                            :page="$page"
                            :questionCount="$questionCount"
                            :question="$curQuestion"
                            :submissionId="$submission->id"
                            wire:key="question-{{ $page }}"
                        />
                    @elseif($curQuestion->question_type === QuestionType::ShortAnswer)
                        <livewire:quiz-solution.short-answer
                            :page="$page"
                            :questionCount="$questionCount"
                            :question="$curQuestion"
                            :submissionId="$submission->id"
                            wire:key="question-{{ $page }}"
                        />
                    @elseif($curQuestion->question_type === QuestionType::MultiSelect)
                        <livewire:quiz-solution.multi-select
                            :page="$page"
                            :questionCount="$questionCount"
                            :question="$curQuestion"
                            :submissionId="$submission->id"
                            wire:key="question-{{ $page }}"
                        />
                    @elseif($curQuestion->question_type === QuestionType::Essay)
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
