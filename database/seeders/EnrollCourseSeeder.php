<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnrollCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Select all courses
        $courses = \App\Models\Course::all();

        // Enroll all students to all courses
        foreach ($courses as $course) {
            $students = \App\Models\Student::all();
            foreach ($students as $student) {
                $course->students()->attach($student->id);
            }
        }
    }
}
