<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\Submission;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubmissionItem>
 */
class SubmissionItemFactory extends Factory
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
            'submission_id' => Submission::factory(),
            'student_id' => Student::factory(),
            'grade' => $this->faker->numberBetween(1, 10),
            'comment' => $this->faker->realText(),
            'submission_urls' => $this->faker->url(),
        ];
    }
}
