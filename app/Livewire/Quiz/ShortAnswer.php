<?php

namespace App\Livewire\Quiz;

use App\Models\Question;
use Livewire\Component;

class ShortAnswer extends Component
{
    public int $page;
    public int $questionCount;
    public Question $question;
    public string $answer;

    public function render()
    {
        return view('livewire.quiz.short-answer');
    }
}
