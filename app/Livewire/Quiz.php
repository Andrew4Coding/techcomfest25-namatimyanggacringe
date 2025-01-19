<?php

namespace App\Livewire;

use App\Jobs\CheckSubmission;
use App\Models\CourseItemProgress;
use App\Models\Question;
use App\Models\QuizSubmission;
use App\Models\QuizSubmissionItem;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use App\Models\Quiz as QuizModel;

class Quiz extends Component
{
    // get id from query params
    public string $id = '';

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

    // actual student's quiz submission
    public QuizSubmission $submission;

    // progress
    public CourseItemProgress $progress;

    // array of flagged question
    public array $flagged = [];

    public int $totalAnswered = 0;

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
        }
    }

    /**
     * @param $id string "actual quiz id"
     * @param $flagged bool "is flagged"
     * @return void
     *
     * This function is triggered to flag a question
     */
    #[On('flag-question')]
    public function flagQuestion(string $id, bool $flagged): void
    {
        // change the flag
        $this->flagged[$id] = $flagged;
    }

    /**
     * @return void
     *
     * This function is for submitting the quiz by changing the submission flag to done
     */
    public function submit()
    {
        // change submission flag
        $this->submission->done = true;
        $this->submission->save();
        $this->progress->is_completed = true;
        $this->progress->save();

        // back, if not exists then move to the index page.
        $quizId = $this->quiz->id;
        $studentId = Auth::user()->userable_id;
        CheckSubmission::dispatch($quizId, $studentId);

        $this->redirectRoute('course.show', ['id' => $this->quiz->courseItem->courseSection->course->id]);
    }


    /**
     * @return string
     *
     * This function is used to generate formatted time from which the quiz started
     * to when the quiz is finished.
     */
    protected function getTimeLeftFormatted(): string
    {
        $timeLeft = $this->getTimeLeft();

        // get hour minute and second
        $hour = intdiv($timeLeft, 3600);
        $minute = intdiv($timeLeft % 3600, 60);
        $second = $timeLeft % 60;

        // return formatted
        return "$hour:$minute:$second";
    }

    /**
     * @return int
     */
    protected function getTimeLeft(): int
    {
        if (!isset($this->submission)) return 0;

        // subtract duration from progress
        $progress = strtotime(date("Y-m-d h:i:sa")) - strtotime($this->submission->created_at);
        return $this->quiz->duration * 60 - $progress;
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
            $this->redirectRoute('courses')->withErrors('Invalid Quiz ID');
            return;
        }

        $this->id = $quizId;

        // if teacher redirect to edit page
        if (Auth::user()->userable_type === Teacher::class) {
            $this->redirect("/quiz/$this->id/edit");
            return;
        }

        // check whether the quiz is found
        try {
            // fetch quiz from database
            $this->quiz = QuizModel
                ::with('questions', 'questions.questionChoices')
                ->withCount('questions')
                ->where('id', $this->id)
                ->firstOrFail(['*']);

            // FIXME: LOGIC UNTUK TIDAK BOLEH MASUK
            $curDate = strtotime(date("Y-m-d h:i:sa"));
            if (strtotime($this->quiz['start']) > $curDate or strtotime($this->quiz['finish']) <= $curDate) {
                // $this->redirectIntended('/');
            }

            // check whether the student already has any submission, if not then
            // create new submission item
            $this->submission = QuizSubmission
                ::where('quiz_id', $this->id)
                ->where('student_id', Auth::user()->userable_id)
                ->firstOrNew([
                    'quiz_id' => $this->id,
                    'student_id' => Auth::user()->userable_id,
                ]);
            $this->progress = CourseItemProgress
                ::where('course_item_id', $this->quiz->courseItem->id)
                ->where('user_id', Auth::user()->id)
                ->firstOrNew([
                    'course_item_id' => $this->quiz->courseItem->id,
                    'user_id' => Auth::user()->id,
                ]);

            $this->submission->save();
            $this->progress->save();

            // FIXME: BENERIN GUE MALAS :V
            if (
                $this->submission->done or // sudah selesai
                $this->getTimeLeft() < 0    // durasi habis
            ) {
                // ini kalau mau "back"
                // $this->redirectIntended('/');
            }

            // get first question
            $this->curQuestion = $this->quiz['questions'][$this->page - 1];

            // get question count
            $this->questionCount = $this->quiz->questions_count;

            // iterate each questions
            foreach ($this->quiz->questions as $question) {
                // set each flag to false
                $this->flagged[$question->id] = false;

                // fetch previous submission item, if any
                $fetched = QuizSubmissionItem
                    ::where('quiz_submission_id', $this->submission->id)
                    ->where('question_id', $question->id)
                    ->first();

                // if there's from the previous submission, mark it
                if ($fetched !== null && $fetched->flagged) {
                    $this->flagged[$question->id] = true;
                }
            }

            $this->totalAnswered = $this->submission->quizSubmissionItems()->count();

            // check if quiz is valid
            $this->isValid = true;
        } catch (ModelNotFoundException $e) {
            $this->redirectIntended('/');
            return;
        }  // FIXME: maybe ini bisa ditambahin error handling yang lebih baik
    }

    // render the quiz
    public function render()
    {
        return view('livewire.quiz', [
            'timeLeft' => $this->getTimeLeftFormatted(),  // get the actual time for wire:poll, ini fungsi timer realtime
        ]);
    }
}
