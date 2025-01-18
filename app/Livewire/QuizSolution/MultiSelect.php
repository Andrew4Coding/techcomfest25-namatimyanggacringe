<?php

namespace App\Livewire\QuizSolution;

use App\Models\Question;
use App\Models\QuizSubmissionItem;
use Livewire\Component;

class MultiSelect extends Component
{

    public int $page;
    public int $questionCount;
    public Question $question;

    public QuizSubmissionItem $submissionItem;
    public string $submissionId;
    public string $feedback;

    public bool $flagged;

    public function saveFeedback()
    {
        $this->submissionItem->feedback = $this->feedback;
        $this->submissionItem->save();
    }

    public function mount($page, $questionCount, $question, $submissionId)
    {
        $this->page = $page;
        $this->questionCount = $questionCount;
        $this->question = $question;
        $this->submissionId = $submissionId;

        $this->submissionItem = QuizSubmissionItem
            ::where('question_id', $question->id)
            ->where('quiz_submission_id', $submissionId)
            ->first();

        $this->feedback = $this->submissionItem->feedback;

        if ($this->submissionItem->flagged !== null) {
            $this->flagged = $this->submissionItem->flagged;
        }
    }

    public function render()
    {
        return view('livewire.quiz-solution.multi-select');
    }
}
