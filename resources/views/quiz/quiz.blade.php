@php
    use Illuminate\Support\Js;
@endphp

@extends('layout.layout')
@section('content')
    <main class="px-20 h-[100vh] flex py-24 gap-20 bg-gray-50">
        {{-- Left Section: Question Navigation --}}
        <section class="flex flex-col w-1/4 gap-4">
            <div id="timer" class="text-gray-700 bg-white px-4 py-2 rounded-lg shadow-md">
                Time Left: <span id="time-left">10:00</span>
            </div>
            <section class="h-full w-full bg-white p-6 shadow-md rounded-lg">
                {{-- Timer --}}

                <h2 class="text-lg font-semibold mb-4">Question Navigator</h2>
                <div class="grid grid-cols-5 gap-3">
                    @for ($i = 1; $i <= $questionCount; $i++)
                        <a onclick="jumpTo({{$i}})">
                            <div
                                id="question-circle-{{$i}}"
                                class="question-circles w-12 h-12 flex items-center justify-center rounded-full border-2
                                        shadow-sm
                                        hover:bg-blue-100 hover:cursor-pointer
                                        text-gray-700 border-gray-300">
                                {{ $i }}
                            </div>
                        </a>
                    @endfor
                </div>
            </section>
        </section>

        {{-- Right Section: Current Question --}}

    </main>

    {{-- Timer Script --}}
    <script>
        // Initialize timer variables
        let time = 10 * 60; // 10 minutes in seconds
        const quiz = {{ Js::from($quiz) }};
        let page = 1;
        const timerElement = document.getElementById('time-left');

        let stateChanges = false;

        function checkForActive() {
            // flush all the question circles
            for (let el of document.getElementsByClassName('question-circles')) {
                el.classList.remove("bg-blue-500", "text-white");
            }

            console.log(document.getElementById(`question-circle-${page}`).innerHTML)

            // set active question
            const activeEl = document.getElementById(`question-circle-${page}`);
            activeEl.classList.add("bg-blue-500", "text-white", "hover:bg-blue-600");
            activeEl.classList.remove("hover:bg-blue-100");

            const question = quiz['questions'][page-1];
            const choices = question['question_choices'];

            document.getElementById("question-content").innerHTML = question['content'];

            const answerBox = document.getElementById("answer-box");

            // reset answerBox
            answerBox.innerHTML = '';

            for (let i = 0; i < choices.length; i++) {
                answerBox.innerHTML += `
                <div class="flex items-center gap-4">
                    <input type="radio" name="answer" id="answer-${i}" value="${choices[i]['content']}"
                            class="h-5 w-5 text-blue-500 focus:ring-blue-400 border-gray-300">
                    <label for="answer-${i}" class="text-gray-700 text-lg cursor-pointer">
                                ${choices[i]['content']}
                    </label>
                </div>
                `
            }
        }

        checkForActive();

        function nextQuestion() {
            if (page < {{ $questionCount }}) {
                page++
                checkForActive();
            }
        }

        function prevQuestion() {
            if (page > 1) {
                page--;
                checkForActive();
            }
        }

        function jumpTo(p) {
            if (p >= 1 && p <= {{ $questionCount }}) {
                page = p;
                checkForActive();
            }
        }

        function submitAnswer()

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
