<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeacherCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = \App\Models\Course::all();

        // Make all teacher teach all courses
        foreach ($courses as $course) {
            $teachers = \App\Models\Teacher::all();
            foreach ($teachers as $teacher) {
                $course->teacher()->associate($teacher);
            }
        }
    }
}
