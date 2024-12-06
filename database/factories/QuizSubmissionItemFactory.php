<?php

namespace Database\Factories;

use App\Models\QuizSubmission;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuizSubmissionItem>
 */
class QuizSubmissionItemFactory extends Factory
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
            'quiz_submission_id' => QuizSubmission::factory(),
            'answer' => $this->faker->word(),
            'score' => $this->faker->numberBetween(1, 10),
        ];
    }
}
