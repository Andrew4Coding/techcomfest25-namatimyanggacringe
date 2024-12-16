<?php

namespace App\Livewire;

use App\Enums\QuestionType;
use App\Models\Question;
use App\Models\QuestionChoice;
use App\Models\Quiz as QuizModel;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Url;
use Livewire\Component;

class QuizTeacher extends Component
{

    // uuid regex for filtering valid uuid
    private string $uuidRegex = "/^[0-9a-fA-F]{8}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{12}$/";

    // check if quiz is valid
    public bool $isValid = false;

    // actual quiz model
    public QuizModel $quiz;

    // QUIZ EDITOR PROPS
    public QuestionType $questionType = QuestionType::MultipleChoice;

    /**
     * @return void
     */
    public function addQuestion()
    {
        // buat kuis baru
        $newQuestion = Question::create([
            'content' => 'Pertanyaan baru',
            'answer' => '',
            'question_type' => $this->questionType,
            'quiz_id' => $this->quiz->id,
        ]);

        // if the selected type is multiple choice or multiselect, create new choices
        if ($this->questionType === QuestionType::MultipleChoice or $this->questionType === QuestionType::MultiSelect)
        {
            $newChoice = QuestionChoice::create([
                'content' => 'Pilihan 1',
                'question_id' => $newQuestion->id,
            ]);

            $newQuestion->questionChoices()->save($newChoice);
        }

        $this->quiz->questions()->save($newQuestion);

        $this->questionType = QuestionType::MultipleChoice;

    }

    public function deleteQuestion($id)
    {
        Question::destroy($id);
        $this->quiz = QuizModel
            ::with('questions', 'questions.questionChoices')
            ->findOrFail($this->quiz->id)
            ->first();
    }

    public function back()
    {
        $this->redirectIntended('/');
    }

    /**
     * @param string $id "quiz ID"
     * @return void
     */
    public function mount(string $quizId)
    {
        // check whether the regex matched and the id given is valid id'
        if (!preg_match($this->uuidRegex, $quizId)) return; // FIXME: maybe ini bisa ditambahin error handling yang lebih baik

        if (Auth::user()->userable_type === Student::class) {
            $this->redirectIntended("/quiz?id=$quizId");
            $this->redirect("/quiz?id=$quizId", true);
            return;
        }


        // check whether the quiz is found
        try {
            $this->quiz = QuizModel::with('questions', 'questions.questionChoices')
                ->withCount('questions')
                ->where('id', $quizId)
                ->firstOrFail();

            // check if quiz is valid
            $this->isValid = true;

        } catch (ModelNotFoundException $e) {
            $this->redirectIntended('/');
        }  // FIXME: maybe ini bisa ditambahin error handling yang lebih baik
    }

    public function render()
    {
        return view('livewire.quiz-teacher');
    }
}
