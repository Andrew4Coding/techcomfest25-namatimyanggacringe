<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        
        return [
            'class' => $this->faker->randomElement(['XII-IPA-1', 'XII-IPA-2', 'XII-IPA-3', 'XII-IPA-4']),
            'nisn' => $this->faker->unique()->numerify('##############')
        ];
    }
}
