<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get All Courses
        $courses = Course::all();

        $course_section = [
            [
                'name' => "Week 01",
                'description' => "Week 01 description",
                'is_public' => TRUE,
                'created_at' => now(),
            ],
            [
                'name' => "Week 02",
                'description' => "Week 02 description",
                'is_public' => TRUE,
                'created_at' => now()->addDays(7),
            ],
            [
                'name' => "Week 03",
                'description' => "Week 03 description",
                'is_public' => TRUE,
                'created_at' => now()->addDays(14),
            ],
            [
                'name' => "Week 04",
                'description' => "Week 04 description",
                'is_public' => TRUE,
                'created_at' => now()->addDays(21),
            ],
            [
                'name' => "Week 05",
                'description' => "Week 05 description",
                'is_public' => TRUE,
                'created_at' => now()->addDays(28),
            ]
        ];

        // For Each Course, Create Course Sections
        $courses->each(function (Course $course) use ($course_section) {
            foreach ($course_section as $section) {
                $course->courseSections()->create($section);
            }
        });
    }
}
