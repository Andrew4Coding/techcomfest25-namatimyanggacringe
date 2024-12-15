<?php

namespace App\Livewire\QuizTeacher;

use App\Models\Question;
use App\Models\QuestionChoice;
use Livewire\Component;

class ShortAnswer extends Component
{
    // Question number
    public int $num;

    public Question $question;

    public string $answer;

    public function mount()
    {
        // Initialize the answer if it's set
        $this->answer = $this->question->answer;
    }

    /**
     * Updates the answer when changed
     */
    public function updateAnswer()
    {
        $this->question->answer = $this->answer;
        $this->question->save();
    }

    public function render()
    {
        return view('livewire.quiz-teacher.short-answer');
    }
}
