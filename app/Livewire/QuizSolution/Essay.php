<?php

namespace App\Livewire\QuizSolution;

use App\Models\Question;
use App\Models\QuizSubmissionItem;
use Livewire\Component;

class Essay extends Component
{

    public int $page;
    public int $questionCount;
    public Question $question;

    public QuizSubmissionItem $submissionItem;
    public string $submissionId;

    public string $answer = '';

    public bool $flagged;

    public function mount($page, $questionCount, $question, $submissionId)
    {
        $this->page = $page;
        $this->questionCount = $questionCount;
        $this->question = $question;
        $this->submissionId = $submissionId;

        $this->submissionItem = QuizSubmissionItem::firstOrCreate([
            'question_id' => $this->question->id,
            'quiz_submission_id' => $this->submissionId,
        ]);

        if ($this->submissionItem->answer !== null) {
            $this->answer = $this->submissionItem->answer;
        }

        if ($this->submissionItem->flagged !== null) {
            $this->flagged = $this->submissionItem->flagged;
        }
    }

    public function render()
    {
        return view('livewire.quiz-solution.essay');
    }
}
