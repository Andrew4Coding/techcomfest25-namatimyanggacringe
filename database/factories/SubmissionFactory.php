<?php

namespace Database\Factories;

use App\Models\CourseItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Submission>
 */
class SubmissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => $this->faker->realText(),
            'due_date' => $this->faker->date(),
            'opened_at' => $this->faker->date(),
            'file_type' => $this->faker->mimeType(),
        ];
    }
}
