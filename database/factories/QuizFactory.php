<?php

namespace Database\Factories;

use App\Models\CourseItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quiz>
 */
class QuizFactory extends Factory
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
            'id' => CourseItem::factory(),
            'start' => $this->faker->dateTime(),
            'finish' => $this->faker->dateTime(),
            'duration' => $this->faker->randomDigit(),
        ];
    }
}
