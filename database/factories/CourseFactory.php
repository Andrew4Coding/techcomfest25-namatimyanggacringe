<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
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
            'name' => $this->faker->title(),
            'description' => $this->faker->paragraph(),
            'teacher_id' => Teacher::factory(),
            'class_code' => Str::random(5),
            'subject' => 
        $this->faker->randomElement(['sosiologi', 'ekonomi', 'bahasa', 'geografi', 'matematika', 'sejarah', 'ipa'])
        ];
        
    }
}
