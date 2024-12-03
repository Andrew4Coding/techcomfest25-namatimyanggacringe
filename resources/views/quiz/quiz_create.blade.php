@extends('layout.layout')

@section('content')
<main class="px-20 py-40">
    <h1 class="text-3xl font-bold mb-5">Create Quiz</h1>
    
    <!-- Form to Create Quiz -->
    <form id="quiz-form" method="POST" enctype="multipart/form-data">
        @csrf
        <!-- Quiz Title -->
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Quiz Title</label>
            <input type="text" name="title" id="title" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
        </div>
        
        <!-- Quiz Description -->
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea name="description" id="description" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" rows="4" required></textarea>
        </div>
        
        <!-- Quiz Date and Time -->
        <div class="mb-4 flex space-x-4">
            <div>
                <label for="quiz_date" class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                <input type="date" name="quiz_date" id="quiz_date" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            </div>
            <div>
                <label for="start_hour" class="block text-sm font-medium text-gray-700 mb-2">Start Hour</label>
                <input type="time" name="start_hour" id="start_hour" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            </div>
            <div>
                <label for="end_hour" class="block text-sm font-medium text-gray-700 mb-2">End Hour</label>
                <input type="time" name="end_hour" id="end_hour" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            </div>
        </div>
        
        <!-- Questions Section -->
        <div id="questions" class="mb-6">
            <h3 class="text-xl font-semibold mb-4">Questions</h3>
        </div>
        
        <!-- Button to Add New Question -->
        <button type="button" id="add-question" class="bg-blue-500 text-white px-4 py-2 rounded-md mb-4">Add Question</button>

        <!-- Submit Button -->
        <button type="submit" id="submit-button" class="mt-4 bg-green-500 text-white px-6 py-3 rounded-md">Create Quiz</button>
    </form>
</main>

