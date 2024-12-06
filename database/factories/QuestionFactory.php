<?php

namespace Database\Factories;

use App\Enums\QuestionType;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'content' => $this->faker->paragraph(),
            'answer' => $this->faker->paragraph(),
            'question_type' => $this->faker->randomElement(QuestionType::class),
            'quiz_id' => Quiz::factory(),
        ];
    }
}
