<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::factory(10)->create();

        Course::factory(5)->create()->each(function (Course $course) use ($students) {
            $course->students()->attach($students->random(rand(1, 5))->pluck('id')->toArray());
        });
    }
}