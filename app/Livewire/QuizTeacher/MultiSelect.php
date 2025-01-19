<?php

namespace App\Livewire\QuizTeacher;

use App\Models\Question;
use App\Models\QuestionChoice;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class MultiSelect extends Component
{
    // Question number
    public int $num;

    public Question $question;

    public array $choices = [];

    public string $content;
    public int $weight;

    public array $answers;

    protected $rules = [
        'choices.*.content' => 'required|string|max:255',
        'answer' => 'required|exists:question_choices,id',
    ];

    public function updateQuestionInfo()
    {
        $this->question->content = $this->content;
        $this->question->weight = $this->weight;
        $this->question->save();
    }


    /**
     * Converts index to corresponding letter (A, B, C, ...)
     */
    public function toLetter($i): string
    {
        $charA = ord('A');
        $limit = 26;

        if ($i < $limit) {
            return chr($charA + $i);
        } else {
            $pref = intdiv($i, $limit) - 1;
            $suf = $i % $limit;
            return chr($charA + $pref) . chr($charA + $suf);
        }
    }

    /**
     * Adds a new choice
     */
    public function addChoice()
    {
        // Create a new QuestionChoice in the database
        $newChoice = QuestionChoice::create([
            'content' => 'Pilihan Baru',
            'question_id' => $this->question->id,
        ]);

        // Add the new choice to the component's choices array
        $this->choices[] = [
            'id' => $newChoice->id,
            'content' => $newChoice->content,
        ];
    }

    /**
     * Updates the answer when changed
     */
    public function updatedAnswers()
    {
        // transform answer to a string
        $this->question->answer = implode(',', $this->answers);
        $this->question->save();
    }

    public function updateChoiceContent($idx)
    {
        foreach ($this->question->questionChoices as $choice) {
            if ($choice->id == $this->choices[$idx]['id']) {
                $choice->content = $this->choices[$idx]['content'];
                $choice->save();
            }
        }
    }

    /**
     * Updates a specific choice by its ID
     */
    public function updateChoice($choiceId)
    {
        $choiceIndex = collect($this->choices)->search(function ($choice) use ($choiceId) {
            return $choice['id'] === $choiceId;
        });

        if ($choiceIndex === false) {
            session()->flash('error', 'Choice not found.');
            return;
        }

        $choice = QuestionChoice::find($choiceId);
        if ($choice) {
            $choice->update(['content' => $this->choices[$choiceIndex]['content']]);
            session()->flash('message', 'Choice updated successfully.');
        } else {
            session()->flash('error', 'Choice not found in the database.');
        }
    }

    /**
     * Deletes a specific choice by its ID
     */
    public function deleteChoice($choiceId)
    {
        $choiceIndex = collect($this->choices)->search(function ($choice) use ($choiceId) {
            return $choice['id'] === $choiceId;
        });

        if ($choiceIndex === false) {
            session()->flash('error', 'Choice not found.');
            return;
        }

        $choice = QuestionChoice::find($choiceId);
        if ($choice) {
            // check for answers
            for ($i = 0; $i < count($this->answers); $i++) {
                if ($this->answers[$i]['id'] === $choiceId) {
                    unset($this->answers[$i]);
                }
                $this->question->answers = implode(',', $this->answers[$i]);
                $this->question->save();
            }
            $choice->delete();
            array_splice($this->choices, $choiceIndex, 1);
            session()->flash('message', 'Choice deleted successfully.');
        } else {
            session()->flash('error', 'Choice not found in the database.');
        }
    }

    public function mount()
    {
        // Initialize the answer if it's set
        $this->answers = explode(',', $this->question->answer);
        $this->content = $this->question->content;
        $this->weight = $this->question->weight;
    }

    public function render()
    {
        return view('livewire.quiz-teacher.multi-select');
    }
}
