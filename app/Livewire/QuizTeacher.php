<?php

namespace App\Livewire;

use App\Enums\QuestionType;
use App\Models\Question;
use App\Models\QuestionChoice;
use App\Models\Quiz as QuizModel;
use App\Models\Student;
use DateTimeInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class QuizTeacher extends Component
{

    // uuid regex for filtering valid uuid
    private string $uuidRegex = "/^[0-9a-fA-F]{8}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{12}$/";

    // check if quiz is valid
    public bool $isValid = false;

    // actual quiz model
    public QuizModel $quiz;

    public \DateTime $startTime;
    public \DateTime $endTime;
    public int $duration;

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
            // create new default choice
            $newChoice = QuestionChoice::create([
                'content' => 'Pilihan 1',
                'question_id' => $newQuestion->id,
            ]);

            $newQuestion->questionChoices()->save($newChoice);
        }

        // save new question
        $this->quiz->questions()->save($newQuestion);

        $this->questionType = QuestionType::MultipleChoice;
    }

    public function updatedStartTime()
    {
        $this->quiz->start = $this->startTime;
        $this->quiz->save();
    }

    public function updatedEndTime()
    {
        $this->quiz->finish = $this->endTime;
        $this->quiz->save();
    }

    public function updatedDuration()
    {
        $this->quiz->duration = $this->duration;
        $this->quiz->save();
    }


    // delete question
    public function deleteQuestion($id)
    {
        Question::destroy($id);
        $this->quiz = QuizModel
            ::with('questions', 'questions.questionChoices')
            ->findOrFail($this->quiz->id)
            ->first();
    }

    public function back(): void
    {
        redirect()->back();
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
            dd(\DateTime::createFromFormat('Y-m-d H:i:sT', $this->quiz->start, new \DateTimeZone('UTC')));
            dd($this->quiz->start);
            $this->startTime = \DateTime::createFromFormat(\DateTimeInterface::RFC3339, $this->quiz->start);
            $this->endTime = $this->quiz->finish;
            $this->duration = $this->quiz->duration;

        } catch (ModelNotFoundException $e) {
            redirect()->back();
        }  // FIXME: maybe ini bisa ditambahin error handling yang lebih baik
    }

    public function render()
    {
        return view('livewire.quiz-teacher');
    }
}
