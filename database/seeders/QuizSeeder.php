<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\QuestionChoice;
use App\Models\Quiz;
use Illuminate\Console\View\Components\Choice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $quiz = Quiz::factory()->createOne();

        $question = Question::factory()->count(10)->create([], $quiz)->each(function (Question $question) {
            $question->questionChoices()->saveMany(QuestionChoice::factory()->count(5)->make([], $question));
        });

        $quiz->questions()->saveMany($question);
    }
}
