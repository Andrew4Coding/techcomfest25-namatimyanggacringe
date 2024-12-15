<?php

namespace App\Livewire\QuizTeacher;

use App\Models\Question;
use App\Models\QuestionChoice;
use Livewire\Component;

class MultipleChoice extends Component
{
    // Question number
    public int $num;

    public Question $question;

    public array $choices = [];

    public string $answer;

    protected $rules = [
        'choices.*.content' => 'required|string|max:255',
        'answer' => 'required|exists:question_choices,id',
    ];

    public function mount()
    {
        // Initialize the choices array with existing question choices
        $this->choices = $this->question->questionChoices->map(function ($choice) {
            return [
                'id' => $choice->id,
                'content' => $choice->content,
            ];
        })->toArray();

        // Initialize the answer if it's set
        $this->answer = $this->question->answer;
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
    public function updatedAnswer()
    {
        $this->validateOnly('answer');

        $this->question->answer = $this->answer;
        $this->question->save();
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

        $this->validateOnly("choices.$choiceIndex.content");

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
            $choice->delete();
            array_splice($this->choices, $choiceIndex, 1);
            session()->flash('message', 'Choice deleted successfully.');

            // If the deleted choice was the answer, reset the answer
            if ($this->answer === $choiceId) {
                $this->answer = null;
                $this->question->answer = null;
                $this->question->save();
            }
        } else {
            session()->flash('error', 'Choice not found in the database.');
        }
    }

    public function render()
    {
        return view('livewire.quiz-teacher.multiple-choice');
    }
}
