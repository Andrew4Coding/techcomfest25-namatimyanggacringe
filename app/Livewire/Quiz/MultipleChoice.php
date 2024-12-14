<?php

namespace App\Livewire\Quiz;

use App\Models\Question;
use App\Models\QuizSubmissionItem;
use Livewire\Component;

class MultipleChoice extends Component
{

    public int $page;
    public int $questionCount;
    public Question $question;

    public QuizSubmissionItem $submissionItem;
    public string $submissionId;

    public array $activeCheck;

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

        foreach ($this->question->questionChoices as $choice)
        {
            $this->activeCheck[$choice->id] = false;
            if ($choice === $this->submissionItem->answer) {
                $this->activeCheck[$choice->id] = true;
            }
        }
    }

    public function updateAnswer($answer)
    {
        $this->submissionItem->answer = $answer;
        $this->submissionItem->save();

        foreach ($this->question->questionChoices as $choice)
        {
            $this->activeCheck[$choice->id] = false;
            if ($choice === $answer) {
                $this->activeCheck[$choice->id] = true;
            }
        }
    }

    public function render()
    {
        return view('livewire.quiz.multiple-choice');
    }
}
