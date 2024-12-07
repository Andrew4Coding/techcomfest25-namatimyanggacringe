@php
    $questions = [
        [
            'question' => 'What is the capital of France?',
            'answers' => ['Paris', 'London', 'Berlin', 'Madrid'],
            'correct' => 0,
        ],
        [
            'question' => 'What is the capital of Germany?',
            'answers' => ['Paris', 'London', 'Berlin', 'Madrid'],
            'correct' => 2,
        ],
        [
            'question' => 'What is the capital of Spain?',
            'answers' => ['Paris', 'London', 'Berlin', 'Madrid'],
            'correct' => 3,
        ],
        [
            'question' => 'What is the capital of England?',
            'answers' => ['Paris', 'London', 'Berlin', 'Madrid'],
            'correct' => 1,
        ],
        [
            'question' => 'What is the capital of Italy?',
            'answers' => ['Paris', 'London', 'Berlin', 'Madrid'],
            'correct' => 0,
        ],
        [
            'question' => 'What is the capital of France?',
            'answers' => ['Paris', 'London', 'Berlin', 'Madrid'],
            'correct' => 0,
        ],
        [
            'question' => 'What is the capital of Germany?',
            'answers' => ['Paris', 'London', 'Berlin', 'Madrid'],
            'correct' => 2,
        ],
        [
            'question' => 'What is the capital of Spain?',
            'answers' => ['Paris', 'London', 'Berlin', 'Madrid'],
            'correct' => 3,
        ],
        [
            'question' => 'What is the capital of England?',
            'answers' => ['Paris', 'London', 'Berlin', 'Madrid'],
            'correct' => 1,
        ],
        [
            'question' => 'What is the capital of Italy?',
            'answers' => ['Paris', 'London', 'Berlin', 'Madrid'],
            'correct' => 0,
        ],
        [
            'question' => 'What is the capital of France?',
            'answers' => ['Paris', 'London', 'Berlin', 'Madrid'],
            'correct' => 0,
        ],
        [
            'question' => 'What is the capital of Germany?',
            'answers' => ['Paris', 'London', 'Berlin', 'Madrid'],
            'correct' => 2,
        ],
        [
            'question' => 'What is the capital of Spain?',
            'answers' => ['Paris', 'London', 'Berlin', 'Madrid'],
            'correct' => 3,
        ],
        [
            'question' => 'What is the capital of England?',
            'answers' => ['Paris', 'London', 'Berlin', 'Madrid'],
            'correct' => 1,
        ],
        [
            'question' => 'What is the capital of Italy?',
            'answers' => ['Paris', 'London', 'Berlin', 'Madrid'],
            'correct' => 0,
        ],
        [
            'question' => 'What is the capital of France?',
            'answers' => ['Paris', 'London', 'Berlin', 'Madrid'],
            'correct' => 0,
        ],
        [
            'question' => 'What is the capital of Germany?',
            'answers' => ['Paris', 'London', 'Berlin', 'Madrid'],
            'correct' => 2,
        ],
        [
            'question' => 'What is the capital of Spain?',
            'answers' => ['Paris', 'London', 'Berlin', 'Madrid'],
            'correct' => 3,
        ],
        [
            'question' => 'What is the capital of England?',
            'answers' => ['Paris', 'London', 'Berlin', 'Madrid'],
            'correct' => 1,
        ],
        [
            'question' => 'What is the capital of Italy?',
            'answers' => ['Paris', 'London', 'Berlin', 'Madrid'],
            'correct' => 0,
        ],
    ];
@endphp

@extends('layout.layout')
@section('content')
    <main class="px-20 h-[100vh] flex py-24 gap-20 bg-gray-50">
        {{-- Left Section: Question Navigation --}}
        <section class="flex flex-col w-1/4 gap-4">
            {{ Auth::user()->userable()->first()->id }}
            <div id="timer" class="text-gray-700 bg-white px-4 py-2 rounded-lg shadow-md">
                Time Left: <span id="time-left">10:00</span>
            </div>
            <section class="h-full w-full bg-white p-6 shadow-md rounded-lg">
                {{-- Timer --}}

                <h2 class="text-lg font-semibold mb-4">Question Navigator</h2>
                <div class="grid grid-cols-5 gap-3">
                    @for ($i = 0; $i < $questionCount; $i++)
                        <a href="/quiz/{{ $id }}?page={{ $i + 1 }}">
                            <div
                                class="w-12 h-12 flex items-center justify-center rounded-full border-2
                                        shadow-sm
                                        hover:bg-blue-100
                                        @if ($i + 1 == $page) bg-blue-500 text-white @else text-gray-700 border-gray-300 @endif">
                                {{ $i + 1 }}
                            </div>
                        </a>
                    @endfor
                </div>
            </section>
        </section>

        {{-- Right Section: Current Question --}}
        <section class="bg-white h-full flex-1 p-10 shadow-md rounded-lg relative">
            {{-- Question and Pagination Info --}}
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">{{ $questions[$page - 1]['question'] }}</h1>
                <span class="text-sm text-gray-500">Question {{ $page }} / {{ $questionCount }}</span>
            </div>

            {{-- Answer Options --}}
            <div class="mt-6 space-y-4">
                @foreach ($questions[$page - 1]['answers'] as $key => $answer)
                    <div class="flex items-center gap-4">
                        <input type="radio" name="answer" id="answer-{{ $key }}" value="{{ $key }}"
                            class="h-5 w-5 text-blue-500 focus:ring-blue-400 border-gray-300">
                        <label for="answer-{{ $key }}" class="text-gray-700 text-lg cursor-pointer">
                            {{ $answer }}
                        </label>
                    </div>
                @endforeach
            </div>

            {{-- Actions: Flag, Next, Submit --}}
            <div class="mt-10 flex justify-between items-center">
                {{-- Flag Question Button --}}
                <button type="button"
                    class="flex items-center gap-2 px-4 py-2 text-yellow-500 bg-yellow-100 rounded-lg hover:bg-yellow-200">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 4.5h10.5a1.5 1.5 0 011.5 1.5v10.5a1.5 1.5 0 01-1.5 1.5H6.75M9 6.75l4.5 4.5L9 15.75" />
                    </svg>
                    Flag Question
                </button>

                {{-- Navigation Buttons --}}
                @if ($page < $questionCount)
                    <a href="/quiz/{{ $id }}?page={{ $page + 1 }}">
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
    </main>

    {{-- Timer Script --}}
    <script>
        // Initialize timer variables
        let time = 10 * 60; // 10 minutes in seconds
        const timerElement = document.getElementById('time-left');

        // Function to format the time as mm:ss
        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = seconds % 60;
            return `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
        }

        // Update the timer every second
        const timerInterval = setInterval(() => {
            if (time > 0) {
                time -= 1;
                timerElement.textContent = formatTime(time);
            } else {
                clearInterval(timerInterval);
                alert('Time is up! Submitting your answers...');
                // Automatically submit the form or redirect
                window.location.href = '/quiz/{{ $id }}/submit';
            }
        }, 1000);
    </script>
@endsection
