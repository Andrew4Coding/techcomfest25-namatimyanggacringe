<?php

namespace App\Livewire;

use App\Models\Quiz as QuizModel;
use App\Models\QuizSubmission;
use App\Models\QuizSubmissionItem;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;

class QuizTeacher extends Component
{

    // uuid regex for filtering valid uuid
    private string $uuidRegex = "/^[0-9a-fA-F]{8}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{12}$/";

    // check if quiz is valid
    public bool $isValid = false;

    // actual quiz model
    public QuizModel $quiz;

    // total question in the quiz
    public int $questionCount;

    /**
     * @param $id string "quiz ID"
     * @return void
     */
    public function mount(string $id): void
    {
        // check whether the regex matched and the id given is valid id
        if (!preg_match($this->uuidRegex, $this->id)) return; // FIXME: maybe ini bisa ditambahin error handling yang lebih baik

        // check whether the quiz is found
        try {
            $this->quiz = QuizModel::with('questions', 'questions.questionChoices')->withCount('questions')->firstOrCreate(['id' => $id]);

            // get question count
            $this->questionCount = $this->quiz->questions_count;

            // check if quiz is valid
            $this->isValid = true;

        } catch (ModelNotFoundException $e) {}  // FIXME: maybe ini bisa ditambahin error handling yang lebih baik

    }



    public function render()
    {
        return view('livewire.quiz-teacher');
    }
}
