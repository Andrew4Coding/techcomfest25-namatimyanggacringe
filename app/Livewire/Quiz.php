<?php

namespace App\Livewire;

use App\Models\Question;
use App\Models\QuizSubmission;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;
use App\Models\Quiz as QuizModel;

class Quiz extends Component
{
    #[Url]
    public string $id = '';

    private string $uuidRegex = "/^[0-9a-fA-F]{8}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{12}$/";

    public bool $isValid = false;

    public QuizModel $quiz;
    public int $page = 1;
    public Question $curQuestion;
    public int $questionCount;

    public QuizSubmission $submission;

    public function moveTo($page)
    {
        $this->page = $page;
        $this->curQuestion = $this->quiz['questions'][$page - 1];
    }

    public function mount() {
        if (preg_match($this->uuidRegex, $this->id)) {
            try {
                // fetch quiz from database
                $this->quiz = QuizModel::with('questions', 'questions.questionChoices')->withCount('questions')->findOrFail($this->id);

                $curDate = strtotime(date("Y-m-d h:i:sa"));
                if (strtotime($this->quiz['start']) > $curDate or strtotime($this->quiz['end']) <= $curDate) {
                    echo "NGENGTOT LU";
                }

                $this->submission = QuizSubmission::firstOrCreate([
                    'quiz_id' => $this->id,
                    'student_id' => Auth::user()->userable_id,
                    ]);

                // get first question
                $this->curQuestion = $this->quiz['questions'][$this->page - 1];

                // get question count
                $this->questionCount = $this->quiz->questions_count;

                // check if quiz is valid
                $this->isValid = true;
            } catch (ModelNotFoundException $e) {}
        }
    }

    public function render()
    {
        return view('livewire.quiz');
    }
}
