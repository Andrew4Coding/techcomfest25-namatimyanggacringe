<?php

namespace App\Livewire;

use App\Models\Question;
use App\Models\QuizSubmission;
use App\Models\QuizSubmissionItem;
use App\Models\SubmissionItem;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
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

    public array $flagged = [];

    public function moveTo($page)
    {
        $this->page = $page;
        $this->curQuestion = $this->quiz['questions'][$page - 1];
    }

    public function next()
    {
        if ($this->page < $this->questionCount) {
            $this->page++;
            $this->curQuestion = $this->quiz['questions'][$this->page - 1];
        }
    }

    public function prev()
    {
        if ($this->page > 1) {
            $this->page--;
            $this->curQuestion = $this->quiz['questions'][$this->page - 1];
        }
    }

    #[On('flag-question')]
    public function flagQuestion($id, $flagged)
    {
        $this->flagged[$id] = $flagged;
    }

    public function submit()
    {
        $this->submission->done = true;
        $this->submission->save();
        $this->redirectIntended('/');
    }

    public function mount()
    {
        if (preg_match($this->uuidRegex, $this->id)) {
            try {
                // fetch quiz from database
                $this->quiz = QuizModel::with('questions', 'questions.questionChoices')->withCount('questions')->findOrFail($this->id);

                // FIXME: LOGIC UNTUK TIDAK BOLEH MASUK
                $curDate = strtotime(date("Y-m-d h:i:sa"));
                if (strtotime($this->quiz['start']) > $curDate or strtotime($this->quiz['finish']) <= $curDate) {
                    echo "Tidak boleh masuk";
                    // ini kalau mau "back"
                    // $this->redirectIntended('/');
                }

                $this->submission = QuizSubmission::firstOrCreate([
                    'quiz_id' => $this->id,
                    'student_id' => Auth::user()->userable_id,
                    ]);

                if ($this->submission->done) {
                    echo "Tidak boleh masuk";
                    // ini kalau mau "back"
                    // $this->redirectIntended('/');
                }

                // get first question
                $this->curQuestion = $this->quiz['questions'][$this->page - 1];

                // get question count
                $this->questionCount = $this->quiz->questions_count;

                foreach ($this->quiz->questions as $question)
                {
                    $this->flagged[$question->id] = false;
                    $fetched = QuizSubmissionItem::where([
                            'question_id' => $question->id,
                            'quiz_submission_id' => $this->submission->id,
                        ])->first();

                    if ($fetched !== null && $fetched->flagged) {
                        $this->flagged[$question->id] = true;
                    }
                }

                // check if quiz is valid
                $this->isValid = true;
            } catch (ModelNotFoundException $e) {}
        }
    }

    public function getTimeLeft()
    {
        $timeLeft = strtotime($this->quiz['finish']) - strtotime(date("Y-m-d h:i:sa"));
        $hour = intdiv($timeLeft, 3600);
        $minute = intdiv($timeLeft % 3600, 60);
        $second = $timeLeft % 60;

        return "$hour:$minute:$second";
    }

    public function render()
    {
        return view('livewire.quiz', [
            'timeLeft' => $this->getTimeLeft(),
        ]);
    }
}
