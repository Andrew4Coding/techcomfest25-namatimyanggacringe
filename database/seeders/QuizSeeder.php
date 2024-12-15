<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseItem;
use App\Models\CourseSection;
use App\Models\Question;
use App\Models\QuestionChoice;
use App\Models\Quiz;
use App\Models\Teacher;
use Illuminate\Console\View\Components\Choice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $quiz = Quiz::factory()->createOne(['id' => '9db9b43e-db7a-4230-a9ae-f73f8e872ad8', 'finish' => date_add(date_create(), \DateInterval::createFromDateString('2 hours'))]);

        $question = Question::factory()->count(10)->create([], $quiz)->each(function (Question $question) {
            $question->questionChoices()->saveMany(QuestionChoice::factory()->count(5)->make([], $question));
        });

        $teacher = Teacher::factory()->createOne();
        $course = Course::factory()->createOne([
            'teacher_id' => $teacher->id,
        ]);
        $courseSection = CourseSection::factory()->createOne([
            'course_id' => $course->id,
        ]);

        $quiz->questions()->saveMany($question);
        $quiz->courseItem()->create([
            'name' => "Kuis 1",
            'description' => "Kuis 1",
            'course_section_id' => $courseSection->id,
        ]);
    }
}
