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
            // FIXME: ini sementara gue batesin ke multiple_choice aj dulu
            'content' => $this->faker->paragraph(),
//            'answer' => $this->faker->paragraph(),
//            'question_type' => $this->faker->randomElement(QuestionType::class),
            'answer' => $this->faker->randomElement(['a', 'b', 'c', 'd', 'e']),
            'question_type' => $this->faker->randomElement(QuestionType::class),
            'quiz_id' => Quiz::factory(),
        ];
    }
}
