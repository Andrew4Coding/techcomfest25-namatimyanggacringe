<?php

@props(['question'])
<section class="bg-white h-full flex-1 p-10 shadow-md rounded-lg relative">
    {{-- Question and Pagination Info --}}
    <div class="flex justify-between items-center">
        <h1 id="question-content" class="text-2xl font-bold"></h1>
        <span class="text-sm text-gray-500">Question {{ $page }} / {{ $questionCount }}</span>
    </div>

    {{-- Answer Options  --}}
    <div id="answer-box" class="mt-6 space-y-4">

    </div>

    {{-- Actions: Flag, Next, Submit --}}
    <div class="mt-10 flex justify-between items-center">
        {{-- Flag Question Button --}}
        <button type="button"
                class="flex items-center gap-2 px-4 py-2 text-yellow-500 bg-yellow-100 rounded-lg hover:bg-yellow-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                 stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M6.75 4.5h10.5a1.5 1.5 0 011.5 1.5v10.5a1.5 1.5 0 01-1.5 1.5H6.75M9 6.75l4.5 4.5L9 15.75"/>
            </svg>
            Flag Question
        </button>

        {{-- Navigation Buttons --}}
        @if ($page < $questionCount)
            <a href="/quiz/{{ $id  }}?page={{ $page + 1 }}">
                <button class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">
                    Next
                </button>
            </a>
        @else
            <button type="submit"
                    class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition">
                Submit
            </button>
        @endif
    </div>
</section>
