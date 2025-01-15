<?php

namespace Database\Factories;

use App\Models\Teacher;
use App\Models\User;
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
        $random_id = rand(1, 6);

        $teacher = User::where('email', 'b@co')->first();

        $subject_data = ['sosiologi', 'ekonomi', 'bahasa', 'geografi', 'matematika', 'sejarah', 'ipa'];
        $subject_name = [
            'sosiologi' => 'Sosiologi Tingkat Lanjut II',
            'ekonomi' => 'Ekonomi Makro IV',
            'bahasa' => 'Bahasa Indonesia',
            'geografi' => 'Geografi Indonesia',
            'matematika' => 'Aljabar Linear',
            'sejarah' => 'Sejarah Minat',
            'ipa' => 'Biologi Dasar',
        ];

        $subject = $subject_data[$random_id];

        return [
            'name' => $subject_name[$subject],
            'description' => $this->faker->paragraph(),
            'teacher_id' => $teacher->userable->id,
            'class_code' => Str::random(5),
            'subject' => $subject,
        ];
        
    }
}
