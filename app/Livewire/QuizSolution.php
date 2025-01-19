<?php

namespace App\Livewire;

use App\Models\Question;
use App\Models\QuizSubmission;
use App\Models\QuizSubmissionItem;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;
use App\Models\Quiz as QuizModel;

class QuizSolution extends Component
{
    #[Url(as: 'id')]
    public string $studentId;

    // uuid regex for filtering valid uuid
    private string $uuidRegex = "/^[0-9a-fA-F]{8}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{12}$/";

    // check if quiz is valid
    public bool $isValid = false;

    // actual quiz model
    public QuizModel $quiz;

    // page for selecting which question to be displayed
    public int $page = 1;

    // current active question
    public Question $curQuestion;

    // total question in the quiz
    public int $questionCount;

    public bool $isCheckedByTeacher;

    // actual student's quiz submission
    public QuizSubmission $submission;

    public QuizSubmissionItem $curSubmissionItem;

    // array of flagged question
    public array $flagged = [];

    /**
     * @param $page
     * @return void
     *
     * This function is used for moving from one page to another page.
     */
    public function moveTo($page): void
    {
        $this->page = $page;
        $this->curQuestion = $this->quiz['questions'][$page - 1];
        $this->curSubmissionItem = $this->submission->quizSubmissionItems[$page - 1];
    }

    /**
     * @return void
     *
     * This function is used for moving to the next question
     */
    public function next(): void
    {
        if ($this->page < $this->questionCount) {
            $this->page++;
            $this->curQuestion = $this->quiz['questions'][$this->page - 1];
            $this->curSubmissionItem = $this->submission->quizSubmissionItems[$this->page - 1];
        }
    }

    /**
     * @return void
     *
     * This function is used for moving to the prev question
     */
    public function prev(): void
    {
        if ($this->page > 1) {
            $this->page--;
            $this->curQuestion = $this->quiz['questions'][$this->page - 1];
            $this->curSubmissionItem = $this->submission->quizSubmissionItems[$this->page - 1];
        }
    }

    public function toggleChecked(): void {
        $this->isCheckedByTeacher = !$this->isCheckedByTeacher;
        $this->submission->is_checked_by_teacher = $this->isCheckedByTeacher;
        $this->submission->save();
    }

    /**
     * @return void
     *
     * mount is used for mounting the component when it first loaded.
     */
    public function mount(string $quizId): void
    {
        // check whether the regex matched and the id given is valid id
        if (!preg_match($this->uuidRegex, $quizId)) {
            $this->redirectIntended("/");
            return;
        } // FIXME: maybe ini bisa ditambahin error handling yang lebih baik

        // check whether the quiz is found
        try {
            // fetch quiz from database
            $this->quiz = QuizModel
                ::with('questions', 'questions.questionChoices')
                ->withCount('questions')
                ->where('id', $quizId)
                ->firstOrFail();


            // get first question
            $this->curQuestion = $this->quiz['questions'][$this->page - 1];

            // get question count
            $this->questionCount = $this->quiz->questions_count;

            if (Auth::user()->userable_type === Teacher::class) {
                $id = $this->studentId;
            } else {
                $id = Auth::user()->userable_id;
            }

            // check submission
            $this->submission = QuizSubmission
                ::where('quiz_id', $quizId)
                ->where('student_id', $id)
                ->with('quizSubmissionItems')
                ->first();

            $this->curSubmissionItem = $this->submission->quizSubmissionItems->first();
            $this->isCheckedByTeacher = $this->submission->is_checked_by_teacher;

            // iterate each questions to mark flagged
            foreach ($this->quiz->questions as $question) {
                // set each flag to false
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

        } catch (ModelNotFoundException $e) {
        }  // FIXME: maybe ini bisa ditambahin error handling yang lebih baik
    }

    // render the quiz
    public function render()
    {
        return view('livewire.quiz-solution');
    }
}
