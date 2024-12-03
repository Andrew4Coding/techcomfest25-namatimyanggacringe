<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $course = \App\Models\Course::first();
        $courseSections = [
            [
                'id' => \Illuminate\Support\Str::uuid()->toString(),
                'course_id' => $course->id,
                'name' => 'Week 01',
                'description' => 'Introduction to Laravel 8',
            ],
            [
                'id' => \Illuminate\Support\Str::uuid()->toString(),
                'course_id' => $course->id,
                'name' => 'Week 02',
                'description' => 'Laravel 8 Models',
            ],
            [
                'id' => \Illuminate\Support\Str::uuid()->toString(),
                'course_id' => $course->id,
                'name' => 'Week 03',
                'description' => 'Laravel 8 Controllers',
            ],
            [
                'id' => \Illuminate\Support\Str::uuid()->toString(),
                'course_id' => $course->id,
                'name' => 'Week 04',
                'description' => 'Laravel 8 Views',
            ],
            [
                'id' => \Illuminate\Support\Str::uuid()->toString(),
                'course_id' => $course->id,
                'name' => 'Week 05',
                'description' => 'Laravel 8 Relationships',
            ]
        ];

        foreach ($courseSections as $courseSection) {
            \App\Models\CourseSection::create($courseSection);
        }
    }
}
