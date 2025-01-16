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
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class QuizTeacher extends Component
{

    // uuid regex for filtering valid uuid
    private string $uuidRegex = "/^[0-9a-fA-F]{8}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{12}$/";

    public bool $isValid = false;

    // LIVEWARE APP STATES
    public QuizModel $quiz;  // Actual Quiz
    public string $startTime;  // Quiz Startime
    public string $endTime;  // Quiz endtime
    public int $duration;  // Quiz duration
    public string $name;  // Quiz title
    public string $description;  // Quiz description

    public bool $isInfoEditable = false;  // Quiz info editable

    // QUIZ EDITOR PROPS
    public QuestionType $questionType = QuestionType::MultipleChoice;


    // STATE CONTROLLERS
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

    public function saveInfo()
    {
        $this->quiz->courseItem->name = $this->name;
        $this->quiz->save();
        $this->quiz->courseItem->description = $this->description;
        $this->quiz->save();
        $this->isInfoEditable = false;
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

    // FUNCTION
    public function back(): void
    {
        redirect()->back();
    }

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
        if ($this->questionType === QuestionType::MultipleChoice or $this->questionType === QuestionType::MultiSelect) {
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

    // SESSION
    public function saveQuiz()
    {
        Session::flash('success', 'Berhasil menyimpan soal!');
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

            $this->isValid = true;

            $datePattern = 'Y-m-d H:i:sT';
            $dateTargetPattern = 'Y-m-d H:i:s';

            $this->startTime = \DateTime::createFromFormat($datePattern, $this->quiz->start)->format($dateTargetPattern);
            $this->endTime = \DateTime::createFromFormat($datePattern, $this->quiz->finish)->format($dateTargetPattern);
            $this->duration = $this->quiz->duration;

            $this->name = $this->quiz->courseItem->name;
            $this->description = $this->quiz->courseItem->description;

        } catch (ModelNotFoundException $e) {
            redirect()->back();
        }  // FIXME: maybe ini bisa ditambahin error handling yang lebih baik
    }

    public function render()
    {
        return view('livewire.quiz-teacher');
    }
}