<script>
    let questionIndex = 0;

    function createQuestionElement(index, questionData = {}) {
        const questionSection = document.getElementById('questions');
        const newQuestion = document.createElement('div');
        newQuestion.classList.add('question', 'bg-gray-100', 'p-4', 'rounded-md', 'mb-4');

        let choicesMarkup = '';
        if (questionData.choices && questionData.choices.length > 0) {
            choicesMarkup = questionData.choices.map((choice, choiceIndex) => `
                <div class="flex items-center mb-2">
                    <input type="text" name="questions[${index}][choices][]" class="mt-1 block w-full p-2 border border-gray-300 rounded-md mr-2" placeholder="Choice" value="${choice}">
                    <input type="radio" name="questions[${index}][correct]" value="${choiceIndex}" class="ml-2" ${choiceIndex == questionData.correct ? 'checked' : ''}> Correct
                    <button type="button" class="remove-choice text-red-500 ml-2">Remove</button>
                </div>
            `).join('');
        }

        newQuestion.innerHTML = `
            <h4 class="text-lg font-bold mb-2">Question ${index + 1}</h4>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Question</label>
                <input type="text" name="questions[${index}][question]" value="${questionData.question || ''}" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Question Type</label>
                <select name="questions[${index}][type]" class="question-type mt-1 block w-full p-2 border border-gray-300 rounded-md">
                    <option value="multiple_choice" ${questionData.type === 'multiple_choice' ? 'selected' : ''}>Multiple Choice</option>
                    <option value="short_answer" ${questionData.type === 'short_answer' ? 'selected' : ''}>Short Answer</option>
                    <option value="essay" ${questionData.type === 'essay' ? 'selected' : ''}>Essay</option>
                </select>
            </div>
            <div class="multiple-choice-options mb-4" id="choices_container_${index}" style="display: ${questionData.type === 'multiple_choice' ? 'block' : 'none'};">
                <label class="block text-sm font-medium text-gray-700 mb-2">Choices</label>
                <div id="choice_list_${index}">${choicesMarkup}</div>
                <button type="button" class="add-choice bg-blue-200 mt-2 py-1 px-2 rounded" data-index="${index}">Add Choice</button>
            </div>
            <div class="short-answer-field mb-4" id="short_answer_container_${index}" style="display: ${questionData.type === 'short_answer' ? 'block' : 'none'};">
                <label class="block text-sm font-medium text-gray-700">Answer</label>
                <input type="text" name="questions[${index}][answer]" value="${questionData.answer || ''}" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
            </div>
            <div class="essay-field mb-4" id="essay_container_${index}" style="display: ${questionData.type === 'essay' ? 'block' : 'none'};">
                <label class="block text-sm font-medium text-gray-700">Essay Answer</label>
                <textarea name="questions[${index}][answer]" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">${questionData.answer || ''}</textarea>
            </div>
            <button type="button" class="remove-question bg-red-500 text-white py-1 px-3 rounded-md">Remove Question</button>
        `;

        questionSection.appendChild(newQuestion);
        questionIndex++;
    }

    document.getElementById('add-question').addEventListener('click', function () {
        createQuestionElement(questionIndex);
        saveFormData();
    });

    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-question')) {
            event.target.parentElement.remove();
            updateQuestionNumbers();
            saveFormData();
        } else if (event.target.classList.contains('remove-choice')) {
            event.target.closest('div.flex').remove();
            saveFormData();
        } else if (event.target.classList.contains('add-choice')) {
            const index = event.target.getAttribute('data-index');
            const choiceList = document.getElementById(`choice_list_${index}`);
            const choiceIndex = choiceList.children.length;
    
            const choiceDiv = document.createElement('div');
            choiceDiv.classList.add('flex', 'items-center', 'mb-2');
            choiceDiv.innerHTML = `
                <input type="text" name="questions[${index}][choices][]" class="mt-1 block w-full p-2 border border-gray-300 rounded-md mr-2" placeholder="Choice">
                <input type="radio" name="questions[${index}][correct]" value="${choiceIndex}" class="ml-2"> Correct
                <button type="button" class="remove-choice text-red-500 ml-2">Remove</button>
            `;
    
            choiceList.appendChild(choiceDiv);
            saveFormData();
        }
    });

    document.addEventListener('change', function (event) {
        if (event.target.classList.contains('question-type')) {
            const index = event.target.name.match(/\[(\d+)\]/)[1];
            const multipleChoiceField = document.getElementById(`choices_container_${index}`);
            const shortAnswerField = document.getElementById(`short_answer_container_${index}`);
            const essayField = document.getElementById(`essay_container_${index}`);

            multipleChoiceField.style.display = 'none';
            shortAnswerField.style.display = 'none';
            essayField.style.display = 'none';

            if (event.target.value === 'multiple_choice') {
                multipleChoiceField.style.display = 'block';
            } else if (event.target.value === 'short_answer') {
                shortAnswerField.style.display = 'block';
            } else if (event.target.value === 'essay') {
                essayField.style.display = 'block';
            }
            saveFormData();
        }
    });

    document.getElementById('quiz-form').addEventListener('input', saveFormData);

    function updateQuestionNumbers() {
        const questions = document.querySelectorAll('.question');
        questions.forEach((question, index) => {
            question.querySelector('h4').innerText = `Question ${index + 1}`;
        });
    }

    function saveFormData() {
        const form = document.getElementById('quiz-form');
        const formData = new FormData(form);
        const serializedData = {};
        
        formData.forEach((value, key) => {
            if (key.endsWith('[]')) {
                const fixedKey = key.slice(0, -2);
                if (!Array.isArray(serializedData[fixedKey])) {
                    serializedData[fixedKey] = [];
                }
                serializedData[fixedKey].push(value);
            } else {
                serializedData[key] = value;
            }
        });

        localStorage.setItem('quizForm', JSON.stringify(serializedData));
    }

    function loadFormData() {
        const formData = JSON.parse(localStorage.getItem('quizForm') || '{}');
        
        document.getElementById('title').value = formData['title'] || '';
        document.getElementById('description').value = formData['description'] || '';
        document.getElementById('quiz_date').value = formData['quiz_date'] || '';
        document.getElementById('start_hour').value = formData['start_hour'] || '';
        document.getElementById('end_hour').value = formData['end_hour'] || '';

        questionIndex = 0;
        if (formData['questions[0][question]']) {
            formData['questions[0][question]'].forEach((_, index) => {
                createQuestionElement(index, {
                    question: formData[`questions[${index}][question]`],
                    type: formData[`questions[${index}][type]`],
                    choices: formData[`questions[${index}][choices]`] || [],
                    correct: formData[`questions[${index}][correct]`] || '',
                    answer: formData[`questions[${index}][answer]`],
                });
            });
        }
    }

    document.getElementById('quiz-form').addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent the form from submitting the traditional way

        const form = event.target;
        const title = form.querySelector('input[name="title"]').value;
        const description = form.querySelector('textarea[name="description"]').value;
        const quizDate = form.querySelector('input[name="quiz_date"]').value;
        const startHour = form.querySelector('input[name="start_hour"]').value;
        const endHour = form.querySelector('input[name="end_hour"]').value;
        
        const questionsData = [];
        const questionElements = form.querySelectorAll('.question');
        
        questionElements.forEach((element, i) => {
            const questionType = element.querySelector(`select[name="questions[${i}][type]"]`).value;
            const questionText = element.querySelector(`input[name="questions[${i}][question]"]`).value;
            
            const questionData = {
                type: questionType,
                question: questionText,
            };
            
            if (questionType === 'multiple_choice') {
                const choices = Array.from(element.querySelectorAll(`input[name="questions[${i}][choices][]"]`))
                    .map(choiceInput => choiceInput.value);

                const correctIndex = Array.from(element.querySelectorAll(`input[name="questions[${i}][correct]"]`))
                    .findIndex(radio => radio.checked);

                questionData.choices = choices;
                questionData.answer = correctIndex !== -1 ? choices[correctIndex] : '';
            } else {
                questionData.answer = element.querySelector(`input[name="questions[${i}][answer]"]`).value || 
                                      element.querySelector(`textarea[name="questions[${i}][answer]"]`)?.value || '';
            }
            
            questionsData.push(questionData);
        });

        const quizData = {
            title: title,
            description: description,
            date: new Date(quizDate).toDateString(),
            startHour: startHour,
            endHour: endHour,
            questions: questionsData,
        };

        console.log(JSON.stringify(quizData, null, 2)); // print JSON for demonstration or submit via AJAX
    });
    
    // Load previous data if available
    document.addEventListener('DOMContentLoaded', loadFormData);
</script>
@endsection