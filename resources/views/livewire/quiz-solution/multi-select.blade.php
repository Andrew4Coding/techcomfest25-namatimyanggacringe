@php use App\Models\Teacher; @endphp
<section class="bg-white flex-1 p-10 shadow-md rounded-lg rounded-tl-none relative">
    {{-- Informasi Pertanyaan dan Pagination --}}
    <div class="flex justify-between items-center">
        <h1 id="question-content" class="text-2xl font-bold">{{ $question['content'] }}</h1>
        <span class="text-sm text-gray-500">Question {{ $page }} / {{ $questionCount }}</span>
    </div>

    {{-- Pilihan Jawaban --}}
    @foreach ($question['questionChoices'] as $choice)
        @php
            $isCorrect = in_array($choice->id, explode(',', $question->answer));
            $isSelected = in_array($choice->id, explode(',', $submissionItem->answer));
        @endphp

        <div wire:key="{{ $choice->id }}" id="answer-box" class="mt-6 space-y-4">
            <div
                class="flex items-center gap-4
                @if ($isCorrect && $isSelected) bg-green-100 border border-green-400 rounded
                @elseif($isSelected && !$isCorrect) bg-red-100 border border-red-400 rounded
                @elseif($isCorrect && !$isSelected) bg-green-50 border border-green-300 rounded @endif
                p-2">
                <input type="checkbox" id="answer-{{ $choice->id }}" value="{{ $choice->id }}"
                    class="answer-checkbox h-5 w-5 text-blue-500 focus:ring-blue-400 border-gray-300"
                    @disabled(true) {{-- Menonaktifkan input setelah submit --}}>
                <label for="answer-{{ $choice->id }}" class="text-gray-700 text-sm cursor-pointer flex items-center">
                    {{ $choice['content'] }}

                    @if ($isCorrect)
                        @if ($isSelected)
                            {{-- Jawaban Benar dan Dipilih --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 ml-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-green-600 ml-1">Benar</span>
                        @else
                            {{-- Jawaban Benar tapi Tidak Dipilih --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-300 ml-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-green-500 ml-1">Benar</span>
                        @endif
                    @endif

                    @if ($isSelected && !$isCorrect)
                        {{-- Jawaban Salah yang Dipilih --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 ml-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span class="text-red-600 ml-1">Salah</span>
                    @endif
                </label>
            </div>
        </div>
    @endforeach

    {{-- Feedback --}}
    <div class="w-full mt-10 flex flex-col items-start ">
        <div class="w-full flex items-center justify-between">
            <h2 class="text-base font-semibold">Feedback</h2>
            @if (Auth::user()->userable_type === Teacher::class)
                <button class="btn btn-sm" onclick="feedback_modal.showModal()">
                    <x-lucide-pencil class="w-4 h-4" />
                </button>
            @endif
        </div>
        <p class="block mt-4 text-sm text-gray-700">
            @if ($submissionItem->feedback === '')
                <span class="text-gray-500">Belum ada feedback</span>
            @else
                {{ $submissionItem->feedback }}
            @endif
        </p>
    </div>

    {{-- Aksi: Flag, Back, Next --}}
    <div class="mt-10 flex justify-between items-center">
        {{-- Tombol Flag Pertanyaan --}}
        <label
            class="flex items-center gap-2 px-4 py-2 rounded-lg @if ($flagged) text-yellow-100 bg-yellow-400 hover:bg-yellow-500 @else text-yellow-500 bg-yellow-100 hover:bg-yellow-200 @endif">
            <input wire:model.change="flagged" type="checkbox" class="hidden" />
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M6.75 4.5h10.5a1.5 1.5 0 011.5 1.5v10.5a1.5 1.5 0 01-1.5 1.5H6.75M9 6.75l4.5 4.5L9 15.75" />
            </svg>
            Flag Question
        </label>
        <button wire:click="$parent.prev"
            class="btn w-1/4 @if ($page <= 1) btn-disabled @else btn-primary @endif">
            Back
        </button>
        <button wire:click="$parent.next"
            class="btn w-1/4 @if ($page >= $questionCount) btn-disabled @else btn-primary @endif">
            Next
        </button>
    </div>
    <dialog id="feedback_modal" class="modal">
        <div class="modal-box w-11/12 max-w-5xl">
            <h3 class="text-lg font-bold mb-3">Give Feedback</h3>
            <textarea wire:model="feedback" class="textarea w-full" rows="4"></textarea>
            <div class="modal-action">
                <form method="dialog">
                    <!-- if there is a button, it will close the modal -->
                    <button class="btn" wire:click="saveFeedback">Simpan</button>
                </form>
            </div>
        </div>
    </dialog>
</section>
